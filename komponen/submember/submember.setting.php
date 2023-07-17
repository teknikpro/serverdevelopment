<?php  
if(isset($_POST['userfullname']))
{
	$userfullname     = cleaninsert($_POST['userfullname']);
	$usergender       = $_POST['usergender'];
	$date             = $_POST['date'];
	$month            = $_POST['month'];
	$year             = $_POST['year'];
	$marriagestatusid = $_POST['marriagestatusid'];
	$useraddress      = cleaninsert($_POST['useraddress']);
	$cityname         = cleaninsert($_POST['cityname']);
	$userpostcode     = cleaninsert($_POST['userpostcode']);
	$propinsiid       = $_POST['propinsiid'];
	$kecid            = $_POST['kecid'];
	$kotaid			  = $_POST['kotaid'];
	$negaraid         = $_POST['negaraid'];
	$aboutme        = cleaninsert($_POST['aboutme']);
	$userphone        = cleaninsert($_POST['userphone']);
	$userphonegsm     = cleaninsert($_POST['userphonegsm']);
	$userhomepage     = cleaninsert($_POST['userhomepage']);
	$useremail        = cleaninsert($_POST['useremail']);
	$fbid             = cleaninsert($_POST['fbid']);
	$twitterid        = cleaninsert($_POST['twitterid']);
	$ymid             = cleaninsert($_POST['ymid']);
	
	
	$userdob		= "$year-$month-$date";

	
	//validasi userphone
	$notlp= str_split($userphone);
	$length=strlen($userphone);
	if($notlp[0] == '0')
	{
		$notlp2 = '62';
	for ($i=1;$i<$length;$i++)
	{
		$baru.=$notlp[$i];
	}
	$userphone2 = "$notlp2"."$baru";
	}
	else
		$userphone2=$userphone;
	//validasi userphonegsm
	$nohp= str_split($userphonegsm);
	$panjang=strlen($userphonegsm);
	if($nohp[0] == '0')
	{
		$nohp2 = '62';
	for ($i=1;$i<$panjang;$i++)
	{
		$baru1.=$nohp[$i];
	}
	$userphonegsm2 = "$nohp2"."$baru1";
	}
	else
		$userphonegsm2=$userphonegsm;

	$filename	= $_FILES['avatar']['name'];
	$filesize	= $_FILES['avatar']['size'];
	$filetmpname	= $_FILES['avatar']['tmp_name'];
	
	if($filesize > 0)
	{
		$folderalbum = "$lokasimember/avatar/";
		if(!file_exists($folderalbum)){	mkdir($folderalbum,0777); }
		
		$imageinfo = getimagesize($filetmpname);
		$imagewidth = $imageinfo[0];
		$imageheight = $imageinfo[1];
		$imagetype = $imageinfo[2];
		
		switch($imagetype)
		{
			case 1: $imagetype="gif"; break;
			case 2: $imagetype="jpg"; break;
			case 3: $imagetype="png"; break;
		}
		
		$photofull = "avatar-".$_SESSION['userid']."-f.".$imagetype;
		resizeimg($filetmpname,"$folderalbum$photofull",800,800);
		
		$photolarge = "avatar-".$_SESSION['userid']."-l.".$imagetype;
		resizeimg($filetmpname,"$folderalbum$photolarge",500,500);
		
		$photomedium = "avatar-".$_SESSION['userid']."-m.".$imagetype;
		resizeimg($filetmpname,"$folderalbum$photomedium",250,250);
		
		$photosmall = "avatar-".$_SESSION['userid']."-s.".$imagetype;
		resizeimg($filetmpname,"$folderalbum$photosmall",80,80);
		
		if(file_exists("$folderalbum$photomedium")){ $avatars = ",avatar='$photomedium'"; }
		
	}

			
	$salah = false;
	$pesan = array();
	
	if(empty($userfullname))
	{
		$pesan[9] = array("pesan"=>"UserfullName anda masih kosong, silahkan isi dengan lengkap terlebih dahulu");
		$salah = true;
	}
	else if(empty($usergender))
	{
		$pesan[10] = array("pesan"=>"Jenis kelamin, silahkan isi dengan lengkap terlebih dahulu");
		$salah = true;
	}
	else if(empty($userphonegsm))
	{
		$pesan[14] = array("pesan"=>"Nomor Telp  anda masih kosong, silahkan isi dengan lengkap terlebih dahulu");
		$salah = true;
	}
	else if(empty($kecid))
	{
		$pesan[12] = array("pesan"=>"Asal kecamatan anda, silahkan isi dengan lengkap terlebih dahulu");
		$salah = true;
	}
	else if(empty($kotaid))
	{
		$pesan[11] = array("pesan"=>"Asal desa, silahkan isi dengan lengkap terlebih dahulu");
		$salah = true;
	}
	else if(!preg_match("/^[a-z0-9]+([\w-])*(((?<![_-])\.(?![_-]))[\w-]*[a-z0-9])*@(?![-])([a-z0-9-]){2,}((?<![-])\.[a-z]{2,6})+$/i", $useremail))
		{
		$pesan[15] = array("pesan"=>"Email masih kosong atau penulisan email kurang benar, silahkan isi Terlebih Dahulu");
		$salah = true;
	}
	else if($date=="00" || $month=="00" || $year=="00")
	{
		$pesan[17] = array("pesan"=>"Tanggal lahir anda belum benar, silahkan isi dengan lengkap terlebih dahulu");
		$salah = true;
	}

	
	if(!$salah)
	{
		$cek = sql_get_var("select count(*) as jml from tbl_member_pointearn where userid='$_SESSION[userid]' and activity='lengkapi-profile'");
		if($cek<1)
		{
			earnpoin("lengkapi-profile",$_SESSION['userid']);
		}
		
		$query= "update tbl_member set userfullname='$userfullname',usergender='$usergender',useremail='$useremail',useraddress='$useraddress',kecid='$kecid',kotaid='$kotaid',
				cityname='$cityname',userhomepage='$userhomepage',userphone='$userphone2',userphonegsm='$userphonegsm2',marriagestatusid='$marriagestatusid',workid='$profesiid',aboutme='$aboutme',
				userdob='$userdob',negaraid='$negaraid',propinsiid='$propinsiid',ymid='$ymid',
				userpostcode='$userpostcode',fbid='$fbid',twitterid='$twitterid' where username='$_SESSION[username]'";
		$hasil = sql($query);
		
		$_SESSION['kecid'] = $kecid;
		$_SESSION['kotaid'] = $kotaid;
		
		
	   if($hasil)
	   {
							   
		$pesanhasil = "Selamat Data anda di $title telah berhasil diupdate, Lakukan perubahan profil secara berkala disesuaikan dengan kondisi anda saat ini.";
		$berhasil = "1";
		}
				
	}
	else
	{
		$pesanhasil = "Penyimpanan setting gagal dilakukan ada beberapa kesalahan yang mesti diperbaiki, silahkan periksa kembali kesalahan dibawah ini";
		$berhasil = "0";
	}
	
$tpl->assign("pesan",$pesan);
$tpl->assign("pesanhasil",$pesanhasil);
$tpl->assign("berhasil",$berhasil);

}

$perintah = sql("select * from tbl_member where username='$_SESSION[username]'");
$data = sql_fetch_data($perintah);
sql_free_result($perintah);

$username         = $data['username'];
$userfullname     = $data['userfullname'];
$useraddress      = $data['useraddress'];
$cityname         = $data['cityname'];
$userphone        = $data['userphone'];
$userphonegsm     = $data['userphonegsm'];
$userflexiphone   = $data['userflexiphone'];
$userhomepage     = $data['userhomepage'];
$useremail        = $data['useremail'];
$aboutme          = $data['aboutme'];
$usergender       = $data['usergender'];
$userreligion     = $data['userreligion'];
$userpostcode     = $data['userpostcode'];
$negaraid         = $data['negaraid'];
$propid       = $data['propinsiid'];
$kecid            = $data['kecid'];
$kotaid            = $data['kotaid'];
$affiliations     = $data['affiliations'];
$companies        = $data['companies'];
$schools          = $data['schools'];
$wiseword         = $data['wiseword'];
$marriagestatusid = $data['marriagestatusid'];
$ymid             = $data['ymid'];
$fbid             = $data['fbid'];
$twitterid        = $data['twitterid'];
$userhobi         = $data['userhobi'];

if(empty($negaraid))
	$negaraid		= "99";

$dob = explode("-","$data[userdob]");

$tpl->assign("username",$username);
$tpl->assign("userfullname",$userfullname);
$tpl->assign("useraddress",$useraddress);
$tpl->assign("cityname",$cityname);
$tpl->assign("userphone",$userphone);
$tpl->assign("userphonegsm",$userphonegsm);
$tpl->assign("userflexiphone",$userflexiphone);
$tpl->assign("userhomepage",$userhomepage);
$tpl->assign("useremail",$useremail);
$tpl->assign("aboutme",$aboutme);
$tpl->assign("usergender",$usergender);
$tpl->assign("userreligion",$userreligion);
$tpl->assign("userpostcode",$userpostcode);
$tpl->assign("negaraid",$negaraid);
$tpl->assign("propinsiid",$propinsiid);
$tpl->assign("kecid",$kecid);
$tpl->assign("kotaid",$kotaid);
$tpl->assign("marriagestatusid",$marriagestatusid);
$tpl->assign("companies",$companies);
$tpl->assign("schools",$schools);
$tpl->assign("wiseword",$wiseword);
$tpl->assign("affiliations",$affiliations);
$tpl->assign("ymid",$ymid);
$tpl->assign("userhobi",$userhobi);
$tpl->assign("fbid",$fbid);
$tpl->assign("twitterid",$twitterid);
$tpl->assign("kecid",$kecid);
$tpl->assign("kotaid",$kotaid);
$tpl->assign("kotaids",$kotaid);

//dapatkan data tanggal
$dateloop = array();
$tempi = 1;
while ($tempi < 32) {
	 if ($tempi < 10){
		 array_push($dateloop,"0".$tempi);
		 $temp2 = "0".$tempi;
	 }else{
		 array_push($dateloop,$tempi);
		 $temp2 = $tempi;
	}
	if($temp2 == $dob[2]) $dateselected = $tempi;
	$tempi++;
}

$monthloop = array();
$tempi = 1;
while ($tempi < 13) {
	 if ($tempi < 10){
		 array_push($monthloop,"0".$tempi);
		  $temp2 = "0".$tempi;
	 }else{
		 array_push($monthloop,$tempi);
		 $temp2 = $tempi;
	}
	if($temp2 == $dob[1]) $monthselected = $tempi;
	$tempi++;

}

$yearloop = array();
$tempi = date("Y")-80;

while($tempi < date("Y") - 10) {
	 array_push($yearloop,$tempi);
	if($tempi == $dob[0]) $yearselected = $tempi;
	$tempi++;

}

if($monthselected<10) $monthselected = "0".$monthselected;
if($dateselected<10) $dateselected = "0".$dateselected;

$tpl -> assign( 'yearloop', $yearloop );
$tpl -> assign( 'yearselected' , $yearselected);
$tpl -> assign( 'monthloop', $monthloop );
$tpl -> assign( 'monthselected' , $monthselected);
$tpl -> assign( 'dateloop', $dateloop );
$tpl -> assign( 'dateselected' , $dateselected);

//negara
$datanegara = array();
$pnegara = "select id,namanegara from tbl_negara order by namanegara asc";
$hnegara = sql($pnegara);
while($dnegara= sql_fetch_data($hnegara))
{
	$datanegara[$dnegara['id']] = array("id"=>$dnegara['id'],"namanegara"=>$dnegara['namanegara']);
}
sql_free_result($hnegara);
$tpl->assign("datanegara",$datanegara);

//propinsi
$datapropinsi = array();
$ppropinsi = "select propid,namapropinsi from tbl_propinsi order by namapropinsi asc";
$hpropinsi = sql($ppropinsi);
while($dpropinsi=sql_fetch_data($hpropinsi))
{
	$datapropinsi[$dpropinsi['propid']] = array("id"=>$dpropinsi['propid'],"namapropinsi"=>$dpropinsi['namapropinsi']);
}
sql_free_result($hpropinsi);
$tpl->assign("datapropinsi",$datapropinsi);

//kota
$datakota = array();
$pkota = "select kotaid,namakota,tipe from tbl_kota where propid='$propid' order by namakota asc";
$hkota = sql($pkota);
while($dkota=sql_fetch_data($hkota))
{
	$kota = $dkota['namakota'];
	$tipe = $dkota['tipe'];
	$kotaids = $dkota['kotaid'];
	
	if($tipe=="Kota") $kota = "$kota (Kota)";
	else $kota = $kota;
	
	$datakota[] = array("kotaid"=>$kotaids,"nama"=>$kota);
}
sql_free_result($hkota);
$tpl->assign("datakota",$datakota);

//propinsi
$datakec = array();
$pkec = "select kecid,namakecamatan from tbl_kecamatan where kotaid='$kotaid' and propid='$propid' order by kecid asc";
$hkec = sql($pkec);
while($dkec=sql_fetch_data($hkec))
{
	$kecid2 = $dkec['kecid'];
	$namakec = $dkec['namakecamatan'];
	$datakec[] = array("kecid"=>$kecid2,"nama"=>$namakec);
}
sql_free_result($hkec);
$tpl->assign("datakecamatan",$datakec);


//profesi
$dataprofesi = array();
$pprofesi = "select id,profesi from tbl_work order by profesi asc";
$hprofesi = sql($pprofesi);
while($dprofesi= sql_fetch_data($hprofesi))
{
	$dataprofesi[$dprofesi['id']] = array("id"=>$dprofesi['id'],"profesi"=>$dprofesi['profesi']);
}
sql_free_result($hprofesi);
$tpl->assign("dataprofesi",$dataprofesi);


?>