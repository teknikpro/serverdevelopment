<?php 
$userid = $_SESSION['userid'];

print_r($_POST);

if($_FILES['file']['size']>0)
{
	$namafile = $_FILES['file']['tmp_name'];
	$namafiles = $_FILES['file']['name'];
	
	$jenis = $_POST['jenis'];
	$chatid = $_POST['chatid'];
	
	
	
	if($jenis=="image")
	{
		$status = "Mengirimkan pesan gambar";
		
		$fbulan = date("Ym");
		if(!file_exists("$lokasimember/userfiles/$fbulan")) mkdir("$lokasimember/userfiles/$fbulan");
		
		//Post Media
		$gambars_maxw = 250;
		$gambars_maxh = 330;
		$gambarm_maxw = 350;
		$gambarm_maxh = 470;
		$gambarl_maxw = 600;
		$gambarl_maxh = 800;
		$gambarf_maxw = 800;
		$gambarf_maxh = 1050;
		
		$newid = newid("id","tbl_chat_message");
		
		$ext 			= getimgext($_FILES['file']);
		$namagambars 	= "media-$fbulan-$userid-$newid-s.$ext";
		$gambars 		= resizeimg($_FILES['file']['tmp_name'],"$lokasimember/userfiles/$fbulan/$namagambars",$gambars_maxw,$gambars_maxh);
		
		$namagambarm 	= "media-$fbulan-$userid-$newid-m.$ext";
		$gambarm 		= resizeimg($_FILES['file']['tmp_name'],"$lokasimember/userfiles/$fbulan/$namagambarm",$gambarm_maxw,$gambarm_maxh);
		
		$namagambarl 	= "media-$fbulan-$userid-$newid-l.$ext";
		$gambarl 		= resizeimg($_FILES['file']['tmp_name'],"$lokasimember/userfiles/$fbulan/$namagambarl",$gambarl_maxw,$gambarl_maxh);
		

		
		if(file_exists("$lokasimember/userfiles/$fbulan/$namagambarl"))
		{ 
			
			$media = array("mediaid"=>$newid,"jenis"=>"image","lokasi"=>"$fbulan","media"=>"uploads/userfiles/$fbulan/$namagambarl","thumb"=>"uploads/userfiles/$fbulan/$namagambars");
			$media = serialize($media);
			
			$to_userid = sql_get_var("select from_userid from tbl_chat where chat_id='$chatid'");
			$date = date("Y-m-d H:i:s");
			
			$sql = "insert into tbl_chat_message(chat_id,from_userid,to_userid,create_date,pesan,media,thumb,jenis,status) values('$chatid','$userid','$to_userid','$date','$status','uploads/userfiles/$fbulan/$namagambarl','uploads/userfiles/$fbulan/$namagambars','image','0')";
			$hsl = sql($sql);
			
			$sql = "update tbl_chat set update_date=now() where chat_id='$chatid'";
			$hsl = sql($sql);
			
			$pesan = substr($status,0,100);
			$title = "Kirim Image Chat untuk anda";		
			
			$data = array("title"=>$title,"message"=>$pesan,"tipe"=>$tipe,"kanal"=>"chat","aksi"=>"chat","postid"=>$chatid,"to_userid"=>$to_userid);
			
			sendgcmbyuserid($title,$pesan, $to_userid, $data);

		}
		
		
	}
	
	if($jenis=="audio")
	{
		$status = "Mengirimkan pesan suara";
		
		$fbulan = date("Ym");
		if(!file_exists("$lokasimember/userfiles/$fbulan")) mkdir("$lokasimember/userfiles/$fbulan");
		
		//Post Media

		
		$newid = newid("id","tbl_chat_message");
		
		$ext 			= getimgext($_FILES['file']);
		$namagambars 	= "media-$fbulan-$userid-$newid-s.m4a";
		copy($_FILES['file']['tmp_name'],"$lokasimember/userfiles/$fbulan/$namagambars");

		
		if(file_exists("$lokasimember/userfiles/$fbulan/$namagambarl"))
		{ 
			
			$to_userid = sql_get_var("select from_userid from tbl_chat where chat_id='$chatid'");
			$date = date("Y-m-d H:i:s");
			
			$sql = "insert into tbl_chat_message(chat_id,from_userid,to_userid,create_date,pesan,media,jenis,status) values('$chatid','$userid','$to_userid','$date','$status','uploads/userfiles/$fbulan/$namagambars','audio','0')";
			$hsl = sql($sql);
			
			$sql = "update tbl_chat set update_date=now() where chat_id='$chatid'";
			$hsl = sql($sql);
			
			$pesan = substr($status,0,100);
			$title = "Kirim Voice Chat untuk anda";		
			
			$data = array("title"=>$title,"message"=>$pesan,"tipe"=>$tipe,"kanal"=>"chat","aksi"=>"chat","postid"=>$chatid,"to_userid"=>$to_userid);
			
			sendgcmbyuserid($title,$pesan, $to_userid, $data);

		}
		
		
	}
	
	$result['status']="OK";
	$result['message']="Berhasil";
	echo json_encode($result);
	
	
}
else
{
	$result['status']="OK";
	$result['upload']="no";
	$result['message']="Tidak ada media yang diupload";
	echo json_encode($result);
}
exit();
?>
