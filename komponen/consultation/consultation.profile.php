<?php 
$tpl->assign("rubrik","$rubrik");

$encryptedData = $var[4];
$key = 12345;
$userid = decrypt($encryptedData, $key);



$mysql = "select userid,username,userfullname,avatar,konsulsecid,konsulsubid,hargakonsultasi,online,rating,aboutme,nama_depan,nama_belakang,gelar,keahlian,kutipan,no_izin,gambardum from tbl_member where tipe='1' and peer='0' and userid='$userid' and tampil_depan='1' order by rating desc limit 1";
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
	$hargarp = rupiah($harga);
	$nama_depan = $data['nama_depan'];
	$nama_belakang = $data['nama_belakang'];
	$gelar = $data['gelar'];
	$keahlian = $data['keahlian'];
	$kutipan = $data['kutipan'];
	$no_izin = $data['no_izin'];
	$gambardum = $data['gambardum'];
	$ringkas = $data['aboutme'];
	
	$sec = sql_get_var("select namasec from tbl_konsul_sec where secid='$konsulsecid'");
	$sub = sql_get_var("select namasub from tbl_konsul_sub where secid='$konsulsecid' and subid='$konsulsubid'");

	$sec = "$sec - $sub";
	
	if(!empty($avatar)) $avatar = "$fulldomain/uploads/avatars/$avatar";
	 else $avatar = "$fulldomain/images/no_pic.jpg"; 

	$url = "$fulldomain/$kanal/profile/$idx";

	$query2 = sql("SELECT penanganan FROM tbl_konsultan_penanganan WHERE userid='$userid' ");
	$isipenanganan = array();
	while($item2 = sql_fetch_data($query2)){
		$penanganan = $item2['penanganan'];

		$isipenanganan[] = [
			"penanganan" => $penanganan,
		];
	}

	$query3 = sql("SELECT pengalaman FROM tbl_konsultan_pengalaman WHERE userid='$userid' ");
	$isipengalaman = array();
	while($item3 = sql_fetch_data($query3)){
		$pengalaman = $item3['pengalaman'];

		$isipengalaman[] = [
			"pengalaman" => $pengalaman,
		];
	}
	
	$datadetail[] = [
		"id"			=> $id,
		"no"			=> $i,
		"nama"			=> $nama,
		"sec"			=> $sec,
		"harga"			=> $harga,
		"hargarp"		=> $hargarp,
		"url"			=> $url,
		"online"		=> $online,
		"avatar"		=> $avatar,
		"rating"		=> $rating,
		"ringkas"		=> $ringkas, 
		"nama_depan" 	=> $nama_depan, 
		"nama_belakang" => $nama_belakang, 
		"gelar" 		=> $gelar, 
		"keahlian" 		=> $keahlian, 
		"kutipan" 		=> $kutipan, 
		"no_izin" 		=> $no_izin, 
		"gambardum" 	=> $gambardum,
		"isipenanganan"	=> $isipenanganan,
		"isipengalaman"	=> $isipengalaman
	];
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
