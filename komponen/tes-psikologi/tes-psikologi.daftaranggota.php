
<?php

$kategori = $_SESSION['setkategori'];
if ($_SESSION['tes_userid']) {

	if($_SESSION['bagian'] == "BAGIANPERTAMA"){
		header("location: $fulldomain/tes-psikologi/tes/$kategori");
		exit();
	}else {
		header("location: $fulldomain/tes-psikologi/teskedua/$kategori");
		exit();
	}

}

$userfullname		= addslashes($_POST['userfullname']);
$kelas				= addslashes($_POST['kelas']);
$sekolah			= addslashes($_POST['sekolah']);
$tanggallahir 		= addslashes($_POST['tanggallahir']);



$salah = false;
$pesan = array();


if (empty($userfullname)) {
	$pesan[] = array("pesan" => "Nama Lengkap masih kosong, silahkan isi Terlebih Dahulu");
	$salah = true;
} else if (empty($kelas)) {
	$pesan[] = array("pesan" => "Kelas Masih Kosong");
	$salah = true;
} else {
	$salah = false;
}


if (!$salah) {

	$perintah = "select max(id_tes_psikologi_user) as baru from tbl_tes_psikologi_user";
	$hasil = sql($perintah);
	$data = sql_fetch_data($hasil);
	$baru = $data['baru'] + 1;

	$tanggal = date("Y-m-d H:i:s");

	$query = "insert into tbl_tes_psikologi_user (nama_lengkap,kelas,sekolah,tanggal_pengisian,tanggallahir)
			 values ('$userfullname','$kelas','$sekolah','$tanggal','$tanggallahir')";
	$hasil = sql($query);

	if ($hasil) {

		session_start();
		unset($_SESSION['tes_hasil']);
		$_SESSION['tes_userid'] 		= $baru;
		$_SESSION['tes_userfullname'] 	= $userfullname;
		$_SESSION['makan'];

		$data = "1";
		$key = "12345";
		$method = "aes-256-cbc";
		$options = 0;
		$iv = "6789";
		$encrypted = openssl_encrypt($data, $method, $key, $options, $iv);
		$encrypted_no_slash = str_replace("/", "", bin2hex($encrypted));

		$_SESSION['setkategori'] = $encrypted_no_slash;
		$_SESSION['bagian'] = "BAGIANPERTAMA";

		header("location: $fulldomain/tes-psikologi/tes/$encrypted_no_slash");
		exit();
	} else {
		$pesanhasil = "Pendaftaran Anda Gagal dilakukan kemungkinan Ada beberapa kesalahan yang harus diperbaiki terlebih dahulu, silahkan periksa kembali kesalahan dibawah ini";
		$berhasil = "0";
	}
} else {
	$pesanhasil = "Pendaftaran Anda Gagal dilakukan kemungkinan Ada beberapa kesalahan yang harus diperbaiki terlebih dahulu, silahkan periksa kembali kesalahan dibawah ini";
	$berhasil = "0";
}
$tpl->assign("pesan", $pesan);
$tpl->assign("pesanhasil", $pesanhasil);
$tpl->assign("berhasil", $berhasil);
