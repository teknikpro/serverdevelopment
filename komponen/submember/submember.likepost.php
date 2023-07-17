<?php
$postid		= $var[4];
if(!empty($postid))
{
	if($aksi=="like-post")
	{
		$perintah	= "select userid,touserid,type from tbl_post where postid='$postid'";
		$hasil		= sql($perintah);
		$data		= sql_fetch_data($hasil);
			$type		= $data['type'];
			$postuser	= $data['userid'];
			$postuser2	= $data['wall_id'];
			$tanggal	= date("Y-m-d H:i:s");
			
			//cek komen wall atau status
			if ($postuser!=$postuser2) $wall = true;
			else $wall = false;
			
		$query	= "insert into tbl_post_like (postid,username,tanggal) values ('$postid','$_SESSION[username]','$tanggal')";
		$hsl	= sql($query);
		if($hsl)
		{
			if ($postuser!=$_SESSION['username'])
			{
				if ($type=="photo")
					setlog($_SESSION['username'],$postuser,"menyukai foto anda","$fulldomain/user/post/$postid","like");
				elseif ($type=="video")
					setlog($_SESSION['username'],$postuser,"menyukai video anda","$fulldomain/user/post/$postid","like");
				elseif ($wall)
					setlog($_SESSION['username'],$postuser,"menyukai pesan dinding anda","$fulldomain/user/post/$postid","like");
				else
					setlog($_SESSION['username'],$postuser,"menyukai status anda","$fulldomain/user/post/$postid","like");
			}
				
			header("location: $fulldomain/user/post/$postid");
		}
	}
	else if($aksi=="unlike-post")
	{
		$query	= "delete from tbl_post_like where postid='$postid' and username='$_SESSION[username]'";
		$hsl	= sql($query);
		if($hsl)
		{
			header("location: $fulldomain/user/post/$postid");
		}
	}
}
?>