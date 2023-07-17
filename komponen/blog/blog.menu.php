<?php
$section = array();
$h = 1;
$perintah = "select secid,nama,keterangan,alias from tbl_blog_sec order by nama asc";
$hasil = sql($perintah);
while ($data =  sql_fetch_data($hasil))
{
	$secid = $data['secid'];
	$namamenu = $data['nama'];
	$aliasmenu = $data['alias'];
	$ketmenu = $data['keterangan'];
	
	$count = sql_get_var("select count(*) as jml from tbl_blog where secid=$secid");
	
	$urlmenu = "$fulldomain/blog/list/$aliasmenu.html";
	$namamenu = "$namamenu";

	$section[$secid] = array("id"=>$secid,"h"=>$h,"nama"=>$namamenu,"urlmenu"=>$urlmenu);
	$h %= 2;
	$h++;

}
sql_free_result($hasil);
$tpl->assign("section",$section);

?>