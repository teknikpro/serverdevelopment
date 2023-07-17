<?php
if (!$_SESSION['tes_hasil']) {
	if ($_SESSION['tes_userid']) {
		header("location: $fulldomain/tes-personality/tes");
		exit();
	}
	header("location: $fulldomain/tes-personality");
	exit();
}


$userfullname = sql_get_var("SELECT userfullname FROM tbl_tes_disc_user WHERE userid='$_SESSION[tes_hasil]' ");
$userid = $_SESSION[tes_hasil];


$jawabanpd = sql_get_var("select count(*) as jml from tbl_tes_disc_jawaban_p where jawaban='D' and userid='$_SESSION[tes_hasil]' ");
$jawabanpi = sql_get_var("select count(*) as jml from tbl_tes_disc_jawaban_p where jawaban='I' and userid='$_SESSION[tes_hasil]' ");
$jawabanps = sql_get_var("select count(*) as jml from tbl_tes_disc_jawaban_p where jawaban='S' and userid='$_SESSION[tes_hasil]' ");
$jawabanpc = sql_get_var("select count(*) as jml from tbl_tes_disc_jawaban_p where jawaban='C' and userid='$_SESSION[tes_hasil]' ");
$jawabanpx = sql_get_var("select count(*) as jml from tbl_tes_disc_jawaban_p where jawaban='X' and userid='$_SESSION[tes_hasil]' ");


$jawabankd = sql_get_var("select count(*) as jml from tbl_tes_disc_jawaban_k where jawaban='D' and userid='$_SESSION[tes_hasil]' ");
$jawabanki = sql_get_var("select count(*) as jml from tbl_tes_disc_jawaban_k where jawaban='I' and userid='$_SESSION[tes_hasil]' ");
$jawabanks = sql_get_var("select count(*) as jml from tbl_tes_disc_jawaban_k where jawaban='S' and userid='$_SESSION[tes_hasil]' ");
$jawabankc = sql_get_var("select count(*) as jml from tbl_tes_disc_jawaban_k where jawaban='C' and userid='$_SESSION[tes_hasil]' ");
$jawabankx = sql_get_var("select count(*) as jml from tbl_tes_disc_jawaban_k where jawaban='X' and userid='$_SESSION[tes_hasil]' ");

$jumlahd = $jawabanpd + $jawabankd;
$jumlahi = $jawabanpi + $jawabanki;
$jumlahs = $jawabanps + $jawabanks;
$jumlahc = $jawabanpc + $jawabankc;

$nilaisikapakhir = sql_get_var("select sum(nilai) from tbl_tesmandiri_user where userid='$_SESSION[tes_hasil]'");

$sikapdesc = sql_get_var_row("select nama,kriteria,keterangan from tbl_tesmandiri_skala where $nilaisikapakhir  >= nilaimin  and $nilaisikapakhir <= nilaimax");

// echo "Jawaban Paling" . '<br>';
// echo "Jumlah D  = " . $jawabanpd . '<br>';
// echo "Jumlah I  = " . $jawabanpi . '<br>';
// echo "Jumlah S  = " . $jawabanps . '<br>';
// echo "Jumlah C  = " . $jawabanpc . '<br>';
// echo "Jumlah X  = " . $jawabanpx . '<br><br><br>';

// echo "Jawaban Kurang" . '<br>';
// echo "Jumlah D  = " . $jawabankd . '<br>';
// echo "Jumlah I  = " . $jawabanki . '<br>';
// echo "Jumlah S  = " . $jawabanks . '<br>';
// echo "Jumlah C  = " . $jawabankc . '<br>';
// echo "Jumlah X  = " . $jawabankx . '<br><br><br>';

// echo "CHENGE" . '<br>';
// echo "Jumlah D  = " . $jumlahd . '<br>';
// echo "Jumlah I  = " . $jumlahi . '<br>';
// echo "Jumlah S  = " . $jumlahs . '<br>';
// echo "Jumlah C  = " . $jumlahc . '<br>';

// die;

$tpl->assign("userfullname", $userfullname);
$tpl->assign("userid", $userid);

$tpl->assign("jawabanpd", $jawabanpd);
$tpl->assign("jawabanpi", $jawabanpi);
$tpl->assign("jawabanps", $jawabanps);
$tpl->assign("jawabanpc", $jawabanpc);
$tpl->assign("jawabanpx", $jawabanpx);

$tpl->assign("jawabankd", $jawabankd);
$tpl->assign("jawabanki", $jawabanki);
$tpl->assign("jawabanks", $jawabanks);
$tpl->assign("jawabankc", $jawabankc);
$tpl->assign("jawabankx", $jawabankx);

$tpl->assign("jumlahd", $jumlahd);
$tpl->assign("jumlahi", $jumlahi);
$tpl->assign("jumlahs", $jumlahs);
$tpl->assign("jumlahc", $jumlahc);
