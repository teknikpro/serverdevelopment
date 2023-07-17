<?php
	$judul_per_hlm 	= 10;
	$batas_paging 	= 5;
	$hlm 			= $var[4];
	
	if($tipe == '2')
		$wherepages = "and masjidid='$uid'";
	else
		$wherepages = "and ulamaid='$uid'";

	$sql = "select count(*) as jml from tbl_agenda where published='1' $wherepages";
	$hsl = sql($sql);
	$tot = sql_result($hsl, 0, jml);
	$hlm_tot = ceil($tot / $judul_per_hlm);		
	if (empty($hlm)){
		$hlm = 1;
		}
	if ($hlm > $hlm_tot){
		$hlm = $hlm_tot;
		}
	
	$ord = ($hlm - 1) * $judul_per_hlm;
	if ($ord < 0 ) $ord=0;

	$perintah="select id,ulamaid,masjidid,nama,tanggal,ringkas from tbl_agenda where published='1' $wherepages limit $ord, $judul_per_hlm";
	$hasil=sql($perintah);
	while($row=sql_fetch_data($hasil))
	{
		$id			= $row['id'];
		$ulamaid	= $row['ulamaid'];
		$masjidid	= $row['masjidid'];
		$nama		= $row['nama'];
		$tanggal	= tanggal($row['tanggal']);
		$ringkas		= $row['ringkas'];
		$alias = getAlias($nama);
		
		$masjid = sql_get_var("select userfullname from tbl_member where userid='$masjidid' and tipe = '2'");
		$ulama = sql_get_var("select userfullname from tbl_member where userid='$ulamaid' and tipe = '1'");
		
		$link = "$fulldomain/$username/readagenda/$id/$alias.html";
		
		$datadetail[$id]=array("id"=>id,"masjid"=>$masjid,"ulama"=>$ulama,"nama"=>$nama,"tanggal"=>$tanggal,"ringkas"=>$ringkas,"link"=>$link);
	}
	sql_free_result($hasil);
	$tpl->assign("datadetail",$datadetail);
	
	//Paging 
	$batas_page =5;
	
	$stringpage = array();
	$pageid =0;
	
	if ($hlm > 1){
		$prev = $hlm - 1;
		$stringpage[$pageid] = array("nama"=>"Awal","link"=>"$domainfull/$kanal/$aksi/1");
		$pageid++;
		$stringpage[$pageid] = array("nama"=>"Sebelumnya","link"=>"$domainfull/$kanal/$aksi/$prev");
	
	}
	else {
		$stringpage[$pageid] = array("nama"=>"Awal","link"=>"");
		$pageid++;
		$stringpage[$pageid] = array("nama"=>"Sebelumnya","link"=>"");
	}
	
	$hlm2 = $hlm - (ceil($batas_page/2));
	$hlm4= $hlm+(ceil($batas_page/2));
	
	if($hlm2 <= 0 ) $hlm3=1;
	   else $hlm3 = $hlm2;
	$pageid++;
	for ($ii=$hlm3; $ii <= $hlm_tot and $ii<=$hlm4; $ii++){
		if ($ii==$hlm){
			$stringpage[$pageid] = array("nama"=>"$ii","link"=>"");
		}else{
			$stringpage[$pageid] = array("nama"=>"$ii","link"=>"$domainfull/$kanal/$aksi/$ii");
		}
		$pageid++;
	}
	if ($hlm < $hlm_tot){
		$next = $hlm + 1;
		$stringpage[$pageid] = array("nama"=>"Selanjutnya","link"=>"$domainfull/$kanal/$aksi/$next");
		$pageid++;
		$stringpage[$pageid] = array("nama"=>"Akhir","link"=>"$domainfull/$kanal/$aksi/$hlm_tot");
	}
	else
	{
		$stringpage[$pageid] = array("nama"=>"Selanjutnya","link"=>"");
		$pageid++;
		$stringpage[$pageid] = array("nama"=>"Akhir","link"=>"");
	}
	$tpl->assign("stringpage",$stringpage);
?>