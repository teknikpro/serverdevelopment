<?php

$perintah ="select nama,alias,ringkas,lengkap,gambar1 from tbl_static where alias='kreasimain'";
$hasil = sql($perintah);
$data=sql_fetch_data($hasil);
$detailnama = $data["nama"];
$alias = $data["alias"];
$detailringkas= $data["ringkas"];
$detaillengkap= $data["lengkap"];
$detailgambar = $data['gambar1'];

$tpl->assign("detailnama",$detailnama);
$tpl->assign("alias",$alias);
$tpl->assign("detailringkas",$detailringkas);
$tpl->assign("detaillengkap",$detaillengkap);
if(!empty($gambar)) $tpl->assign("detailgambar","$fulldomain/gambar/$kanal/$gambar");

?>