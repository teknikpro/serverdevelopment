<?php 
include("submember.history.php");

//Untuk Member
$mysql = "select id,ringkas,nama,create_date,alias,gambar from tbl_untukmember where published='1' order by id desc limit 4";
$hasil = sql($mysql);

$datauntukmember = array();
$a =1;
while ($data = sql_fetch_data($hasil)) {	
		$tanggal = $data['create_date'];
		$nama = $data['nama'];
		$id = $data['id'];
		$ringkas = $data['ringkas'];
		$alias = $data['alias'];
		$tanggal = tanggal($tanggal);
		$gambar = $data['gambar'];

		if(!empty($gambar)) $gambar = "$fulldomain/gambar/untukmember/$gambar";
			 else $gambar = "";
			 
		$link = "$fulldomain/user/untukmember/read/$id/$alias";
			
		$datauntukmember[$id] = array("id"=>$id,"no"=>$a,"nama"=>$nama,"ringkas"=>$ringkas,"namasec"=>$namasec,"tanggal"=>$tanggal,"alias"=>$alias,"link"=>$link,"gambar"=>$gambar);
		$a++;	
}
$a = 0;
sql_free_result($hasil);
$tpl->assign("datauntukmember",$datauntukmember);



?>