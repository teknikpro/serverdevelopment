<?php 
$subaksi = $var[4];
if($subaksi=="setonline")
{
	$status = $var[5];
	if($status=="online")
	 { 
		sql("update tbl_member set online='0' where userid='$_SESSION[userid]'"); echo "1"; 
	}
	else if($status=="offline"){
	  sql("update tbl_member set online='1' where userid='$_SESSION[userid]'");
	 
	 }
	 
	$tanggal = date("Y-m-d H:i:s");
	$views = "insert into tbl_member_log(tipe,userid,create_date,via) values('$status','$_SESSION[userid]','$tanggal','web')";
	$hsl = sql( $views);
	
	header("location: $fulldomain/konsultan/");
	exit();
}

$sql = "select count(*) as jml from tbl_chat where to_userid='$_SESSION[userid]' and finish='0'";
$hsl = sql($sql);
$tot = sql_result($hsl, 0, jml);

$tpl->assign("jml_post",$tot);

$hlm_tot = ceil($tot / $judul_per_hlm);		
if (empty($hlm)){
	$hlm = 1;
}
if ($hlm > $hlm_tot){
$hlm = $hlm_tot;
}
$ord = ($hlm - 1) * $judul_per_hlm;
if ($ord < 0 ) $ord=0;

$perintah	= "select from_userid,chat_id,to_userid,create_date from tbl_chat where to_userid='$_SESSION[userid]' and finish='0' order by update_date desc limit 5";
$hasil		= sql($perintah);
$datadetail	= array();
$no = 0;
while($data = sql_fetch_data($hasil))
{
	$tanggal = $data['create_date'];
	$nama = $data['nama'];
	$id = $data['chat_id'];
	$ringkas = $data['ringkas'];
	$alias = $data['alias'];
	$tanggal = tanggal($tanggal);
	$from_userid = $data['from_userid'];
	
	$user = getprofileid($from_userid);
	
	$jml = sql_get_var("select count(*) as jml from tbl_chat_message where to_userid='$_SESSION[userid]' and chat_id='$id'");
	$jmlbelum = sql_get_var("select count(*) as jml from tbl_chat_message where to_userid='$_SESSION[userid]' and chat_id='$id' and isread='0'");
	
	if($jml>0)
	{ 
		
		$link = "$fulldomain/konsultan/chat/start/$id";
		 
		$no++;
		$datadetail[$id] = array("id"=>$id,"no"=>$i,"user"=>$user,"tanggal"=>$tanggal,"link"=>$link,"gambar"=>$gambar,"status"=>$publish,"jmlchat"=>$jml,"jmlnoread"=>$jmlbelum);
	}
}
sql_free_result($hasil);
$tpl->assign("datadetail",$datadetail);

?>