<?php 
$username			= clean($_POST['username']);
$userpassword		= $_POST['userpassword'];
$userfullname		= clean($_POST['userfullname']);
$useremail			= clean($_POST['useremail']);
$userphonegsm			= clean($_POST['userphonegsm']);
$usergender			= clean($_POST['usergender']);
$fromcart			= clean($_POST['fromcart']);
$date             = $_POST['date'];
$month            = $_POST['month'];
$year             = $_POST['year'];
$marriagestatusid = $_POST['marriagestatusid'];
$pekerjaan			= clean($_POST['pekerjaan']);
$pendidikan			= clean($_POST['pendidikan']);
$tempatkerja			= clean($_POST['tempatkerja']);
$is_medis			= clean($_POST['is_medis']);
$is_pasienrshs			= clean($_POST['is_pasienrshs']);
$covid_kondisi = $_POST['covid_kondisi'];
$propinsiid       = $_POST['propinsiid'];
$kecid            = $_POST['kecid'];
$kotaid			  = $_POST['kotaid'];

$userdob		= "$year-$month-$date";

$salah = false;
$pesan = array();


$jumlah = sql_get_var("select count(*) as jumlah from tbl_member where username='$username'");
$jumlahemail = sql_get_var("select count(*) as jumlah from tbl_member where useremail='$useremail'");
$jumlahhp = sql_get_var("select count(*) as jumlah from tbl_member where userphonegsm='$userphonegsm'");


if(!preg_match('/^[a-zA-z0-9_]{3,25}$/',$username))
{
	$pesan[] = array("pesan"=>"Salah penulisan username, Username hanya bisa menggunakan huruf,angka dan tanda (-). Tidak kurang dari 3 huruf dan tidak lebih dari 25 huruf");
	$salah = true;
}
else if(empty($username))
{
	$pesan[] = array("pesan"=>"Username Name masih kosong, silahkan isi Terlebih Dahulu");
	$salah = true;
}
else if(empty($userfullname))
	{
	$pesan[] = array("pesan"=>"Nama Lengkap masih kosong, silahkan isi Terlebih Dahulu");
	$salah = true;
	}
else if(empty($pendidikan))
	{
	$pesan[] = array("pesan"=>"Pendidikan masih kosong, silahkan isi Terlebih Dahulu");
	$salah = true;
	}
else if(empty($pekerjaan))
	{
	$pesan[] = array("pesan"=>"Pekerjaan masih kosong, silahkan isi Terlebih Dahulu");
	$salah = true;
	}

else if(!preg_match("/^[a-z0-9]+([\w-])*(((?<![_-])\.(?![_-]))[\w-]*[a-z0-9])*@(?![-])([a-z0-9-]){2,}((?<![-])\.[a-z]{2,6})+$/i", $useremail))
	{
	$pesan[] = array("pesan"=>"Email masih kosong atau penulisan email kurang benar, silahkan isi Terlebih Dahulu");
	$salah = true;
	}
else if($jumlah > 0)
	{
	$pesan[] = array("pesan"=>"Username <strong>$username</strong> sudah digunakan oleh orang lain, silahkan pilih yang lain");
	$salah = true;
	}
else if($jumlahemail > 0)
	{
	$pesan[] = array("pesan"=>"Alamat <strong>$useremail</strong> sudah digunakan oleh orang lain, silahkan pilih yang lain");
	$salah = true;
	}


else{ $salah = false; }


if(!$salah){

	$userpassword1 = md5($userpassword);
	$username = strtolower($username); 
   
  	$perintah = "select max(userid) as baru from tbl_member";
	$hasil = sql($perintah);
	$data = sql_fetch_data($hasil);
	$baru = $data['baru']+1;
	
	$tanggal = date("Y-m-d H:i:s");
	
	$num = 10000+$baru;
	$num = substr($num,1,4);
	$memberid = date("ym").$num;
	
    $query = "insert into tbl_member (userid,userdob,username,memberid,userfullname,userpassword,useremail,userphonegsm,usergender,usercreateddate,pendidikan,pekerjaan,marriagestatusid,useractivestatus,kecid,kotaid,propinsiid,is_medis,covid_kondisi,tempat_kerja,is_pasienrshs)
			 values ('$baru','$userdob','$username','$memberid','$userfullname','$userpassword1','$useremail','$userphonegsm','$usergender','$tanggal','$pendidikan','$pekerjaan','$marriagestatusid','1','$kecid','$kotaid','$propinsiid','$is_medis','$covid_kondisi','$tempatkerja','$is_pasienrshs')";
    $hasil = sql($query);
	
	$query=("insert into tbl_member_stats(userid,login) values ('$baru','1')");
    $hasil = sql($query);
	
   if($hasil)
   {
		
		earnpoin("register",$baru);
				
		// buat recod untuk konfirmasi
		$code = "$baru"."$username".date("YmdHis");
		$code = md5($code);
		$perintah4= "insert into tbl_member_konfirmasi(userid,username,code) values ('$baru','$username','$code')";
		$hasil4 = sql($perintah4);
		$perintah5= "update tbl_konfigurasi set nummember=nummember+1";
		$hasil5 = sql($perintah5);
		
		//kirimSMS($userphonegsm,"Terima kasih sudah mendaftar di $title, nikmati semua fasilitas dengan melengkapi semua informasi profile anda");

		
$isi = "Email Konfirmasi Pendaftaran $title
=========================================================================

Yth. $userfullname,

Selamat pendaftaran anda telah berhasil dilakukan. Username dan password yang anda 
gunakan adalah sebagai berikut : :

Username : $username / $useremail
Password : $userpassword

Silahkan login dan nikmati semua fitur yang tersedia untuk anda.

Terima Kasih
$owner";

$isihtml = "<br />
<strong><strong>Email Konfirmasi Pendaftaran $title</strong></strong><br /><br />

Yth. $userfullname,<br />
Selamat pendaftaran anda telah berhasil dilakukan. Username dan password yang anda gunakan 
adalah sebagai berikut :<br />
<br />

<strong>Username : </strong>$username / $useremail<br />
<strong>Password : </strong>$userpassword
<br />
<br />

Silahkan login dan nikmati semua fitur yang tersedia untuk anda
<br />
<br />

Terima Kasih<br />

$owner";

$subject = "Terima Kasih Sudah Mendaftar";

sendmail($userfullname,$useremail,$subject,$isi,emailhtml($isihtml));
		

	$pesanhasil = "Selamat anda telah terdaftar di $title, username yang dapat anda gunakan adalah <b>$username</b> dan passwordnya seperti yang telah anda tentukan dan <b>telah kami kirim ke email anda</b>, 
   		Jaga kerahasiaan password anda dan jangan lupa. <b>silahkan buka email anda.</b>";
	$berhasil = 1;
	
	$perintah	= "select userid,userfullname,username,userdirname,avatar,verified,fbcid,twcid,gpcid,tipe,userlastloggedin from tbl_member where userid='$baru' and useractivestatus='1'";
	$hasil 		= sql($perintah);
	$row 			= sql_fetch_data($hasil);
	$userid  		= $row['userid'];
	$username 		= $row['username'];
	$userfullname 	= $row['userfullname'];
	$userdirname 	= $row['userdirname'];
	$avatar 		= $row['avatar'];
	$verified 		= $row['verified'];
	$fbcid 			= $row['fbcid'];
	$twcid 			= $row['twcid'];
	$gpcid 			= $row['gpcid'];
	$refuserid 		= $row['refuserid'];
	$tipe 		= $row['tipe'];
	$lastlogin = $row['userlastloggedin'];
	
	

	session_start();	
	$_SESSION['userid'] 		= $userid;
	$_SESSION['userfullname'] 	= $userfullname;
	$_SESSION['username'] 		= $username;
	$_SESSION['userdirname'] 	= $userdirname;
	$_SESSION['verified'] 		= $verified;
	$_SESSION['avatar'] 		= $avatar;
	$_SESSION['pss'] 			= $pass;
	$_SESSION['fbcid'] 			= $fbcid;
	$_SESSION['twcid'] 			= $twcid;
	$_SESSION['gpcid'] 			= $gpcid;
	$_SESSION['contactid'] 		= $refuserid;
	$_SESSION['usertipe'] 		= $tipe;
	
	header("location: $fulldomain/tesmandiri/tes");
	exit();
	
	
	}
			
}
else
{
	$pesanhasil = "Pendaftaran Anda Gagal dilakukan kemungkinan Ada beberapa kesalahan yang harus diperbaiki terlebih dahulu, silahkan periksa kembali kesalahan dibawah ini";
	$berhasil = "0";
}	
$tpl->assign("pesan",$pesan);
$tpl->assign("pesanhasil",$pesanhasil);
$tpl->assign("berhasil",$berhasil);
?>
