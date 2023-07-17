<?php
$katid = $var[4];
$katid = str_replace(".html", "", $katid);

if (!empty($katid) && $katid != "global") {
	$perintah1 = "select * from tbl_konsul_sec where alias='$katid'";
	$hasil1 = sql($perintah1);
	$row1 = sql_fetch_data($hasil1);
	$namasec = $row1['nama'];
	$secid = $row1['secid'];
	$secalias = $row1['alias'];
	$penjelasan = $row1['penjelasan'];
} else {
	$secalias = "global";
	$tpl->assign("rubrik", "$rubrik");
}

if (!empty($secid)) {
	if ($katid == "sahabat-ngobrol") {
		$where .= " and status_konsultan='2' ";
		$sahabat = "Kategori ini hanya bisa diakses oleh user yang berusia dibawah 18 tahun";
		$sahabatNgobrol = 1;
	} elseif ($katid == "school-counseling") {
		$where .= " and status_konsultan='2' ";
		$sahabat = "Kategori ini hanya bisa akses oleh member sekolah";
		$sekolah = 1;
	} else {
		$where .= " and status_konsultan='0' ";
	}
	$kat = sql_get_var("select namasec from tbl_konsul_sec where secid='$secid'");
}
if (!empty($subid)) {
	$where .= " and ( konsulsubid='$subid' or konsulmultisec like '%-$subid-%')";
	$kat = sql_get_var("select namasub from tbl_konsul_sub where subid='$subid'");
}


$tpl->assign("rubrik", "$rubrik > $kat");


$judul_per_hlm = 10;
$batas_paging = 5;
$hlm = $var[5];

$sql = "select count(*) as jml from tbl_member where tipe='1' and peer='0' $where";
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

$mysql = "select userid,username,userfullname,avatar,konsulsecid,konsulsubid,hargakonsultasi,online,rating,aboutme,judul_simposium from tbl_member where tipe='1' and simposium='1' $where order by rating desc limit $ord, $judul_per_hlm";
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
	$rating = $data['rating'];
	$judul_simposium = $data['judul_simposium'];
	$hargarp = rupiah($harga);

	$ringkas = ringkas($data['aboutme'], 20);

	$sec = sql_get_var("select namasec from tbl_konsul_sec where secid='$konsulsecid'");
	$sub = sql_get_var("select namasub from tbl_konsul_sub where secid='$konsulsecid' and subid='$konsulsubid'");

	$sec = "$sec - $sub";

	if (!empty($avatar)) $avatar = "$fulldomain/uploads/avatars/$avatar";
	else $avatar = "$fulldomain/images/no_pic.jpg";

	if ($katid == "konsultasi-umum") {
		$hargarp = "Harga : " . $hargarp . " / Sesi ";
	} else {
		$hargarp = "";
	}




	$url = "$fulldomain/$kanal/profile/$idx";

	$datadetail[] = array("id" => $id, "no" => $i, "nama" => $nama, "sec" => $sec, "harga" => $harga, "hargarp" => $hargarp, "url" => $url, "online" => $online, "avatar" => $avatar, "rating" => $rating, "ringkas" => $ringkas, "katid" => $katid, "judulsimposium" => $judul_simposium);
	$i++;
}
sql_free_result($hasil);
$tpl->assign("datadetail", $datadetail);
$tpl->assign("sahabat", $sahabat);
$tpl->assign("sekolah", $sekolah);
$tpl->assign("sahabatNgobrol", $sahabatNgobrol);

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
