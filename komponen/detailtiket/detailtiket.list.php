<?php

$menuvoucher = $var[3];

$mysql = "select voucherid,nama,ringkas,alias,published,id,term,harga,startdate,enddate,startusedate,endusedate,qty,views,gambar1 from tbl_world_voucher where voucherid='$menuvoucher' order by create_date desc limit 1";
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
    $idx = $data['idx'];
    $harga = rupiah($data['harga']);


    $mysql1 = "select nama,alias,secid from tbl_world where id='$idx'";
    $hasil1 = sql($mysql1);
    $data1 = sql_fetch_data($hasil1);
    $secalias = $data1['alias'];
    $namasec = $data1['nama'];


    if (!empty($gambar)) $gambar = "/gambar/world/$gambar";
    else $gambar = "";

    $link = "$fulldomain/corner/read/$voucherid/$alias";


    $datadetail[] = array("id" => $id, "a" => $a, "nama" => $nama, "ringkas" => $ringkas, "namasec" => $namasec, "term" => $term, "tanggal" => $tanggal, "harga" => $harga, "link" => $link, "gambar" => $gambar);
    $a++;
}
$a = 0;
sql_free_result($hasil);
$tpl->assign("datadetail", $datadetail);
$tpl->assign("detailid", $voucherid);
$tpl->assign("detailnama", $nama);
