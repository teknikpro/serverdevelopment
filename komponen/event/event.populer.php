<?php 
$mysql = "select id,ringkas,nama,create_date,alias,gambar,gambar1,secid,views from tbl_blog where published='1' order by views desc limit 10";
$hasil = sql($mysql);

$blogpopuler = array();
$a =1;
while ($data = sql_fetch_data($hasil)) {	
		$tanggal = $data['create_date'];
		$nama = $data['nama'];
		$ids = $data['id'];
		$ringkas = $data['ringkas'];
		$ringkas = ringkas($data['ringkas'],22);
		$alias = $data['alias'];
		$tanggal1 = tanggal($tanggal);
		$gambar = $data['gambar'];
		$gambar1 = $data['gambar1'];
		$secid = $data['secid'];
		$views = number_format($data['views'],0,",",".");
		
		if($a=="1") $gambar = $gambar1;
		else $gambar = $gambar;
		
		
		$mysql1 = "select nama,alias,secid from tbl_blog_sec where secid='$secid'";
		$hasil1 = sql($mysql1);
		$data1 = sql_fetch_data($hasil1);
		$secalias = $data1['alias'];

	
		if(!empty($gambar)) $gambar = "$domain/gambar/blog/$gambar";
			 else $gambar = "";
		$link = "$fulldomain/blog/read/$secalias/$ids/$alias";
			
		$blogpopuler[$ids] = array("id"=>$ids,"no"=>$a,"nama"=>$nama,"ringkas"=>$ringkas,"namasec"=>$namasec,"tanggal"=>$tanggal1,"views"=>$views,"alias"=>$alias,"link"=>$link,"gambar"=>$gambar);
		$a++;	
}
$a = 0;
sql_free_result($hasil);
$tpl->assign("blogpopuler",$blogpopuler);

?>