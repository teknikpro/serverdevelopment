<?php
$email = $var[4];

//Cari Referensi
$hsl = sql("select userfullname,refuserid,useremail from tbl_subscriber where useremail='$email'");
$dt = sql_fetch_data($hsl);
$contactid = $dt['refuserid'];
$subuserfullname = $dt['userfullname'];
$subuseremail = $dt['useremail'];
sql_free_result($hsl);

$tpl->assign("subuserfullname",$subuserfullname);
$tpl->assign("subuseremail",$subuseremail);


//profil photo
$perintah	="select userid,userfullname,avatar,posting,follower,following,tema,usergender,useraddress,cityname,useremail,userphonegsm from tbl_member where userid='$contactid' limit 1";
$hasil= sql($perintah);
$profil= sql_fetch_data($hasil);
sql_free_result($hasil);

$iduser = $profil['userid'];
$contactname = $profil['userfullname'];
$avatar = $profil['avatar'];
$contactuseremail = $profil['useremail'];
$contactuserphone = $profil['userphonegsm'];

$avatar = str_replace("-m.","-f.",$avatar);

$online = sql_get_var("select userid from tbl_useronline where userid='$contactid'");
if($online>0) $online = "1";

if ($avatar)
	$linkphoto="$fulldomain/uploads/avatars/$avatar";
else
	$linkphoto="$lokasiwebtemplate/images/no_pic.jpg";


$tpl->assign("contactphoto",$linkphoto);	
$tpl->assign("contactname",$contactname);
$tpl->assign("contactonline",$online);
$tpl->assign("contactid",$contactid);

$tpl->assign("backlink",$_SERVER['HTTP_REFERER']);
?>