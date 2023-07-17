<?php
$mysql = "select id,ringkas,nama,create_date,alias,gambar,secid,infogambar from tbl_blog where published='1' and gambar!='' order by id desc limit 4";
$hasil = sql($mysql);

$datadepanblog = array();
$a = 1;
while ($data = sql_fetch_data($hasil)) {
	$tanggal = $data['create_date'];
	$nama = $data['nama'];
	$id = $data['id'];
	$ringkas = $data['ringkas'];
	$alias = $data['alias'];
	$tanggal = tanggal($tanggal);
	$gambar = $data['gambar'];
	$secid = $data['secid'];
	$infogambar = $data['infogambar'];


	$mysql1 = "select nama,alias,secid from tbl_blog_sec where secid='$secid'";
	$hasil1 = sql($mysql1);
	$data1 = sql_fetch_data($hasil1);
	$secalias = $data1['alias'];
	$namasec = $data1['nama'];


	if (!empty($gambar)) $gambar = "$fulldomain/gambar/blog/$gambar";
	else $gambar = "";
	$link = "$fulldomain/coba-blog/read/$secalias/$id/$alias";

	$datadepanblog[$id] = array("id" => $id, "no" => $a, "nama" => $nama, "ringkas" => $ringkas, "namasec" => $namasec, "tanggal" => $tanggal, "alias" => $alias, "link" => $link, "gambar" => $gambar, "$infogambar" => $infogambar);
	$a++;
}
$a = 0;
sql_free_result($hasil);
$tpl->assign("datadepanblog", $datadepanblog);
