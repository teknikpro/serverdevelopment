<?php 
$secid = $var[4];
if(!empty($secid))
	$wherefaq = "and secid='$secid'";

$mysql = "select id,jawaban,pertanyaan,create_date from tbl_faq where published='1' $wherefaq order by id asc";
$hasil = sql($mysql);
$datadetail = array();		
$i = 1;
while ($data =  sql_fetch_data($hasil)) 
{	
	$tanggal 	= $data['create_date'];
	$nama 		= $data['pertanyaan'];
	$id			= $data['id'];
	$ringkas 	= $data['jawaban'];
	$tanggal 	= tanggal($tanggal);
		
	$datadetail[$id] = array("id"=>$id,"no"=>$i,"nama"=>$nama,"ringkas"=>$ringkas,"tanggal"=>$tanggal,"alias"=>$alias);
	$i++;
		
}
sql_free_result($hasil);
$tpl->assign("datadetail",$datadetail);

$tpl->assign("rubrik","Frequently Ask Question");
if(!empty($secid)) $tpl->assign("detailnama","$nama");
?>
