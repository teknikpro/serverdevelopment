<?php 
$tpl->assign("rubrik","$rubrik");

$idsoal = $var[4];

$query = sql_get_var_row("SELECT soal,tipe FROM tbl_kokologi_soal WHERE id_kokologi_soal='$idsoal' ");
$soal = $query['soal'];
$tipe = $query['tipe'];

if($tipe === "PG"){
    $jawabankoko = 1;
} else {
    $jawabankoko = 0;
}

$query1	= sql("SELECT id_kokologi_jawaban,id_kokologi_soal,jawaban FROM tbl_kokologi_jawaban WHERE id_kokologi_soal = '$idsoal' ");
while($dt = sql_fetch_data($query1)){
    $kokologijawaban[] = array("id_kokologi_jawaban" => $dt['id_kokologi_jawaban'], "id_kokologi_soali" => $dt['id_kokologi_soal'], "jawaban" => $dt['jawaban'], "link" => "$fulldomain/kokologi/penjelasan/");
}



$tpl->assign("id_kokologi_soal", $idsoal);
$tpl->assign("soal", $soal);
$tpl->assign("tipe", $jawabankoko);
$tpl->assign("jawaban", $kokologijawaban);

