<?php

$menuvoucher = $var['3'];
$idmenuvoucher = $var['4'];
$idmenuvoucher1 = $var['5'];

$urllogin = $menuvoucher . "/" . $idmenuvoucher . "/" . $idmenuvoucher1;

if ($_SESSION['userid'] && $aksi != "profile") {

	//teman
	$log 		= $_SESSION['userid'];
	$namalog 	= $_SESSION['username'];
	$tgl_skrg 	= date('m');
	$Jumlah_komentar = 0;

	//profil photo
	$perintah	= "select userid,userfullname,userdob,memberid,userlastloggedin,avatar,posting,follower,following,tema,usergender,useraddress,cityname,userpostcode,userphonegsm,negaraid,workid,useremail,header,fbcid,twcid,otpemail,otphandphone,otpemailvalid,otphandphonevalid,regvoucher
			from tbl_member where userid='$_SESSION[userid]'";
	$hasil = sql($perintah);
	$profil = sql_fetch_data($hasil);
	sql_free_result($hasil);

	$Iduser       = $profil['userid'];
	$FullNameuser = $profil['userfullname'];
	$userDOB      = $profil['userdob'];
	$avatar       = $profil['avatar'];
	$memberid       = $profil['memberid'];
	$posting      = number_format($profil['posting'], 0, ".", ".");
	$follower     = number_format($profil['follower'], 0, ".", ".");
	$following    = number_format($profil['following'], 0, ".", ".");
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
	$regvoucher        = $profil['regvoucher'];

	if ($avatar)
		$linkphoto = "$fulldomain/uploads/avatars/$avatar";
	else
		$linkphoto = "$fulldomain/images/no_pic.jpg";

	if (empty($header))
		$header = "$lokasiwebtemplate/images/cover.jpg";

	$tpl->assign("linkphoto", $linkphoto);
	$tpl->assign("FullNameuser", $FullNameuser);
	$tpl->assign("tahun", $tahun);
	$tpl->assign("posting", $posting);
	$tpl->assign("follower", $follower);
	$tpl->assign("following", $following);
	$tpl->assign("tema", $tema);
	$tpl->assign("header", $header);
	$tpl->assign("headermember", $header);
	$tpl->assign("fbcid", $fbcid);
	$tpl->assign("twid", $twcid);
	$tpl->assign("useremail", $useremail);
	$tpl->assign("userphonegsm", $userphonegsm);
	$tpl->assign("memberid", $memberid);
	$tpl->assign("otpemail", $otpemail);
	$tpl->assign("otphandphone", $otphandphone);
	$tpl->assign("otpemailvalid", $otpemailvalid);
	$tpl->assign("otphandphonevalid", $otphandphonevalid);
	$tpl->assign("regvoucher", $regvoucher);

	$salahcek	= false;

	/*if(empty($FullNameuser) or ($userDOB=="0000-00-00") or empty($usergender) or empty($useraddress) or empty($cityname) or empty($userpostcode) or empty($userphonegsm) or (!preg_match("/^[a-z0-9]+([\w-])*(((?<![_-])\.(?![_-]))[\w-]*[a-z0-9])*@(?![-])([a-z0-9-]){2,}((?<![-])\.[a-z]{2,6})+$/i", $useremail)) )
	{
		$salahcek = true;
	}*/

	if ($salahcek) {
		$pesanhasilcek = "Data profil anda belum lengkap, silahkan lengkapi terlebih dahulu.";
		$berhasilcek = "1";
	}

	$tpl->assign("pesancek", $pesancek);
	$tpl->assign("pesanhasilcek", $pesanhasilcek);
	$tpl->assign("berhasilcek", $berhasilcek);


	//Point
	$totalpoin = sql_get_var("select balancetotal as jml from tbl_member_point_history where userid='$_SESSION[userid]' and status='1' order by id desc");

	if ($totalpoin > 0)
		$totalpoin = number_format($totalpoin, 0, ".", ".");
	else
		$totalpoin = 0;

	$tpl->assign("totalpoin", $totalpoin);

	//Notifikasi
	$jmlnotifikasi = sql_get_var("select count(*) as jml from tbl_notifikasi where tousername='$_SESSION[username]' and status='0'");
	$tpl->assign("jmlnotifikasi", $jmlnotifikasi);
}

if (!$_SESSION['userid']) {
	$tpl->assign("menuvoucher", $menuvoucher);
	$tpl->assign("idmenuvoucher", $idmenuvoucher);
	$tpl->assign("urllogin", $urllogin);
	if (empty($aksi)) $aksi = "login";
} else if ($aksi == "setting") {
	$subaksi = $var[4];
	$tpl->assign("subaksi", $subaksi);

	if ($subaksi == "gantipassword") {
		$nama_aksi = "Ubah Password";
		$file = "submember.gantipassword.php";
	} elseif ($subaksi == "profile") {
		$nama_aksi = "Pengaturan Profile";
		$file = "submember.setting.profile.php";
	} elseif ($subaksi == "avatar") {
		$nama_aksi = "Ganti Avatar";
		$file = "submember.setting.avatar.php";
	} elseif ($subaksi == "tampilan") {
		$nama_aksi = "Ganti Gambar Latar Belakang";
		$file = "submember.setting.tampilan.php";
	} elseif ($subaksi == "privacy") {
		$nama_aksi = "Keamanan Privacy";
		$file = "submember.setting.privacy.php";
	} elseif ($subaksi == "notifikasiemail") {
		$nama_aksi = "Notifikasi Email";
		$file = "submember.setting.notifikasiemail.php";
	} else {
		$nama_aksi = "Perubahan Profil";
		//$file = "submember.setting.php"; 
	}
}
//update status

else if ($aksi == "listpost") $file =  "submember.post.php";
else if ($aksi == "stream") $file =  "submember.post.php";
else if ($aksi == "add-post") $file = "submember.addpost.php";
else if ($aksi == "add-komen") $file =  "submember.addkomen.php";
else if ($aksi == "delkomen") $file =  "submember.delkomen.php";
else if ($aksi == "delpost") $file =  "submember.delpost.php";
else if (($aksi == "like-post") or ($aksi == "unlike-post")) $file =  "submember.likepost.php";
else if ($aksi == "info") $file =  "info.php";
else if ($aksi == "poin") {
	$file =  "submember.poin.php";
	$nama_aksi = "Poin Member";
} else if ($aksi == "notifikasi") {
	$file =  "submember.notifikasi.php";
	$nama_aksi = "Notifikasi";
} else if ($aksi == "produk") {
	$file =  "submember.produk.php";
	$nama_aksi = "Produk Saya";
} else if ($aksi == "transaksi") {
	$file =  "submember.transaksi.php";
	$nama_aksi = "Transaksi Saya";
} else if ($aksi == "redeem") {
	$file =  "submember.redeem.php";
	$nama_aksi = "Redeem Poin";
} else if ($aksi == "untukmember") {
	$file =  "submember.untukmember.php";
	$nama_aksi = "Untuk Member";
} else if ($aksi == "kirimotp") {
	$file =  "submember.kirimotp.php";
	$nama_aksi = "Kirim Kode Validasi";
} else {
	if (empty($aksi)) $aksi = "dashboard";
}
$tpl->assign("namaaksi", $nama_aksi);
if (!empty($file)) {
	include($file);
} else if (file_exists($lokasiweb . "komponen/submember/submember.$aksi.php")) include("submember.$aksi.php");

if ($_SESSION['fbcid']) {
	$fbcid 			= $_SESSION['fbcid'];
	$daftaremail 	= $_SESSION['fbemail'];
	$daftarname 	= $_SESSION['fbname'];
	$daftaravatar 	= $_SESSION['fbavatar'];
	$first_name		= $_SESSION['first_name'];
	$last_name		= $_SESSION['last_name'];

	$tpl->assign("fbcid", $fbcid);
	$tpl->assign("daftaremail", $daftaremail);
	$tpl->assign("daftarname", $daftarname);
	$tpl->assign("daftaravatar", $daftaravatar);
	$tpl->assign("first_name", $first_name);
	$tpl->assign("last_name", $last_name);
}

if ($_SESSION['twcid']) {
	$twcid 			= $_SESSION['twcid'];
	$daftaremail 	= $_SESSION['fbemail'];
	$daftarname 	= $_SESSION['twname'];
	$daftaravatar 	= $_SESSION['twpicture'];
	$daftarusername = strtolower($_SESSION['twuname']);

	$tpl->assign("twcid", $twcid);
	$tpl->assign("daftaremail", $daftaremail);
	$tpl->assign("daftarname", $daftarname);
	$tpl->assign("daftaravatar", $daftaravatar);
	$tpl->assign("daftarusername", $daftarusername);
	$tpl->assign("first_name", $daftarname);
}

if ($aksi == "pay") {
	$tpl->display("pay.html");
} elseif (!$_SESSION['userid']) {
	$tpl->display('user-login.html');
} else $tpl->display('user.html');
