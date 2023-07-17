<?php

$mysql = "SELECT * FROM tbl_kokologi_soal WHERE publish='1' ORDER BY create_date DESC";
$hasil = sql($mysql);

$datadetail = array();
while ($data =  sql_fetch_data($hasil)) {
	$id_kokologi_soal			= $data['id_kokologi_soal'];
	$soal						= $data['soal'];
	$soal_pendek				= $data['soal_pendek'];

	$linkweb	= $fulldomain;

	$kokologisoal[] = array("id_kokologi_soal" => $id_kokologi_soal, "soal" => $soal, "soal_pendek" => $soal_pendek, "link" => $linkweb );

}
sql_free_result($hasil);
$tpl->assign("kokologisoal", $kokologisoal);
