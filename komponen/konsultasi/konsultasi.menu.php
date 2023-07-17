<?php 
$section = array();
$h = 1;
$perintah = "select secid,nama,keterangan,alias from tbl_konsultasi_sec order by secid asc";
$hasil = sql($perintah);
while ($data =  sql_fetch_data($hasil))
{
	$secid = $data['secid'];
	$namamenu = $data['nama'];
	$aliasmenu = $data['alias'];
	$ketmenu = $data['keterangan'];
	
	$urlmenu = "$fulldomain/konsultasi/list/$aliasmenu";

	$section[$secid] = array("id"=>$secid,"h"=>$h,"nama"=>$namamenu,"urlmenu"=>$urlmenu);
	$h %= 2;
	$h++;

}
sql_free_result($hasil);
$tpl->assign("section",$section);
?>