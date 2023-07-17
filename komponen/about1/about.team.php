<?php 
$mysql = "select id,lengkap,nama,create_date,alias,gambar from tbl_team where published='1' $where order by id asc";
$hasil = sql( $mysql);
$datadetail = array();	
$jumlahdata	= sql_num_rows($hasil);
$i = 1;
while ($data =  sql_fetch_data($hasil)) 
{	
	$tanggal 	= $data['create_date'];
	$nama 		= $data['nama'];
	$id 		= $data['id'];
	$ringkas 	= $data['lengkap'];
	$alias 		= $data['alias'];
	$tanggal 	= tanggal($tanggal);
	$gambar 	= $data['gambar'];
	
	if(!empty($gambar)) $gambar = "$fulldomain/gambar/$kanal/$gambar";
	 else $gambar = "";

	$link = "$fulldomain/$kanal/read/$id/$alias";
		
	$datadetail[$id] = array("id"=>$id,"no"=>$i,"nama"=>$nama,"ringkas"=>$ringkas,"tanggal"=>$tanggal,"alias"=>$alias,"link"=>$link,"gambar"=>$gambar,"urlradio"=>$urlradio);
	$i++;
}
sql_free_result($hasil);
$tpl->assign("datadetail",$datadetail);
$tpl->assign("jumlahdata",$jumlahdata);
$tpl->assign("rubrik","Our Team");
?>
