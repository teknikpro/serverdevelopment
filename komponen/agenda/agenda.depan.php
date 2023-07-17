<?php
$mysql = "select id,ringkas,nama,alias,tanggal,tempat from tbl_agenda where published='1' order by tanggal desc limit 3";
$hasil = sql($mysql);

$datadepanagenda = array();
$a =1;
while ($data = sql_fetch_data($hasil)) {	
		$tanggal = $data['tanggal'];
		$nama = $data['nama'];
		$id = $data['id'];
		$ringkas = $data['ringkas'];
		$ringkas = substr($ringkas,0,50);
		$alias = $data['alias'];
		$tanggal = tanggalonly($tanggal);
		$tempat = $data['tempat'];

		$link = "$fulldomain/agenda/read/$id/$alias.html";
			
		$datadepanagenda[$id] = array("id"=>$id,"a"=>$a,"nama"=>$nama,"ringkas"=>$ringkas,"tanggal"=>$tanggal,"tempat"=>$tempat,"link"=>$link,"gambar"=>$gambar);
		$a++;	
}
$a = 0;
sql_free_result($hasil);
$tpl->assign("datadepanagenda",$datadepanagenda);

?>