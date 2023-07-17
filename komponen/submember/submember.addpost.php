<?php
if(empty($_SESSION['username'])) exit();
if($_POST['tousername']!="")
{
	$tousername 	= $_POST['tousername'];
	$ex 			= getProfileName($tousername);
	$touserfullname = $ex['userfullname'];
	$touserid 		= $ex['userid'];
}
else
{
	$tousername 	= $_SESSION['username'];
	$touserid 		= $_SESSION['userid'];
	$touserfullname = $_SESSION['userfullname'];
}

$sharefb	= $_POST['sharefb'];
$sharetw	= $_POST['sharetw'];
$isi		= bersih($_POST['isi']);

//die($_SESSION['twcid']);
//Post Media
$gambars_maxw = 250;
$gambars_maxh = 330;
$gambarm_maxw = 350;
$gambarm_maxh = 470;
$gambarl_maxw = 600;
$gambarl_maxh = 800;
$gambarf_maxw = 800;
$gambarf_maxh = 1050;

$jmlstring = strlen($isi)-substr_count($isi, ' ');

if(!empty($isi) && $jmlstring>9 )
{
	if($isi == "Apa yang Anda pikirkan?") $isi = "";
	
	$tanggal	= date("Y-m-d H:i:s");
	$idbaru 	= newid("postid","tbl_post");

	$perintah2	= "insert into tbl_post (postid,userid,username,tousername,touserid,userfullname,touserfullname,isi,tanggal,home) 
				values ('$idbaru','$_SESSION[userid]','$_SESSION[username]','$tousername','$touserid','$_SESSION[userfullname]','$touserfullname','$isi','$tanggal','$_SESSION[verified]')";
	$hasil2		=  sql($perintah2);
	
	
	
	if($hasil2)
	{
		$mention = "";
		$isii = explode(" ",$isi);
		for($u=0;$u<count($isii);$u++)
		{
			$kata = $isii[$u];
			if($kata[0]=="@")
			{
				$katax = str_replace("@","",$kata);
				
				$mnt = sql_get_var("select username from tbl_member where username='$katax' limit 1");
				
				if(!empty($mnt))
				{
					$mention .="$katax,";
					setlog($_SESSION['username'],$katax,"menyebut sahabat dalam statusnya","$fulldomain/user/post/$idbaru","comment");
					$isi = str_replace("$kata","<a href=\"$fulldomain/$mnt\">$kata</a>",$isi);
				}
				
			}
			/*if($kata[0]=="#")
			{
				$katax = str_replace("#","",$kata);
				$isi = str_replace("$kata","<a href=\"$fulldomain/user/cari/$katax\">$kata</a>",$isi);
			}
			if(preg_match("/http:\/\//i",$kata))
			{
				$isi = str_replace("$kata","<a href=\"$kata\" target=\"_blank\"  title=\"$kata\">Tautan</a>",$isi);
			}*/
		}
		
		$sqlp	= "update tbl_member set posting=posting+1 where username='$_SESSION[username]'";
		$qry 	= sql($sqlp);
	}
	
	//Cek Youtube
	if(preg_match("/youtube.com\/watch\?v=/i",$isi))
	{
		$conten = explode(" ",$isi);
		for($i=0;$i<count($conten);$i++)
		{
			if(preg_match("/youtube.com\/watch\?v=/i",$conten[$i]))
			{ 
				$youtube = $conten[$i];
				$isibaru = str_replace($youtube,"",$isi);
			}
		}
		
		$video	= explode("watch?v=",$youtube);
		$video	= explode("&",$video[1]);
		
		$youtubeid = $video[0];
		
		$newid 	= newid("mediaid","tbl_post_media");
		$fbulan = date("Ym");
		if(!file_exists("$lokasimember/userfiles/$fbulan")) mkdir("$lokasimember/userfiles/$fbulan");
		
		//Get data from Youtube
		$url 	= "http://gdata.youtube.com/feeds/api/videos/$youtubeid";
		$curl 	= curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_TIMEOUT, 20);
		$result = curl_exec($curl); 
		
		$youtubetitle 	= ucwords(getTitle($result));
		$youtubedesc 	= getDesc($result);
		$youtubethumb 	= getThumbnailUrl($result,true);
		$youtubetitle   = str_replace("'","`",$youtubetitle);
		$youtubedesc    = str_replace("'","`",$youtubedesc);
		$youtubethumb   = $youtubethumb[0];
		
		
		$ext 			= "jpg";
		$namagambarm 	= "youtube-$fbulan-$_SESSION[userid]-$newid-m.$ext";
		
		save_image($youtubethumb,"$lokasimember/userfiles/$fbulan/$namagambarm");
		
		$namagambars 	= "youtube-$fbulan-$_SESSION[userid]-$newid-s.$ext";
		$gambars 		= resizeimg("$lokasimember/userfiles/$fbulan/$namagambarm","$lokasimember/userfiles/$fbulan/$namagambars",$gambars_maxw,$gambars_maxh);
		
		$namagambarl 	= "youtube-$fbulan-$_SESSION[userid]-$newid-l.$ext";
		$gambarl 		= resizeimg("$lokasimember/userfiles/$fbulan/$namagambarm","$lokasimember/userfiles/$fbulan/$namagambarl",$gambarl_maxw,$gambarl_maxh);
		
		$namagambarf 	= "youtube-$fbulan-$_SESSION[userid]-$newid-f.$ext";
		$gambarf 		= resizeimg("$lokasimember/userfiles/$fbulan/$namagambarm","$lokasimember/userfiles/$fbulan/$namagambarf",$gambarf_maxw,$gambarf_maxh);
		
		$perintah	= "insert into tbl_post_media(create_date,jenis,userid,nama,ringkas,gambar,gambar_s,gambar_m,gambar_l,gambar_f,url,youtubeid,published) 
					values ('$tanggal','youtube','$_SESSION[userid]','$youtubetitle','$youtubedesc','$namagambarm','$namagambars','$namagambarm','$namagambarl',
					'$namagambarf','$youtube','$youtubeid','1')";
		$hasil		=  sql($perintah);
		
		$media = array("mediaid"=>$newid,"jenis"=>"youtube","lokasi"=>"$fbulan","nama"=>$youtubetitle,"media"=>"uploads/userfiles/$fbulan/$namagambarl","url"=>$youtube,
						"youtubeid"=>$youtubeid);
		$media = serialize($media);
 		$newisi = trim($isibaru);

		if(empty($newisi)) $isibaru = $youtubetitle;
		
		$sql = "update tbl_post set media='$media',isi='$isibaru' where postid='$idbaru'";
		$res = sql($sql);
		//die("here".$youtube);
	}
	
	//Upload Gambar
	$fbulan = date("Ym");
	if(!file_exists("$lokasimember/userfiles/$fbulan")) mkdir("$lokasimember/userfiles/$fbulan");
	
	if($_FILES['filePhoto']['size']>0)
	{
		$newid = newid("mediaid","tbl_post_media");
		
		$ext 			= getimgext($_FILES['filePhoto']);
		$namagambars 	= "media-$fbulan-$_SESSION[userid]-$newid-s.$ext";
		$gambars 		= resizeimg($_FILES['filePhoto']['tmp_name'],"$lokasimember/userfiles/$fbulan/$namagambars",$gambars_maxw,$gambars_maxh);
		
		$namagambarm 	= "media-$fbulan-$_SESSION[userid]-$newid-m.$ext";
		$gambarm 		= resizeimg($_FILES['filePhoto']['tmp_name'],"$lokasimember/userfiles/$fbulan/$namagambarm",$gambarm_maxw,$gambarm_maxh);
		
		$namagambarl 	= "media-$fbulan-$_SESSION[userid]-$newid-l.$ext";
		$gambarl 		= resizeimg($_FILES['filePhoto']['tmp_name'],"$lokasimember/userfiles/$fbulan/$namagambarl",$gambarl_maxw,$gambarl_maxh);
		
		$namagambarf 	= "media-$fbulan-$_SESSION[userid]-$newid-f.$ext";
		$gambarf 		= resizeimg($_FILES['filePhoto']['tmp_name'],"$lokasimember/userfiles/$fbulan/$namagambarf",$gambarf_maxw,$gambarf_maxh);
		
		if(file_exists("$lokasimember/userfiles/$fbulan/$namagambarl"))
		{ 
			$perintah	= "insert into tbl_post_media(create_date,jenis,userid,nama,ringkas,gambar,gambar_s,gambar_m,gambar_l,gambar_f,url,published) 
						values ('$tanggal','photo','$_SESSION[userid]','$isi','$isi','$namagambarm','$namagambars','$namagambarm','$namagambarl','$namagambarf','','1')";
			$hasil		=  sql($perintah);
			
			$media = array("mediaid"=>$newid,"jenis"=>"photo","lokasi"=>"$fbulan","media"=>"uploads/userfiles/$fbulan/$namagambarl");
			$media = serialize($media);
			
			$sql = "update tbl_post set media='$media' where postid='$idbaru'";
			$res = sql($sql);
			
			$photo = true;
		}
	}

	if($tousername!=$_SESSION['username']) setlog($_SESSION['username'],"$tousername","menulis status didinding anda","$fulldomain/user/post/$idbaru");
	
	//Earn Point
	if(strlen($isi)>19) earnpoin("update-status",$_SESSION['userid']);
	
	
	header("location:$fulldomain/user");
}
else
{
	header("location:$fulldomain/user");
}
?>