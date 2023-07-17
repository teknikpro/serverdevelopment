<?php
if(!$_SESSION['userid'] && empty($_SESSION['userid']))
{
	header("location: $fulldomain/temperaturkecemasan/login");
	exit();
}
if(isset($_POST['update']))
{
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
	
	$query= "update tbl_member set userfullname='$userfullname',usergender='$usergender',kecid='$kecid',kotaid='$kotaid',marriagestatusid='$marriagestatusid',pekerjaan='$pekerjaan',
			pendidikan='$pendidikan',userdob='$userdob',negaraid='$negaraid',propinsiid='$propinsiid',tempat_kerja='$tempatkerja', is_medis='$is_medis',is_pasienrshs='$is_pasienrshs',
			covid_kondisi='$covid_kondisi' where userid='$_SESSION[userid]'";
	$hasil = sql($query);
	
	if($hasil)
	{
		header("location: $fulldomain/temperaturkecemasan/tes");
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
		$covid_kondisi          = $data['covid_kondisi'];
		$is_pasienrshs         = $data['is_pasienrshs'];
		$marriagestatusid = $data['marriagestatusid'];
		$ymid             = $data['ymid'];
		$fbid             = $data['fbid'];
		$twitterid        = $data['twitterid'];
		$tempat_kerja         = $data['tempatkerja'];
		
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
		$tpl->assign("propinsiid",$propid);
		$tpl->assign("kecid",$kecid);
		$tpl->assign("kotaid",$kotaid);
		$tpl->assign("marriagestatusid",$marriagestatusid);
		$tpl->assign("is_medis",$is_medis);
		$tpl->assign("covid_kondisi",$covid_kondisi);
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
		$tempi = date("Y")-100;
		
		while($tempi < date("Y") - 1) {
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
		

	}

	
}
?>	
