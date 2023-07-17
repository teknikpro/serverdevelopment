<?php 	
$hlm=$file;
$judul_per_hlm = 100;

$sql1 = "select nama,ringkas,alias,secid from tbl_$kanal"."_sec where secid='$katid'";
$hsl1 = sql($sql1);
while ($row = sql_fetch_data($hsl1))
{
	$nama = $row['nama'];
	$ringkas = $row['ringkas'];
	$secid = $row['secid'];
	$alias = $row['alias'];
	
	$tpl->assign("namagaleri",$nama);
	$tpl->assign("detailnama",$nama);
	$tpl->assign("ringkas",$ringkas);
	$tpl->assign("detailringkas",$ringkas);
	
	$sql = "select count(*) as jml from tbl_$kanal where secid='$secid' and gambar!=''";
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
	
	
	
	$sql = "select nama,id,gambar from tbl_$kanal where secid='$secid' and gambar!='' order by id desc limit  $ord,$judul_per_hlm";
	$hsl = sql($sql);
	$datagaleridetail = array();
	while ($row = sql_fetch_data($hsl))
	{
		$nama = $row['nama'];
		$id = $row['id'];
		$gambar = $row['gambar'];
		$url = "$fulldomain/galeri/read/$secid/$id/$alias";
		
		$datagaleridetail[$id] = array("nama"=>$nama,"gambar"=>"$domain/gambar/galeri/$gambar","url"=>$url);
	}
	
	$tpl->assign("datagaleridetail",$datagaleridetail);

	//Paging 
	$batas_page =5;
	
	$stringpage = array();
	$pageid =0;
	
	if ($hlm > 1){
		$prev = $hlm - 1;
		$stringpage[$pageid] = array("nama"=>"Awal","link"=>"$domainfull/$kanal/$aksi/$katid/$alias/$katid/$alias/1");
		$pageid++;
		$stringpage[$pageid] = array("nama"=>"Sebelumnya","link"=>"$domainfull/$kanal/$aksi/$katid/$alias/$prev");

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
			$stringpage[$pageid] = array("nama"=>"$ii","link"=>"$domainfull/$kanal/$aksi/$katid/$alias/$ii");
		}
		$pageid++;
	}
	if ($hlm < $hlm_tot){
		$next = $hlm + 1;
		$stringpage[$pageid] = array("nama"=>"Selanjutnya","link"=>"$domainfull/$kanal/$aksi/$katid/$alias/$next");
		$pageid++;
		$stringpage[$pageid] = array("nama"=>"Akhir","link"=>"$domainfull/$kanal/$aksi/$katid/$alias/$hlm_tot");
	}
	else
	{
		$stringpage[$pageid] = array("nama"=>"Selanjutnya","link"=>"");
		$pageid++;
		$stringpage[$pageid] = array("nama"=>"Akhir","link"=>"");
	}
	$tpl->assign("stringpage",$stringpage);
	//Selesai Paging
}
	?>
