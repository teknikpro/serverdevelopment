<?php 
$mysql = "select id,ringkas,nama,create_date,alias,gambar,secid,views from tbl_konsultasi where published='1' and lengkap!='' order by create_date desc limit 3";
$hasil = sql($mysql);

$datadepankonsultasi = array();
$a =1;
while ($data = sql_fetch_data($hasil)) {	
		$tanggal = $data['create_date'];
		$nama = $data['nama'];
		$id = $data['id'];
		$ringkas = ringkas($data['ringkas'],15);
		$alias = $data['alias'];
		$tanggal = tanggal($tanggal);
		$gambar = $data['gambar'];
		$secid = $data['secid'];
		$views = rupiah($data['views']);
		
		
		$mysql1 = "select nama,alias,secid from tbl_konsultasi_sec where secid='$secid'";
		$hasil1 = sql($mysql1);
		$data1 = sql_fetch_data($hasil1);
		$secalias = $data1['alias'];
		$namasec = $data1['nama'];

	
		if(!empty($gambar)) $gambar = "/gambar/konsultasi/$gambar";
			 else $gambar = "";
		
		$link = "$fulldomain/konsultasi/read/$secalias/$id/$alias";
		$urlkanal = "$fulldomain/konsultasi/list/$secalias";
			
		$datadepankonsultasi[$id] = array("id"=>$id,"a"=>$a,"nama"=>$nama,"ringkas"=>$ringkas,"namasec"=>$namasec,"urlkanal"=>$urlkanal,"tanggal"=>$tanggal,"views"=>$views,"link"=>$link,"gambar"=>$gambar);
		$a++;	
}
$a = 0;
sql_free_result($hasil);
$tpl->assign("datadepankonsultasi",$datadepankonsultasi);


//Konsultan
$perintah 	= "select userid from tbl_member where useractivestatus='1' and tipe='1'";
$hasil 		= sql($perintah);
while ($data =  sql_fetch_data($hasil))
{
	$userid 		= $data['userid'];
	$jmlaudio 	= rupiah($data['jmlaudio']);
	
	$user = getprofileid($userid);
				
	$uploader[$userid] = array("id"=>$userid,"user"=>$user,"jmlaudio"=>$jmlaudio);
	$h %= 2;
	$h++;
}
sql_free_result($hasil);
$tpl->assign("uploader",$uploader);

?>