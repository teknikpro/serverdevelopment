<?php
$tpl->assign("userfullname",$_SESSION['userfullname']);
$tpl->assign("done",$var[4]);

$sql = "select secid,nama from tbl_quiz order by nama";
$hsl = sql($sql);
while($data = sql_fetch_data($hsl))
{
	$secid = $data['secid'];
	$namasec=  $data['nama'];
	
	$j = sql_get_var("select count(*) as jml from tbl_quiz_soal where secid='$secid'");
	
	$topik[] = array("secid"=>$secid,"namasec"=>$namasec,"jumlah"=>$j);
}
$tpl->assign("topik",$topik);
?>