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

if(empty($userfullname))
	{
	$pesan[] = array("pesan"=>"Nama Lengkap masih kosong, silahkan isi Terlebih Dahulu");
	$salah = true;
	}
else if(empty($pendidikan))
	{
	$pesan[] = array("pesan"=>"Pendidikan masih kosong, silahkan isi Terlebih Dahulu");
	$salah = true;
	}
else if(!preg_match("/^[a-z0-9]+([\w-])*(((?<![_-])\.(?![_-]))[\w-]*[a-z0-9])*@(?![-])([a-z0-9-]){2,}((?<![-])\.[a-z]{2,6})+$/i", $useremail))
	{
	$pesan[] = array("pesan"=>"Email masih kosong atau penulisan email kurang benar, silahkan isi Terlebih Dahulu");
	$salah = true;
	}


else{ $salah = false; }


if(!$salah){

  	$perintah = "select max(userid) as baru from tbl_tes_user";
	$hasil = sql($perintah);
	$data = sql_fetch_data($hasil);
	$baru = $data['baru']+1;
	
	$tanggal = date("Y-m-d H:i:s");
    $query = "insert into tbl_tes_user (userid,userdob,userfullname,useremail,userphonegsm,usergender,usercreateddate,pendidikan,pekerjaan,marriagestatusid,kotaid,propinsiid,is_medis,covid_kondisi,tempat_kerja,is_pasienrshs)
			 values ('$baru','$userdob','$userfullname','$useremail','$userphonegsm','$usergender','$tanggal','$pendidikan','$pekerjaan','$marriagestatusid','$kotaid','$propinsiid','$is_medis','$covid_kondisi','$tempatkerja','$is_pasienrshs')";
    $hasil = sql($query);
	
	
   if($hasil)
   {
		

	session_start();	
	$_SESSION['tes_userid'] 		= $baru;
	$_SESSION['tes_userfullname'] 	= $userfullname;
	
	header("location: $fulldomain/termometer-depresi/tes");
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
