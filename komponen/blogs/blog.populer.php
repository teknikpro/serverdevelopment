<?php 
$mysql = "select id,ringkas,nama,create_date,alias,secid,gambar,gambar1,userid from tbl_blog where published='1' order by views desc limit 10";
$hasil = sql( $mysql);


$datapopuler = array();		
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
	$userid = $data['userid'];
	
	$perintah = "select alias from tbl_blog_sec where secid='$secid'";
	$res = sql($perintah);
	$dt =  sql_fetch_data($res);
	$secalias1 = $dt['alias'];
	sql_free_result($res);
	
	if(empty($secalias1))
		$secalias1= "uncategorize";
	
	if($i==1){ $gambar= $gambar1; }
	else { $gambar = $gambar; }
	
	if(!empty($gambar)) $gambar = "$fulldomain/gambar/blog/$userid/$gambar";
	 else $gambar = "";
		 

	$link = "$fulldomain/blog/read/$secalias1/$id/$alias";
		
	$datapopuler[$id] = array("id"=>$id,"no"=>$i,"nama"=>$nama,"ringkas"=>$ringkas,"tanggal"=>$tanggal,"alias"=>$alias,"link"=>$link,"gambar"=>$gambar);
	$i++;
		
}
sql_free_result($hasil);
$tpl->assign("datapopuler",$datapopuler);

?>
