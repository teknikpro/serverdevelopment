<?php 
$tpl->assign("rubrik","$rubrik");

$idsoal = $var[4];
$tanggal = date("Y-m-d");

$jawaban = sql_get_var("SELECT jawaban FROM tbl_kokologi_jawaban WHERE id_kokologi_soal='$idsoal' "); 

$isijawab   = cleaninsert($_POST['jawaban']);
$query = "insert into tbl_kokologi_user (id_kokologi_soal,tanggal,jawaban) values ('$idsoal','$tanggal','$isijawab')";
$hasil = sql($query);

$tpl->assign("jawaban", $jawaban);

