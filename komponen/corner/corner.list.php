<?php

$idcorner = $var[4];

if ($idcorner) {
	$perintah = "SELECT secid,namasec FROM tbl_world_sec WHERE alias='$idcorner' ";
	$hsl = sql($perintah);
	$value = sql_fetch_data($hsl);
	$secid = $value["secid"];
	$namasec = $value["namasec"];
	$where = "and id_wolrd_sec='$secid' ";

	$namaevent = $namasec;
} else {
	$where = "";

	$namaevent = "TERBARU";
}

$mysql = "select voucherid,nama,ringkas,alias,published,id,term,harga,startdate,enddate,startusedate,endusedate,qty,views,gambar from tbl_world_voucher where published='1' and community='0'  $where order by create_date desc limit 8";
$hasil = sql($mysql);

$datadepankonsultasi = array();
$a = 1;
while ($data = sql_fetch_data($hasil)) {
	$tanggal = $data['create_date'];
	$nama = $data['nama'];
	$voucherid = $data['voucherid'];
	$ringkas = ringkas($data['ringkas'], 15);
	$alias = $data['alias'];
	$tanggal = tanggal($tanggal);
	$gambar = $data['gambar'];
	$idx = $data['id'];
	$harga = rupiah($data['harga']);
	$startdate 		= tanggalbulan($data['startdate']);
	$enddate 		= tanggalsingkat($data['enddate']);


	$mysql1 = "select nama,alias,secid,gambar from tbl_world where id='$idx'";
	$hasil1 = sql($mysql1);
	$data1 = sql_fetch_data($hasil1);
	$secalias = $data1['alias'];
	$namasec = $data1['nama'];
	$gambarsec = $data1['gambar'];

	if (!empty($gambarsec)) $gambarsec = "$fulldomain/gambar/community/$gambarsec";
	else $gambarsec = "";


	if (!empty($gambar)) $gambar = "$fulldomain/gambar/community/$gambar";
	else $gambar = "";

	$link = "$fulldomain/corner/read/$voucherid/$alias?menu=corner/read&id=$voucherid";


	$datadepankonsultasi[] = array("id" => $id, "a" => $a, "nama" => $nama, "ringkas" => $ringkas, "namasec" => $namasec, "urlkanal" => $urlkanal, "tanggal" => $tanggal, "harga" => $harga, "link" => $link, "startdate" => $startdate, "enddate" => $enddate, "gambar" => $gambar, "gambarsec" => $gambarsec);
	$a++;
}
$a = 0;
sql_free_result($hasil);
$tpl->assign("datadepankonsultasi", $datadepankonsultasi);
$tpl->assign("namaevent", $namaevent);

$mysql = "select a.id,a.nama,a.ringkas,a.gambar,a.secid,a.subid,a.alias from tbl_world a, tbl_world_voucher b where a.id=b.id group by a.id order by a.id asc limit 10";
$hasil = sql($mysql);

$datadetail = array();
$i = 0;
while ($data =  sql_fetch_data($hasil)) {
	$tanggal = $data['create_date'];
	$nama = $data['nama'];
	$idx = $data['id'];
	$konsulsecid = $data['secid'];
	$konsulsubid = $data['subid'];
	$avatar = $data['gambar'];
	$harga = $data['hargakonsultasi'];
	$online = $data['online'];
	$ringkas = ringkas($data['aboutme'], 20);
	$alias = $data['alias'];
	$hargarp = rupiah($harga);

	$sec = sql_get_var("select namasec from tbl_world_sec where secid='$konsulsecid'");
	$sub = sql_get_var("select namasub from tbl_world_sub where secid='$konsulsecid' and subid='$konsulsubid'");

	$sec = "$sec - $sub";

	if (!empty($avatar)) $avatar = "$fulldomain/gambar/world/$avatar";
	else $avatar = "$fulldomain/images/no_pic.jpg";

	$url = "$fulldomain/world/$alias";

	$datadepankonsultan[] = array("id" => $id, "no" => $i, "nama" => $nama, "sec" => $sec, "url" => $url, "ringkas" => $ringkas, "harga" => $harga, "hargarp" => $hargarp, "online" => $online, "avatar" => $avatar, "rating" => $rating);
	$i++;
}
sql_free_result($hasil);
$tpl->assign("datadepankonsultan", $datadepankonsultan);
