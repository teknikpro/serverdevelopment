<?php
	if($_POST['save']=="save")
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
				$backlink	= "$fulldomain/user/password.html";
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
				$backlink	= "$fulldomain/user/password.html";
			}
			if(empty($usernewpassword))
			{
				$error 		= "Kata sandi baru masih kosong, silahkan isi terlebih dahulu.";
				$style		= "alert-danger";
				$backlink	= "$fulldomain/user/password.html";
			}
			if(empty($userRePassword))
			{
				$error 		= "Kata sandi kedua masih kosong, silahkan isi terlebih dahulu.";
				$style		= "alert-danger";
				$backlink	= "$fulldomain/user/password.html";
			}
		}
		$tpl->assign("error",$error);
		$tpl->assign("style",$style);
		$tpl->assign("backlink",$backlink);
	}
	
	$tpl->assign("namarubrik","Pengaturan Kata Sandi");
?>