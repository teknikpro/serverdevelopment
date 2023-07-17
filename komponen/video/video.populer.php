<?php  
$mysql = "select id,ringkas,nama,create_date,alias,secid,gambar,gambar1 from tbl_video where published='1' order by views desc  limit 10";
$hasil = sql( $mysql);

$videopopuler = array();		
$i = 1;
while ($data =  sql_fetch_data($hasil)) {	
	$tanggal = $data['create_date'];
	$nama = $data['nama'];
	$id = $data['id'];
	$ringkas = $data['ringkas'];
	$alias = $data['alias'];
	$tanggal = tanggal($tanggal);
	$gambar = $data['gambar'];
	$gambar1 = $data['gambar1'];
	$secid = $data['secid'];
	
	
	if($i==1){ $gambar= $gambar1; }
	else { $gambar = $gambar; }
	
	if(!empty($gambar)) $gambar = "$fulldomain/gambar/video/$gambar";
	 else $gambar = "";
		 

	$link = "$fulldomain/video/read/$id/$alias";
		
	$videopopuler[$id] = array("id"=>$id,"no"=>$i,"nama"=>$nama,"ringkas"=>$ringkas,"tanggal"=>$tanggal,"alias"=>$alias,"link"=>$link,"gambar"=>$gambar);
	$i++;
		
}
sql_free_result($hasil);
$tpl->assign("videopopuler",$videopopuler);

?>
