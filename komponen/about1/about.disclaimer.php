<?php 

	$mysql = "select id,lengkap,nama,create_date,alias,gambar,gambar1 from tbl_static where alias='$aksi'  limit 1";
	$hasil = sql( $mysql);
	
	$data =  sql_fetch_data($hasil);	
	$tanggal = $data['create_date'];
	$nama = $data['nama'];
	$id = $data['id'];
	$lengkap = $data['lengkap'];
	$alias = $data['alias'];
	$tanggal = tanggal($tanggal);
	$gambar = $data['gambar'];
	$gambar1 = $data['gambar1'];
	$urlradio = $data['urlradio'];
	/*if($i==0){ $gambar= $gambar1; }
	else { $gambar = $gambar; }*/
	
	if(!empty($gambar1)) $gambar1 = "$fulldomain/gambar/$kanal/$gambar1";
	 else $gambar1 = "";
	 
	sql_free_result($hasil);
	$tpl->assign("detailid",$id);
	$tpl->assign("detailnama",$nama);
	$tpl->assign("detailringkas",$ringkas);
	$tpl->assign("detaillengkap",$lengkap);
	$tpl->assign("detailgambar",$gambar1);
	$tpl->assign("detailtanggal",$tanggal);
	//$tpl->assign("rubrik","Disclaimer");
	$tpl->assign("rubrik","$rubrik");
?>
