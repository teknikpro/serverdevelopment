<?php
$mysql = "select voucherid,nama,ringkas,alias,published,id,term,harga,startdate,enddate,startusedate,endusedate,qty,views,gambar1,waktumulai,waktuselesai,banner from tbl_world_voucher where voucherid='$katid' order by create_date desc limit 1";
$hasil = sql($mysql);

$datadetail = array();
$a = 1;
while ($data = sql_fetch_data($hasil)) {
	$tanggal = $data['create_date'];
	$nama = $data['nama'];
	$voucherid = $data['voucherid'];
	$ringkas = ringkas($data['ringkas'], 15);
	$term = $data['term'];
	$tanggal = tanggal($tanggal);
	$gambar = $data['gambar1'];
	$idx = $data['id'];
	$harga = rupiah($data['harga']);
	$startdate = tanggalbulan($data['startdate']);
	$enddate = tanggalsingkat($data['enddate']);
	$waktumulai = date('H:i', strtotime($data['waktumulai']));
	$waktuselesai = date('H:i', strtotime($data['waktuselesai']));
	$banner = $data['banner'];


	$mysql1 = "select nama,alias,secid,alamat from tbl_world where id='$idx'";
	$hasil1 = sql($mysql1);
	$data1 = sql_fetch_data($hasil1);
	$secalias = $data1['alias'];
	$namasec = $data1['nama'];
	$alamat = $data1['alamat'];

	// jenis tiket
	$jenistiket = array();
	$mysql2 = "SELECT jenis_tiket,tanggal_mulai,tanggal_selesai,harga,jumlah FROM tbl_world_jenis_tiket WHERE id_voucher ='$katid' ";
	$hasil2 = sql($mysql2);

	while($item = sql_fetch_data($hasil2)){
		$jenis_tiket 		= $item['jenis_tiket'];
		$tanggal_mulai		= $item['tanggal_mulai'];
		$tanggal_selesai	= tanggalsingkat($item['tanggal_selesai']);
		$jam_selesai		= jamselesai($item['tanggal_selesai']);
		$harga				= rupiah($item['harga']);
		$jumlah				= $item['jumlah'];


		$jenistiket[] = [
			"jenis_tiket"			=> $jenis_tiket,
			"tanggal_mulai"			=> $tanggal_mulai,
			"tanggal_selesai"		=> $tanggal_selesai,
			"jam_selesai"			=> $jam_selesai,
			"harga"					=> $harga,
			"jumlah"				=> $jumlah
		];
	}


	if (!empty($gambar)) $gambar = "/gambar/community/$gambar";
	else $gambar = "";

	if (!empty($banner)) $banner = "/gambar/world/$banner";
	else $banner = "";

	$link = "$fulldomain/corner/read/$voucherid/$alias";

	$datadetail[] = array("id" => $id, "a" => $a, "nama" => $nama, "ringkas" => $ringkas, "namasec" => $namasec, "term" => $term, "tanggal" => $tanggal, "harga" => $harga, "link" => $link, "startdate" => $startdate, "enddate" => $enddate, "waktumulai" => $waktumulai, "waktuselesai" => $waktuselesai, "alamat" => $alamat, "gambar" => $gambar, "banner" => $banner, "datatiket" => $jenistiket);
	$a++;
}
$a = 0;
sql_free_result($hasil);
$tpl->assign("datadetail", $datadetail);
$tpl->assign("detailid", $voucherid);
$tpl->assign("detailnama", $nama);
$tpl->assign("harga", $harga);

$login = $_SESSION['userid'];

if (!$login and $harga > 0) {
	$ceklogin = 'onsubmit="return cek();"';
}


if ($harga == 0) {
	$linkevent = "/cart/gratis";
} else {
	$linkevent = "/cart/evoucher";
}

$tpl->assign("linkevent", $linkevent);
$tpl->assign("ceklogin", $ceklogin);
