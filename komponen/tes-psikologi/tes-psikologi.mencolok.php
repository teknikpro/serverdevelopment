<?php

if (!$_SESSION['tes_hasil']) {
	if ($_SESSION['tes_userid']) {
		header("location: $fulldomain/tes-sekolah/tes");
		exit();
	}
	header("location: $fulldomain/tes-sekolah");
	exit();
}

$userid = $_SESSION[tes_hasil];
$mencolok = implode(",", $_POST['mencolok']);

if (empty($mencolok)) {
	$pesan[] = array("pesan" => "Harap isi daftar yang mencolok pada anak");
	$salah = true;
} else {

    $query = "insert into tbl_sekolah_mencolok (userid,mencolok)
			 values ('$userid','$mencolok')";
	$hasil = sql($query);

    if ($hasil) {
		header("location: $fulldomain/tes-sekolah/pertanyaan");
		exit();
	} else {
		$pesanhasil = "gagal";
		$berhasil = "0";
	}

}
