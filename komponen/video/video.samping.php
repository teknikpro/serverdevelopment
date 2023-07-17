<?php 
$mysql = "select id,ringkas,nama,create_date,alias,gambar,views from tbl_video where published='1' order by views desc limit 2";
$hasil = sql($mysql);

$videosamping = array();
$a =1;
while ($data = sql_fetch_data($hasil)) {	
		$tanggal = $data['create_date'];
		$nama = $data['nama'];
		$id = $data['id'];
		$ringkas = $data['ringkas'];
		$alias = $data['alias'];
		$tanggal = tanggal($tanggal);
		$gambar = $data['gambar'];
		$views = rupiah($data['views']);
		
		$ringkas = substr(bersih($ringkas),0,100);

		if(!empty($gambar)) $gambar = "$fulldomain/gambar/video/$gambar";
			 else $gambar = "";
			 
		$link = "$fulldomain/video/read/$id/$alias";
			
		$videosamping[$id] = array("id"=>$id,"a"=>$a,"nama"=>$nama,"ringkas"=>$ringkas,"views"=>$views,"tanggal"=>$tanggal,"alias"=>$alias,"link"=>$link,"gambar"=>$gambar);
		$a++;	
}
$a = 0;
sql_free_result($hasil);
$tpl->assign("videosamping",$videosamping);

?>