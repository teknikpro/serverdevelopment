<?php 
$mysql = "select id,testimoni,nama,create_date,company,gambar,userid from tbl_testimoni order by rand() limit 2";
$hasil = sql( $mysql);

$testimonidepan = array();		
$i = 0;
while ($data =  sql_fetch_data($hasil)) {	
	$tanggal = $data['create_date'];
	$nama = $data['nama'];
	$id2 = $data['id'];
	$testimoni = $data['testimoni'];
	$tanggal = tanggal($tanggal);
	$gambar = $data['gambar'];
	$company = $data['company'];
	$company = $data['company'];
	$userids = $data['userid'];
	
	$testimoni = substr($testimoni,0,300)."...";
	
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
	
	

	$testimonidepan[$id2] = array("id"=>$id2,"no"=>$i,"nama"=>$nama,"company"=>$company,"testimoni"=>$testimoni,"tanggal"=>$tanggal,"alias"=>$alias,"link"=>$link,"gambar"=>$gambar);
	$i++;
		
}
sql_free_result($hasil);
$tpl->assign("testimonidepan",$testimonidepan);

?>
