<?php
if (empty($aksi)) $aksi = "read";

$sql = "select id,nama,ringkas,lengkap,oleh,alias,gambar,gambar1 from tbl_static where alias='kontak'";
$hsl = sql($sql);
$row = sql_fetch_data($hsl);

$id 		= $row['id'];
$nama 		= $row['nama'];
$ringkas 	= $row['ringkas'];
$lengkap 	= $row['lengkap'];
$oleh 		= $row['oleh'];
$alias	 	= $row['alias'];
$gambar 	= $row['gambar'];
$gambar1 	= $row['gambar1'];

sql_free_result($hsl);

$tpl->assign("contactnama", $nama);
$tpl->assign("contactringkas", $ringkas);
$tpl->assign("contactlengkap", $lengkap);

if (file_exists($lokasiweb . "/komponen/$kanal/$kanal.$aksi.php")) include("$kanal.$aksi.php");
else {
	$aksi = "list";
	include("$kanal.list.php");
}

$tpl->display("hubungi.html");
