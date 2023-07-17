<?php 
$hlm = $var[5];
$judul_per_hlm = 10;;

$sql = "select count(*) as jml from tbl_post where media!='' and home='1'";
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

$mysql = "select postid,media,isi,userid,tanggal from tbl_post where media!='' and home='1' order by postid desc limit $ord, $judul_per_hlm";
$hasil = sql( $mysql);


$datadetail = array();		
$i = 0;
while ($data =  sql_fetch_data($hasil)) {	
	$tanggal = $data['tanggal'];
	$id = $data['postid'];
	$isi = nl2br($data['isi']);
	$tanggal = tanggal($tanggal);
	$media = $data['media'];
	$userids = $data['userid'];
	
	$views = number_format($data['views'],0,",",".");
	
	//Media
	if(!empty($media))
	{
		$media = unserialize($media);
		$mjenis = $media['jenis'];
		$mcontent = $media['gambar'];
		$mlokasi = $media['lokasi'];
		$mcontent = $media['media'];
		$myoutubeid = $media['youtubeid'];
		$mnama = $media['nama'];
		$mcontent = "$fulldomain/$mcontent";
		$murl = $media['url'];		
	}
	
	if(!empty($userids))
	{
	
		$perintah	="select userid,avatar,userfullname from tbl_member where userid='$userids'";
		$hasils= sql($perintah);
		$profil= sql_fetch_data($hasils);
		sql_free_result($hasils);

		$userfullname = ucwords($profil['userfullname']);
		
		if($avatar){ $linkphoto="$fulldomain/uploads/avatars/$avatar"; }

	}
	
		 

	$datadetail[] = array("id"=>$id,"no"=>$i,"nama"=>$userfullname,"company"=>$company,"isi"=>$isi,"tanggal"=>$tanggal,"alias"=>$alias,"link"=>$link,"gambar"=>$mcontent);
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
