
<?php

$username			= addslashes($_POST['username']);
$userpassword		= $_POST['userpassword'];
$userfullname		= addslashes($_POST['userfullname']);
$usergender			= addslashes($_POST['usergender']);
$tempatlahir		= addslashes($_POST['tempatlahir']);
$tanggallahir		= addslashes($_POST['tanggallahir']);
$stk				= addslashes($_POST['stk']);
$sekolah			= addslashes($_POST['sekolah']);
$alamat				= addslashes($_POST['alamat']);
$namaguru			= addslashes($_POST['namaguru']);
$teleponguru		= addslashes($_POST['teleponguru']);
$termcondition		= addslashes($_POST['termcondition']);
// $tanggalpengisian	= clean($_POST['tanggalpengisian']);


$salah = false;
$pesan = array();


if (empty($userfullname)) {
	$pesan[] = array("pesan" => "Nama Lengkap masih kosong, silahkan isi Terlebih Dahulu");
	$salah = true;
} else if (empty($namaguru)) {
	$pesan[] = array("pesan" => "Nama Guru masih kosong, silahkan isi Terlebih Dahulu");
	$salah = true;
} else {
	$salah = false;
}


if (!$salah) {

	$perintah = "select max(id_tes_sekolah) as baru from tbl_sekolah_user";
	$hasil = sql($perintah);
	$data = sql_fetch_data($hasil);
	$baru = $data['baru'] + 1;

	$tanggal = date("Y-m-d H:i:s");

	$query = "insert into tbl_sekolah_user (nama_lengkap,jenis_kelamin,tempat_lahir,tanggal_lahir,lama_stk,sekolah,alamat,nama_guru,tanggal_pengisian,teleponguru,termcondition)
			 values ('$userfullname','$usergender','$tempatlahir','$tanggallahir','$stk','$sekolah', '$alamat', '$namaguru', '$tanggal', '$teleponguru', '$termcondition')";
	$hasil = sql($query);

	if ($hasil) {

		session_start();
		unset($_SESSION['tes_hasil']);
		$_SESSION['tes_userid'] 		= $baru;
		$_SESSION['tes_userfullname'] 	= $userfullname;

		header("location: $fulldomain/tes-sekolah/tes");
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
