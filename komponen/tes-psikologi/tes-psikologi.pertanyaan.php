<?php

if (!$_SESSION['tes_hasil']) {
	if ($_SESSION['tes_userid']) {
		header("location: $fulldomain/tes-sekolah/mencolok");
		exit();
	}
	header("location: $fulldomain/tes-sekolah");
	exit();
}

$userid 	= $_SESSION[tes_hasil];
$pertanyaan = $_POST['pertanyaan'];

if (empty($pertanyaan)) {
	$pesan[] = array("pesan" => "Harap isi daftar yang mencolok pada anak");
	$salah = true;
} else {

    $query = "insert into tbl_sekolah_pertanyaan (userid,pertanyaan)
			 values ('$userid','$pertanyaan')";
	$hasil = sql($query);

    if ($hasil) {
		header("location: $fulldomain/tes-sekolah/hasil");
		exit();
	} else {
		$pesanhasil = "gagal";
		$berhasil = "0";
	}

}
