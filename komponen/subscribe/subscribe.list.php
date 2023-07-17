<?php
if(isset($_POST['nama']))
{
	
	$userphone			= clean($_POST['handphone']);
	$userfullname		= clean($_POST['nama']);
	$useremail			= clean($_POST['email']);
	$kota			= clean($_POST['kota']);
	$sefter			= clean($_POST['sefter']);
	
	if(empty($userfullname))
	{
		$pesan[] = array("pesan"=>"Nama Lengkap masih kosong, silahkan isi Terlebih Dahulu");
		$salah = true;
	}
	else if(empty($userphone))
	{
		$pesan[] = array("pesan"=>"Nomor Handphone masih kosong, silahkan isi Terlebih Dahulu");
		$salah = true;
	}
	else if(!preg_match("/^[a-z0-9]+([\w-])*(((?<![_-])\.(?![_-]))[\w-]*[a-z0-9])*@(?![-])([a-z0-9-]){2,}((?<![-])\.[a-z]{2,6})+$/i", $useremail))
	{
		$pesan[] = array("pesan"=>"Email masih kosong atau penulisan email kurang benar, silahkan isi Terlebih Dahulu");
		$salah = true;
	}
	else{ $salah = false; }
	
	if(!$salah)
	{
		
		$jumlahemail = sql_get_var("select count(*) as jumlah from tbl_subscriber where useremail='$useremail'");
		
	
		if($jumlahemail>0)
		{
			$_SESSION['popnewsletter'] = "1";
			setcookie("popupnewsletter","1", time() + (86400 * 30 * 365), "/", ".sentradetox.com"); // 86400 = 1 day
			header("location: $fulldomain/subscribe/registered/$useremail");
		}
		else
		{
			
				$perintah = "select max(subscriberid) as baru from tbl_subscriber";
				$hasil = sql($perintah);
				$data = sql_fetch_data($hasil);
				$baru = $data['baru']+1;
				
				$tgl = date("Y-m-d H:i:s");
			
				$query = "insert into tbl_subscriber (subscriberid,refuserid,userfullname,useremail,userhandphone,kota,sefter,create_date)
						 values ('$baru','$contactid','$userfullname','$useremail','$userphone','$kota','$sefter','$tgl')";
				$hasil = sql($query);
				
			
				if($hasil)
				{
					$_SESSION['popnewsletter'] = "1";
					setcookie("popupnewsletter","1", time() + (86400 * 30 * 365), "/", ".sentradetox.com"); // 86400 = 1 day
					
					//Info Ke Referensi 
					$subject = "Ada Subcriber Baru yang Harus Ditindak Lanjuti";
					$message = "Hai $contactname, ada yang subcriber kedalam website SentraDetox dan anda menjadi referensi. 
						Subcriber yang mendaftar bernama <strong>$userfullname</strong> dengan alamat email <strong>$useremail</strong>, Nomor Handphone <strong>$userphone</strong> dan berasal dari kota <strong>$kota</strong>.
						Silahkan tindak lanjuti informasi berikut, jika sudah ditindak lanjuti jangan lupa untuk update status dengan login ke www.sentradetox.com";
					
					sendmail($contactname,$contactuseremail,$subject,$message,emailhtml($message));
					kirimSMS($contactuserphone,"New Subscriber SentraDetox dari $userfullname,Handphone: $userphone, Email: $useremail di $kota");
					kirimSMS($userphone,"Terima kasih sudah subcribe di SentraDetox, Konsultan Detox anda $contactname akan segera menghubungi anda. Ebook anda dapat didownload di http://bit.ly/1Y9HmlH");
					
					
					//Kirim ke Subcriber
					$subject = "Ebook 21 Hari Detox Sehat Siap Didownload";
					$message = "Hai $userfullname, Terima kasih sudah Subscribe di SENTRADETOX.COM, anda saat ini sudah dapat
					mendownload EBook yang kami janjikan yaitu <strong>21 Hari Detox Sehat Langsing dan Menawan</strong>.
					Anda juga dapat konsultasi secara gratis kepada konsultan Detox yang akan menghubungi anda suatu waktu.
					<br><br>
					<a href=\"http://bit.ly/1Y9HmlH\"><strong>Download Ebook</strong></a>
					<br>
					<br>";
					
					sendmail($userfullname,$useremail,$subject,$message,emailhtml($message));
					
					//sendgcm($contactid,"New Prospek: Ada Subcriber Baru yang Harus Ditindak Lanjuti");
					
					header("location: $fulldomain/subscribe/success");
				}
				else
				{
					$msg = "Pendaftaran Newsletter gagal dilakukan, silahkan untuk mencoba sekali menggunakan form yang kami sedikan dibawah ini";
					$tpl->assign("msg",$msg);
					$tpl->assign("pesan",$pesan);
				}
		}
	}
	else
	{
		$msg = "Pendaftaran Newsletter gagal dilakukan, silahkan untuk mencoba sekali menggunakan form yang kami sedikan dibawah ini";
		$tpl->assign("msg",$msg);
		$tpl->assign("pesan",$pesan);
	}
}

?>