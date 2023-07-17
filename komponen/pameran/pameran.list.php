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

$mysql = "select voucherid,nama,ringkas,alias,published,id,term,harga,startdate,enddate,startusedate,endusedate,qty,views,gambar from tbl_world_voucher where published='1' $where order by create_date desc limit 8";
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
$perintah = "select secid,namasec,alias from tbl_world_sec where status='1' and simposium='1' order by secid asc";
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


$datasection = array();
$queryvideo = "SELECT * FROM tbl_video_yt";
$hasilvideo = sql($queryvideo);
while ($value = sql_fetch_data($hasilvideo)) {
    $id_video   = $value["id_video"];
    $judul      = $value["judul"];
    $gambar     = $value["gambar"];
    $penjelasan = $value["penjelasan"];
    $link       = $value["link"];

    $urlvideo = "$fulldomain/tonton-video/$link";
    $gambarvideo = "$fulldomain/gambar/pameran/$gambar";

    $datasection[$id_video] = array("id_video" => $id_video, "judul" => $judul, "gambar" => $gambarvideo, "penjelasan" => $penjelasan, "link" => $urlvideo);
}
sql_free_result($value);
$tpl->assign("datasection", $datasection);
