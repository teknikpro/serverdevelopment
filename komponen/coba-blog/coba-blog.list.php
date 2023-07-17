<?php
$katid = $var[4];
$katid = str_replace(".html", "", $katid);

if (!empty($katid) && $katid != "global") {
	$perintah1 = "select * from tbl_blog_sec where alias='$katid'";
	$hasil1 = sql($perintah1);
	$row1 = sql_fetch_data($hasil1);
	$namasec = $row1['nama'];
	$secid = $row1['secid'];
	$secalias = $row1['alias'];
	$where = "and secid='$secid'";
	$rubrik = "$namasec";
	$tpl->assign("rubrik", "$rubrik");
	$tpl->assign("arsip", "$fulldomain/coba-blog/list/$secalias/");
} else {
	$secalias = "global";
	$tpl->assign("rubrik", "$rubrik");
	$tpl->assign("arsip", "$fulldomain/coba-blog/list/$secalias/");
}


$judul_per_hlm = 10;
$batas_paging = 5;
$hlm = $var[5];

$sql = "select count(*) as jml from tbl_$kanal where published='1' $where";
$hsl = sql($sql);
$tot = sql_result($hsl, 0, "jml");
$hlm_tot = ceil($tot / $judul_per_hlm);
if (empty($hlm)) {
	$hlm = 1;
}
if ($hlm > $hlm_tot) {
	$hlm = $hlm_tot;
}

$ord = ($hlm - 1) * $judul_per_hlm;
if ($ord < 0) $ord = 0;

$mysql = "select id,ringkas,nama,create_date,alias,secid,gambar,gambar1 from tbl_$kanal where published='1' $where order by id  desc limit $ord, $judul_per_hlm";
$hasil = sql($mysql);


$datadetail = array();
$i = 0;
while ($data =  sql_fetch_data($hasil)) {
	$tanggal = $data['create_date'];
	$nama = bersih($data['nama']);
	$id = $data['id'];
	$ringkas =  bersih($data['ringkas']);
	$alias = $data['alias'];
	$tanggal = tanggal($tanggal);
	$gambar = $data['gambar'];
	$gambar1 = $data['gambar1'];
	$secid = $data['secid'];

	$perintah = "select alias,nama from tbl_blog_sec where secid='$secid'";
	$res = sql($perintah);
	$dt =  sql_fetch_data($res);
	$secalias1 = $dt['alias'];
	$namasec = $dt['nama'];
	sql_free_result($res);

	if ($i == 0) {
		$gambar = $gambar1;
	} else {
		$gambar = $gambar;
	}

	if (!empty($gambar)) $gambar = "$fulldomain/gambar/$kanal/$gambar";
	else $gambar = "";


	$link = "$fulldomain/$kanal/read/$secalias1/$id/$alias";
	$urlsec = "$fulldomain/$kanal/list/$secalias1";

	$datadetail[$id] = array("id" => $id, "no" => $i, "namasec" => $namasec, "urlsec" => $urlsec, "nama" => $nama, "ringkas" => $ringkas, "tanggal" => $tanggal, "alias" => $alias, "link" => $link, "gambar" => $gambar);
	$i++;
}




sql_free_result($hasil);
$tpl->assign("datadetail", $datadetail);

//Paging 
$batas_page = 5;

$stringpage = array();
$pageid = 0;

if ($hlm > 1) {
	$prev = $hlm - 1;
	$stringpage[$pageid] = array("nama" => "Awal", "link" => "$fulldomain/$kanal/$aksi/$secalias/1");
	$pageid++;
	$stringpage[$pageid] = array("nama" => "Sebelumnya", "link" => "$fulldomain/$kanal/$aksi/$secalias/$prev");
} else {
	$stringpage[$pageid] = array("nama" => "Awal", "link" => "");
	$pageid++;
	$stringpage[$pageid] = array("nama" => "Sebelumnya", "link" => "");
}

$hlm2 = $hlm - (ceil($batas_page / 2));
$hlm4 = $hlm + (ceil($batas_page / 2));

if ($hlm2 <= 0) $hlm3 = 1;
else $hlm3 = $hlm2;
$pageid++;
for ($ii = $hlm3; $ii <= $hlm_tot and $ii <= $hlm4; $ii++) {
	if ($ii == $hlm) {
		$stringpage[$pageid] = array("nama" => "$ii", "link" => "");
	} else {
		$stringpage[$pageid] = array("nama" => "$ii", "link" => "$fulldomain/$kanal/$aksi/$secalias/$ii");
	}
	$pageid++;
}
if ($hlm < $hlm_tot) {
	$next = $hlm + 1;
	$stringpage[$pageid] = array("nama" => "Selanjutnya", "link" => "$fulldomain/$kanal/$aksi/$secalias/$next");
	$pageid++;
	$stringpage[$pageid] = array("nama" => "Akhir", "link" => "$fulldomain/$kanal/$aksi/$secalias/$hlm_tot");
} else {
	$stringpage[$pageid] = array("nama" => "Selanjutnya", "link" => "");
	$pageid++;
	$stringpage[$pageid] = array("nama" => "Akhir", "link" => "");
}
$tpl->assign("stringpage", $stringpage);
