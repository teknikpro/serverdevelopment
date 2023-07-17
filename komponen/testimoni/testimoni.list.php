<?php 
$hlm = $var[5];
$judul_per_hlm = 10;;

$sql = "select count(*) as jml from tbl_$kanal where testimoni!=''";
$hsl = sql( $sql);
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

$mysql = "select id,testimoni,nama,create_date,company,gambar from tbl_$kanal where testimoni!='' order by id  desc limit $ord, $judul_per_hlm";
$hasil = sql( $mysql);


$datadetail = array();		
$i = 0;
while ($data =  sql_fetch_data($hasil)) {	
	$tanggal = $data['create_date'];
	$nama = ucwords($data['nama']);
	$id = $data['id'];
	$testimoni = nl2br($data['testimoni']);
	$tanggal = tanggal($tanggal);
	$gambar = $data['gambar'];
	$company = $data['company'];
	$userids = $data['userid'];
	
	$views = number_format($data['views'],0,",",".");
	
	if(!empty($userids))
	{
	
		$perintah	="select userid,avatar from tbl_member where userid='$userids'";
		$hasils= sql($perintah);
		$profil= sql_fetch_data($hasils);
		sql_free_result($hasils);

		$avatar = $profil['avatar'];
		
		if($avatar){ $linkphoto="$fulldomain/uploads/avatars/$avatar"; }
		else { $linkphoto="$lokasiwebtemplate/images/no_pic.jpg"; }
			
		$gambar = $linkphoto;
	}
	else
	{
		
		if(!empty($gambar)) $gambar = "$fulldomain/gambar/testimoni/$gambar";
		 else $gambar = "$fulldomain/images/noimages.jpg";
	}
		 

	$datadetail[$id] = array("id"=>$id,"no"=>$i,"nama"=>$nama,"company"=>$company,"testimoni"=>$testimoni,"tanggal"=>$tanggal,"alias"=>$alias,"link"=>$link,"gambar"=>$gambar);
	$i++;
		
}
sql_free_result($hasil);
$tpl->assign("datadetail",$datadetail);

//Paging 
$batas_page =5;

$stringpage = array();
$pageid =0;

if ($hlm > 1){
	$prev = $hlm - 1;
	$stringpage[$pageid] = array("nama"=>"Awal","link"=>"$fulldomain/$kanal/$aksi/$secalias/1");
	$pageid++;
	$stringpage[$pageid] = array("nama"=>"Sebelumnya","link"=>"$fulldomain/$kanal/$aksi/$secalias/$prev");

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
		$stringpage[$pageid] = array("nama"=>"$ii","link"=>"$fulldomain/$kanal/$aksi/$secalias/$ii");
	}
	$pageid++;
}
if ($hlm < $hlm_tot){
	$next = $hlm + 1;
	$stringpage[$pageid] = array("nama"=>"Selanjutnya","link"=>"$fulldomain/$kanal/$aksi/$secalias/$next");
	$pageid++;
	$stringpage[$pageid] = array("nama"=>"Akhir","link"=>"$fulldomain/$kanal/$aksi/$secalias/$hlm_tot");
}
else
{
	$stringpage[$pageid] = array("nama"=>"Selanjutnya","link"=>"");
	$pageid++;
	$stringpage[$pageid] = array("nama"=>"Akhir","link"=>"");
}
$tpl->assign("stringpage",$stringpage);
		
?>
