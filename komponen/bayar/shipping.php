<?php
include("../../setingan/web.config.inc.php");

	$kotaid		= $_POST[kotaid];
	$orderid	= $_POST[orderid];
	
	$kota		= sql_get_var("select namakota from tbl_kota where kotaid='$kotaid'");
	
	$transaksiid	= sql_get_var("select transaksiid from tbl_transaksi where orderid='$orderid'");
	
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
	
	$perintah	= "select id,harga,service,kota from tbl_ongkos WHERE `kota` like '$kota%'";
	// echo $perintah;
	$hasil		= sql($perintah);
	while($data		= sql_fetch_data($hasil))
	{
		$id			= $data['id'];
		$harga		= $data['harga'];
		$service	= $data['service'];
		$kota		= $data['kota'];
		
		$ongkoskirim 	= $harga * $jumlahberatkirim;

		$harga2			= "Rp. ". number_format($harga,0,".",".");
		$ongkoskirim2	= "Rp. ". number_format($ongkoskirim,0,".",".");
		$subtotalnya	= $subtotal + $harga;
		$subtotalnya2	= "Rp. ". number_format($subtotalnya,0,".",".");
		
		$ongkos .= "<input type='radio' name='service' value='$id'>  <strong>$kota</strong> - $service : $harga2/KG ||$harga||$id||$jumlahberatkirim
		";
	}
	sql_free_result($hasil);
	
	echo "$ongkos";
?>