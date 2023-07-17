<?php 
// Include Librari PHPMailer
include("global.inc.php");
include("web.fungsi.php");

$templatenya			= "dfunstation";
$lokasitemplate			= "$lokasiweb/template/$templatenya/";
$lokasitemplate_c		= "$lokasiweb/templates_c/$templatenya/";

//Konfigurasi untuk Smarty Template Enggine
ini_set("include_path","$lokasiweb/librari/smarty/libs/");
require_once "Smarty.class.php";

$tpl = new Smarty;
$tpl->template_dir	= $lokasitemplate;
$tpl->compile_dir	= $lokasitemplate_c;


//Konfigurasi Site
$sql 			= "select * from tbl_konfigurasi where webid='1' limit 1";
$hsl 			= sql($sql);
$row			= sql_fetch_data($hsl);
$webid			= $row['webid'];
$title			= $row['title'];
$domain			= $row['domain'];
$owner			= $row['owner'];
$support		= $row['support'];
$deskripsi		= $row['deskripsi'];
$support_email	= $row['support_email'];
$metakeyword	= $row['metakeyword'];
$fulldomain		= $row['domain'];
$webfacebook	= $row['webfacebook'];
$webtwitter		= $row['webtwitter'];
$webinstagram = $row['webinstagram'];
$webyoutube = $row['webyoutube'];
$issmtp			= $row['issmtp'];
$smtpuser 		= $row['smtpuser'];
$smtphost 		= $row['smtphost'];
$smtpport 		= $row['smtpport'];
$smtppass 		= $row['smtppass'];
$issmtp 		= $row['issmtp'];
$logo 			= $row['logo'];
$nummember 		= $row['nummember'];
$youtubepop 	= $row['youtubeid'];
$jedatransfer = $row['jedapembayaran'];
$minimaltarik = $row['minimaltarik'];
$matauang 		= "Rp";
$lokasinewtemplate          = "https://www.serverdevelopment2020.my.id/template/dfunstation/newtemplate/";
			
sql_free_result($hsl);

$date = date("Y-m-d H:i:s");

$lokasiwebmember	 		= "$fulldomain/uploads/"; 
$lokasiwebmedia	 		= "$fulldomain/medias/"; 

$tpl->assign("title",$title);
$tpl->assign("domain",$domain);
$tpl->assign("support",$support);
$tpl->assign("webdesc",$deskripsi);
$tpl->assign("webfacebook",$webfacebook);
$tpl->assign("webtwitter",$webtwitter);
$tpl->assign("webyoutube",$webyoutube);
$tpl->assign("webinstagram",$webinstagram);
$tpl->assign("support_email",$support_email);
$tpl->assign("metakeyword",$metakeyword);
$tpl->assign("lokasitemplate",$lokasitemplate);
$tpl->assign("lokasiwebtemplate","$fulldomain/template/$templatenya/");
$tpl->assign("weblogo","$domain/gambar/web/$logo");
$tpl->assign("lokasiwebmember","$lokasiwebmember");
$tpl->assign("fulldomain","$fulldomain");
$tpl->assign("nummember","$nummember");
$tpl->assign("youtubepop","$youtubepop");
$tpl->assign("sitekey",$sitekey);
$tpl->assign("minimaltarik",$minimaltarik);

$uid = md5(uniqid());
$tpl->assign("uid","$uid");

?>