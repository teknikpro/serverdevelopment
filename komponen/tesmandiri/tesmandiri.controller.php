<?php
header("location: $fulldomain/temperaturkecemasan");
exit();

if($_SESSION['userid'] && $aksi!="profile")
{
	
	//profil photo
	$perintah	="select userid,userfullname,userdob,memberid,userlastloggedin,avatar,posting,follower,following,tema,usergender,useraddress,cityname,userpostcode,userphonegsm,negaraid,workid,useremail,header,fbcid,twcid,otpemail,otphandphone,otpemailvalid,otphandphonevalid,regvoucher
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
	$regvoucher        = $profil['regvoucher'];
	
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
	
}


if(empty($aksi) && $_SESSION['userid'])
{
	header("location: $fulldomain/tesmandiri/tes");
	exit();
}



if(!$_SESSION['userid'] && empty($aksi) ) {
	if(empty($aksi)) $aksi = "login";
	$file =  "tesmandiri.login.php"; 
	$nama_aksi = "Kirim Kode Validasi";
}
elseif(!$_SESSION['userid'] && $aksi=="login" ) {
	if(empty($aksi)) $aksi = "login";
	$file =  "tesmandiri.login.php"; 
	$nama_aksi = "Kirim Kode Validasi";
}
else if(!$_SESSION['userid'] && $aksi=="daftaranggota")
{
	$file =  "tesmandiri.daftaranggota.php";
	$nama_aksi = "Kirim Kode Validasi";
}
else if($aksi=="tes") $file =  "tesmandiri.tes.php";
else if($aksi=="hasil") $file =  "tesmandiri.hasil.php";
else
{
	if(empty($aksi)) $aksi = "dashboard";
}

$tpl->assign("namaaksi",$nama_aksi);
if(!empty($file))
{
	include($file); 
}


if(empty($_SESSION['userid']))
{
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
	
}

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

//lokasi
$dataarea = array();
$pkec = "select covid_id,nama from tbl_covid_area";
$hkec = sql($pkec);
while($dkec=sql_fetch_data($hkec))
{
	$covid_id = $dkec['covid_id'];
	$nama = $dkec['nama'];
	$dataarea[] = array("covid_id"=>$covid_id,"nama"=>$nama);
}
sql_free_result($hkec);
$tpl->assign("dataarea",$dataarea);


if($aksi=="pay")
{
	$tpl->display("pay.html");
}
elseif(!$_SESSION['userid']) {
	$tpl->display('tesmandiri.html');
}
else $tpl->display('tesmandiri.html');
?>