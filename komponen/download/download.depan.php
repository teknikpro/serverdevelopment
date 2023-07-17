<?php 
$mysql = "select id,ringkas,nama,create_date,alias,gambar,secid from tbl_download where published='1' order by create_date desc limit 3";
$hasil = sql($mysql);

$datadepandownload = array();
$a =1;
while ($data = sql_fetch_data($hasil)) {	
		$tanggal = $data['create_date'];
		$nama = $data['nama'];
		$id = $data['id'];
		$ringkas = $data['ringkas'];
		$alias = $data['alias'];
		$tanggal = tanggal($tanggal);
		$gambar = $data['gambar'];
		$secid = $data['secid'];
		
		
		$mysql1 = "select nama,alias,secid from tbl_download_sec where secid='$secid'";
		$hasil1 = sql($mysql1);
		$data1 = sql_fetch_data($hasil1);
		$secalias = $data1['alias'];

	
		if(!empty($gambar)) $gambar = "$domain/gambar/download/$gambar";
			 else $gambar = "";
		$link = "$fulldomain/download/read/$secalias/$id/$alias.html";
			
		$datadepandownload[$id] = array("id"=>$id,"a"=>$a,"nama"=>$nama,"ringkas"=>$ringkas,"namasec"=>$namasec,"tanggal"=>$tanggal,"alias"=>$alias,"link"=>$link,"gambar"=>$gambar);
		$a++;	
}
$a = 0;
sql_free_result($hasil);
$tpl->assign("datadepandownload",$datadepandownload);

?>