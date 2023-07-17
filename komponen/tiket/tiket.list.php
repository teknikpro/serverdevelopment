<?php

//Cek Detail Atau Bukan
$alias = $var[3];
if($alias!="list" && !empty($alias))
{
	$id = sql_get_var("select id from tbl_world where alias='$alias'");
	if(!empty($id))
	{
		$aksi = "read";
		$tpl->assign("aksi","read");
		
		include("tiket.read.php");
	}
}
else
{

$mysql = "SELECT id,ringkas,nama,create_date,alias,secid,gambar,gambar1 FROM tbl_world WHERE published='1' AND community='1'  "; 
$hasil = sql($mysql);
		
$datadetail = array();
$i = 0;
while ($data =  sql_fetch_data($hasil)) {	
    $tanggal = $data['create_date'];
	$nama = $data['nama'];
	$id = $data['id'];
	$ringkas = ringkas($data['ringkas'],20);
	$alias = $data['alias'];
	$tanggal1 = tanggal($tanggal);
	$gambar = $data['gambar'];
	$secid = $data['secid'];

    $perintah = "select alias,namasec from tbl_world_sec where secid='$secid'";
	$res = sql($perintah);
	$dt =  sql_fetch_data($res);
	$secalias = $dt['alias'];
	$namasec = $dt['namasec'];
	sql_free_result($res);

	
	if(!empty($gambar)) $gambar = "$domain/gambar/community/$gambar";
	 else $gambar = "";
		 

	$link = "$fulldomain/$kanal/$alias";
		
	$datadetail[$id] = array("id"=>$id,"no"=>$i,"nama"=>$nama,"ringkas"=>$ringkas,"tanggal"=>$tanggal1,"date"=>$tanggal,"alias"=>$alias,"link"=>$link,"gambar"=>$gambar,"namasec"=>$namasec);
	$i++;
}

sql_free_result($hasil);
$tpl->assign("datadetail",$datadetail);

}


?>