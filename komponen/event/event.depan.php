<?php 
$mysql = "select id,ringkas,nama,create_date,alias,gambar from tbl_services where published='1' order by create_date desc limit 4";
$hasil = sql($mysql);

$datadepanservices = array();
$a =1;
while ($data = sql_fetch_data($hasil)) {	
		$tanggal = $data['create_date'];
		$nama = $data['nama'];
		$id = $data['id'];
		$ringkas = bersih($data['ringkas']);
		$alias = $data['alias'];
		$tanggal = tanggal($tanggal);
		$gambar = $data['gambar'];
		$secid = $data['secid'];
		
		$ringkas2 = "";
		$ringkas1 = explode(" ",$ringkas);
		for($c=0;$c<10;$c++)
		{
			$ringkas2 .= "$ringkas1[$c] ";
		}
		
	
		if(!empty($gambar)) $gambar = "$domain/gambar/services/$gambar";
			 else $gambar = "";
		$link = "$fulldomain/services/read/$id/$alias";
			
		$datadepanservices[$id] = array("id"=>$id,"a"=>$a,"nama"=>$nama,"ringkas"=>$ringkas2,"namasec"=>$namasec,"tanggal"=>$tanggal,"alias"=>$alias,"link"=>$link,"gambar"=>$gambar);
		$a++;	
}
$a = 0;
sql_free_result($hasil);
$tpl->assign("datadepanservices",$datadepanservices);

?>