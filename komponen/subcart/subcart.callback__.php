<?php
require_once($lokasiweb."librari/veritrans/Veritrans.php");

Veritrans_Config::$isProduction = $isProduction;
Veritrans_Config::$serverKey = "$serverkey";
	
$notif = new Veritrans_Notification();

$transaction = $notif->transaction_status;
$type = $notif->payment_type;
$order_id = $notif->order_id;
$fraud = $notif->fraud_status;


if ($transaction == 'capture') {
    if ($type == 'credit_card'){
      if($fraud == 'challenge'){
        echo "Transaction order_id: " . $order_id ." is challenged by FDS";
        }
        else {
        echo "Transaction order_id: " . $order_id ." successfully captured using " . $type;
        }
      }
    }
  else if ($transaction == 'settlement'){
    echo "Transaction order_id: " . $order_id ." successfully transfered using " . $type;
    }
    else if($transaction == 'pending'){
    echo "Waiting customer to finish transaction order_id: " . $order_id . " using " . $type;
    }
    else if ($transaction == 'deny') {
    echo "Payment using " . $type . " for transaction order_id: " . $order_id . " is denied.";
    }
    else if ($transaction == 'expire') {
    echo "Payment using " . $type . " for transaction order_id: " . $order_id . " is expired.";
    }
    else if ($transaction == 'cancel') {
    echo "Payment using " . $type . " for transaction order_id: " . $order_id . " is canceled.";
  }
