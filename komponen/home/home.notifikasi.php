<?php 
if($_SESSION['username'])
{
	//Notifikasi
	$jml = sql_get_var("select count(*) as jml from tbl_notifikasi where (tousername='$_SESSION[username]') and status='0'");
	$tpl->assign("jmlnotifikasi",$jml);
	$tpl->assign("notifikasi",$notifikasi);
	
	//Message
	//$jmlm = sql_get_var("select count(*) as jml from tbl_pesan where penerima='$_SESSION[userid]' and hapus!='$_SESSION[userid]' and baca='0'");
	//$tpl->assign("jmlpesan",$jmlm);
}


?>