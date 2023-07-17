<?php

namespace Midtrans;

require_once($lokasiweb . "librari/midtrans/Midtrans.php");
Config::$isProduction = true;
Config::$serverKey = "$serverkey";

printExampleWarningMessage();

try {
    $notif = new Notification();
} catch (\Exception $e) {
    exit($e->getMessage());
}

$notif = $notif->getResponse();
$transaction = $notif->transaction_status;
$type = $notif->payment_type;
$order_id = $notif->order_id;
$fraud = $notif->fraud_status;
$payment_type = $notif->payment_type;
$settlement_time = $notif->settlement_time;

// AMBIL DATA
$idvoucher = sql_get_var_row("select userid,nama_produk,jumlah from tbl_transaksi_all where invoice='$order_id'");
$userid = $idvoucher['userid'];
$namavoucher = $idvoucher['nama_produk'];
$totaltagihan = $idvoucher['jumlah'];

$user = sql_get_var_row("select userid,userfullname,useremail,userphonegsm from tbl_member where userid='$userid' ");
$userfullname = $user['userfullname'];
$useremail = $user['useremail'];
$userphonegsm = $user['userphonegsm'];

// Get Voucher
// $getvoucher = sql_get_var_row("select transaksiid,invoiceid from tbl_transaksi_voucher where invoiceid='$order_id'");
// $transaksiid = $getvoucher["transaksiid"];
// $invoiceid   = $getvoucher["invoiceid"];



if ($transaction == 'capture') {
    if ($type == 'credit_card') {
        if ($fraud == 'challenge') {
            echo "Transaction order_id: " . $order_id . " is challenged by FDS";
            sql("UPDATE tbl_transaksi_all SET status_transaksi='CHALLENGED', payment_type='$payment_type', tanggal_pembayaran='$settlement_time' WHERE invoiceid='$order_id' ");
        } else {
            echo "Transaction order_id: " . $order_id . " successfully captured using " . $type;
            sql("UPDATE tbl_transaksi_all SET status_transaksi='SUCCESS', payment_type='$payment_type', tanggal_pembayaran='$settlement_time' WHERE invoiceid='$order_id' ");

            // update table transaksi voucher
            // sql("UPDATE tbl_transaksi_voucher SET paid='1', settlementdate='$settlement_time' WHERE invoiceid='$order_id' ");

            // kirim email
            $subject            = "$title, Konfirmasi Pembayaran #$order_id";
            $html = "Yth. $userfullname.<br><br>Ini merupakan konfirmasi pembayaran tiket anda. Berikut ini adalah informasi pembayarannya:<br><br>
            <strong>Nomor Pembayaran:</strong> $order_id<br>
            <strong>Voucher:</strong> $namavoucher<br>
            <strong>Total Tagihan:</strong> $totaltagihan<br><br>
            Terimakasih telah melakukan pembayaran";
            $sendmail            = sendmail($userfullname, $useremail, $subject, $html, emailhtml($html));
        }
    }
} else if ($transaction == 'settlement') {
    echo "Transaction order_id: " . $order_id . " successfully transfered using " . $type;
    sql("UPDATE tbl_transaksi_all SET status_transaksi='SUCCESS', payment_type='$payment_type', tanggal_pembayaran='$settlement_time' WHERE invoice='$order_id' ");

    // update table transaksi voucher
    // sql("UPDATE tbl_transaksi_voucher SET paid='1', settlementdate='$settlement_time' WHERE invoiceid='$order_id' ");

    // kirim email
    $subject            = "$title, Konfirmasi Pembayaran #$order_id";
    $html = "Yth. $userfullname.<br><br>Ini merupakan konfirmasi pembayaran tiket anda. Berikut ini adalah informasi pembayarannya:<br><br>
    <strong>Nomor Pembayaran:</strong> $order_id<br>
    <strong>Voucher:</strong> $namavoucher<br>
    <strong>Total Tagihan:</strong> $totaltagihan<br><br>
    Terimakasih telah melakukan pembayaran";
    $sendmail            = sendmail($userfullname, $useremail, $subject, $html, emailhtml($html));

    // update tabel member
} else if ($transaction == 'pending') {
    echo "Waiting customer to finish transaction order_id: " . $order_id . " using " . $type;
    sql("UPDATE tbl_transaksi_all SET status_transaksi='PENDING', payment_type='$payment_type', tanggal_pembayaran='$settlement_time' WHERE invoice='$order_id' ");
} else if ($transaction == 'deny') {
    echo "Payment using " . $type . " for transaction order_id: " . $order_id . " is denied.";
    sql("UPDATE tbl_transaksi_all SET status_transaksi='DENY', payment_type='$payment_type', tanggal_pembayaran='$settlement_time' WHERE invoice='$order_id' ");
} else if ($transaction == 'expire') {
    echo "Payment using " . $type . " for transaction order_id: " . $order_id . " is expired.";
    sql("UPDATE tbl_transaksi_all SET status_transaksi='EXPIRE', payment_type='$payment_type', tanggal_pembayaran='$settlement_time' WHERE invoice='$order_id' ");

    // update table transaksi voucher
    // sql("UPDATE tbl_transaksi_voucher SET paid='2', settlementdate='$settlement_time' WHERE invoiceid='$order_id' ");

    // kirim email
    $subject            = "$title, Pembayaran Expire #$order_id";
    $html = "Yth. $userfullname.<br><br>Pembayaran ada sudah expire, harap melakukan transaksi dari awal:<br><br> Berikut adalah detail pembayaran yang expire
    <strong>Nomor Pembayaran:</strong> $order_id<br>
    <strong>Voucher:</strong> $namavoucher<br>
    <strong>Total Tagihan:</strong> $totaltagihan<br><br>
    Terimakasih";
    $sendmail            = sendmail($userfullname, $useremail, $subject, $html, emailhtml($html));

    // ambil userid konsultan
    $voucherid = sql_get_var("SELECT chat_id FROM tbl_transaksi_voucher WHERE invoiceid='$order_id' ");
    $konsultanid = sql_get_var("SELECT to_userid FROM tbl_chat WHERE chat_id='$voucherid' ");

    // update tabel member
    sql("UPDATE tbl_member SET busy=0 WHERE userid=$konsultanid ");
} else if ($transaction == 'cancel') {
    echo "Payment using " . $type . " for transaction order_id: " . $order_id . " is canceled.";
    sql("UPDATE tbl_transaksi_all SET status_transaksi='CANCEL', payment_type='$payment_type', tanggal_pembayaran='$settlement_time' WHERE invoice='$order_id' ");
}

function printExampleWarningMessage()
{
    if ($_SERVER['REQUEST_METHOD'] != 'POST') {
        echo 'Notification-handler are not meant to be opened via browser / GET HTTP method. It is used to handle Midtrans HTTP POST notification / webhook.';
    }
    if (strpos(Config::$serverKey, 'your ') != false) {
        echo "<code>";
        echo "<h4>Please set your server key from sandbox</h4>";
        echo "In file: " . __FILE__;
        echo "<br>";
        echo "<br>";
        echo htmlspecialchars('Config::$serverKey = \'<your server key>\';');
        die();
    }
}
