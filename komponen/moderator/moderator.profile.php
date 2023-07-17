<?php 
$tpl->assign("rubrik","$rubrik");



$mysql = "select userid,username,userfullname,avatar,konsulsecid,konsulsubid,hargakonsultasi,online,rating,aboutme,judul_simposium from tbl_member where tipe='1' and peer='0' and userid='$katid' order by rating desc limit 1";
$hasil = sql( $mysql);


$datadetail = array();		
$i = 0;
while ($data =  sql_fetch_data($hasil)) {	
	$tanggal = $data['create_date'];
	$nama = $data['userfullname'];
	$idx = $data['userid'];
	$konsulsecid = $data['konsulsecid'];
	$konsulsubid = $data['konsulsubid'];
	$avatar = $data['avatar'];
	$harga = $data['hargakonsultasi'];
	$online = $data['online'];
	$rating = $data['rating'];
	$judul_simposium = $data['judul_simposium'];
	$hargarp = rupiah($harga);
	
	$ringkas = $data['aboutme'];
	
	$sec = sql_get_var("select namasec from tbl_konsul_sec where secid='$konsulsecid'");
	$sub = sql_get_var("select namasub from tbl_konsul_sub where secid='$konsulsecid' and subid='$konsulsubid'");

	$sec = "$sec - $sub";
	
	if(!empty($avatar)) $avatar = "$fulldomain/uploads/avatars/$avatar";
	 else $avatar = "$fulldomain/images/no_pic.jpg";
	 
	

		 

	$url = "$fulldomain/$kanal/profile/$idx";
		
	$datadetail[] = array("id"=>$id,"no"=>$i,"nama"=>$nama,"sec"=>$sec,"harga"=>$harga,"hargarp"=>$hargarp,"url"=>$url,"online"=>$online,"avatar"=>$avatar,"rating"=>$rating,"ringkas"=>$ringkas, "judulsimposium" => $judul_simposium);
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
