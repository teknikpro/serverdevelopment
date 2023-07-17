<?php

$mysql = "select userid,username,userfullname,avatar,konsulsecid,konsulsubid,hargakonsultasi,online,rating,aboutme from tbl_member where tipe='1' and peer='0' and guru='0' and status_konsultan='0' order by rating asc limit 8";
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
