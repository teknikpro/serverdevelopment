<?php 
$code = $katid;
if(!empty($code))
{
	$perintah = "select id,userid,username,code,status,tanggal from tbl_member_konfirmasi where code='$code'";
	$hasil = sql($perintah);
	$jumlah = sql_num_rows($hasil);
		
	if($jumlah < 1 )
	{
		$pesanhasil = "Maaf, kode konfirmasi yang dimasukkan tidak cocok. Silahkan mencoba lagi dengan membuka e-mail kamu dan gunakan URL yang diberikan untuk validasi pendaftaran.";
		$berhasil	= "0";
	}
	else
	{
		$data = sql_fetch_data($hasil);
		$code = $data['kode'];
		$username = $data['username'];
		$status = $data['status'];
		
		if($status)
		{
			$pesanhasil = "Maaf, kode konfirmasi gagal didaftarkan karena kode tersebut telah dipakai atau kamu sudah pernah melakukan konfirmasi pendaftaran sebelumnya, silahkan mencoba login langsung ke website $title.";		
			$berhasil	= "0";
		}
		else
		{
			//update status member 
			$perintah1 = sql("update tbl_member_konfirmasi set status='1' where username='$username'");
			$perintah1 = sql("update tbl_member set userActiveStatus='1' where username='$username'");

			$perintah2 = "select userid,userfullname,username,userdirname from tbl_member where username='$username' and useractivestatus='1'";
			
			$hasil2 = sql($perintah2);
			$row = sql_fetch_data($hasil2);
			$userfullname =$row['userfullname'];
			$userdirname = $row['userdirname'];
			$username = $row['username'];
			$userid = $row['userid'];
			
			
			$_SESSION['userid'] = $userid;
			$_SESSION['userfullname'] = $userfullname;
			$_SESSION['username'] = $username;
			$_SESSION['userdirname'] = $userdirname;
			
		
			$views = sql("update tbl_member_stats set login=login+1 where userid='$userid'");

			$pesanhasil = "Pendaftaran telah dilakukan, silahkan login terlebih dahulu dibagian atas. Selanjutnya kamu perlu melengkapi  data di profile $title supaya bisa menikmati 
			semua fasilitas yang ada di $title";
			
			$berhasil	= "1";


		}
		
	}
}

$tpl->assign("pesanhasil",$pesanhasil);
$tpl->assign("berhasil",$berhasil);
		
?>
