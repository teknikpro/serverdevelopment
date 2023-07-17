<?php

$mysql = "select id,ringkas,nama,create_date,alias,gambar,secid,views from tbl_konsultasi where published='1' and lengkap!='' order by create_date desc limit 8";
$hasil = sql($mysql);

$datadepankonsultasi = array();
$a = 1;
while ($data = sql_fetch_data($hasil)) {
	$tanggal = $data['create_date'];
	$nama = $data['nama'];
	$id = $data['id'];
	$ringkas = ringkas($data['ringkas'], 15);
	$alias = $data['alias'];
	$tanggal = tanggal($tanggal);
	$gambar = $data['gambar'];
	$secid = $data['secid'];
	$views = rupiah($data['views']);


	$mysql1 = "select nama,alias,secid from tbl_konsultasi_sec where secid='$secid' and tampil_web='1' ";
	$hasil1 = sql($mysql1);
	$data1 = sql_fetch_data($hasil1);
	$secalias = $data1['alias'];
	$namasec = $data1['nama'];
	$penjelasan = $data1['penjelasan'];


	if (!empty($gambar)) $gambar = "/gambar/konsultasi/$gambar";
	else $gambar = "";

	$link = "$fulldomain/konsultasi/read/$secalias/$id/$alias";
	$urlkanal = "$fulldomain/konsultasi/list/$secalias";

	$datadepankonsultasi[$id] = array("id" => $id, "a" => $a, "nama" => $nama, "penjelasan" => $penjelasan, "ringkas" => $ringkas, "namasec" => $namasec, "urlkanal" => $urlkanal, "tanggal" => $tanggal, "views" => $views, "link" => $link, "gambar" => $gambar);
	$a++;
}
$a = 0;
sql_free_result($hasil);
$tpl->assign("datadepankonsultasi", $datadepankonsultasi);

$mysql = "select userid,username,userfullname,avatar,konsulsecid,konsulsubid,hargakonsultasi,online,rating,aboutme,judul_simposium from tbl_member where tipe='1' and simposium='2' order by rating asc limit 8";
$hasil = sql($mysql);

$datadetail = array();
$i = 0;
while ($data =  sql_fetch_data($hasil)) {
	$tanggal = $data['create_date'];
	$nama = $data['userfullname'];
	$idx = $data['userid'];
	$konsulsecid = $data['konsulsecid'];
	$konsulsubid = $data['konsulsubid'];
	$avatar = $data['avatar'];
	$harga = $data['hargakonsultasi'];
	$online = $data['online'];
	$ringkas = ringkas($data['aboutme'], 20);
	$rating = $data['rating'];
	$hargarp = rupiah($harga);
	$judulsimposium = $data['judul_simposium'];

	$sec = sql_get_var("select namasec from tbl_konsul_sec where secid='$konsulsecid'");
	$sub = sql_get_var("select namasub from tbl_konsul_sub where secid='$konsulsecid' and subid='$konsulsubid'");

	$sec = "$sec - $sub";

	if (!empty($avatar)) $avatar = "$fulldomain/uploads/avatars/$avatar";
	else $avatar = "$fulldomain/images/no_pic.jpg";

	$url = "$fulldomain/$kanal/profile/$idx";


	$datadepankonsultan[] = array("id" => $id, "no" => $i, "nama" => $nama, "sec" => $sec, "url" => $url, "ringkas" => $ringkas, "harga" => $harga, "hargarp" => $hargarp, "online" => $online, "avatar" => $avatar, "rating" => $rating, "judulsimposium" => $judulsimposium);
	$i++;
}
sql_free_result($hasil);
$tpl->assign("datadepankonsultan", $datadepankonsultan);
