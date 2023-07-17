<?php 
//header("Content-type: text/javascript");
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Max-Age: 1000');
header('Access-Control-Allow-Headers: Content-Type');

$callback = $_GET['callback'];

// Include Librari PHPMailer
include("global.inc.php");
include("web.fungsi.php");

//Konfigurasi Site
$sql = "select * from tbl_konfigurasi where webid='1' limit 1";
$hsl = sql($sql);
$row = sql_fetch_data($hsl);
$webid	= $row['webid'];
$title	= $row['title'];
$domain	= $row['domain'];
$owner	= $row['owner'];
$support	= $row['support'];
$deskripsi	= $row['deskripsi'];
$support_email	= $row['support_email'];
$metakeyword	= $row['metakeyword'];
$fulldomain	= $row['domain'];
$webfacebook	= $row['webfacebook'];
$webtwitter	= $row['webtwitter'];
$issmtp = $row['issmtp'];
$smtpuser = $row['smtpuser'];
$smtphost = $row['smtphost'];
$smtpport = $row['smtpport'];
$smtppass = $row['smtppass'];
$issmtp = $row['issmtp'];
$logo = $row['logo'];
$nummember = $row['nummember'];
			
sql_free_result($hsl);

$date = date("Y-m-d H:i:s");

$lokasiwebmember	 	= "$fulldomain/uploads/"; 
$lokasiwebmedia	 		= "$fulldomain/medias/"; 

?>