<?php 

if(isset($_POST['userPassword']))
{
	$userPassword		= $_POST['userPassword'];
	$userNewPassword	= $_POST['userNewPassword'];
	$userRePassword		= $_POST['userRePassword'];
	$pesan = array();

	if(!empty($userPassword) || !empty($userRePassword) || !empty($userNewPassword))
	{
		$password = true;
		
		$passLama = sql_get_var("select userpassword from tbl_member where username='$_SESSION[username]'");
			  
		if(md5($userPassword)!=$passLama)
			{
			$pesan[3] = array("pesan"=>"Sepertinya Password lama anda kurang benar, silahkan dicoba kembali");
			$salah = true;
			}
		else if(empty($userPassword))
			{
			$pesan[4] = array("pesan"=>"Password Lama Masih kosong, silahkan isi Terlebih Dahulu");
			$salah = true;
			}
		else if(empty($userNewPassword))
			{
			$pesan[5] = array("pesan"=>"Password Baru masih kosong, silahkan isi Terlebih Dahulu");
			$salah = true;
			}
		else if(empty($userRePassword))
			{
			$pesan[6] = array("pesan"=>"Password kedua masih kosong, silahkan isi Terlebih Dahulu");
			$salah = true;
			}
		else if($userNewPassword!=$userRePassword)
			{
			$pesan[7] = array("pesan"=>"Password Baru yang pertama dan kedua tidak sama, silahkan isi Terlebih Dahulu");
			$salah = true;
			}
		else
			{ $salah = false; }
		
		
		if(!$salah)
		{
			if($password)
			{
				$userNewPassword = md5($userNewPassword);
				$tanggal = date("Y-m-d H:i:s");   
				
				$query=("update tbl_member set userpassword='$userNewPassword',userlastactive='$tanggal' where username='$_SESSION[username]'");
				$hasil = sql($query);
				
				$pesanhasil = "Pergantian Password berhasil dilakukan, lakukan perubahan Password secara berkala dalam upaya membantu sistem mengamankan akun anda.";
				$berhasil = "1";
			}
		}
		else
		{
			$pesanhasil = "Penyimpanan setting gagal dilakukan ada beberapa kesalahan yang mesti diperbaiki, silahkan periksa kembali kesalahan dibawah ini";
			$berhasil = "0";
		}
	}
	
$tpl->assign("pesan",$pesan);
$tpl->assign("pesanhasil",$pesanhasil);
$tpl->assign("berhasil",$berhasil);
}
?>