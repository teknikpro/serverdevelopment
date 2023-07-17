<?php 
$postid		= $_POST['postid'];
$postUser	= $_POST['postUser'];
$post		= $_POST['post'];
$komentar	= bersih($_POST['komentar']);
$tanggal = date("Y-m-d H:i:s");

if($_SESSION['username'])
{
	$perintah="insert into tbl_post_komen(postid,username,userfullname,userid,isi,tanggal) values ('$postid', '$_SESSION[username]', '$_SESSION[userfullname]', '$_SESSION[userid]', 
				'$komentar', '$tanggal')";
	$hasil=  sql($perintah);
	if($hasil)
	{
		//Notifikasi
		if($postUser!=$_SESSION['username']) setlog($_SESSION['username'],$postUser,"mengomentari status anda","$fulldomain/user/post/$postid");
		
		$prnth	= "update tbl_post set jmlkomen=jmlkomen+1 where postid='$postid'";
		$re		=  sql($prnth);
		
		$sql	= "select username from tbl_post_komen where postid='$postid' and username!='$_SESSION[username]' and username!='$postUser' group by username";
		$query	=  sql($sql);
		while($row = sql_fetch_data($query))
		{
			$userName2	= $row['username'];
			setlog($_SESSION['username'],$userName2,"mengomentari status $postUser","$fulldomain/user/post/$postid","komentar");
		}
		header("location: $fulldomain/user/post/$postid");
	}
}
else
	header("location: $fulldomain/user/post/$postid");
?>