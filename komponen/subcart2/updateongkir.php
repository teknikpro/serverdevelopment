<?php
	header('Content-Type: application/json');
	include("../../setingan/web.config.inc.php");
	session_start();
	
	$ongkirid		= $_POST['ongkirid'];
	$transaksiid	= $_POST['transaksiid'];
		
	$data			= " SELECT SUM(berat) as total_berat from tbl_transaksi_detail where transaksiid='$transaksiid'";
	$hasil			= sql($data);
	$total_berat 	= sql_result($hasil, "0", "total_berat");
	
	//$beratkirim 	= $total_berat/1000;
	$beratkirim 	= $total_berat;
	$beratkirim 	= explode(".",$beratkirim);
	$beratkirim1 	= "0.".$beratkirim[1];
	
	if(($beratkirim1 < 1 ) && ($beratkirim1 > 0)) 
		{ $beratkirim1 = 1; }
	else if(($beratkirim1 < 1 ) && ($beratkirim[0] < 1)) 
		{ $beratkirim1 = 1; }
	else 
		{ $beratkirim1 = 0; }
	
	$jumlahberatkirim = $beratkirim[0] + $beratkirim1;
	
	$perintah	= "select id,harga,service,kota from tbl_ongkos WHERE `id` = '$ongkirid'";
	// echo $perintah;
	$hasil		= sql($perintah);
	$data		= sql_fetch_data($hasil);
	
	$id			= $data['id'];
	$harga		= $data['harga'];
	$service	= $data['service'];
	$kota		= $data['kota'];
	
	$ongkoskirim 	= $harga * $jumlahberatkirim;

	$harga2			= "Rp. ". number_format($harga,0,".",".");
	$ongkoskirim2	= "Rp. ". number_format($ongkoskirim,0,".",".");
	
	sql_free_result($hasil);
		
	$totaltagihan = sql_get_var(" SELECT SUM(totalharga) as total_subtotal from tbl_transaksi_detail where transaksiid='$transaksiid'");
	
	//tampilkan diskon voucher
	$qryv = sql(" SELECT totaldiskon, totaltagihanafterdiskon, matauang from tbl_transaksi where transaksiid='$transaksiid'");
	$rowv = sql_fetch_data($qryv);
	$totaldiskon = $rowv['totaldiskon'];
	$matauang = $rowv['matauang'];
	$totaltagihanafterdiskon = $totaltagihan-$totaldiskon;
	
	//update total tagihan di tbl_transaksi
	sql("update tbl_transaksi set ongkosid='$ongkirid', ongkoskirim='$ongkoskirim' where transaksiid='$transaksiid'");
	
	if($totaltagihanafterdiskon==0)
		$totaltagihanakhir = $totaltagihan+$ongkoskirim;
	else
		$totaltagihanakhir = $totaltagihanafterdiskon+$ongkoskirim;
	
	$totaltagihan1 = number_format($totaltagihan,0,",",".");
	$totaltagihan2 = "Rp. $totaltagihan1";
	
	$totaltagihanakhir1 = number_format($totaltagihanakhir,0,",",".");
	$totaltagihanakhir2 = "Rp. $totaltagihanakhir1";

	
	$desharga[]	= array("hargaservis"=>$harga2,"subtotal"=>$totaltagihanakhir2,"subtotalval"=>$totaltagihanakhir,"ongkoskirim"=>$ongkoskirim2,"beratkirim"=>$jumlahberatkirim);
	
	echo json_encode($desharga);
?>