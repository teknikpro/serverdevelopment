<?php

$alamat = $var[4];

$idcorner = $var[5];

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

$mysql = "select voucherid,nama,ringkas,alias,published,id,term,harga,startdate,enddate,startusedate,endusedate,qty,views,gambar from tbl_world_voucher where simposium='1' $where order by create_date desc limit 8";
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
    $idx = $data['idx'];
    $harga = rupiah($data['harga']);

    $mysql1 = "select nama,alias,secid from tbl_world where id='$idx'";
    $hasil1 = sql($mysql1);
    $data1 = sql_fetch_data($hasil1);
    $secalias = $data1['alias'];
    $namasec = $data1['nama'];

    if (!empty($gambar)) $gambar = "$fulldomain/gambar/world/$gambar";
    else $gambar = "";

    $link = "$fulldomain/detailtiket/$voucherid/$alias?menu=detailtiket&id=$voucherid";

    $datadepankonsultasi[] = array("id" => $id, "a" => $a, "nama" => $nama, "ringkas" => $ringkas, "namasec" => $namasec, "urlkanal" => $urlkanal, "tanggal" => $tanggal, "harga" => $harga, "link" => $link, "gambar" => $gambar);
    $a++;
}
$a = 0;
sql_free_result($hasil);
$tpl->assign("datadepankonsultasi", $datadepankonsultasi);
$tpl->assign("namaevent", $namaevent);


if (!$alamat) {
    $alamat = "about";
}

$tpl->assign("about", $alamat);

$section = array();
$h = 1;
$perintah = "select secid,namasec,alias from tbl_world_sec where simposium='1' order by secid asc";
$hasil = sql($perintah);
while ($data =  sql_fetch_data($hasil)) {
    $secid = $data['secid'];
    $namamenu = $data['namasec'];
    $aliasmenu = $data['alias'];
    $ketmenu = $data['keterangan'];

    $urlmenu = "$fulldomain/paket/list/$aliasmenu";

    $section[$secid] = array("id" => $secid, "h" => $h, "nama" => $namamenu, "url" => $urlmenu);
    $h %= 2;
    $h++;
}
sql_free_result($hasil);
$tpl->assign("section", $section);

$mysql1 = "select userid,username,userfullname,avatar,konsulsecid,konsulsubid,hargakonsultasi,online,rating,aboutme from tbl_member where tipe='1' and simposium='1' order by rating asc limit 8";
$hasil1 = sql($mysql1);

$datadetail = array();
$i = 0;
while ($data =  sql_fetch_data($hasil1)) {
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

    $sec = sql_get_var("select namasec from tbl_konsul_sec where secid='$konsulsecid'");
    $sub = sql_get_var("select namasub from tbl_konsul_sub where secid='$konsulsecid' and subid='$konsulsubid'");

    $sec = "$sec - $sub";

    if (!empty($avatar)) $avatar = "$fulldomain/uploads/avatars/$avatar";
    else $avatar = "$fulldomain/images/no_pic.jpg";

    $url = "$fulldomain/$kanal/profile/$idx";

    $datadepankonsultan[] = array("id" => $id, "no" => $i, "nama" => $nama, "sec" => $sec, "url" => $url, "ringkas" => $ringkas, "harga" => $harga, "hargarp" => $hargarp, "online" => $online, "avatar" => $avatar, "rating" => $rating);
    $i++;
}
sql_free_result($hasil);
$tpl->assign("datadepankonsultan", $datadepankonsultan);

$dataaja = "heheh";
$tpl->assign("dataaja", $dataaja);
