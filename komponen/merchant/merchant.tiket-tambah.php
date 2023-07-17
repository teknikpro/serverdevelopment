<?php

$idmerchant = $var[4];
$tpl->assign("idmerchant",$idmerchant);

if(isset($_POST['namavoucher'])){
	$namavoucher = $_POST['namavoucher'];
	$ringkas = $_POST['ringkas'];
	$harga = $_POST['harga'];
	$awalberlaku = $_POST['awalberlaku'];
	$akhirberlaku = $_POST['akhirberlaku'];
	$kuantitas = $_POST['kuantitas'];
	$awaltayang = $_POST['awaltayang'];
	$akhirtayang = $_POST['akhirtayang'];
	$term = $_POST['term'];
	$id_merchant = $_POST['idmerchant'];

	$tanggal = date('Y-m-d H:i:s');
	$userid = $_SESSION['userid'];

	$perintah = "INSERT INTO tbl_world_voucher(id,nama,ringkas,term,startdate,enddate,startusedate,endusedate,harga,qty,create_date,create_userid,published) VALUES ('$id_merchant','$namavoucher','$ringkas','$term','$awalberlaku','$akhirberlaku','$awaltayang','$akhirtayang','$harga','$kuantitas','$tanggal','$userid','0')";
	$hasil = sql($perintah);



	$pesantambah = "Data barhasil ditambah";
	header("location: $fulldomain/merchant/daftar-tiket/tambahberhasil");
}


?>