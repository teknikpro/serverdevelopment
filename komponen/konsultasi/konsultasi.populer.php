<?php
$mysql = "select id,ringkas,nama,create_date,alias,gambar,secid,views from tbl_konsultasi where published='1' order by views desc limit 10";
$hasil = sql($mysql);

$dataartikelpopuler = array();
$a =1;
while ($data = sql_fetch_data($hasil)) {	
		$tanggal = $data['create_date'];
		$nama = $data['nama'];
		$ids = $data['id'];
		$ringkas = $data['ringkas'];
		$alias = $data['alias'];
		$tanggal = tanggal($tanggal);
		$gambar = $data['gambar'];
		$secid = $data['secid'];
		$views = $data['views'];
		
		$views = number_format($views,0,0,".");
		
		
		$mysql1 = "select nama,alias,secid from tbl_konsultasi_sec where secid='$secid'";
		$hasil1 = sql($mysql1);
		$data1 = sql_fetch_data($hasil1);
		$secalias = $data1['alias'];
		$namasec = $data1['nama'];

	
		if(!empty($gambar)) $gambar = "$fulldomain/gambar/konsultasi/$gambar";
			 else $gambar = "";
		$link = "$fulldomain/konsultasi/read/$secalias/$ids/$alias";
			
		$dataartikelpopuler[$ids] = array("id"=>$id,"no"=>$a,"nama"=>$nama,"ringkas"=>$ringkas,"namasec"=>$namasec,"tanggal"=>$tanggal,"views"=>$views,"link"=>$link,"gambar"=>$gambar);
		$a++;	
}
$a = 0;
sql_free_result($hasil);
$tpl->assign("konsultasisamping",$dataartikelpopuler);

?>