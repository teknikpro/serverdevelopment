<?php

if (!$_SESSION['tes_userid']) {
	header("location: $fulldomain/tes-psikologi/");
	exit();
}

if(!$var[4]){
	header("location: $fulldomain/tes-psikologi/daftaranggota");
	exit();
}

$encrypted_no_slash = "3934332b457057466537384d4b315650656851756a673d3d";
$key = "12345";
$method = "aes-256-cbc";
$options = 0;
$iv = "6789";
$encrypted = hex2bin($encrypted_no_slash);
$getkategori = openssl_decrypt($encrypted, $method, $key, $options, $iv);


// id bagian
$getidbagian = sql_get_var("SELECT bagian FROM tbl_tes_psikologi_kategori WHERE id_tes_psikologi_kategori='$getkategori' ");
$getnamebagian = sql_get_var("SELECT bagian FROM tbl_tes_psikologi_bagian WHERE id_tes_psikologi_bagian='$getidbagian' ");


// subbagian
$getidsubbagian = sql_get_var("SELECT sub_bagian FROM tbl_tes_psikologi_kategori WHERE id_tes_psikologi_kategori='$getkategori' ");
$getnamesubbagian = sql_get_var("SELECT sub_bagian FROM tbl_tes_psikologi_sub_bagian WHERE id_tes_psikologi_sub_bagian='$getidsubbagian' ");

// kategori
$getnamekategori = sql_get_var("SELECT kategori FROM tbl_tes_psikologi_kategori WHERE id_tes_psikologi_kategori='$getkategori' ");

$tanggal = date("Y-m-d");


if (isset($_POST['sikap'])) {

	$countcat = sql_get_var("SELECT COUNT(*) AS total FROM tbl_tes_psikologi_soal WHERE kategori='$getkategori' ");

	$firsnumber = sql_get_var_row("SELECT id_tes_psikologi_soal FROM tbl_tes_psikologi_soal WHERE kategori='$getkategori' LIMIT 1");
	$idtespsikologisoal = $firsnumber['id_tes_psikologi_soal'];

	$dt = ($countcat + 1);


	for($i = 1; $i < $dt; $i++){
		$soalawal = $idtespsikologisoal++;
		$jawaban = $_POST["soal-$soalawal"];

		if(is_array($jawaban)){
			$jawaban = implode(",", $_POST["soal-$soalawal"]);
		}else {
			$jawaban = $_POST["soal-$soalawal"];
		}

		if (!empty($jawaban)) {
			$sql = "insert into tbl_tes_psikologi_data(userid,bagian,sub_bagian,kategori,soal,jawaban) values('$_SESSION[tes_userid]','$getidbagian','$getidsubbagian','$getkategori','$soalawal','$jawaban')";
			$hsl = sql($sql);
		}
		unset($jawaban);
	}

	if ($hsl) {

		if($getkategori == 8){
			$tambah = 2;
		}else {
			$tambah = 1;
		}

		$data = ($getkategori + $tambah);
		$encrypted = openssl_encrypt($data, $method, $key, $options, $iv);
		$encrypted_no_slash = str_replace("/", "", bin2hex($encrypted));

		$_SESSION['setkategori'] = $encrypted_no_slash;

		if($getkategori == 11){
			unset($_SESSION['tes_userid']);
			header("location: $fulldomain/tes-psikologi/selesai");
			exit();
		}

		header("location: $fulldomain/tes-psikologi/tes/$encrypted_no_slash");
		exit();
	} else {
		$pesanhasil = "Penyimpanan data pendaftaran gagal dilakukan ada beberapa kesalahan yang mesti diperbaiki, silahkan periksa kembali kemungkinan ada kesalahan yang harus anda perbaiki";
		$berhasil = "0";
	}

	$tpl->assign("pesan", $pesan);
	$tpl->assign("pesanhasil", $pesanhasil);
	$tpl->assign("berhasil", $berhasil);
}


$query = sql("SELECT id_tes_psikologi_soal,bagian,sub_bagian,kategori,soal,tipe_soal FROM tbl_tes_psikologi_soal WHERE kategori='$getkategori' ");
while($item = sql_fetch_data($query)){
	$id_tes_psikologi_soal	= $item['id_tes_psikologi_soal'];
	$bagian					= $item['bagian'];
	$sub_bagian				= $item['sub_bagian'];
	$kategori				= $item['kategori'];
	$soal					= $item['soal'];
	$tipe_soal				= $item['tipe_soal'];

	$query2 = sql("SELECT id_tes_psikologi_jawaban,jawaban,nilai FROM tbl_tes_psikologi_jawaban WHERE soal='$id_tes_psikologi_soal' ");
	$jawabantest = array();
	while($item2 = sql_fetch_data($query2)){
		$id_tes_psikologi_jawaban = $item2['id_tes_psikologi_jawaban'];
		$jawaban		= $item2['jawaban'];
		$nilai			= $item2['nilai'];

		$jawabantest[] = [
			"id_tes_psikologi_jawaban" => $id_tes_psikologi_jawaban,
			"jawaban"	=> $jawaban,
			"nilai"		=> $nilai
		];
	}

	$soaltest[] = [
		"id_tes_psikologi_soal" 	=> $id_tes_psikologi_soal,
		"bagian"					=> $bagian,
		"sub_bagian" 				=> $sub_bagian,
		"kategori"					=> $kategori,
		"soal"						=> $soal,
		"tipe_soal"					=> $tipe_soal,
		"jawabantest"				=> $jawabantest
	];
}

$actionform = "insert-$getkategori";
$tpl->assign("actionform", $actionform );
$tpl->assign("bagian", $getnamebagian);
$tpl->assign("subbagian", $getnamesubbagian);
$tpl->assign("kategori", $getnamekategori);
$tpl->assign("soaltest", $soaltest);
