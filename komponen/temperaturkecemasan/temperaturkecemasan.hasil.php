<?php 
if(!$_SESSION['userid'])
{
	header("location: $fulldomain/temperaturkecemasan");
	exit();
}

$jmlsoalsikap = sql_get_var("select count(*) as jml from tbl_tesmandiri_soal");

$nilaisikapakhir = sql_get_var("select sum(nilai) from tbl_tesmandiri_user where userid='$_SESSION[userid]'");
//$nilaimax = $jmlsoalsikap*4;


//$nilaisikapakhir = ceil(($nilaisikap/$nilaimax)*100);


$sikapdesc = sql_get_var_row("select nama,kriteria,keterangan from tbl_tesmandiri_skala where $nilaisikapakhir  >= nilaimin  and $nilaisikapakhir <= nilaimax");

$tpl->assign("sikapdesc",$sikapdesc);
$tpl->assign("nilai",$nilaisikapakhir);

?>