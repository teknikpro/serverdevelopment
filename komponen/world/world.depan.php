<?php 
$mysql = "select id,ringkas,nama,create_date,alias,gambar,gambar1,secid from tbl_world where published='1' order by rand() limit 3";
$hasil = sql($mysql);

$datadepanworld = array();
$a =1;
while ($data = sql_fetch_data($hasil)) {	
		$tanggal = $data['create_date'];
		$nama = $data['nama'];
		$id = $data['id'];
		$ringkas = $data['ringkas'];
		$ringkas = ringkas($data['ringkas'],15);
		$alias = $data['alias'];
		$tanggal1 = tanggal($tanggal);
		$gambar = $data['gambar'];

		$secid = $data['secid'];
			
		
		$mysql1 = "select namasec,alias,secid from tbl_world_sec where secid='$secid'";
		$hasil1 = sql($mysql1);
		$data1 = sql_fetch_data($hasil1);
		$secalias = $data1['alias'];
		$namasec = $data1['namasec'];

	
		if(!empty($gambar)) $gambar = "$domain/gambar/world/$gambar";
			 else $gambar = "";
	
		$link = "$fulldomain/world/$alias";
		$linksec = "$fulldomain/world/list/$secalias";
			
		$datadepanworld[$id] = array("id"=>$id,"no"=>$a,"linksec"=>$linksec,"nama"=>$nama,"ringkas"=>$ringkas,"namasec"=>$namasec,"tanggal"=>$tanggal1,"namasec"=>$namasec,"alias"=>$alias,"link"=>$link,"gambar"=>$gambar);
		$a++;	
}
$a = 0;
sql_free_result($hasil);
$tpl->assign("datadepanworld",$datadepanworld);

?>