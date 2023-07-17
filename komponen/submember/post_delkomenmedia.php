<?php 
$mediaid=$var[4];
$id=$var[5];
	
$perintah="delete from tbl_post_media_komen where username='$_SESSION[username]' and id='$id'";
$hasil= mysql_query($perintah);
if($hasil)
{
	header("location:$fulldomain/user");
}
?>