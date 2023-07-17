<?php
$perintah ="select nama,alias,ringkas,lengkap,gambar1 from tbl_static where alias='membership'";
$hasil = sql($perintah);
$data=sql_fetch_data($hasil);
$detailnama = $data["nama"];
$alias = $data["alias"];
$detailringkas= $data["ringkas"];
$detaillengkap= $data["lengkap"];
$gambar = $data['gambar1'];

$tpl->assign("detailnama",$detailnama);
$tpl->assign("alias",$alias);
$tpl->assign("detailringkas",$detailringkas);
$tpl->assign("detaillengkap",$detaillengkap);

if(!empty($gambar)) $tpl->assign("detailgambar","$fulldomain/gambar/$kanal/$gambar");

?>