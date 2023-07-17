<?php  
$mysql = "select id,ringkas,nama,create_date,alias,gambar from tbl_paketwisata where published='1' order by create_date desc limit 6";
$hasil = sql($mysql);

$datadepanpaketwisata = array();
$a =1;
while ($data = sql_fetch_data($hasil)) {	
		$tanggal = $data['create_date'];
		$nama = $data['nama'];
		$id = $data['id'];
		$ringkas = $data['ringkas'];
		$alias = $data['alias'];
		$tanggal = tanggal($tanggal);
		$gambar = $data['gambar'];
		
		if(!empty($gambar)) $gambar = "$domain/gambar/paketwisata/$gambar";
			 else $gambar = "";
		$link = "$fulldomain/paketwisata/read/$secalias/$id/$alias.html";
			
		$datadepanpaketwisata[$id] = array("id"=>$id,"a"=>$a,"nama"=>$nama,"ringkas"=>$ringkas,"tanggal"=>$tanggal,"alias"=>$alias,"link"=>$link,"gambar"=>$gambar);
		$a++;	
}
$a = 0;
sql_free_result($hasil);
$tpl->assign("datadepanpaketwisata",$datadepanpaketwisata);

?>