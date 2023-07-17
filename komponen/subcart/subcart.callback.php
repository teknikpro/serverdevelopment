<?php
 
 require_once($lokasiweb . "librari/midtrans/Midtrans.php");
\Midtrans\Config::$isProduction = true;
\Midtrans\Config::$serverKey = 'Mid-server-KCPKYSP2xw-W7Uvl_9Svbp3O';
$notif = new \Midtrans\Notification();
 
$transaction = $notif->transaction_status;
$type = $notif->payment_type;
$order_id = $notif->order_id;
$fraud = $notif->fraud_status;
$payment_type = $notif->payment_type;
$settlement_time = $notif->settlement_time;
$nomorvir = $notif->va_numbers->va_number;
$namabank = $notif->bank->bank;

// AMBIL DATA
$idvoucher = sql_get_var_row("select userid,nama_produk,jumlah,transaksi,email,userfullname from tbl_transaksi_all where invoice='$order_id'");
$userid = $idvoucher['userid'];
$namavoucher = $idvoucher['nama_produk'];
$totaltagihan = $idvoucher['jumlah'];
$cekstatus = $idvoucher['transaksi'];
$useremail = $idvoucher['email'];
$userfullname = $idvoucher['userfullname'];

$user = sql_get_var_row("select userid,userfullname,useremail,userphonegsm from tbl_member where userid='$userid' ");
$userphonegsm = $user['userphonegsm'];


if ($transaction == 'capture') {
    if ($type == 'credit_card') {
        if ($fraud == 'challenge') {
            echo "Transaction order_id: " . $order_id . " is challenged by FDS";
            $paid = 2;
            sql("UPDATE tbl_transaksi_all SET status_transaksi='CHALLENGED', payment_type='$payment_type', va_number='$nomorvir', bank='$namabank', tanggal_pembayaran='$settlement_time' WHERE invoiceid='$order_id' ");
        } else {
            $paid = 1;
            echo "Transaction order_id: " . $order_id . " successfully captured using " . $type;
            sql("UPDATE tbl_transaksi_all SET status_transaksi='SUCCESS', payment_type='$payment_type', va_number='$nomorvir', bank='$namabank', tanggal_pembayaran='$settlement_time' WHERE invoiceid='$order_id' ");

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
    $paid = 1;
    echo "Transaction order_id: " . $order_id . " successfully transfered using " . $type;
    sql("UPDATE tbl_transaksi_all SET status_transaksi='SUCCESS', payment_type='$payment_type', va_number='$nomorvir', bank='$namabank', tanggal_pembayaran='$settlement_time' WHERE invoice='$order_id' ");

    // update table transaksi voucher
    // sql("UPDATE tbl_transaksi_voucher SET paid='1', settlementdate='$settlement_time' WHERE invoiceid='$order_id' ");


    // kirim email
    $subject            = "$title, Konfirmasi Pembayaran #$order_id";
    $html = 'Yth. ' .$userfullname. '<br><br>Ini merupakan konfirmasi pembayaran tiket anda. Berikut ini adalah informasi pembayarannya:<br><br>
    <strong>Nomor Pembayaran:</strong>' . $order_id .' <br>
    <strong>Voucher:</strong> ' . $namavoucher . '<br>
    <strong>Total Tagihan:</strong> ' . $totaltagihan . '<br><br>
    Silahkan klik link dibawah ini untuk melihat barcode <br><br>
    <a href="https://www.dfunstation.com/tiket/barcode/'.$order_id.'" >Lihat Barcode</a> <br><br>
    Terimakasih telah melakukan pembayaran';
    $sendmail            = sendmail($userfullname, $useremail, $subject, $html, emailhtml($html));

    // update tabel member
} else if ($transaction == 'pending') {
    $paid = 0;
    echo "Waiting customer to finish transaction order_id: " . $order_id . " using " . $type;
    sql("UPDATE tbl_transaksi_all SET status_transaksi='PENDING', payment_type='$payment_type', va_number='$nomorvir', bank='$namabank', tanggal_pembayaran='$settlement_time' WHERE invoice='$order_id' ");
} else if ($transaction == 'deny') {
    $paid = 3;
    echo "Payment using " . $type . " for transaction order_id: " . $order_id . " is denied.";
    sql("UPDATE tbl_transaksi_all SET status_transaksi='DENY', payment_type='$payment_type', va_number='$nomorvir', bank='$namabank', tanggal_pembayaran='$settlement_time' WHERE invoice='$order_id' ");
} else if ($transaction == 'expire') {
    $paid = 3;
    echo "Payment using " . $type . " for transaction order_id: " . $order_id . " is expired.";
    sql("UPDATE tbl_transaksi_all SET status_transaksi='EXPIRE', payment_type='$payment_type', va_number='$nomorvir', bank='$namabank', tanggal_pembayaran='$settlement_time' WHERE invoice='$order_id' ");

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

    // ambil data voucher
    $idmerchant = sql_get_var("SELECT voucherid FROM tbl_transaksi_voucher WHERE invoiceid='$order_id' ");
    $quantity = sql_get_var("SELECT qty FROM tbl_world_voucher WHERE voucherid='$idmerchant' ");
    $jumlahtiket = sql_get_var("SELECT jumlahtiket FROM tbl_transaksi_voucher WHERE invoiceid='$order_id' ");
    $addqty = $quantity + (int)$jumlahtiket;

    // update tabel member
    sql("UPDATE tbl_member SET busy=0 WHERE userid=$konsultanid ");

    // update quantity voucher
    sql("UPDATE tbl_world_voucher SET qty='$addqty' WHERE voucherid='$idmerchant' ");
} else if ($transaction == 'cancel') {
    $paid = 2;
    echo "Payment using " . $type . " for transaction order_id: " . $order_id . " is canceled.";
    sql("UPDATE tbl_transaksi_all SET status_transaksi='CANCEL', payment_type='$payment_type', va_number='$nomorvir', bank='$namabank', tanggal_pembayaran='$settlement_time' WHERE invoice='$order_id' ");
}

if($cekstatus == "konsultasi" ){
  sql("UPDATE tbl_chat SET paid='$paid' WHERE invoice='$order_id' ");
}elseif($cekstatus == "belitiket"){
    sql("UPDATE tbl_transaksi_voucher SET paid='$paid' WHERE invoiceid='$order_id' ");
}
 

?>

