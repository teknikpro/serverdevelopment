<?php 
if(isset($_POST['validateemail']) || isset($_POST['validatehp']) )
{
	
	$code = $_POST['code'];
	
	if(isset($_POST['validateemail']))
	{
		
		$kode = sql_get_var("select otpemail from tbl_member where userid='$_SESSION[userid]'");
		
		if($kode==$code)
		{
			
			$hasilx = sql("update tbl_member set otpemailvalid='1' where  userid='$_SESSION[userid]'");
			
			if($hasilx)
			{
				$pesanhasil = "Terima kasih, saat ini email anda tervalidasi di $title ini. Validasi juga handphone anda untuk mendapatkan diskon masuk d'Fun Station";
				$berhasil	= "1";
			}
			else
			{
				$pesanhasil = "Mohon maaf pengiriman kode gagal dilakukan, periksa email anda untuk memastikan bahwa kode sudah kami kirimkan.";
				$berhasil	= "0";
			}	
		}
		else
		{
			$pesanhasil = "Mohon maaf validasi kode gagal dilakukan, kode yang anda masukan salah.";
			$berhasil	= "0";
		}
	}
	
	if(isset($_POST['validatehp']))
	{
		
		$kode = sql_get_var("select otphandphone from tbl_member where userid='$_SESSION[userid]'");
		
		if($kode==$code)
		{
			
			$hasilx = sql("update tbl_member set otphandphonevalid='1' where  userid='$_SESSION[userid]'");
			
			if($hasilx)
			{
				$pesanhasil = "Terima kasih, saat ini handphone anda tervalidasi di $title ini. Validasi juga email anda untuk mendapatkan diskon masuk d'Fun Station (jika belum)";
				$berhasil	= "1";
			}
			else
			{
				$pesanhasil = "Mohon maaf pengiriman kode gagal dilakukan, periksa handphone anda untuk memastikan bahwa kode sudah kami kirimkan.";
				$berhasil	= "0";
			}	
		}
		else
		{
			$pesanhasil = "Mohon maaf validasi kode gagal dilakukan, kode yang anda masukan salah.";
			$berhasil	= "0";
		}
	}
	
	$tpl->assign("validate","1");
	
	$tpl->assign("pesanhasil",$pesanhasil);
	$tpl->assign("berhasil",$berhasil);
	$tpl->assign("jenis",$jenis);
	

}
else
{

	$jenis = $var[4];
	
	if($jenis=="email")
	{
		$last = $_SESSION['lastvalidemail'];
		
		if(empty($last))
		{
			$code = generateno(6);
			$hasilx = sql("update tbl_member set otpemail='$code' where userid='$_SESSION[userid]'");
			
			$perintah = "select userid,userfullname,username from tbl_member where userid='$_SESSION[userid]'";
			$hasil = sql($perintah);
			$data = sql_fetch_data($hasil);
			$userid = $data['userid'];
			$userfullname = $data['userfullname'];
			$username = $data['username'];
			
			
			$subject = "Kode Validasi Email d'Fun Station";
			$isi =" 
	Kode Validasi Email d'Fun Station
	==========================================
		
	Yth. $userfullname
	Berikut ini adalah kode yang dapat anda masukan untuk melakukan
	validasi email anda di dFun Station:
		
	Kode : $code
		
	Silahkan masukan kode yang anda terima ini dihalaman dashboard
	member dFun Station anda.
		
	$owner
	=============================================	
			";
			$isihtml = "
	<strong>Kode Validasi Email d'Fun Station</strong><br />
	<br /><br />	
	Yth. $userfullname<br />	
	Berikut ini adalah kode yang dapat anda masukan untuk melakukan
	validasi email anda di dFun Station:<br /><br />
		
	Kode : <strong>$code</strong><br /><br />
		
	Silahkan masukan kode yang anda terima ini dihalaman dashboard
	member dFun Station anda.<br /><br />
		
	$owner";
		
		sendmail($userfullname,$useremail,$subject,$isi,emailhtml($isihtml));
	   
		}
		
		if($hasilx)
		{
			$pesanhasil = "Kami telah mengirimkan kode melalui email anda, silahkan cek email anda dan masukan kode untuk memvalidasi email anda di website $title ini.";
			$berhasil	= "1";
			
			$_SESSION['lastvalideemail'] = 1;
		}
		else
		{
			$pesanhasil = "Mohon maaf pengiriman kode gagal dilakukan karena anda melakukan pengiriman kode berulang ulang, tunggu beberapa saat kembali untuk mengirimkan kode, periksa email anda untuk memastikan bahwa kode sudah kami kirimkan.";
			$berhasil	= "0";
		}
		
		$tpl->assign("pesanhasil",$pesanhasil);
		$tpl->assign("berhasil",$berhasil);
		$tpl->assign("jenis",$jenis);
	
	}
	
	if($jenis=="hp")
	{
		$last = $_SESSION['lastvalidehp'];
		
		if(empty($last))
		{
			$code = generateno(6);
			$hasilx = sql("update tbl_member set otphandphone='$code' where userid='$_SESSION[userid]'");
			
			$perintah = "select userid,userfullname,userphonegsm from tbl_member where userid='$_SESSION[userid]'";
			$hasil = sql($perintah);
			$data = sql_fetch_data($hasil);
			$userid = $data['userid'];
			$userfullname = $data['userfullname'];
			$userphonegsm = $data['userphonegsm'];
			
			if($userphonegsm)
			{
				$isi ="Kode validasi member di dFun Station anda adalah: $code";
				kirimsms($userphonegsm,$isi);
			}
			
			if($hasilx)
			{
				$pesanhasil = "Kami telah mengirimkan kode melalui handphone anda dengan nomor $userphonegsm, silahkan cek handphone anda dan masukan kode untuk memvalidasi handphone anda di website $title ini.";
				$berhasil	= "1";
				
				$_SESSION['lastvalidehp'] = 1;
			}
			else
			{
				$pesanhasil = "Mohon maaf pengiriman kode gagal dilakukan karena anda melakukan pengiriman kode berulang ulang, tunggu beberapa saat kembali untuk mengirimkan kode, periksa handphone anda untuk memastikan bahwa kode sudah kami kirimkan.";
				$berhasil	= "0";
			}
			
			$tpl->assign("pesanhasil",$pesanhasil);
			$tpl->assign("berhasil",$berhasil);
			$tpl->assign("jenis",$jenis);
	   
		}
		
		
	
	}
	
	if($jenis=="kirim")
	{
			$perintah = "select userid,useremail,userfullname,userphonegsm,regvoucher from tbl_member where userid='$_SESSION[userid]'";
			$hasil = sql($perintah);
			$data = sql_fetch_data($hasil);
			$regvoucher = $data['regvoucher'];
			$userfullname = $data['userfullname'];
			$useremail = $data['useremail'];
			$userphonegsm = $data['userphonegsm'];
			
			
			if($regvoucher=="0")
			{
				
				$vouchercode = generateCode(6);
				$vouchercode = strtoupper($vouchercode);
				
				$expiredate = date('Y-m-d H:i:s', strtotime("+30 days"));
				$expiredate = tanggal($expiredate);
				
				$email = '<table style="border:2px solid #666; font-family: Arial; padding:10px; background:#FFF" width="609" border="0" cellspacing="5" cellpadding="5">
			  <tr>
				<td width="225" height="256"><img src="https://www.dfunstation.com/images/img.logo.dfun.jpg"></td>
				<td width="349" align="center" style="font-size:20px"><p>VOUCHER POTONGAN MASUK</p>
				  <p><span style="font-size:40px; font-weight:bold">'.$vouchercode.'</span><br>
					<br>
					Potongan masuk Dfun Station sebesar 20%  </p>
				  <p  style="font-size:14px">Berlaku hingga<br> '.$expiredate.'</p>
				  <p>&nbsp;</p></td>
			  </tr>
			</table>';
			
				
				$date = date("Y-m-d H:i:s");
				$newredeeimd = newid("id","tbl_member_redeem");
				$sql = "insert into tbl_member_redeem(id,create_date,userid,poin,vouchercode,nama,redeemid)
							values('$newredeeimd','$date','$_SESSION[userid]','$poin','$vouchercode','Potongan Masuk dari Registrasi','$idcontent')";
				$hsl = sql($sql);
				
				$pesansms = "Yth. $userfullname. Kode voucher registrasi anda adalah: $vouchercode. Expire: $expiredate. dfunStation";
				$emailjudul  = "Voucher Potongan Masuk dfun Station";
				
				kirimSMS($userphonegsm,$pesansms);
				sendmail($userfullname,$useremail,$emailjudul,$email,$email);
				
				$pesanhasil = "Selamat, kode voucher sudah kami kirimkan melalui email dan handphone anda. Silahkan datang ke dFun Station dan tunjukan voucher yang anda
				miliki ke petugas tiket";
				$berhasil	= "1";
				
				sql("update tbl_member set regvoucher='1' where userid='$_SESSION[userid]'");
			}
			else
			{
				$pesanhasil = "Mohon maaf pengiriman voucher gagal dilakukan karena anda melakukan pengiriman voucher berulang ulang, tunggu beberapa saat kembali untuk mengirimkan kode, periksa handphone anda untuk memastikan bahwa kode sudah kami kirimkan.";
				$berhasil	= "0";
			}
			

			
			$tpl->assign("pesanhasil",$pesanhasil);
			$tpl->assign("berhasil",$berhasil);
			$tpl->assign("jenis",$jenis);
	   
	
	
	}
	
	
	
	

}
?>
