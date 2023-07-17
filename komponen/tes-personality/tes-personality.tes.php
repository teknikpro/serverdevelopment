<?php

if (!$_SESSION['tes_userid']) {
	header("location: $fulldomain/temperaturkecemasan");
	exit();
}

$tanggal = date("Y-m-d");


if (isset($_POST['sikap'])) {
	$dt = $_POST['soal'];
	for ($i = 0; $i < count($dt); $i++) {
		$soalid = $dt[$i];
		$jawabanp = $_POST["p-$soalid"];
		$jawabank = $_POST["k-$soalid"];

		if (!empty($jawabanp)) {
			$sql = "insert into tbl_tes_disc_jawaban_p(userid,soalid,jawaban,tanggal) values('$_SESSION[tes_userid]','$soalid','$jawabanp','$tanggal')";
			$hsl = sql($sql);
		}
		unset($jawabanp);
		if (!empty($jawabank)) {
			$sql = "insert into tbl_tes_disc_jawaban_k(userid,soalid,jawaban,tanggal) values('$_SESSION[tes_userid]','$soalid','$jawabank','$tanggal')";
			$hsl = sql($sql);
		}
		unset($jawabank);
	}

	if ($hsl) {
		$pesanhasil = "Selamat kuisioner anda untuk <strong>$paket[paket]</strong> di $title telah berhasil, silahkan lanjutkan
		proses belajar anda ataupun ujian anda";
		$berhasil = "1";

		session_start();
		$_SESSION['tes_hasil'] = $_SESSION['tes_userid'];
		unset($_SESSION['tes_userid']);
		header("location: $fulldomain/tes-personality/hasil");
		exit();
	} else {
		$pesanhasil = "Penyimpanan data pendaftaran gagal dilakukan ada beberapa kesalahan yang mesti diperbaiki, silahkan periksa kembali kemungkinan ada kesalahan yang harus anda perbaiki";
		$berhasil = "0";
	}

	$tpl->assign("pesan", $pesan);
	$tpl->assign("pesanhasil", $pesanhasil);
	$tpl->assign("berhasil", $berhasil);
}

$perintah2 = sql("select id_soal_disc,nama_soal from tbl_tes_disc_soal order by id_soal_disc asc");
while ($data2 = sql_fetch_data($perintah2)) {
	$soalid = $data2['id_soal_disc'];
	$namasoal = $data2['nama_soal'];

	$perintah3 = sql("select id_disc_pertanyaan,pertanyaan_disc,nomor,jawaban_p,jawaban_k from tbl_tes_disc_pertanyaan where nomor='$soalid' ");
	$isipertanyaan = array();
	while ($data4 = sql_fetch_data($perintah3)){
		$id_disc_pertanyaan = $data4['id_disc_pertanyaan'];
		$pertanyaan_disc = $data4['pertanyaan_disc'];
		$nomor = $data4['nomor'];
		$jawaban_p = $data4['jawaban_p'];
		$jawaban_k = $data4['jawaban_k'];
		$isipertanyaan[] = array("id_disc" => $id_disc_pertanyaan, "pertanyaannya" => $pertanyaan_disc, "nomornya" => $nomor, "jawaban_p" => $jawaban_p, "jawaban_k" => $jawaban_k );
	}

	$soal[] = array("soalid" => $soalid, "nomor" => $namasoal, "pertanyaan" => $isipertanyaan );

}

// echo var_dump($soal);
// die;

$tpl->assign("soalsikap", $soal);
