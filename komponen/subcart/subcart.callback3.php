<?php

namespace Midtrans;

require_once($lokasiweb . "librari/midtrans/Midtrans.php");
Config::$isProduction = false;
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
$nomorvir = $notif->va_numbers->va_number;
$namabank = $notif->va_numbers->bank;

// AMBIL DATA
$idvoucher = sql_get_var_row("select userid,nama_produk,jumlah,transaksi from tbl_transaksi_all where invoice='$order_id'");
$userid = $idvoucher['userid'];
$namavoucher = $idvoucher['nama_produk'];
$totaltagihan = $idvoucher['jumlah'];
$cekstatus = $idvoucher['transaksi'];

$user = sql_get_var_row("select userid,userfullname,useremail,userphonegsm from tbl_member where userid='$userid' ");
$userfullname = $user['userfullname'];
$useremail = $user['useremail'];
$userphonegsm = $user['userphonegsm'];

if ($transaction == 'capture') {
    if ($type == 'credit_card') {
        if ($fraud == 'challenge') {
            $paid = 2;
            $status_transaksi = "CHALLENGED";
            echo "Transaction order_id: " . $order_id . " is challenged by FDS";
        } else {
            $paid = 1;
            $status_transaksi = "SUCCESS";
            echo "Transaction order_id: " . $order_id . " successfully captured using " . $type;
            
        }
    }
} else if ($transaction == 'settlement') {
    $paid = 1;
    $status_transaksi = "SUCCESS";
    echo "Transaction order_id: " . $order_id . " successfully transfered using " . $type;

    // update tabel member
} else if ($transaction == 'pending') {
    $paid = 0;
    $status_transaksi = "PENDING";
    echo "Waiting customer to finish transaction order_id: " . $order_id . " using " . $type;
    
} else if ($transaction == 'deny') {
    $paid = 3;
    $status_transaksi = "DENY";
    echo "Payment using " . $type . " for transaction order_id: " . $order_id . " is denied.";
    
} else if ($transaction == 'expire') {
    $paid = 3;
    $status_transaksi = "EXPIRE";
    echo "Payment using " . $type . " for transaction order_id: " . $order_id . " is expired.";

    // ambil userid konsultan
    $voucherid = sql_get_var("SELECT chat_id FROM tbl_transaksi_voucher WHERE invoiceid='$order_id' ");
    $konsultanid = sql_get_var("SELECT to_userid FROM tbl_chat WHERE chat_id='$voucherid' ");

    // update tabel member
    sql("UPDATE tbl_member SET busy=0 WHERE userid=$konsultanid ");
} else if ($transaction == 'cancel') {
    $paid = 2;
    $status_transaksi = "CANCEL";
    echo "Payment using " . $type . " for transaction order_id: " . $order_id . " is canceled.";
}

// kirim email
// $subject            = "$title, Konfirmasi Pembayaran #$order_id";
// $html = "Yth. $userfullname.<br><br>Ini merupakan konfirmasi pembayaran tiket anda. Berikut ini adalah informasi pembayarannya:<br><br>
// <strong>Nomor Pembayaran:</strong> $order_id<br>
// <strong>Voucher:</strong> $namavoucher<br>
// <strong>Total Tagihan:</strong> $totaltagihan<br><br>
// Terimakasih telah melakukan pembayaran";
// $sendmail            = sendmail($userfullname, $useremail, $subject, $html, emailhtml($html));

if($cekstatus == "konsultasi" ){
    sql("UPDATE tbl_transaksi_all SET status_transaksi='$status_transaksi', payment_type='$payment_type', va_number='$nomorvir', bank='$namabank', tanggal_pembayaran='$settlement_time' WHERE invoiceid='$order_id' ");
    sql("UPDATE tbl_chat SET paid='$paid' WHERE invoice='$order_id' ");
}elseif($cekstatus == "belitiket"){
    sql("UPDATE tbl_transaksi_all SET status_transaksi='$status_transaksi', payment_type='$payment_type', va_number='$nomorvir', bank='$namabank', tanggal_pembayaran='$settlement_time' WHERE invoiceid='$order_id' ");
    sql("UPDATE tbl_transaksi_voucher SET paid='$paid' WHERE invoiceid='$order_id' ");
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
