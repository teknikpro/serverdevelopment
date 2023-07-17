<?php
if (!$_SESSION['tes_hasil']) {
	if ($_SESSION['tes_userid']) {
		header("location: $fulldomain/termometer-depresi/tes");
		exit();
	}
	header("location: $fulldomain/termometer-depresi");
	exit();
}

$jmlsoalsikap = sql_get_var("select count(*) as jml from tbl_depresi_soal");

$tanggal = sql_get_var("select tanggal from tbl_depresi_user where userid='$_SESSION[tes_hasil]' order by tanggal limit 1");
$nilaisikapakhir = sql_get_var("select sum(nilai) from tbl_depresi_user where userid='$_SESSION[tes_hasil]'");

$sikapdesc = sql_get_var_row("select nama,kriteria,keterangan from tbl_depresi_skala where $nilaisikapakhir  >= nilaimin  and $nilaisikapakhir <= nilaimax");

$tpl->assign("sikapdesc", $sikapdesc);
$tpl->assign("nilai", $nilaisikapakhir);
