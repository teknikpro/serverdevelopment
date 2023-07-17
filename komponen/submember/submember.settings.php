<?php
	if($_POST['saveacc']=="1")
	{
		$useremail			= $_POST['useremail'];
		$userfullname		= $_POST['userfullname'];
		$date				= $_POST['date'];
		$month				= $_POST['month'];
		$year				= $_POST['year'];
		$usergender			= $_POST['usergender'];
		$userphone			= $_POST['userphone'];
		$userphonegsm		= $_POST['userphonegsm'];
		$fbid				= $_POST['fbid'];
		$twitterid			= $_POST['twitterid'];
		$idpekerjaan		= $_POST['pekerjaanid'];
		$idpendidikan 		= $_POST['pendidikanid'];
		$userdob 			= "$year-$month-$date";

		$query	= "update tbl_member set userfullname='$userfullname',usergender='$usergender', pekerjaanid='$idpekerjaan', pendidikanid='$idpendidikan', userdob='$userdob',userphone='$userphone',userphonegsm='$userphonegsm', 
				fbid='$fbid',twitterid='$twitterid' where userid='$_SESSION[userid]'";
		$hasil 	= sql($query);
				
		if($hasil)
		{
			$error 		= "Selamat data anda di $title telah berhasil diubah, Lakukan perubahan profil secara berkala disesuaikan dengan kondisi anda saat ini.";
			$style		= "alert-success";
			$backlink	= "$fulldomain/user/settings.html";
		}
		else
		{
			$error 		= "Penyimpanan data gagal dilakukan ada beberapa kesalahan yang harus diperbaiki, silahkan periksa kembali.";
			$style		= "alert-danger";
			$backlink	= "$fulldomain/user/settings.html";
		}
		
		$tpl->assign("error",$error);
		$tpl->assign("style",$style);
		$tpl->assign("backlink",$backlink);
	}
	
	if($_POST['saveper']=="1")
	{
		
		$useraddress		= $_POST['useraddress'];
		$negaraid			= $_POST['negaraid'];
		$propinsiid			= $_POST['propinsiid'];
		$kotaid				= $_POST['cityname'];
		$userpostcode		= $_POST['userpostcode'];
		
		$query	= "update tbl_member set useraddress='$useraddress',cityname='$kotaid',negaraid='$negaraid',propinsiid='$propinsiid',userpostcode='$userpostcode' 
				where userid='$_SESSION[userid]'";
		$hasil 	= sql($query);
				
		if($hasil)
		{
			$error 		= "Selamat data anda di $title telah berhasil diubah, Lakukan perubahan profil secara berkala disesuaikan dengan kondisi anda saat ini.";
			$style		= "alert-success";
			$backlink	= "$fulldomain/user/settings.html";
		}
		else
		{
			$error 		= "Penyimpanan data gagal dilakukan ada beberapa kesalahan yang harus diperbaiki, silahkan periksa kembali.";
			$style		= "alert-danger";
			$backlink	= "$fulldomain/user/settings.html";
		}
		
		$tpl->assign("error",$error);
		$tpl->assign("style",$style);
		$tpl->assign("backlink",$backlink);
	}
	
	if($_POST['savepass']=="1")
	{
		$password1 	= $_POST['password1'];
		$password2 	= $_POST['password2'];
		$password3 	= $_POST['password3'];
		
		$passcode	= $salt.$password1;

		if(!empty($password1) || !empty($password2) || !empty($password3))
		{
			//Cek sudah terdaftar atau belum
			$passLama 	= sql_get_var("select userpassword from tbl_member where username='$_SESSION[username]'");
				  
			if(md5($passcode)!=$passLama)
			{
				$error 		= "Sepertinya kata sandi lama Anda kurang benar, silahkan dicoba kembali.";
				$style		= "alert-danger";
				$backlink	= "$fulldomain/user/settings.html";
			}
			else if($password2!=$password3)
			{
				$error 		= "Kata sandi baru yang pertama dan kedua tidak sama, silahkan isi terlebih dahulu.";
				$style		= "alert-danger";
				$backlink	= "$fulldomain/user/password.html";
			}
			else
			{
				if($password)
				{
					$usernewpassword 	= md5($password2);
					$tanggal 			= date("Y-m-d H:i:s");   
					
					$query	= "update tbl_member set userpassword='$usernewpassword',userlastactive='$tanggal' where username='$_SESSION[username]'";
					$hasil 	= sql($query);
					
					$error 		= "Penyimpanan kata sandi berhasil dilakukan.";
					$style		= "alert-success";
					$backlink	= "$fulldomain/user/dashboard.html";
				}
			}
		}
		else
		{
			if(empty($password1))
			{
				$error 		= "Kata sandi lama masih kosong, silahkan isi terlebih dahulu.";
				$style		= "alert-danger";
				$backlink	= "$fulldomain/user/settings.html";
			}
			if(empty($usernewpassword))
			{
				$error 		= "Kata sandi baru masih kosong, silahkan isi terlebih dahulu.";
				$style		= "alert-danger";
				$backlink	= "$fulldomain/user/settings.html";
			}
			if(empty($userRePassword))
			{
				$error 		= "Kata sandi kedua masih kosong, silahkan isi terlebih dahulu.";
				$style		= "alert-danger";
				$backlink	= "$fulldomain/user/settings.html";
			}
		}
		$tpl->assign("error",$error);
		$tpl->assign("style",$style);
		$tpl->assign("backlink",$backlink);
	}
	
	if($_POST['savepership']=="1")
	{
		
		$nama				= $_POST['nama'];
		$namalengkap		= $_POST['namalengkap'];
		$useraddress		= $_POST['useraddress'];
		$userphonegsm		= $_POST['userphonegsm'];
		$propinsiid			= $_POST['propinsiid'];
		$kotaid				= $_POST['cityname'];
		$userpostcode		= $_POST['userpostcode'];
		$date = date("Y-m-d H:i:s");
		
		$new = newid("useralamatid","tbl_member_alamat");
		
		$query	= "insert into tbl_member_alamat (useralamatid,nama,userid,userfullname,useraddress,kotaid,propinsiid,userpostcode,userphonegsm,create_date) values
					('$new','$nama','$_SESSION[userid]','$namalengkap','$useraddress','$kotaid','$propinsiid','$userpostcode','$userphonegsm','$date')";//die($query);
		$hasil 	= sql($query);
				
		if($hasil)
		{
			$error 		= "Selamat data anda di $title telah berhasil ditambahkan, Lakukan perubahan profil secara berkala disesuaikan dengan kondisi anda saat ini.";
			$style		= "alert-success";
			$backlink	= "$fulldomain/user/settings.html";
		}
		else
		{
			$error 		= "Penyimpanan data gagal dilakukan ada beberapa kesalahan yang harus diperbaiki, silahkan periksa kembali.";
			$style		= "alert-danger";
			$backlink	= "$fulldomain/user/settings.html";
		}
		
		$tpl->assign("error",$error);
		$tpl->assign("style",$style);
		$tpl->assign("backlink",$backlink);
	}
	
	
	$perintah 	= "select * from tbl_member where userid='$_SESSION[userid]'";
	$hasil 		= sql($perintah);
	$data 		= sql_fetch_data($hasil);
		
	$username 			= $data['username'];
	$userfullname		= $data['userfullname'];
	$useraddress		= $data['useraddress'];
	$kotaId				= $data['cityname'];
	$cityname			= sql_get_var("select namakota from tbl_kota where kotaid='$kotaId'");
	$userphone			= $data['userphone'];
	$userphonegsm		= $data['userphonegsm'];
	$useremail			= $data['useremail'];
	$aboutme			= $data['aboutme'];
	$usergender			= $data['usergender'];
	$userpostcode		= $data['userpostcode'];
	$negaraid			= $data['negaraid'];
	$propinsiid			= $data['propinsiid'];
	$idpekerjaan		= $data['pekerjaanid'];
	$idpendidikan		= $data['pendidikanid'];
	$kotaid				= $data['cityname'];
	$affiliations		= $data['affiliations'];
	$schools 			= $data['schools'];
	$YMId				= $data['YMId'];
	$fbid				= $data['fbid'];
	$twitterid			= $data['twitterid'];
	$DOB 				= explode("-",$data['userdob']);
	
	if(preg_match("/@/i",$username))
	{
		$uname1	= explode("@",$username);
		$uname	= $uname1[0];
	}
	else $uname = $username;
	
	$namalengkap		= explode(" ",$userfullname);
	$cnama				= count($namalengkap);
	$firstname			= $namalengkap[0];
	$lastname			= "";
	for($n=1; $n<=$cnama; $n++)
	{
		$lastname .= $namalengkap[$n]. " ";
	}
	
	$lastname = trim($lastname);
	
	$tpl->assign("username",$uname);
	$tpl->assign("usernameold",$username);
	$tpl->assign("userfullname",$userfullname);
	$tpl->assign("firstname",$firstname);
	$tpl->assign("lastname",$lastname);
	$tpl->assign("useraddress",$useraddress);
	$tpl->assign("cityname",$cityname);
	$tpl->assign("userphone",$userphone);
	$tpl->assign("userphonegsm",$userphonegsm);
	$tpl->assign("useremail",$useremail);
	$tpl->assign("aboutme",$aboutme);
	$tpl->assign("usergender",$usergender);
	$tpl->assign("userpostcode",$userpostcode);
	$tpl->assign("negaraid",$negaraid);
	$tpl->assign("propinsiid",$propinsiid);
	$tpl->assign("pekerjaanid",$idkerja);
	$tpl->assign("companies",$companies);
	$tpl->assign("fbid",$fbid);//echo $fbid;
	$tpl->assign("twitterid",$twitterid);
	$tpl->assign("kotaid",$kotaid);
	
	sql_free_result($hasil);

	//dapatkan data tanggal
	$dateLoop = array();
	$tempI = 1;
	while ($tempI < 32) {
		 if ($tempI < 10){
			 array_push($dateLoop,"0".$tempI);
			 $temp2 = "0".$tempI;
		 }else{
			 array_push($dateLoop,$tempI);
			 $temp2 = $tempI;
		}
		if($temp2 == $DOB[2]) $dateSelected = $temp2;
		$tempI++;
	}

	$monthLoop = array();
	$tempI = 1;
	while ($tempI < 13) {
		 if ($tempI < 10){
			 array_push($monthLoop,"0".$tempI);
			  $temp2 = "0".$tempI;
		 }else{
			 array_push($monthLoop,$tempI);
			 $temp2 = $tempI;
		}
		if($temp2 == $DOB[1]) $monthSelected = $temp2;
		$tempI++;

	}
	
	$yearLoop = array();
	$tempI = date("Y")-80;

	while ($tempI < date("Y") - 10) {
		 array_push($yearLoop,$tempI);
		if($tempI == $DOB[0]) $yearSelected = $tempI;
		$tempI++;

	}	
	$tpl -> assign( 'yearLoop', $yearLoop );
	$tpl -> assign( 'yearSelected' , $yearSelected);
	$tpl -> assign( 'monthLoop', $monthLoop );
	$tpl -> assign( 'monthSelected' , $monthSelected);
	$tpl -> assign( 'dateLoop', $dateLoop );
	$tpl -> assign( 'dateSelected' , $dateSelected);

	
	
	//Negara
	$datanegara = array();
	$pnegara 	= "select id,namanegara from tbl_negara order by namanegara asc";
	$hnegara 	= sql($pnegara);
	while($dnegara = sql_fetch_data($hnegara))
	{
		$idnegara	= $dnegara['id'];
		
		if($negaraid==0)
		{
			if($idnegara==97) $select = "selected='selected'";
			else  $select = "";
		}
		elseif($negaraid==$idnegara) $select = "selected='selected'";
		else $select = "";
		
		$datanegara[$idnegara] = array("id"=>$dnegara['id'],"namanegara"=>$dnegara['namanegara'],"select"=>$select);
	}
	sql_free_result($hnegara);
	$tpl->assign("datanegara",$datanegara);

	//propinsi
	$datapropinsi 	= array();
	$ppropinsi 		= "select propid,namapropinsi from tbl_propinsi order by namapropinsi asc";
	$hpropinsi 		= mysql_query($ppropinsi);
	while($dpropinsi= sql_fetch_data($hpropinsi))
	{
		$id	= $dpropinsi['propid'];
		
		if($propinsiid==$id) $select = "selected='selected'";
		else $select = "";
		
		$datapropinsi[$id] = array("id"=>$id,"namapropinsi"=>$dpropinsi['namapropinsi'],"select"=>$select);
	}
	sql_free_result($hpropinsi);
	$tpl->assign("datapropinsi",$datapropinsi);
	
	if($propinsiid!=0)
	{
		//kota
		$sqlkota	= "select kotaid,namakota from tbl_kota where propid='$propinsiid' order by namakota asc";
		$reskota	= sql($sqlkota);
		$datakota	= array();
		while ($rowkota	= sql_fetch_data($reskota))
		{
			$idkota	= $rowkota['kotaid'];
			$kota	= $rowkota['namakota'];
			
			if($kotaid==$idkota) $select = "selected='selected'";
			else $select = "";
			
			$datakota[$idkota]	= array("idkota"=>$idkota,"kota"=>$kota,"select"=>$select);
		}
		sql_free_result($reskota);
		$tpl->assign("datakota",$datakota);
	}

	//$tpl->assign("namarubrik","Account Setting");
	$tpl->assign("namarubrik","Pengaturan Akun");

	//Pekerjaan
	$datakerja = array();
	$sql 	= "select pekerjaanid,nama from tbl_pekerjaan order by nama asc";
	$hasil 	= sql($sql);
	while($data = sql_fetch_data($hasil))
	{
		$pekerjaanid	= $data['pekerjaanid'];
		
		
			if($idpekerjaan==$pekerjaanid) $select = "selected='selected'";
			else  $select = "";
		
		
		$datapekerjaan[$pekerjaanid] = array("pekerjaanid"=>$data['pekerjaanid'],"nama"=>$data['nama'],"select"=>$select);
	}
	sql_free_result($hasil);
	$tpl->assign("datapekerjaan",$datapekerjaan);

	//Pendidikan
	$datakerja = array();
	$sql 	= "select pendidikanid,nama from tbl_pendidikan order by pendidikanid asc";
	$hasil 	= sql($sql);
	while($data = sql_fetch_data($hasil))
	{
		$pendidikanid	= $data['pendidikanid'];
		
		
			if($idpendidikan==$pendidikanid) $select = "selected='selected'";
			else  $select = "";
		
		
		$datapendidikan[$pendidikanid] = array("pendidikanid"=>$data['pendidikanid'],"nama"=>$data['nama'],"select"=>$select);
	}
	sql_free_result($hasil);
	$tpl->assign("datapendidikan",$datapendidikan);
	
	//Data alamat Shipping
	$detailalamat = array();
	$palamat 	= "select useralamatid,nama,userfullname, useraddress, userpostcode, userphonegsm, kotaid, propinsiid from tbl_member_alamat where userid='$_SESSION[userid]' order by useralamatid asc";
	$halamat 	= sql($palamat);
	while($dalamat = sql_fetch_data($halamat))
	{
		$useralamatid	= $dalamat['useralamatid'];
		$nama	= $dalamat['nama'];
		$namapenerima	= $dalamat['userfullname'];
		$alamatpenerima	= $dalamat['useraddress'];
		$kodepos	= $dalamat['userpostcode'];
		$telppenerima	= $dalamat['userphonegsm'];
		$kotaidp	= $dalamat['kotaid'];
		$propinsiidp	= $dalamat['propinsiid'];
		
		$propinsip 		= sql_get_var("select namapropinsi from tbl_propinsi where propid='$propinsiidp'");
		$kotap			= sql_get_var("select namakota from tbl_kota where propid='$propinsiidp' and kotaid='$kotaidp'");
				
		$detailalamat[$useralamatid] = array("useralamatid"=>$useralamatid,"nama"=>$nama,"namapenerima"=>$namapenerima,"alamatpenerima"=>$alamatpenerima,"kodepos"=>$kodepos,"telppenerima"=>$telppenerima,"propinsip"=>$propinsip,"kotap"=>$kotap);
	}
	sql_free_result($halamat);
	$tpl->assign("detailalamat",$detailalamat);

	
?>