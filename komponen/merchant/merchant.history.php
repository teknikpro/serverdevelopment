<?php

$idtransaksi = $var[4];
$getinvoice = sql_get_var("SELECT invoiceid FROM tbl_transaksi_voucher WHERE transaksiid='$idtransaksi' ");
$tpl->assign("namarubrik","Histori Transaksi");

	$id_merchant = sql_get_var("SELECT merchant FROM tbl_member WHERE userid='$_SESSION[userid]' ");
		
	$sql1 	= "select * from tbl_transaksi_voucher where merchant='$id_merchant' and paid='1' order by create_date desc";
	$hsl1 	= sql($sql1);
	$listtransaksi	= array();
	$no	= 1;
	while ($row1 = sql_fetch_data($hsl1))
	{
		$transaksiid             	= $row1['transaksiid'];
		$invoiceid              	= $row1['invoiceid'];
		$nama                 		= $row1['nama'];
		$totaltagihan              	= number_format($row1['totaltagihan']);
		$tanggaltransaksi        	= tanggal($row1['create_date']);
		$paid            			= $row1['paid'];
		$penggunaan 				= $row1['penggunaan'];
		$userid						= $row1['userid'];
		$tanggalbarcode				= tanggal($row1['tanggal_barcode']);
		$jenistiket					= $row1['jenistiket'];
		$jumlahtiket				= $row1['jumlahtiket'];

		if($jenistiket == "tiket"){
			$userfullname = sql_get_var("SELECT nama FROM tbl_transaksi_tiket WHERE idtiket='$userid' ");
		}else {
			$userfullname = sql_get_var("SELECT userfullname FROM tbl_member WHERE userid='$userid' ");
		}

		if($paid == 0){
			$paid = "Pending";
			$label = "warning";
		}elseif($paid == 1){
			$paid = "Lunas";
			$label = "success";
		}else {
			$paid = "Expire";
			$label = "danger";
		}

		if($penggunaan == 1 ){
			$penggunaan = "Sudah Digunakan";
			$digunakan = "success";
			$tanggalbarcode	=  $tanggalbarcode;
		}else {
			$penggunaan = "Belum digunakan";
			$digunakan = "warning";
			$tanggalbarcode = "";
		}

		
		$listtransaksi[$transaksiid] = array("transaksiid"=>$transaksiid,"invoiceid"=>$invoiceid,"nama"=>$nama,"totaltagihan"=>$totaltagihan,"tanggaltransaksi"=>$tanggaltransaksi,"paid"=>$paid,"penggunaan"=>$penggunaan,"no"=>$no,"label"=>$label,"digunakan"=>$digunakan,"userfullname"=>$userfullname,"tanggal_barcode"=>$tanggalbarcode,"jumlahtiket"=>$jumlahtiket);
		$no++;
	}

	// echo var_dump($listtransaksi);
	// die;

	sql_free_result($hsl1);
	$tpl->assign("listtransaksi",$listtransaksi);
	$tpl->assign("idtransaksi",$idtransaksi);
	$tpl->assign("getinvoice",$getinvoice);
?>