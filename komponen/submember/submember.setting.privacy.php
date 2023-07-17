<?php 
if(isset($_POST['submit']))
{
	$pvprofile	= $_POST['pvprofile'];
	$pvbirth	= $_POST['pvbirth'];
	$pvemail	= $_POST['pvemail'];
	$pvaddress	= $_POST['pvaddress'];
	$pvphonegsm	= $_POST['pvphonegsm'];
	$pvmessage	= $_POST['pvmessage'];
				
	$query= "update tbl_member set pvprofile='$pvprofile',pvbirth='$pvbirth',pvemail='$pvemail',pvaddress='$pvaddress',
			pvphonegsm='$pvphonegsm',pvmessage='$pvmessage' where username='$_SESSION[username]'";
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

$perintah = sql("select pvprofile,pvbirth,pvemail,pvaddress,pvphonegsm,pvmessage from tbl_member where username='$_SESSION[username]'");
$data = sql_fetch_data($perintah);
sql_free_result($perintah);

$pvprofile 	= $data['pvprofile'];
$pvbirth	= $data['pvbirth'];
$pvemail	= $data['pvemail'];
$pvaddress	= $data['pvaddress'];
$pvphonegsm	= $data['pvphonegsm'];
$pvmessage	= $data['pvmessage'];

$tpl->assign("pvprofile",$pvprofile);
$tpl->assign("pvbirth",$pvbirth);
$tpl->assign("pvemail",$pvemail);
$tpl->assign("pvaddress",$pvaddress);
$tpl->assign("pvphonegsm",$pvphonegsm);
$tpl->assign("pvmessage",$pvmessage);
?>