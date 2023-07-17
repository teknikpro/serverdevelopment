<?php
	header('Content-Type: application/json');
	include("../../setingan/web.config.inc.php");
	session_start();
	
	$useralamatid 	= $_POST["useralamatid"];
	
	$perintah 	= "SELECT * FROM tbl_member_alamat WHERE useralamatid='$useralamatid'";
	$hasil 		= sql($perintah);
	$data 		= sql_fetch_data($hasil);
	
	$userfullname	= $data['userfullname'];
	$nama			= $data['nama'];
	$userid			= $data['userid'];
	$propinsiid		= $data['propinsiid'];
	$useraddress 	= $data['useraddress'];
	$kotaid 		= $data['kotaid'];
	$userpostcode	= $data['userpostcode'];
	$kota 			= sql_get_var("select namakota from tbl_kota where kotaid='$kotaid'");
	$userphonegsm 	= $data['userphonegsm'];
	$propinsi 		= sql_get_var("select namapropinsi from tbl_propinsi where propid='$propinsiid'");
	
	$desalamat[]	= array("useralamatid"=>$useralamatid,"userfullname"=>$userfullname,"namaalamat"=>$nama,"useraddress"=>$useraddress,"userpostcode"=>$userpostcode,"kota"=>$kota,"propinsi"=>$propinsi,"userphonegsm"=>$userphonegsm,"kotaid"=>$kotaid);
	
	echo json_encode($desalamat);
?>