<?php
//Pojok Muslimah
$mysql = "select id,ringkas,nama,create_date,alias,gambar,secid from tbl_blog where published='1' order by id desc limit 3";
$hasil = sql($mysql);

$datadepanblog = array();
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
		
		
		$mysql1 = "select nama,alias,secid from tbl_blog_sec where secid='$secid'";
		$hasil1 = sql($mysql1);
		$data1 = sql_fetch_data($hasil1);
		$secalias = $data1['alias'];
		$namasec = $data1['nama'];

	
		if(!empty($gambar)) $gambar = "$fulldomain/uploads/blog/$bloguserid/$gambar";
			 else $gambar = "";
		$link = "$fulldomain/blog/read/$secalias/$ids/$alias";
			
		$datadepanblog[$ids] = array("id"=>$ids,"no"=>$a,"nama"=>$nama,"ringkas"=>$ringkas,"namasec"=>$namasec,"tanggal"=>$tanggal,"alias"=>$alias,"link"=>$link,"gambar"=>$gambar);
		$a++;	
}
$a = 0;
sql_free_result($hasil);
//$tpl->assign("datadepanblog",$datadepanblog);

?>