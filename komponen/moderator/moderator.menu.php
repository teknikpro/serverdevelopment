<?php
$section = array();
$h = 1;
$perintah = "select secid,namasec,alias,penjelasan from tbl_konsul_sec where tampil_web='1' order by secid asc";
$hasil = sql($perintah);
while ($data =  sql_fetch_data($hasil)) {
	$secid = $data['secid'];
	$namamenu = $data['namasec'];
	$aliasmenu = $data['alias'];
	$ketmenu = $data['keterangan'];
	$penjelasan = $data['penjelasan'];

	$urlmenu = "$fulldomain/consultation/konsultan/$aliasmenu";

	$section[$secid] = array("id" => $secid, "h" => $h, "nama" => $namamenu, "penjelasan" => $penjelasan, "url" => $urlmenu);
	$h %= 2;
	$h++;
}
sql_free_result($hasil);
$tpl->assign("section", $section);
