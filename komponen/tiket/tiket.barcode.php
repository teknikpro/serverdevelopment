<?php 

$invoice = $var[4];
$query = sql_get_var("SELECT invoiceid FROM tbl_transaksi_voucher WHERE invoiceid='$invoice' ");
$idvoucher = sql_get_var("SELECT transaksiid FROM tbl_transaksi_voucher WHERE invoiceid='$invoice' ");

if($query){
    echo '<p style="text-align:center;" ><img width="800px" src="https://www.dfunstation.com/gambar/barcode/belitiket'. $idvoucher .'.png"></p>';
}else {
    echo "barcode tidak tersedia";
}

die;



?>