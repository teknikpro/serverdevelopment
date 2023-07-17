<?php
$sql	= "select id,nama,url,gambar,ringkas,warna from tbl_slide where termometer='1' order by rand() limit 5";
$query	= sql($sql);
$a = 0;
$no = 1;

while ($row = sql_fetch_data($query)) {
	$id		= $row['id'];
	$nama	= $row['nama'];
	$url	= $row['url'];
	$gambar	= $row['gambar'];
	$ringkas	= $row['ringkas'];
	$warna	= $row['warna'];

	if (!empty($gambar)) {
		$gambar	= "$fulldomain/gambar/slide/$gambar";

		$slide[$id]	= array("id" => $id, "no" => $no, "a" => $a, "nama" => $nama, "ringkas" => $ringkas, "url" => $url, "gambar" => $gambar, "warna" => $warna);
	}
	$no++;
	$a++;
}
sql_free_result($query);
$tpl->assign("slide", $slide);
