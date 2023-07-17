<?php 
if(isset($_POST['useremail']))
{
	$useremail	= $_POST['useremail'];

	$pesan = array();	

	 if(!preg_match("/^[a-z0-9]+([\w-])*(((?<![_-])\.(?![_-]))[\w-]*[a-z0-9])*@(?![-])([a-z0-9-]){2,}((?<![-])\.[a-z]{2,6})+$/i", $useremail))
	{
		$pesanhasil = "Mohon maaf penggantian Password gagal dilakukan,Sepertinya ada kesalahan penulisan email anda, silahkan periksa kembali";
		$berhasil = "0";
	}
	else
	{

		$perintah = "select userid,userfullname,username from tbl_member where useremail='$useremail' and useractivestatus ='1'";
		$hasil = sql($perintah);
		$data = sql_fetch_data($hasil);
		$userid = $data['userid'];
		$userfullname = $data['userfullname'];
		$username = $data['username'];
		
		if(sql_num_rows($hasil) > 0)
		{
			
			$kode = generateCode(8);
			$pass = md5($kode);

			$query = "update tbl_member set userpassword='$pass' where username='$username'";
			$hasil = sql($query);
		
		if($hasil)
	   	{
		$subject = "$userfullname, Informasi password baru untuk anda";
		$isi =" 
Penggantian Password $title
======================================================================
	
Yth. $userfullname
Selamat $userfullname , Password anda di $title sekarang telah diganti 
dapat digunakan kembali. Silahkan anda login menggunakan :
	
Username  : $username<br />
Password : $kode
	
Jika Password dirasa terlalu panjang, silahkan ganti password secepatnnya. 
Terimakasih atas kesetiaan anda kepada $title
	
$owner
======================================================================	
		";
		$isihtml = "
<strong>Penggantian Password $title</strong><br />
<br />	
Yth. $userfullname<br />
Selamat $userfullname , Password anda di $title sekarang telah diganti 
dapat digunakan kembali. Silahkan anda login menggunakan :<br /><br />
	
Username  : $username<br />
Password : $kode<br />
<br /><br />	
Jika Password dirasa terlalu panjang, silahkan ganti password secepatnnya. 
Terimakasih atas kesetiaan anda kepada $title
<br /><br />	
$owner<br />
<br />";
		
		sendmail($userfullname,$useremail,$subject,$isi,emailhtml($isihtml));
	   
		$pesanhasil = "Selamat anda telah mereset password anda yang telah lupa, sekarang password anda telah kami kirim ke email sahabat, terkadang
		email kami masuk kedalam bulk inbox jika sahabat menggunakan email yahoo. Silahkan gunakan dengan sebaik mungkin atau
		ganti dengan password yang mudah anda ingat."; 
		$berhasil = "1";
		}
 	}
	else
	{
	
	$pesanhasil = "Mohon maaf penggantian Password gagal dilakukan, kemungkian username atau email anda tidak terdaftar dalam database kami, Jika
				 anda belum menjadi member, silahkan daftar terlebih dahulu";
	$berhasil = "0";
	}
	}
}
$tpl->assign("pesanhasil",$pesanhasil);
$tpl->assign("berhasil",$berhasil);
?>