<?php 
header("location: /mitra/history");
exit();
/*
$sql = "select count(*) as jml from tbl_chat where to_userid='$_SESSION[userid]'";
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

$perintah	= "select from_userid,chat_id,to_userid,create_date from tbl_chat where to_userid='$_SESSION[userid]' order by update_date desc limit 5";
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
	
	 
	$link = "$fulldomain/mitra/chat/start/$id";
	 
	$no++;
	$datadetail[$id] = array("id"=>$id,"no"=>$i,"user"=>$user,"tanggal"=>$tanggal,"link"=>$link,"gambar"=>$gambar,"status"=>$publish);
}
sql_free_result($hasil);
$tpl->assign("datadetail",$datadetail);

?>*/