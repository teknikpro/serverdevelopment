<?php
if (!$_SESSION['tes_hasil']) {
	if ($_SESSION['tes_userid']) {
		header("location: $fulldomain/termometer-depresi/tes");
		exit();
	}
	header("location: $fulldomain/temperaturkecemasan");
	exit();
}

$jmlsoalsikap = sql_get_var("select count(*) as jml from tbl_tesmandiri_soal");

$nilaisikapakhir = sql_get_var("select sum(nilai) from tbl_tesmandiri_user where userid='$_SESSION[tes_hasil]'");

$sikapdesc = sql_get_var_row("select nama,kriteria,keterangan from tbl_tesmandiri_skala where $nilaisikapakhir  >= nilaimin  and $nilaisikapakhir <= nilaimax");

$tpl->assign("sikapdesc", $sikapdesc);
$tpl->assign("nilai", $nilaisikapakhir);
