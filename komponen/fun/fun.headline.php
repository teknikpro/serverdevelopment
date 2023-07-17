<?php 
$mysql = "select id,ringkas,nama,create_date,alias,gambar,url,kanal from tbl_headline order by create_date desc limit 5";
$hasil = sql($mysql);

$no = 1;
$a = 1;
while ($data = sql_fetch_data($hasil))
 {	
		$tanggal = $data['create_date'];
		$nama = $data['nama'];
		$idheadline = $data['id'];
		$ringkas = ringkas($data['ringkas'],20);
		$url = $data['url'];
		$tanggal = tanggal($tanggal);
		$gambar = $data['gambar'];
		$kanal = $data['kanal'];
		
		if($kanal=="berita"){
			$urlkanal = "$fulldomain/berita";
			$kanal = "Fun Berita";
		}
		if($kanal=="blog"){
			$urlkanal = "$fulldomain/blog";
			$kanal = "Fun Blog";
		}
		
		if(empty($gambar)) $gambar = "/images/img.noimage.jpg";
		
		$link = "$url";
		
		if($no>1) $gambar = str_replace("-l.",".",$gambar);
			
		$dataheadline[] = array("id"=>$idheadline,"a"=>$a,"no"=>$no,"nama"=>$nama,"ringkas"=>$ringkas,"namasec"=>$kanal,"kanal"=>$kanal,"urlkanal"=>$urlkanal,"tanggal"=>$tanggal,"alias"=>$alias,"link"=>$link,"gambar"=>$gambar);
		$a++;
		$no++;	
}
sql_free_result($hasil);
$tpl->assign("dataheadline",$dataheadline);

?>