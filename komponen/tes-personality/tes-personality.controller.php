<?php


if ($aksi == "daftaranggota") {
	$file =  "tes-personality.daftaranggota.php";
	$nama_aksi = "Kirim Kode Validasi";
} else if ($aksi == "tes") $file =  "tes-personality.tes.php";
else if ($aksi == "user") $file =  "tes-personality.user.php";
else if ($aksi == "hasil") $file =  "tes-personality.hasil.php";
else {
	if (empty($aksi)) $aksi = "dashboard";
}

$tpl->assign("namaaksi", $nama_aksi);
if (!empty($file)) {
	include($file);
}

//dapatkan data tanggal
$dateloop = array();
$tempi = 1;
while ($tempi < 32) {
	if ($tempi < 10) {
		array_push($dateloop, "0" . $tempi);
		$temp2 = "0" . $tempi;
	} else {
		array_push($dateloop, $tempi);
		$temp2 = $tempi;
	}
	if ($temp2 == $dob[2]) $dateselected = $tempi;
	$tempi++;
}

$monthloop = array();
$tempi = 1;
while ($tempi < 13) {
	if ($tempi < 10) {
		array_push($monthloop, "0" . $tempi);
		$temp2 = "0" . $tempi;
	} else {
		array_push($monthloop, $tempi);
		$temp2 = $tempi;
	}
	if ($temp2 == $dob[1]) $monthselected = $tempi;
	$tempi++;
}

$yearloop = array();
$tempi = date("Y") - 76;

while ($tempi < date("Y") - 1) {
	array_push($yearloop, $tempi);
	if ($tempi == $dob[0]) $yearselected = $tempi;
	$tempi++;
}

if ($monthselected < 10) $monthselected = "0" . $monthselected;
if ($dateselected < 10) $dateselected = "0" . $dateselected;

$tpl->assign('yearloop', $yearloop);
$tpl->assign('yearselected', $yearselected);
$tpl->assign('monthloop', $monthloop);
$tpl->assign('monthselected', $monthselected);
$tpl->assign('dateloop', $dateloop);
$tpl->assign('dateselected', $dateselected);


//propinsi
$datapropinsi = array();
$ppropinsi = "select propid,namapropinsi from tbl_propinsi order by namapropinsi asc";
$hpropinsi = sql($ppropinsi);
while ($dpropinsi = sql_fetch_data($hpropinsi)) {
	$datapropinsi[$dpropinsi['propid']] = array("id" => $dpropinsi['propid'], "namapropinsi" => $dpropinsi['namapropinsi']);
}
sql_free_result($hpropinsi);
$tpl->assign("datapropinsi", $datapropinsi);

//kota
$datakota = array();
$pkota = "select kotaid,namakota,tipe from tbl_kota where propid='$propid' order by namakota asc";
$hkota = sql($pkota);
while ($dkota = sql_fetch_data($hkota)) {
	$kota = $dkota['namakota'];
	$tipe = $dkota['tipe'];
	$kotaids = $dkota['kotaid'];

	if ($tipe == "Kota") $kota = "$kota (Kota)";
	else $kota = $kota;

	$datakota[] = array("kotaid" => $kotaids, "nama" => $kota);
}
sql_free_result($hkota);
$tpl->assign("datakota", $datakota);

//propinsi
$datakec = array();
$pkec = "select kecid,namakecamatan from tbl_kecamatan where kotaid='$kotaid' and propid='$propid' order by kecid asc";
$hkec = sql($pkec);
while ($dkec = sql_fetch_data($hkec)) {
	$kecid2 = $dkec['kecid'];
	$namakec = $dkec['namakecamatan'];
	$datakec[] = array("kecid" => $kecid2, "nama" => $namakec);
}
sql_free_result($hkec);
$tpl->assign("datakecamatan", $datakec);

//propinsi
$datapropinsi = array();
$ppropinsi = "select propid,namapropinsi from tbl_propinsi order by namapropinsi asc";
$hpropinsi = sql($ppropinsi);
while ($dpropinsi = sql_fetch_data($hpropinsi)) {
	$datapropinsi[$dpropinsi['propid']] = array("id" => $dpropinsi['propid'], "namapropinsi" => $dpropinsi['namapropinsi']);
}
sql_free_result($hpropinsi);
$tpl->assign("datapropinsi", $datapropinsi);

//kota
$datakota = array();
$pkota = "select kotaid,namakota,tipe from tbl_kota where propid='$propid' order by namakota asc";
$hkota = sql($pkota);
while ($dkota = sql_fetch_data($hkota)) {
	$kota = $dkota['namakota'];
	$tipe = $dkota['tipe'];
	$kotaids = $dkota['kotaid'];

	if ($tipe == "Kota") $kota = "$kota (Kota)";
	else $kota = $kota;

	$datakota[] = array("kotaid" => $kotaids, "nama" => $kota);
}
sql_free_result($hkota);
$tpl->assign("datakota", $datakota);

//lokasi
$dataarea = array();
$pkec = "select covid_id,nama from tbl_covid_area";
$hkec = sql($pkec);
while ($dkec = sql_fetch_data($hkec)) {
	$covid_id = $dkec['covid_id'];
	$nama = $dkec['nama'];
	$dataarea[] = array("covid_id" => $covid_id, "nama" => $nama);
}
sql_free_result($hkec);
$tpl->assign("dataarea", $dataarea);


if ($aksi == "pay") {
	$tpl->display("pay.html");
} elseif (!$_SESSION['tes_userid']) {
	$tpl->display('tes-personality.html');
} else $tpl->display('tes-personality.html');
