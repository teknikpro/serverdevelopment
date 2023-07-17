<?php

$idtransaksi = $var[4];

$query = sql_get_var_row("SELECT transaksiid,create_date,invoiceid,nama,totaltagihan,kadaluarsa,userid,merchant,jenistiket,jumlahtiket FROM tbl_transaksi_voucher WHERE transaksiid='$idtransaksi' ");
$transaksiid = $query['transaksiid'];
$create_date = tanggal($query['create_date']);
$invoiceid = $query['invoiceid'];
$nama = $query['nama'];
$totaltagihan = number_format($query['totaltagihan']);
$kadaluarsa = tanggal($query['kadaluarsa']);
$userid = $query['userid'];
$merchant = $query['merchant'];
$jenistiket	= $query['jenistiket'];
$jumlahtiket = $query['jumlahtiket'];

$id_merchant = sql_get_var("SELECT merchant FROM tbl_member WHERE userid='$_SESSION[userid]' ");

if($jenistiket == "tiket"){
	$namapembeli = sql_get_var("SELECT nama FROM tbl_transaksi_tiket WHERE idtiket='$userid' ");
}else {
	$namapembeli = sql_get_var("SELECT userfullname FROM tbl_member WHERE userid='$userid' ");
}



$tpl->assign("transaksiid",$transaksiid);
$tpl->assign("create_date",$create_date);
$tpl->assign("invoiceid",$invoiceid);
$tpl->assign("nama",$nama);
$tpl->assign("totaltagihan",$totaltagihan);
$tpl->assign("kadaluarsa",$kadaluarsa);
$tpl->assign("namapembeli",$namapembeli);
$tpl->assign("jumlahtiket", $jumlahtiket);

if(isset($_POST['transaksiid'])){
	
	$transaksiid = $_POST['transaksiid'];

	$cekdata = sql_get_var("SELECT transaksiid FROM tbl_transaksi_voucher WHERE transaksiid='$transaksiid' ");

	if($cekdata){
		if($merchant = $id_merchant ){
			$tanggal = date("Y-m-d H:i:s");
			sql("UPDATE tbl_transaksi_voucher SET penggunaan='1',tanggal_barcode='$tanggal' WHERE transaksiid='$transaksiid' ");
			$doneinput = '1';

			header("Location: $fulldomain/merchant/history/$transaksiid");
		exit();
		}else {

			$otoritas = '1';
			header("Location: $fulldomain/merchant/history-detail/$transaksiid");
		}
	}

	$tpl->assign("doneinput",$doneinput);
	$tpl->assign("otoritas",$otoritas);
	$tpl->assign("invoiceid",$invoiceid);
	
}


?>