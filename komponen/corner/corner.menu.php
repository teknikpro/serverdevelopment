<?php
$section = array();
$h = 1;
$perintah = "select secid,namasec,alias from tbl_world_sec where status='1' order by secid asc";
$hasil = sql($perintah);
while ($data =  sql_fetch_data($hasil)) {
	$secid = $data['secid'];
	$namamenu = $data['namasec'];
	$aliasmenu = $data['alias'];
	$ketmenu = $data['keterangan'];

	$urlmenu = "$fulldomain/corner/list/$aliasmenu";

	$section[$secid] = array("id" => $secid, "h" => $h, "nama" => $namamenu, "url" => $urlmenu);
	$h %= 2;
	$h++;
}
sql_free_result($hasil);
$tpl->assign("section", $section);
