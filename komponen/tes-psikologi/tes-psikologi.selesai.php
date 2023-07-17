<?php

$data = sql_get_var("SELECT SUM(jawaban) AS total_amount FROM tbl_tes_psikologi_data WHERE kategori='1';");

$cek = sql_get_var("SELECT jawaban FROM tbl_tes_psikologi_data WHERE id_tes_psikologi_data='6' ");
$numbers = explode(",", $cek);
$sum = array_sum($numbers);

$data = "Selesai";

$tpl->assign("data", $data);

