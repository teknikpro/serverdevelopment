<?php

// echo "Sedang dalam pengembangan";
// die;

$pesan = $var[4];

if ($pesan == "saldokurang"){
    $alert = "Saldo Masih Kurang, Penarikan Minimal Rp. $minimaltarik";
}

$userid = sql_get_var("SELECT merchant FROM tbl_member WHERE userid=$_SESSION[userid] ");

$pendapatan = sql_get_var("SELECT SUM(totaltagihan) FROM tbl_transaksi_voucher WHERE merchant='$userid' AND paid='1' ");
$penarikan = sql_get_var("SELECT SUM(jumlah) FROM tbl_transaksi_tiket_penarikan WHERE userid='$userid' AND proses='1' ");

$sisa_saldo = ($pendapatan - $penarikan);

$tpl->assign("pendapatan",number_format($pendapatan));
$tpl->assign("sisa_saldo",number_format($sisa_saldo));
$tpl->assign("pesan",$alert);

?>