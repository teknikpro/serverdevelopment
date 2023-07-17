<?php
$input = file_get_contents('php://input');
$data = date('Y-m-d H:i:s')." | Transaksi Upgrade $order_id\r\n";
$file = "logs/backlog-upgrade.txt";
$open = fopen($file, "a+"); 
fwrite($open, "$data"); 
fclose($open);


if ($transaction == 'capture') {
  if ($type == 'credit_card'){
    if($fraud == 'challenge'){
		  $message = "Transaction order_id: " . $order_id ." is challenged by FDS";
      } 
      else
	  {
	  
		$orderid = $order_id;
			
		$sql = "update tbl_transaksi_upgrade set status='3',paymentinfo='$input' where invoiceid='$orderid'";
		$hasil	= sql($sql);
		
		
	
		if($hasil)
		{
			$sql = sql("select transaksiid,totalinvoice,userid,paketid from tbl_transaksi_upgrade where invoiceid='$orderid'");
			
			$dt = sql_fetch_data($sql);
			
			$transaksiid = $dt['transaksiid'];
			$totalinvoice = $dt['totalinvoice'];
			$userid = $dt['userid'];
			$paketid = $dt['paketid'];
		
			// ambil data kontak admin
			$tk       = sql_get_var_row("select nama,alamat,telp,gsm from tbl_static where alias='kontak' limit 1");
			$namatk   = $tk['nama'];
			$alamattk = $tk['alamat'];
			$telptk   = $tk['telp'];
			$gsmtk    = $tk['gsm'];
			
			$paket = sql_get_var_row("select nama,harga,berlaku,voucher30,voucher30podcast,diskon10konsulfs,diskon20fs,diskonmitra,berlaku,harga,disc_perpanjangan from tbl_member_sec where secid='$paketid'");
			
			
			$harga = $paket['harga'];
			$harga1 = rupiah($paket['harga']);
			$nama = $paket['nama'];
			$voucher30 = $paket['voucher30'];
			$berlaku = $paket['berlaku'];
			$diskon10konsulfs = $paket['diskon10konsulfs'];
			$voucher30podcast = $paket['voucher30podcast'];
			
			
			//create voucher
			for($i=0;$i<$voucher30;$i++)
			{
				//CreateVoucher
				$kode = date("Ym").generateCode(6);
				$kode = strtoupper($kode);
				$voucherid = newid("voucherid","tbl_chat_voucher");
				
				$nama = "Voucher Chat 30 Menit";
				
				$expire = date("Y-m-d H:i:s", strtotime("+1 months"));
				$texpire = tanggal($expire);
				
				$query	= "insert into tbl_chat_voucher(`voucherid`,kode,nama,userid,`transid`,`status`,create_date, create_userid,update_userid,menit,expiredate) 
							values ('$voucherid','$kode','$nama','$userid','$orderid','0','$date','$userid','$userid','30','$expire')";
				$hasil = sql($query);
			}
			
			//create voucher
			for($i=0;$i<$voucher30podcast;$i++)
			{
				//CreateVoucher
				$kode = date("Ym").generateCode(6);
				$kode = strtoupper($kode);
				$voucherid = newid("voucherid","tbl_chat_voucher");
				
				$nama = "Voucher Chat 30 Menit Podcast";
				
				$expire = date("Y-m-d H:i:s", strtotime("+1 months"));
				$texpire = tanggal($expire);
				
				$query	= "insert into tbl_chat_voucher(`voucherid`,kode,nama,userid,`transid`,`status`,create_date, create_userid,update_userid,menit,expiredate,podcast) 
							values ('$voucherid','$kode','$nama','$userid','$orderid','0','$date','$userid','$userid','30','$expire','1')";
				$hasil = sql($query);
			}
			
			
			//Berlaku
			$expire = date("Y-m-d H:i:s", strtotime("+$berlaku months"));
			$texpire = tanggal($expire);
			
			//Update
			$sql = "update tbl_member set paketid='$paketid',expirepaket='$expire' where userid='$userid'";
			$hsl = sql($sql);			

			
			$user = sql_get_var_row("select userfullname,useremail,userphonegsm from tbl_member where userid='$userid'");
			$userfullname = $user['userfullname'];
			$useremail = $user['useremail'];
			$userphonegsm = $user['userphonegsm'];
			
			$subject			= "Upgrade Berhasil";
			$html = "Yth. $userfullname.<br><br>Terima kasih telah melakukan Upgrade membership di $title.
			Saat ini keanggotaan anda adalah $nama. <br><br>Anda berhak mendapatkan $voucher30 Voucher Konsultasi 30 menit dan $voucher30podcast voucher
			konsultasi podcast selama 30 menit dan
			banyak promo kami lainnya.
			<br><br>Keanggotaan anda akan berlaku selama <strong>$berlaku bulan</strong> atau sampai
			dengan tanggal $texpire. Terima kasih";
						
			$sendmail	= sendmail($userfullname,$useremail,$subject,$html,emailhtml($html));
			

			//kirim sms ke admin
			$sqlh     = "select gsm from tbl_static where alias='kontak' limit 1";
			$queryh   = sql($sqlh);
			$rowh     = sql_fetch_data($queryh);
			$gsmadmin = $rowh['gsm'];
		
			//$kirimsms	= kirimSMS($gsmadmin,"Info: Informasi Upgade $orderid - $title, silahkan login ke $fulldomain/panel utk melihat detail pemesanan. Terimakasih");
			
		}
     
	 
	 
      }
    }
  }
else if ($transaction == 'settlement')
{
  
 
  
  if ($type !='')
  {
		$orderid = $order_id;
			
		$orderid = $order_id;
			
		$sql = "update tbl_transaksi_upgrade set status='3',paymentinfo='$input' where invoiceid='$orderid'";
		$hasil	= sql($sql);
	
		if($hasil)
		{
			$sql = sql("select transaksiid,totalinvoice,userid,paketid from tbl_transaksi_upgrade where invoiceid='$orderid'");
			$dt = sql_fetch_data($sql);
			
			$transaksiid = $dt['transaksiid'];
			$totalinvoice = $dt['totalinvoice'];
			$userid = $dt['userid'];
			$paketid = $dt['paketid'];
		
			
			// ambil data kontak admin
			$tk       = sql_get_var_row("select nama,alamat,telp,gsm from tbl_static where alias='kontak' limit 1");
			$namatk   = $tk['nama'];
			$alamattk = $tk['alamat'];
			$telptk   = $tk['telp'];
			$gsmtk    = $tk['gsm'];
			
			$paket = sql_get_var_row("select nama,harga,berlaku,voucher30,voucher30podcast,diskon10konsulfs,diskon20fs,diskonmitra,berlaku,harga,disc_perpanjangan from tbl_member_sec where secid='$paketid'");
			$harga = $paket['harga'];
			$harga1 = rupiah($paket['harga']);
			$nama = $paket['nama'];
			$voucher30 = $paket['voucher30'];
			$berlaku = $paket['berlaku'];
			$diskon10konsulfs = $paket['diskon10konsulfs'];
			$voucher30podcast = $paket['voucher30podcast'];
			
			//create voucher
			for($i=0;$i<$voucher30;$i++)
			{
				//CreateVoucher
				$kode = date("Ym").generateCode(6);
				$kode = strtoupper($kode);
				$voucherid = newid("voucherid","tbl_chat_voucher");
				
				$nama = "Voucher Chat 30 Menit";
				
				$expire = date("Y-m-d H:i:s", strtotime("+1 months"));
				$texpire = tanggal($expire);
				
				$query	= "insert into tbl_chat_voucher(`voucherid`,kode,nama,userid,`transid`,`status`,create_date, create_userid,update_userid,menit,expiredate) 
							values ('$voucherid','$kode','$nama','$userid','$orderid','0','$date','$userid','$userid','30','$expire')";
				$hasil = sql($query);
			}
			
			//create voucher
			for($i=0;$i<$voucher30podcast;$i++)
			{
				//CreateVoucher
				$kode = date("Ym").generateCode(6);
				$kode = strtoupper($kode);
				$voucherid = newid("voucherid","tbl_chat_voucher");
				
				$nama = "Voucher Chat 30 Menit Podcast";
				
				$expire = date("Y-m-d H:i:s", strtotime("+1 months"));
				$texpire = tanggal($expire);
				
				$query	= "insert into tbl_chat_voucher(`voucherid`,kode,nama,userid,`transid`,`status`,create_date, create_userid,update_userid,menit,expiredate,podcast) 
							values ('$voucherid','$kode','$nama','$userid','$orderid','0','$date','$userid','$userid','30','$expire','1')";
				$hasil = sql($query);
			}
			
			
			//Berlaku
			$expire = date("Y-m-d H:i:s", strtotime("+$berlaku months"));
			$texpire = tanggal($expire);
			
			//Update
			$sql = "update tbl_member set paketid='$paketid',expirepaket='$expire' where userid='$userid'";
			$hsl = sql($sql);			

			
			$user = sql_get_var_row("select userfullname,useremail,userphonegsm from tbl_member where userid='$userid'");
			$userfullname = $user['userfullname'];
			$useremail = $user['useremail'];
			$userphonegsm = $user['userphonegsm'];
			
			$subject			= "Upgrade Berhasil";
			$html = "Yth. $userfullname.<br><br>Terima kasih telah melakukan Upgrade membership di $title.
			Saat ini keanggotaan anda adalah $nama. <br><br>Anda berhak mendapatkan $voucher30 Voucher Konsultasi 30 menit dan $voucher30podcast voucher
			konsultasi podcast selama 30 menit dan
			banyak promo kami lainnya.
			<br><br>Keanggotaan anda akan berlaku selama <strong>$berlaku bulan</strong> atau sampai
			dengan tanggal $texpire. Terima kasih";
						
			$sendmail	= sendmail($userfullname,$useremail,$subject,$html,emailhtml($html));
			

			//kirim sms ke admin
			$sqlh     = "select gsm from tbl_static where alias='kontak' limit 1";
			$queryh   = sql($sqlh);
			$rowh     = sql_fetch_data($queryh);
			$gsmadmin = $rowh['gsm'];
		
			//$kirimsms	= kirimSMS($gsmadmin,"Info: Informasi Upgade $orderid - $title, silahkan login ke $fulldomain/panel utk melihat detail pemesanan. Terimakasih");
			
		}
		
		$message = "Payment using " . $type . " for transaction order_id: " . $order_id . " is succsess.";
    }
} 
else if($transaction == 'pending')
{
  
  		$orderid = $order_id;
			
		$sql = "update tbl_transaksi_upgrade set status='2',paymentinfo='$input' where invoiceid='$orderid'";
		$hasil	= sql($sql);
	
		if($hasil)
		{
			$sql = sql("select transaksiid,totalinvoice,userid,paketid from tbl_transaksi_upgrade where invoiceid='$orderid'");
			$dt = sql_fetch_data($sql);
			
			$transaksiid = $dt['transaksiid'];
			$totalinvoice = $dt['totalinvoice'];
			$userid = $dt['userid'];
			$paketid = $dt['paketid'];
		
			
			// ambil data kontak admin
			$tk       = sql_get_var_row("select nama,alamat,telp,gsm from tbl_static where alias='kontak' limit 1");
			$namatk   = $tk['nama'];
			$alamattk = $tk['alamat'];
			$telptk   = $tk['telp'];
			$gsmtk    = $tk['gsm'];
			sql_free_result($hslm);
			
			$paket = sql_get_var_row("select nama,harga,berlaku,voucher30,voucher30podcast,diskon10konsulfs,diskon20fs,diskonmitra,berlaku,harga,disc_perpanjangan from tbl_member_sec where secid='$paketid'");
			$harga = $paket['harga'];
			$harga1 = rupiah($paket['harga']);
			$nama = $paket['nama'];
			$voucher30 = $paket['voucher30'];
			$berlaku = $paket['berlaku'];
			$diskon10konsulfs = $paket['diskon10konsulfs'];
			$voucher30podcast = $paket['voucher30podcast'];
			
					
			$user = sql_get_var_row("select userfullname,useremail,userphonegsm from tbl_member where userid='$userid'");
			$userfullname = $user['userfullname'];
			$useremail = $user['useremail'];
			$userphonegsm = $user['userphonegsm'];
			
			$subject			= "Transaksi Pending: $orderid";
			$html = "Yth. $userfullname.<br><br>Ini merupakan tagihan proses upgrade membership anda. Berikut ini adalah
			informasi tagihan anda:<br><br>
			<strong>Nomor Tagihan:</strong> $orderid<br>
			<strong>Paket Membership:</strong> $nama<br>
			<strong>Harga:</strong> $harga1<br><br>
			Silahkan lanjutkan pembayaran anda pembayaran anda. Panduan cara pembayaran kami kirimkan di email
			yang berbeda. Lakukan pembayaran sebelum $datetransfer. Terima kasih";  
						
			$sendmail			= sendmail($userfullname,$useremail,$subject,$html,emailhtml($html));
			

		}

	$message = "Waiting customer to finish transaction order_id: " . $order_id . " using " . $type;
} 
else if ($transaction == 'deny') 
{
	$message = "Payment using " . $type . " for transaction order_id: " . $order_id . " is deny.";
}
else if ($transaction == 'cancel') 
{

	$message = "Payment using " . $type . " for transaction order_id: " . $order_id . " is canceled.";
}


echo $message;

$input = file_get_contents('php://input');
$data = date('Y-m-d H:i:s')." | $message\r\n";
$file = "logs/backlog-pg.txt";
$open = fopen($file, "a+"); 
fwrite($open, "$data"); 
fclose($open);

exit();

?>