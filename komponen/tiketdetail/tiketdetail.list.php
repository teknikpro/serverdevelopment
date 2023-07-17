<?php

$idvoucher = $var[3];

if ($idvoucher == "form") {
    $tpl->assign("tiketform", $idvoucher);
    $tpl->assign("getid", $var[4]);
}

$perintah = "SELECT voucherid,nama,alias,ringkas,term,harga,qty,gambar FROM tbl_world_voucher WHERE voucherid='$idvoucher'";
$hasil = sql($perintah);
$data = sql_fetch_data($hasil);
$voucherid      = $data["voucherid"];
$nama           = $data["nama"];
$alias          = $data["alias"];
$ringkas        = $data["ringkas"];
$term           = $data["term"];
$harga          = number_format($data["harga"]);
$qty            = $data["qty"];
$gambar         = $data["gambar"];

if ($gambar) {
    $gambar = "$fulldomain/gambar/community/$gambar";
} else {
    $gambar = "$fulldomain/gambar/community/community-voucher-tiket-1-keluarga-52.png";
}

$tpl->assign("nama", $nama);
$tpl->assign("alias", $alias);
$tpl->assign("ringkas", $ringkas);
$tpl->assign("term", $term);
$tpl->assign("harga", $harga);
$tpl->assign("qty", $qty);
$tpl->assign("gambar", $gambar);
$tpl->assign("voucherid", $voucherid);
