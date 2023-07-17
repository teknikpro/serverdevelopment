<?php
if(isset($_POST['user']))
{
	$user 		= $_POST['user'];
	$user 		= str_replace("'","`",$user);
	$pass 		= $_POST['pass'];
	$password 	= md5($pass);	
	$perintah	= "select userid,userfullname,username,userdirname,avatar,verified,fbcid,twcid,gpcid,tipe,userlastloggedin from tbl_member where (username='$user' or useremail='$user') and userpassword='$password' and useractivestatus='1'";
	$hasil 		= sql($perintah);
	
	if(sql_num_rows($hasil)<1)
	{
		header("location: $fulldomain/tesmandiri/usernameerror");
		exit();
	}
	else
	{
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
		
		
		$userlastloggedin = date("Y-m-d H:i:s");
		
		$tnow = substr($userlastloggedin,0,10);
		$tlast = substr($lastlogin,0,10);
		
		if($tnow!=$tlast)
		{
			earnpoin("login",$_SESSION['userid']);
		}
		
		$views = "update tbl_member_stats set login=login+1 where userid='$userid'";
		$hsl = sql( $views);
		
		$views = "update tbl_member set userlastloggedin='$userlastloggedin',userlastactive='$userlastloggedin' where userid='$userid'";
		$hsl = sql( $views);
		
				
		$pelempar = "$fulldomain/tesmandiri/tes/"; 
		
		header("location: $pelempar");
		exit();


	}
}
else
{
	if($_SESSION['userid'])
	{
		$perintah = sql("select * from tbl_member where userid='$_SESSION[userid]'");
		$data = sql_fetch_data($perintah);
		sql_free_result($perintah);
		
		echo "select * from tbl_member where userid='$_SESSION[userid]'";
		
		print_r($data);
		
		$username         = $data['username'];
		$userfullname     = $data['userfullname'];
		$useraddress      = $data['useraddress'];
		$cityname         = $data['cityname'];
		$userphone        = $data['userphone'];
		$userphonegsm     = $data['userphonegsm'];
		$pendidikan   = $data['pendidikan'];
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
		$pekerjaan     = $data['pekerjaan'];
		$is_medis        = $data['is_medis'];
		$schools          = $data['schools'];
		$is_pasienrshs         = $data['is_pasienrshs'];
		$marriagestatusid = $data['marriagestatusid'];
		$ymid             = $data['ymid'];
		$fbid             = $data['fbid'];
		$twitterid        = $data['twitterid'];
		$tempat_kerja         = $data['tempat_kerja'];
		
		if(empty($negaraid))
			$negaraid		= "99";
		
		$dob = explode("-","$data[userdob]");
		
		$tpl->assign("username",$username);
		$tpl->assign("userfullname",$userfullname);
		$tpl->assign("useraddress",$useraddress);
		$tpl->assign("cityname",$cityname);
		$tpl->assign("userphone",$userphone);
		$tpl->assign("userphonegsm",$userphonegsm);
		$tpl->assign("pendidikan",$pendidikan);
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
		$tpl->assign("is_medis",$is_medis);
		$tpl->assign("schools",$schools);
		$tpl->assign("is_pasienrshs",$is_pasienrshs);
		$tpl->assign("pekerjaan",$pekerjaan);
		$tpl->assign("ymid",$ymid);
		$tpl->assign("tempat_kerja",$tempat_kerja);
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
		

		/*
		
		
		$pelempar = "$fulldomain/tesmandiri/tes/"; 
		header("location: $pelempar");
		exit();*/
	}
	
}
?>	
