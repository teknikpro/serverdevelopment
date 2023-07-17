<?php 
	$adsID= base64_decode($katid);
	
	$url = sql_get_var("select link from tbl_banner where id='$adsID'");
	$tanggal = date("d");
	$bulan = date("m");
	$tahun = date("Y");
	
	$perintahc = "update tbl_banner_stats set klik=klik+1 where id='$adsID' and tanggal='$tanggal' and bulan='$bulan' and tahun='$tahun'";
	$hasilc = sql($perintahc);
	
	header ("location: $url");
	exit();	
?>
