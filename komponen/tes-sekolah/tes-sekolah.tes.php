<?php

if (!$_SESSION['tes_userid']) {
	header("location: $fulldomain/tes-sekolah");
	exit();
}

$tanggal = date("Y-m-d");


if (isset($_POST['sikap'])) {
	$dt = $_POST['soal'];

	for ($i = 0; $i < count($dt); $i++) {
		$soalid = $dt[$i];
		$jawaban = $_POST["j-$soalid"];
		if($jawaban != 1) {
			$jawaban = 33;
		}
		$tipe 	 = $_POST["tipe-$soalid"];

		if (!empty($jawaban)) {
			$sql = "insert into tbl_sekolah_tes(userid,soalid,nilai,tanggal,tipe) values('$_SESSION[tes_userid]','$soalid','$jawaban','$tanggal','$tipe')";
			$hsl = sql($sql);
		}
		unset($jawaban);
	}

	if ($hsl) {
		$pesanhasil = "Selamat kuisioner anda untuk <strong>$paket[paket]</strong> di $title telah berhasil, silahkan lanjutkan
		proses belajar anda ataupun ujian anda";
		$berhasil = "1";

		session_start();
		$_SESSION['tes_hasil'] = $_SESSION['tes_userid'];
		unset($_SESSION['tes_userid']);
		header("location: $fulldomain/tes-sekolah/mencolok");
		exit();
	} else {
		$pesanhasil = "Penyimpanan data pendaftaran gagal dilakukan ada beberapa kesalahan yang mesti diperbaiki, silahkan periksa kembali kemungkinan ada kesalahan yang harus anda perbaiki";
		$berhasil = "0";
	}

	$tpl->assign("pesan", $pesan);
	$tpl->assign("pesanhasil", $pesanhasil);
	$tpl->assign("berhasil", $berhasil);
}

$perintah2 = sql("SELECT id_sekolah_soal,nomor,pertanyaan,tipe FROM tbl_sekolah_soal ORDER BY nomor ASC");
while ($data2 = sql_fetch_data($perintah2)) {
	$id_sekolah_soal 	= $data2['id_sekolah_soal'];
	$nomor 				= $data2['nomor'];
	$pertanyaan 		= $data2['pertanyaan'];
	$tipe 				= $data2['tipe'];

	$perintah3 = sql("SELECT jawaban,bobot FROM tbl_sekolah_isi WHERE id_soal='$id_sekolah_soal' ");
	$jawaban1 = array();
	while ($data3 = sql_fetch_data($perintah3)) {
		$jawaban = $data3['jawaban'];
		$bobot = $data3['bobot'];
		$jawaban1[] = array("jawaban" => $jawaban, "bobot" => $bobot  );
	}

	$soal[] = array("soalid" => $id_sekolah_soal, "nomor" => $nomor, "pertanyaan" => $pertanyaan, "tipe" => $tipe, "pilihan" => $jawaban1);

}


$tpl->assign("soalsikap", $soal);
