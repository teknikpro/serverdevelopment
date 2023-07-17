<?php  
if(isset($_POST['submit']))
{
	$pvnotif	= $_POST['pvnotif'];
	$pvfriend	= $_POST['pvfriend'];
				
	$query= "update tbl_member set pvnotif='$pvnotif',pvfriend='$pvfriend' where username='$_SESSION[username]'";
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

$perintah = sql("select pvnotif,pvfriend from tbl_member where username='$_SESSION[username]'");
$data = sql_fetch_data($perintah);
sql_free_result($perintah);

$pvnotif	= $data['pvnotif'];
$pvfriend	= $data['pvfriend'];

$tpl->assign("pvnotif",$pvnotif);
$tpl->assign("pvfriend",$pvfriend);
?>