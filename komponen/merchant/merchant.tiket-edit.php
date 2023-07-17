<?php

$idvoucher = $var[4];

$query = sql_get_var_row("SELECT * FROM tbl_world_voucher WHERE voucherid='$idvoucher' ");
$nama           = $query['nama'];
$ringkas        = $query['ringkas'];
$harga          = $query['harga'];
$startdate      = $query['startdate'];
$enddate        = $query['enddate'];
$qty            = $query['qty'];
$startusedate   = $query['startusedate'];
$endusedate     = $query['endusedate'];
$term           = $query['term'];
$id             = $query['id'];

$tpl->assign("nama",$nama);
$tpl->assign("ringkas",$ringkas);
$tpl->assign("harga",$harga);
$tpl->assign("startdate",$startdate);
$tpl->assign("enddate",$enddate);
$tpl->assign("qty",$qty);
$tpl->assign("startusedate",$startusedate);
$tpl->assign("endusedate",$endusedate);
$tpl->assign("term",$term);
$tpl->assign("id",$id);
$tpl->assign("idvoucher",$idvoucher);

if(isset($_POST['namavoucher'])){
    $namavoucher    = $_POST['namavoucher'];
    $ringkas        = $_POST['ringkas'];
    $harga          = $_POST['harga'];
    $awalberlaku    = $_POST['awalberlaku'];
    $akhirberlaku   = $_POST['akhirberlaku'];
    $kuantitas      = $_POST['kuantitas'];
    $awaltayang     = $_POST['awaltayang'];
    $akhirtayang    = $_POST['akhirtayang'];
    $term           = $_POST['term'];
    $idvoucher      = $_POST['idvoucher'];


    $perintah = "UPDATE tbl_world_voucher SET nama='$namavoucher',ringkas='$ringkas',term='$term',startdate='$awalberlaku',enddate='$akhirberlaku',startusedate='$awaltayang',endusedate='$akhirtayang',harga='$harga',qty='$kuantitas' WHERE voucherid='$idvoucher'";
    $hasil = sql($perintah);

    header("location: $fulldomain/merchant/daftar-tiket/editberhasil");
}


?>