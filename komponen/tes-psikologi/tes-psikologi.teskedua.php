<?php

if (!$_SESSION['tes_userid']) {
	header("location: $fulldomain/tes-psikologi/");
	exit();
}

if(!$var[4]){
	header("location: $fulldomain/tes-psikologi/daftaranggota");
	exit();
}

$kat = $_SESSION['setkategori'];

if($var[4] != $kat){
	if($_SESSION['bagian'] == "BAGIANPERTAMA"){
		header("location: $fulldomain/tes-psikologi/tes/$kat");
		exit();
	}else {
		header("location: $fulldomain/tes-psikologi/teskedua/$kat");
		exit();
	}
}


$encrypted_no_slash = $var[4];
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

	$countcat = sql_get_var("SELECT COUNT(*) AS total FROM tbl_tes_psikologi_jawaban WHERE kategori='$getkategori' ");

	$firsnumber = sql_get_var_row("SELECT id_tes_psikologi_jawaban FROM tbl_tes_psikologi_jawaban WHERE kategori='$getkategori' LIMIT 1");
	$idtespsikologijawaban = $firsnumber['id_tes_psikologi_jawaban'];

	$dt = ($countcat + 1);

	for($i = 1; $i < $dt; $i++){
		$idjawaban = $idtespsikologijawaban++;
		$soal = sql_get_var("SELECT soal FROM tbl_tes_psikologi_jawaban WHERE id_tes_psikologi_jawaban='$idjawaban' ");

		$jawaban = $_POST["jawab-$idjawaban"];

		if (!empty($jawaban)) {
			$sql = "insert into tbl_tes_psikologi_datadua(userid,bagian,sub_bagian,kategori,soal,id_jawaban,jawaban) values('$_SESSION[tes_userid]','$getidbagian','$getidsubbagian','$getkategori','$soal','$idjawaban','$jawaban')";
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

		if($getkategori == 39){
			unset($_SESSION['tes_userid']);
			unset($_SESSION['bagian']);
			header("location: $fulldomain/tes-psikologi/selesai");
			exit();
		}

		header("location: $fulldomain/tes-psikologi/teskedua/$encrypted_no_slash");
		exit();
	} else {
		$pesanhasil = "Penyimpanan data pendaftaran gagal dilakukan ada beberapa kesalahan yang mesti diperbaiki, silahkan periksa kembali kemungkinan ada kesalahan yang harus anda perbaiki";
		$berhasil = "0";
	}

	$tpl->assign("pesan", $pesan);
	$tpl->assign("pesanhasil", $pesanhasil);
	$tpl->assign("berhasil", $berhasil);
}


$query = sql("SELECT id_tes_psikologi_soal,bagian,sub_bagian,kategori,soal,tipe_soal,catatan FROM tbl_tes_psikologi_soal WHERE kategori='$getkategori' ");
while($item = sql_fetch_data($query)){
	$id_tes_psikologi_soal	= $item['id_tes_psikologi_soal'];
	$bagian					= $item['bagian'];
	$sub_bagian				= $item['sub_bagian'];
	$kategori				= $item['kategori'];
	$soal					= $item['soal'];
	$tipe_soal				= $item['tipe_soal'];
	$catatan				= $item['catatan'];

	$query2 = sql("SELECT id_tes_psikologi_jawaban,jawaban,nilai,lain FROM tbl_tes_psikologi_jawaban WHERE soal='$id_tes_psikologi_soal' ");
	$jawabantest = array();
	while($item2 = sql_fetch_data($query2)){
		$id_tes_psikologi_jawaban = $item2['id_tes_psikologi_jawaban'];
		$jawaban		= $item2['jawaban'];
		$nilai			= $item2['nilai'];
		$lain 			= $item2['lain'];

		$jawabantest[] = [
			"id_tes_psikologi_jawaban" => $id_tes_psikologi_jawaban,
			"jawaban"	=> $jawaban,
			"nilai"		=> $nilai,
			"lain"		=> $lain
		];
	}

	$soaltest[] = [
		"id_tes_psikologi_soal" 	=> $id_tes_psikologi_soal,
		"bagian"					=> $bagian,
		"sub_bagian" 				=> $sub_bagian,
		"kategori"					=> $kategori,
		"soal"						=> $soal,
		"tipe_soal"					=> $tipe_soal,
		"catatan"					=> $catatan,
		"jawabantest"				=> $jawabantest
	];
}

if($getkategori == 39){
	$button = "Selesai";
}else {
	$button = "Selanjutnya";
}

$actionform = "insert-$getkategori";
$tpl->assign("actionform", $actionform );
$tpl->assign("bagian", $getnamebagian);
$tpl->assign("subbagian", $getnamesubbagian);
$tpl->assign("kategori", $getnamekategori);
$tpl->assign("soaltest", $soaltest);
$tpl->assign("button", $button);
