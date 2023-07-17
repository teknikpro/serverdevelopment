<?php
$mysql = "select id,ringkas,nama,create_date,alias,gambar,youtubeid from tbl_video_testimoni where published='1' order by rand() limit 2";
$hasil = sql($mysql);

$datadepanvideotestimoni = array();
$a =1;
while ($data = sql_fetch_data($hasil)) {	
		$tanggal = $data['create_date'];
		$nama = $data['nama'];
		$id = $data['id'];
		$ringkas = $data['ringkas'];
		$alias = $data['alias'];
		$tanggal = tanggal($tanggal);
		$gambar = $data['gambar'];
		$youtubeid = $data['youtubeid'];
	
		if(!empty($gambar)) $gambar = "$fulldomain/gambar/videotestimoni/$gambar";
			 else $gambar = "";
		$link = "$fulldomain/videotestimoni/read/$id/$alias";
			
		$datadepanvideotestimoni[$id] = array("id"=>$id,"a"=>$a,"nama"=>$nama,"ringkas"=>$ringkas,"youtubeid"=>$youtubeid,"tanggal"=>$tanggal,"alias"=>$alias,"url"=>$link,"gambar"=>$gambar);
		$a++;	
}
$a = 0;
sql_free_result($hasil);
$tpl->assign("datadepanvideotestimoni",$datadepanvideotestimoni);

?>