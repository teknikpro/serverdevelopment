<?php 

$id = $var[4];

$gambar = sql_get_var("select gambar from tbl_post_media where mediaid='$id' and userid='$_SESSION[userid]'");

$ex = explode("-",$gambar);
$yearm = $ex[1];

if(!empty($gambar)) 
{
	$gambar = "$lokasimember/userfiles/$yearm/$gambar";
}
	
$query= "delete from tbl_post_media where mediaid='$id' and userid='$_SESSION[userid]'";
$hasil = sql($query);

if($hasil)
{
	header("location: $fulldomain/user/media");					   
}
else
{
	$pesanhasil = "Penghapusan data media gagal dilakukan.";
	$berhasil = "0";
}
$tpl->assign("pesan",$pesan);
$tpl->assign("pesanhasil",$pesanhasil);
$tpl->assign("berhasil",$berhasil);
?>