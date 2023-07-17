<?php

$idvoucher = $_POST['idvoucher'];

$chat = sql_get_var_row("select voucherid,id,nama,harga,endusedate,qty from tbl_world_voucher where voucherid='$idvoucher'");
$voucherid = $chat['voucherid'];
$idkomunitas = $chat['id'];
$namavoucher = $chat['nama'];
$harga = $chat['harga'];
$qty = $chat['qty'];
$harga2 = $harga;
$kadaluarsa = date("Y-m-d H:i:s", strtotime($chat['endusedate']));

$userid = newid("idtiket", "tbl_transaksi_tiket");
$userfullname = $_POST['namalengkap'];
$useremail = $_POST['email'];
$userphonegsm = $_POST['telepon'];
$jumlah     = $_POST['jumlah'];

$idtransaksiall = newid("id_transaksi_all", "tbl_transaksi_all");

$cekdata = sql_get_var("SELECT voucherid FROM tbl_world_voucher where voucherid='$idvoucher' ");

if($cekdata){

$perintah = ("INSERT INTO tbl_transaksi_tiket(idtiket,nama,telepon,email,jumlah) VALUES ('$userid','$userfullname', '$userphonegsm', '$useremail', '$jumlah') ");
$prt1 = sql($perintah);

header('Location: https://www.dfunstation.com/bayartiket/www/belivoucher-send.html?action=read&id='. $idvoucher .'&userid=' . $userid . '&idtransaksi=' .$idtransaksiall. ' ');
exit;

}



