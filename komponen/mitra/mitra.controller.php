<?php

if($_SESSION['userid'] && $aksi!="profile")
{
	
	//teman
	$log 		= $_SESSION['userid'];
	$namalog 	= $_SESSION['username'];
	$tgl_skrg 	= date('m');
	$Jumlah_komentar = 0;

	//profil photo
	$perintah	="select userid,userfullname,userdob,memberid,userlastloggedin,avatar,posting,follower,following,tema,usergender,useraddress,cityname,userpostcode,userphonegsm,negaraid,workid,useremail,header,fbcid,twcid,otpemail,otphandphone,otpemailvalid,otphandphonevalid,tipe
			from tbl_member where userid='$_SESSION[userid]'";
	$hasil= sql($perintah);
	$profil= sql_fetch_data($hasil);
	sql_free_result($hasil);

	$Iduser       = $profil['userid'];
	$FullNameuser = $profil['userfullname'];
	$userDOB      = $profil['userdob'];
	$avatar       = $profil['avatar'];
	$memberid       = $profil['memberid'];
	$posting      = number_format($profil['posting'],0,".",".");
	$follower     = number_format($profil['follower'],0,".",".");
	$following    = number_format($profil['following'],0,".",".");
	$tema         = $profil['tema'];
	$usergender   = $profil['usergender'];
	$useraddress  = $profil['useraddress'];
	$cityname     = $profil['cityname'];
	$userpostcode = $profil['userpostcode'];
	$userphonegsm = $profil['userphonegsm'];
	$negaraid     = $profil['negaraid'];
	$workid       = $profil['workid'];
	$useremail    = $profil['useremail'];
	$header       = $profil['header'];
	$fbcid        = $profil['fbcid'];
	$twcid        = $profil['twcid'];
	$otpemail        = $profil['otpemail'];
	$otphandphone        = $profil['otphandphone'];
	$otpemailvalid        = $profil['otpemailvalid'];
	$otphandphonevalid        = $profil['otphandphonevalid'];
	$tipe        = $profil['tipe'];
	
	if($tipe!="3") header("location: $fulldomain");
	
	if ($avatar)
		$linkphoto="$fulldomain/uploads/avatars/$avatar";
	else
		$linkphoto="$fulldomain/images/no_pic.jpg";

	if(empty($header))
		$header = "$lokasiwebtemplate/images/cover.jpg";

	$tpl->assign("linkphoto",$linkphoto);	
	$tpl->assign("FullNameuser",$FullNameuser);
	$tpl->assign("tahun",$tahun);
	$tpl->assign("posting",$posting);	
	$tpl->assign("follower",$follower);
	$tpl->assign("following",$following);
	$tpl->assign("tema",$tema);
	$tpl->assign("header",$header);
	$tpl->assign("headermember",$header);
	$tpl->assign("fbcid",$fbcid);
	$tpl->assign("twid",$twcid);
	$tpl->assign("useremail",$useremail);
	$tpl->assign("userphonegsm",$userphonegsm);
	$tpl->assign("memberid",$memberid);
	$tpl->assign("otpemail",$otpemail);
	$tpl->assign("otphandphone",$otphandphone);
	$tpl->assign("otpemailvalid",$otpemailvalid);
	$tpl->assign("otphandphonevalid",$otphandphonevalid);
	$tpl->assign("regvoucher",$regvoucher);
	
	$salahcek	= false;	
		
	/*if(empty($FullNameuser) or ($userDOB=="0000-00-00") or empty($usergender) or empty($useraddress) or empty($cityname) or empty($userpostcode) or empty($userphonegsm) or (!preg_match("/^[a-z0-9]+([\w-])*(((?<![_-])\.(?![_-]))[\w-]*[a-z0-9])*@(?![-])([a-z0-9-]){2,}((?<![-])\.[a-z]{2,6})+$/i", $useremail)) )
	{
		$salahcek = true;
	}*/
	
	if($salahcek)
	{
		$pesanhasilcek = "Data profil anda belum lengkap, silahkan lengkapi terlebih dahulu.";
		$berhasilcek = "1";
	}
	
	$tpl->assign("pesancek",$pesancek);
	$tpl->assign("pesanhasilcek",$pesanhasilcek);
	$tpl->assign("berhasilcek",$berhasilcek);
	
	
	//Point
	$pageid = sql_get_var("select id from tbl_world where userid='$_SESSION[userid]' limit 1");

	//Notifikasi
	$jmlnotifikasi = sql_get_var("select count(*) as jml from tbl_notifikasi where tousername='$_SESSION[username]' and status='0'");
	$tpl->assign("jmlnotifikasi",$jmlnotifikasi);
	
	
	
}

if(!$_SESSION['userid']) {
	if(empty($aksi)) $aksi = "login";
}
else if(!$_SESSION['userid']) {
	if(empty($aksi)) $aksi = "login";
}
	   
else if($aksi=="setting") 
{ 
	$subaksi = $var[4];
	$tpl->assign("subaksi",$subaksi);
	
	if($subaksi=="gantipassword") 
	{ 
		$nama_aksi = "Ubah Password";
	  	$file = "mitra.gantipassword.php"; 
	}
	elseif($subaksi=="profile") 
	{ 
		$nama_aksi = "Pengaturan Profile";
	  	$file = "mitra.setting.profile.php"; 
	}
	elseif($subaksi=="avatar") 
	{ 
		$nama_aksi = "Ganti Avatar";
	  	$file = "mitra.setting.avatar.php"; 
	}
	elseif($subaksi=="tampilan") 
	{ 
		$nama_aksi = "Ganti Gambar Latar Belakang";
	  	$file = "mitra.setting.tampilan.php"; 
	}
	elseif($subaksi=="privacy") 
	{ 
		$nama_aksi = "Keamanan Privacy";
	  	$file = "mitra.setting.privacy.php"; 
	}
	elseif($subaksi=="notifikasiemail") 
	{ 
		$nama_aksi = "Notifikasi Email";
	  	$file = "mitra.setting.notifikasiemail.php"; 
	}
  	else
	{
		$nama_aksi = "Perubahan Profil";
		//$file = "mitra.setting.php"; 
	}
}
else if($aksi=="notifikasi") { $file =  "mitra.notifikasi.php"; $nama_aksi = "Notifikasi";}
else if($aksi=="histori") { $file =  "mitra.histori.php"; $nama_aksi = "Histori Transaksi";}
else if($aksi=="voucher") { $file =  "mitra.voucher.php"; $nama_aksi = "Voucher";}
else if($aksi=="evoucher") { $file =  "mitra.evoucher.php"; $nama_aksi = "eVoucher";}
else
{
	if(empty($aksi)) $aksi = "dashboard";
}
$tpl->assign("namaaksi",$nama_aksi);

if(!empty($file))
{
	include($file); 
}
else if(file_exists($lokasiweb."komponen/mitra/mitra.$aksi.php")) include("mitra.$aksi.php");



if($aksi=="pay")
{
	$tpl->display("pay.html");
}
elseif(!$_SESSION['userid']) {
	$tpl->display('mitra-login.html');
}
else $tpl->display('mitra.html');
?>