<?php 
$username		= $var[3];
$hlm 			= $var[5];
$aksi 			= $var[4];


include"profile.profile.php";

$tpl->assign("unameteman",$username);
$tpl->assign("fname",$userfullname);
$tpl->assign("photoAvatarB",$avatar);
$tpl->assign("title","$userfullname di dFunStation.com ");

$tpl->assign("aksi",$aksi);


if((!$fb) and $aksi=="" or $aksi=="listpost" or $aksi=="readpost" or $aksi=="post") { include "./komponen/submember/submember.post.php"; }

if($_SESSION['username'])
{
	
	if($name!==$_SESSION['username'])
	{
		//cek teman or bukan
		$jumfollow	= sql_get_var("select count(*) as jml from tbl_follow where userid='$_SESSION[userid]' and fid='$uid'");
	
		if ($name==$_SESSION['username']) 
			$statusteman	= 2; //liat profil sendiri
		else if ($jumfollow > 0) 
			$statusteman	= 1; //liat profil temen
		else 
			$statusteman	= 0; //bukan temen
		
		
		$tpl->assign("statusteman",$statusteman);
	}
	
	
}

if($aksi == "blog") { include "./komponen/member/member.blog.php"; $namaaksi = "Blog";}
else if($aksi == "readblog") { include "./komponen/member/member.readblog.php"; $namaaksi = "Read Blog";}
elseif($aksi == "viewer") { include "profile.viewer.php"; $namaaksi = "Viewer"; }
else if($aksi=="follower") {include "./komponen/follow/followers.php"; $namaaksi = "Pengikut";}
else if($aksi=="following") {include "./komponen/follow/following.php"; $namaaksi = "Mengikuti";}
else if(($aksi=="media") or ($aksi=="readmedia")) {include "./komponen/member/member.media.php"; $namaaksi = "Photo &amp; Video";}
else if($aksi=="konten") {include "profile.konten.php"; $namaaksi = "Khazanah";}
else if($aksi=="video") {include "profile.video.php"; $namaaksi = "Video";}
else if($aksi=="audio") {include "profile.audio.php"; $namaaksi = "Audio";}
else if($aksi=="agenda") {include "profile.agenda.php"; $namaaksi = "Agenda";}
else if($aksi=="readagenda") {include "profile.readagenda.php"; $namaaksi = "Agenda";}


if($_SESSION['username'])
{
if($name!=$_SESSION['username'])
{
	$cek = sql_get_var("select id from tbl_viewer where userid='$_SESSION[userid]' and puserid='$uid'");
	$tgl = date("Y-m-d H:i:s");
	
	if($cek)
		$viewrprofil = "update tbl_viewer set tanggal='$tgl' where id='$cek'";
	else
		$viewrprofil = "insert into tbl_viewer (userid,username,userfullname,puserid) values ('$_SESSION[userid]','$_SESSION[username]','$_SESSION[userfullname]','$uid')";
		
	$hslviewrprofil = sql($viewrprofil);
}
	$avatar = sql_get_var("select avatar from tbl_member where userid='$_SESSION[userid]'");
	if ($avatar)
		$linkphoto="$fulldomain/uploads/avatars/$avatar";
	else
		$linkphoto="$lokasiwebtemplate/images/no_pic.jpg";
		
	$tpl->assign("linkphoto",$linkphoto);
}



$tpl->assign("namaaksi",$namaaksi);

$tpl->display("$kanal.html");

?>
