<?php 
$tpl->assign("rubrik","$rubrik");

$idsoal = $var[4];
$idjawaban = $var[5];
$tanggal = date("Y-m-d");

$penjelasan = sql_get_var("SELECT penjelasan FROM tbl_kokologi_penjelasan WHERE id_kokologi_soal='$idsoal' AND id_kokologi_jawaban='$idjawaban' ");
$jawaban = sql_get_var("SELECT jawaban FROM tbl_kokologi_jawaban WHERE id_kokologi_jawaban='$idjawaban' "); 

$view = sql_get_var("SELECT view FROM tbl_kokologi_soal WHERE id_kokologi_soal='$idsoal' ");
$addview = $view+1;

sql("UPDATE tbl_kokologi_soal SET view='$addview' WHERE id_kokologi_soal='$idsoal' ");

$query = "insert into tbl_kokologi_user (id_kokologi_soal,tanggal,jawaban) values ('$idsoal','$tanggal','$jawaban')";
$hasil = sql($query);

$tpl->assign("penjelasan", $penjelasan);
$tpl->assign("jawaban", $jawaban);

