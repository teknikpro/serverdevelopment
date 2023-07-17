<?php

require_once($lokasiweb . "librari/veritrans2/Veritrans.php");
Veritrans_Config::$isProduction = false;
Veritrans_Config::$serverKey = "$serverkey";
$notif = new Veritrans_Notification();

$transaction = $notif->transaction_status;
$type = $notif->payment_type;
$order_id = $notif->order_id;
$fraud = $notif->fraud_status;

if ($transaction == 'capture') {
    if ($type == 'credit_card'){
      if($fraud == 'challenge'){
        sql("UPDATE tbl_transaksi_all SET status_transaksi='challenge' WHERE invoice='$order_id' ");
        echo "Transaction order_id: " . $order_id ." is challenged by FDS";
        } 
        else {
        sql("UPDATE tbl_transaksi_all SET status_transaksi='success' WHERE invoice='$order_id' ");
        echo "Transaction order_id: " . $order_id ." successfully captured using " . $type;
        }
      }
    }
else if ($transaction == 'settlement'){
    sql("UPDATE tbl_transaksi_all SET status_transaksi='success' WHERE invoice='$order_id' ");
    echo "Transaction order_id: " . $order_id ." successfully transfered using " . $type;
    } 
else if($transaction == 'pending'){
    sql("UPDATE tbl_transaksi_all SET status_transaksi='pending' WHERE invoice='$order_id' ");
    echo "Waiting customer to finish transaction order_id: " . $order_id . " using " . $type;
    } 
else if ($transaction == 'deny') {
    sql("UPDATE tbl_transaksi_all SET status_transaksi='deny' WHERE invoice='$order_id' ");
    echo "Payment using " . $type . " for transaction order_id: " . $order_id . " is denied.";
    }
else if ($transaction == 'expire') {
    sql("UPDATE tbl_transaksi_all SET status_transaksi='expire' WHERE invoice='$order_id' ");
    echo "Payment using " . $type . " for transaction order_id: " . $order_id . " is expired.";
    }
else if ($transaction == 'cancel') {
    sql("UPDATE tbl_transaksi_all SET status_transaksi='cancel' WHERE invoice='$order_id' ");
    echo "Payment using " . $type . " for transaction order_id: " . $order_id . " is canceled.";
}

?>