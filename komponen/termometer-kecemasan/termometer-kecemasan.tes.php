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
		$jawaban = $_POST["j-$soalid"];

		if (!empty($jawaban)) {
			$sql = "insert into tbl_tesmandiri_user(userid,nilai,soalid,tanggal) values('$_SESSION[tes_userid]','$jawaban','$soalid','$tanggal')";
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
		header("location: $fulldomain/termometer-kecemasan/hasil");
		exit();
	} else {
		$pesanhasil = "Penyimpanan data pendaftaran gagal dilakukan ada beberapa kesalahan yang mesti diperbaiki, silahkan periksa kembali kemungkinan ada kesalahan yang harus anda perbaiki";
		$berhasil = "0";
	}

	$tpl->assign("pesan", $pesan);
	$tpl->assign("pesanhasil", $pesanhasil);
	$tpl->assign("berhasil", $berhasil);
}

$perintah2 = sql("select soalid,nomor,pertanyaan,tidaksamasekali,kadangkadang,jarang,sering from tbl_tesmandiri_soal order by nomor asc");
while ($data2 = sql_fetch_data($perintah2)) {
	$soalid = $data2['soalid'];
	$nomor = $data2['nomor'];
	$pertanyaan = $data2['pertanyaan'];
	$tidaksamasekali = $data2['tidaksamasekali'];
	$kadangkadang = $data2['kadangkadang'];
	$jarang = $data2['jarang'];
	$sering = $data2['sering'];

	$soal[] = array("soalid" => $soalid, "nomor" => $nomor, "pertanyaan" => $pertanyaan, "tidaksamasekali" => $tidaksamasekali, "jarang" => $jarang, "kadangkadang" => $kadangkadang, "sering" => $sering);
}

$tpl->assign("soalsikap", $soal);
