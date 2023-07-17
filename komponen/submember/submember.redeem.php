<?php
$subacc = $var[4];
$tpl->assign("subacc",$subacc);

if($subacc=="create")
{
	if(empty($_SESSION['username'])){ header("location: $fulldomain/user/");  exit(); }
	
	$last = sql_get_var("select point from tbl_member where userid='$_SESSION[userid]'");
	$tpl->assign("lastpoin",$last);
	
	$id = $var[5];
	
	$perintah = "select id,nama,oleh,ringkas,lengkap,gambar1,gambar,poin,alias,views from tbl_redeem where id='$id'";
	$hasil = sql($perintah);
	$data =  sql_fetch_data($hasil);
	
	$idcontent = $data['id'];
	$nama=$data['nama'];
	$oleh = $data['oleh'];
	$lengkap= $data['lengkap'];
	$tanggal = tanggal($data['create_date']);
	$gambar = $data['gambar1'];
	$gambarshare = $data['gambar'];
	$ringkas = $data['ringkas'];
	$alias = $data['alias'];
	$poin = $data['poin'];
	
	$tpl->assign("detailid",$idcontent);
	$tpl->assign("detailnama",$nama);
	$tpl->assign("detaillengkap",$lengkap);
	$tpl->assign("detailringkas",$ringkas);
	$tpl->assign("detailcreator",$oleh);
	$tpl->assign("detailtanggal",$tanggal);
	
	

	if($last<$poin)
	{
		$msg = "Poin anda tidak cukup untuk melakukan penukaran poin untuk paket redeem <b>$nama</b> yang mengharuskan anda memiliki poin sebanyak <strong>$poin poin</strong>. Silahkan
		kumpulkan poin lagi dan tukarkan poin anda dengan banyak paket redeem yang tersedia";
		$cukup = "0";
	}
	else{
		$msg = "Apakah anda yakin akan menukarkan poin anda sebanyak <strong>$poin poin </strong>untuk paket <b>$nama</b>. Kami akan mengirimkan voucher penukaran poin anda
		melalui email. Silahkan cetak email anda atau tunjukan kepada petugas di dfun station yang siap melayani anda.";
		$cukup = "1";
	}
	
	$tpl->assign("msg",$msg);
	$tpl->assign("cukup",$cukup);
}
else if($subacc=="send")
{
	if(empty($_SESSION['username'])){ header("location: $fulldomain/user/");  exit(); }
	
	$last = sql_get_var("select point from tbl_member where userid='$_SESSION[userid]'");
	$tpl->assign("lastpoin",$last);
	
	$id = $var[5];
	
	$perintah = "select id,nama,oleh,ringkas,lengkap,gambar1,gambar,poin,alias,views from tbl_redeem where id='$id'";
	$hasil = sql($perintah);
	$data =  sql_fetch_data($hasil);
	
	$idcontent = $data['id'];
	$nama=$data['nama'];
	$oleh = $data['oleh'];
	$lengkap= $data['lengkap'];
	$tanggal = tanggal($data['create_date']);
	$gambar = $data['gambar1'];
	$gambarshare = $data['gambar'];
	$ringkas = $data['ringkas'];
	$alias = $data['alias'];
	$poin = $data['poin'];
	
	$tpl->assign("detailid",$idcontent);
	$tpl->assign("detailnama",$nama);
	$tpl->assign("detaillengkap",$lengkap);
	$tpl->assign("detailringkas",$ringkas);
	$tpl->assign("detailcreator",$oleh);
	$tpl->assign("detailtanggal",$tanggal);
	
	
	if(empty($idcontent)){ header("location: $fulldomain/redeem/");  exit(); }
	
	if(isset($_POST['id']))
	{
		$vouchercode = generateCode(6);
		$vouchercode = strtoupper($vouchercode);
		
		$expiredate = date('Y-m-d H:i:s', strtotime("+30 days"));
		$expiredate = tanggal($expiredate);
		
		$email = '<table style="border:2px solid #666; font-family: Arial; padding:10px; background:#FFF" width="609" border="0" cellspacing="5" cellpadding="5">
	  <tr>
		<td width="225" height="256"><img src="https://www.dfunstation.com/images/img.logo.dfun.jpg"></td>
		<td width="349" align="center" style="font-size:20px"><p>VOUCHER PENUKARAN POIN<br>
		  MEMBER DFUN STATION</p>
		  <p><span style="font-size:40px; font-weight:bold">'.$vouchercode.'</span><br>
			<br>
			'.$nama.'      </p>
		  <p  style="font-size:14px">Berlaku hingga<br> '.$expiredate.'</p>
		  <p>&nbsp;</p></td>
	  </tr>
	</table>';
	
		
		//Data User
		$sqlm = "select username,userfullname,useremail,userphonegsm from tbl_member where userid='$_SESSION[userid]'";
		$hslm = sql($sqlm);
		$datam = sql_fetch_data($hslm);
		
		$userfullname = $datam['userfullname'];
		$useremail = $datam['useremail'];
		$userhandphone = $datam['userphonegsm'];
		$memberid = $datam['memberid'];
		sql_free_result($hslm);	
		
		
		$date = date("Y-m-d H:i:s");
		$newredeeimd = newid("id","tbl_member_redeem");
		$sql = "insert into tbl_member_redeem(id,create_date,userid,poin,vouchercode,nama,redeemid)
					values('$newredeeimd','$date','$_SESSION[userid]','$poin','$vouchercode','$nama','$idcontent')";
		$hsl = sql($sql);
		
		if($hsl)
		{
			$pesansms = "Penukaran $poin Poin anda berhasil untuk $nama, VoucherCode anda $vouchercode. dfunStation";
			$emailjudul  = "Voucher Penukaran Poin Member dfun Station";
			
			kirimSMS($userhandphone,$pesansms);
			sendmail($userfullname,$useremail,$emailjudul,$email,$email);
			
			$date = date("Y-m-d H:i:s");
			$expiredate = date('Y-m-d H:i:s', strtotime("+$pointexpire months"));
			
			$totpoint = $last-$poin;
		
			$sql = "insert into tbl_member_pointredeem(create_date,userid,transid,point,status,conversion,balance)
					values('$date','$userid','$newredeeimd','$poin','1','$redeempoint','$totpoint')";
			$hsl = sql($sql);
			
		
			$sql = "insert into tbl_member_point_history(create_date,userid,transid,ordernumber,redeemnumber,point,tipe,balancetotal,activity)
								values('$date','$_SESSION[userid]','$newredeeimd','$earnordernumber','$ordernumber','$poin','DB','$totpoint','redeem')";
			$hsl = sql($sql);
			
			
			$update = sql("update tbl_member set point=$totpoint where userid='$_SESSION[userid]'");
			
			$msg = "Penukaran poin anda untuk paket <b>$nama</b> berhasil dilakukan, anda bisa membuka email anda untuk melihat
			voucher yang telah kami kirimkan. Terima kasih";
			$cukup = "1";
			
			$msg = base64_encode($nama);
			header("location: $fulldomain/user/redeem/sukses/$msg");
			exit();
		
		}
	}
}
else
{
	$msg = base64_decode($var[5]);
	$msg = "Penukaran poin anda untuk paket <b>$msg</b> berhasil dilakukan, anda bisa membuka email anda untuk melihat
			voucher yang telah kami kirimkan. Terima kasih";
			$cukup = "1";
	$tpl->assign("pesanhasil",$msg);
	$tpl->assign("berhasil",$cukup);
			
}

?>