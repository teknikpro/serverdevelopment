<?php
	$id = $var[4];

	// data news member
	$nm	= "select id,nama,ringkas,create_date,gambar1,gambar,lengkap,oleh from tbl_news_member where published='1' and id='$id'";
	$qn	= sql($nm);
	$rn = sql_fetch_data($qn);
		$detailid		= $rn['id'];
		$detailnama		= $rn['nama'];
		$detailringkas	= $rn['ringkas'];
		$detaillengkap	= $rn['lengkap'];
		$detailtanggal	= tanggal($rn['create_date']);
		$detailgambar	= $rn['gambar1'];
		$detailgambar1	= $rn['gambar'];
		$detailalias	= getalias($nama);
		$detailoleh		= $rn['oleh'];
		
		if(!empty($detailgambar)) $detailgambar = "$fulldomain/gambar/user-news/$detailgambar";
		else $detailgambar = "";
		
		if(!empty($detailgambar1)) $detailgambar1 = "$fulldomain/gambar/user-news/$detailgambar1";
		else $detailgambar1 = "";
		
	sql_free_result($qn);
	$tpl->assign("detailid",$detailid);
	$tpl->assign("detailnama",$detailnama);
	$tpl->assign("detailringkas",$detailringkas);
	$tpl->assign("detaillengkap",$detaillengkap);
	$tpl->assign("detailtanggal",$detailtanggal);
	$tpl->assign("detailgambar",$detailgambar);
	$tpl->assign("detailgambar1",$detailgambar1);
	$tpl->assign("detailalias",$detailalias);
	$tpl->assign("detailcreator",$detailoleh);
	
	$tpl->assign("namarubrik","$detailnama");
?>