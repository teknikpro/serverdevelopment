<?php 
if(isset($_POST['submit']))
{
	$aboutme	= bersih($_POST['aboutme']);
	$wiseword	= bersih($_POST['wiseword']);
	$companies	= bersih($_POST['companies']);
	$hobi		= bersih($_POST['hobi']);
	
	$query= "update tbl_member set aboutme='$aboutme',wiseword='$wiseword',companies='$companies',userhobi='$hobi' where username='$_SESSION[username]'";
	$hasil = sql($query);
	
   if($hasil)
   {
						   
	$pesanhasil = "Selamat Data anda di $title telah berhasil diupdate, Lakukan perubahan profil secara berkala disesuaikan dengan kondisi anda saat ini.";
	$berhasil = "1";
	}
				
	
$tpl->assign("pesan",$pesan);
$tpl->assign("pesanhasil",$pesanhasil);
$tpl->assign("berhasil",$berhasil);

}

$perintah = sql("select aboutme,companies,wiseword,userhobi from tbl_member where username='$_SESSION[username]'");
$data = sql_fetch_data($perintah);
sql_free_result($perintah);

$aboutme		= cleaninsert($data['aboutme']);
$companies		= $data['companies'];
$wiseword 		= $data['wiseword'];
$userhobi		= $data['userhobi'];

$tpl->assign("aboutme",$aboutme);
$tpl->assign("companies",$companies);
$tpl->assign("wiseword",$wiseword);
$tpl->assign("userhobi",$userhobi);
?>