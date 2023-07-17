<?php 
$mediaid		= $_POST['mediaid'];
$postUser	= $_POST['postUser'];
$post		= $_POST['post'];
$komentar	= bersih($_POST['komentar']);
$tanggal = date("Y-m-d H:i:s");

if($_SESSION['username'])
{
	$perintah="insert into tbl_post_media_komen(mediaid,username,userfullname,userid,isi,tanggal) values ('$mediaid', '$_SESSION[username]', '$_SESSION[userfullname]', '$_SESSION[userid]', 
				'$komentar', '$tanggal')";
	$hasil=  sql($perintah);
	if($hasil)
	{
		//Notifikasi
		if($postUser!=$_SESSION['username']) setlog($_SESSION['username'],$postUser,"mengomentari media anda","$fulldomain/user/readmedia/$mediaid");
		
		/*$prnth	= "update tbl_post set jmlkomen=jmlkomen+1 where postid='$postid'";
		$re		=  sql($prnth);*/
		
		$sql	= "select username from tbl_post_media_komen where mediaid='$mediaid' and username!='$_SESSION[username]' and username!='$postUser' group by username";
		$query	=  sql($sql);
		while($row = sql_fetch_data($query))
		{
			$userName2	= $row['username'];
			setlog($_SESSION['username'],$userName2,"mengomentari media $postUser","$fulldomain/$postUser/readmedia/$mediaid","komentar");
		}
		header("location: $fulldomain/$postUser/readmedia/$mediaid");
	}
}
else
	header("location: $fulldomain/$postUser/readmedia/$mediaid");
?>