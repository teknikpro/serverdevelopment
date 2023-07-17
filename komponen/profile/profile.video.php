<?php
	
	$judul_per_hlm = 10;
	$batas_paging = 5;
	$hlm 			= $var[5];
	/*$aliassec	= $var[4];
	
	if(empty($aliassec) or ($aliassec == "allsec"))
	{
		$where	= '';
		$aliass	= 'allsec';
	}
	else
	{
		$temp	= explode("-",$aliassec);
		$jenis	= $temp[0];
		$secid	= $temp[1];
		if($jenis == "masjid")
			$where	= "and masjidid='$secid'";
		elseif($jenis == "kategori")
			$where	= "and secid='$secid'";
		else
			$where	= "and ulamaid='$secid'";
			
		$aliass	= $aliassec;
	}
	
	$tpl->assign("aliass",$aliass);*/

if($tipe == '2')
	$where = "and masjidid='$uid'";
else
	$where = "and ulamaid='$uid'";

$sql = "select count(*) as jml from tbl_konten where published='1' and tipe = 'video' $where";
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
$x=0;

$sql	= "select id,nama,ringkas,create_date,gambar,durasi,ulamaid,masjidid,secid,views from tbl_konten where published='1' and tipe = 'video' $where order by id desc limit $ord, $judul_per_hlm";
$query	= sql($sql);
$aa		= 1;
while($row = sql_fetch_data($query))
{
	$id			= $row['id'];
	$nama		= $row['nama'];
	$alias		= getAlias($nama);
	$lengkap	= $row['lengkap'];
	$durasi		= $row['durasi'];
	/*$ulamaId		= getUlama($row['ulamaId']);
	$masjidId		= getMasjid($row['masjidId']);*/
	$ringkas	= $row['ringkas'];
	$jmlView	= $row['views'];

	$gambar	= $row['gambar'];
	$tanggal= tanggal($row['create_date']);
	$secid= $row['secid'];
	if(strlen($judul) > 50)
	{
		$nama	= substr($nama,0,50);
		$nama	.="...";
	}
	else
		$nama		= $nama;
		
	if(strlen($ringkas) > 80)
	{
		$ringkas	= substr($ringkas,0,80);
		$ringkas	.="...";
	}
	else
		$ringkas		= $ringkas;
	
	if($gambar)
		$gambar="$fulldomain/media/video/$gambar";
	else
		$gambar="";
		
	$durasi = str_replace("Menit","",$durasi);
	$durasi = str_replace("menit","",$durasi);
	
	$secalias1 = sql_get_var("select alias from tbl_konten_sec where secid='$secid'");
	
	$link_detail	= "$fulldomain/video/read/$secalias1/$id/$alias.html";
	
	$list[$id]	= array("id"=>$id,"tanggal"=>$tanggal,"nama"=>$nama,"durasi"=>$durasi,"ringkas"=>$ringkas,"link"=>$link_detail,"gambar"=>$gambar,"nama"=>$nama,"jmlKomen"=>$jmlKomen,"jmlView"=>$jmlView,"ulama"=>$ulamaId,"masjid"=>$masjidId);
	
	$aa++;
}
sql_free_result($query);
$tpl->assign("datadetail",$list);

$batas_page =5;

$stringpage = array();
$pageid =0;
if ($hlm > 1){
	$prev = $hlm - 1;
	$stringpage[$pageid] = array("nama"=>"Awal","link"=>"$domainfull/$name/$aksi/1");
	$pageid++;
	$stringpage[$pageid] = array("nama"=>"Sebelumnya","link"=>"$domainfull/$name/$aksi/$prev");

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
if($site!="m")

for ($ii=$hlm3; $ii <= $hlm_tot and $ii<=$hlm4; $ii++){
	if ($ii==$hlm){
		$stringpage[$pageid] = array("nama"=>"$ii","link"=>"");
	}else{
		$stringpage[$pageid] = array("nama"=>"$ii","link"=>"$domainfull/$name/$aksi/$ii");
	}
	$pageid++;
}
if ($hlm < $hlm_tot){
	$next = $hlm + 1;
	$stringpage[$pageid] = array("nama"=>"Selanjutnya","link"=>"$domainfull/$name/$aksi/$next");
	$pageid++;
	$stringpage[$pageid] = array("nama"=>"Akhir","link"=>"$domainfull/$name/$aksi/$hlm_tot");
}
else
{
	$stringpage[$pageid] = array("nama"=>"Selanjutnya","link"=>"");
	$pageid++;
	$stringpage[$pageid] = array("nama"=>"Akhir","link"=>"");
}
$tpl->assign("stringpage",$stringpage);
//Selesai Paging
?>