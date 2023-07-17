<?php
$input = file_get_contents('php://input');
$data = date('Y-m-d H:i:s')." | Transaksi Paket $order_id\r\n";
$file = "logs/backlog-paket.txt";
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
			
		$sql = "update tbl_transaksi_paket set status='3',paymentinfo='$input' where invoiceid='$orderid'";
		$hasil	= sql($sql);
		
		
	
		if($hasil)
		{
			$sql = sql("select transaksiid,totalinvoice,userid,paketid from tbl_transaksi_paket where invoiceid='$orderid'");
			
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
			
			$paket = sql_get_var_row("select nama,harga,berlaku,voucher60,voucher60podcast from tbl_paket_voucher where paketid='$paketid'");
			
			
			$harga = $paket['harga'];
			$harga1 = rupiah($paket['harga']);
			$nama = $paket['nama'];
			$voucher60 = $paket['voucher60'];
			$berlaku = $paket['berlaku'];
			$voucher60podcast = $paket['voucher60podcast'];
			
			
			//create voucher
			for($i=0;$i<$voucher60;$i++)
			{
				//CreateVoucher
				$kode = date("Ym").generateCode(6);
				$kode = strtoupper($kode);
				$voucherid = newid("voucherid","tbl_chat_voucher");
				
				$nama = "Voucher Chat 60 Menit";
				
				$expire = date("Y-m-d H:i:s", strtotime("+1 months"));
				$texpire = tanggal($expire);
				
				$query	= "insert into tbl_chat_voucher(`voucherid`,kode,nama,userid,`transid`,`status`,create_date, create_userid,update_userid,menit,expiredate) 
							values ('$voucherid','$kode','$nama','$userid','$orderid','0','$date','$userid','$userid','60','$expire')";
				$hasil = sql($query);
			}
			
			//create voucher
			for($i=0;$i<$voucher60podcast;$i++)
			{
				//CreateVoucher
				$kode = date("Ym").generateCode(6);
				$kode = strtoupper($kode);
				$voucherid = newid("voucherid","tbl_chat_voucher");
				
				$nama = "Voucher Chat 60 Menit Podcast";
				
				$expire = date("Y-m-d H:i:s", strtotime("+1 months"));
				$texpire = tanggal($expire);
				
				$query	= "insert into tbl_chat_voucher(`voucherid`,kode,nama,userid,`transid`,`status`,create_date, create_userid,update_userid,menit,expiredate,podcast) 
							values ('$voucherid','$kode','$nama','$userid','$orderid','0','$date','$userid','$userid','60','$expire','1')";
				$hasil = sql($query);
			}
			
			
			//Berlaku
			$expire = date("Y-m-d H:i:s", strtotime("+$berlaku months"));
			$texpire = tanggal($expire);
			
				
			$user = sql_get_var_row("select userfullname,useremail,userphonegsm from tbl_member where userid='$userid'");
			$userfullname = $user['userfullname'];
			$useremail = $user['useremail'];
			$userphonegsm = $user['userphonegsm'];
			
			$subject			= "Pembelian Paket Voucher Berhasil";
			$html = "Yth. $userfullname.<br><br>Terima kasih telah melakukan Paket Voucher Berhasil di $title.<br><br>
			Anda berhak mendapatkan $voucher60 Voucher Konsultasi 60 menit dan $voucher60podcast voucher
			konsultasi podcast selama 60 menit.
			<br><br>Voucher anda akan berlaku selama <strong>$berlaku bulan</strong> atau sampai
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
			
		$sql = "update tbl_transaksi_paket set status='3',paymentinfo='$input' where invoiceid='$orderid'";
		$hasil	= sql($sql);
	
		if($hasil)
		{
			$sql = sql("select transaksiid,totalinvoice,userid,paketid from tbl_transaksi_paket where invoiceid='$orderid'");
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
			
			$paket = sql_get_var_row("select nama,harga,berlaku,voucher60,voucher60podcast from tbl_paket_voucher where paketid='$paketid'");
			$harga = $paket['harga'];
			$harga1 = rupiah($paket['harga']);
			$nama = $paket['nama'];
			$voucher60 = $paket['voucher60'];
			$berlaku = $paket['berlaku'];
			$diskon10konsulfs = $paket['diskon10konsulfs'];
			$voucher60podcast = $paket['voucher60podcast'];
			
			//create voucher
			for($i=0;$i<$voucher60;$i++)
			{
				//CreateVoucher
				$kode = date("Ym").generateCode(6);
				$kode = strtoupper($kode);
				$voucherid = newid("voucherid","tbl_chat_voucher");
				
				$nama = "Voucher Chat 60 Menit";
				
				$expire = date("Y-m-d H:i:s", strtotime("+1 months"));
				$texpire = tanggal($expire);
				
				$query	= "insert into tbl_chat_voucher(`voucherid`,kode,nama,userid,`transid`,`status`,create_date, create_userid,update_userid,menit,expiredate) 
							values ('$voucherid','$kode','$nama','$userid','$orderid','0','$date','$userid','$userid','60','$expire')";
				$hasil = sql($query);
			}
			
			//create voucher
			for($i=0;$i<$voucher60podcast;$i++)
			{
				//CreateVoucher
				$kode = date("Ym").generateCode(6);
				$kode = strtoupper($kode);
				$voucherid = newid("voucherid","tbl_chat_voucher");
				
				$nama = "Voucher Chat 60 Menit Podcast";
				
				$expire = date("Y-m-d H:i:s", strtotime("+1 months"));
				$texpire = tanggal($expire);
				
				$query	= "insert into tbl_chat_voucher(`voucherid`,kode,nama,userid,`transid`,`status`,create_date, create_userid,update_userid,menit,expiredate,podcast) 
							values ('$voucherid','$kode','$nama','$userid','$orderid','0','$date','$userid','$userid','60','$expire','1')";
				$hasil = sql($query);
			}
			
			
			//Berlaku
			$expire = date("Y-m-d H:i:s", strtotime("+$berlaku months"));
			$texpire = tanggal($expire);
			
			

			
			$user = sql_get_var_row("select userfullname,useremail,userphonegsm from tbl_member where userid='$userid'");
			$userfullname = $user['userfullname'];
			$useremail = $user['useremail'];
			$userphonegsm = $user['userphonegsm'];
			$subject			= "Pembelian Paket Voucher Berhasil";
			$html = "Yth. $userfullname.<br><br>Terima kasih telah melakukan Paket Voucher Berhasil di $title.<br><br>
			Anda berhak mendapatkan $voucher60 Voucher Konsultasi 60 menit dan $voucher60podcast voucher
			konsultasi podcast selama 60 menit.
			<br><br>Voucher anda akan berlaku selama <strong>$berlaku bulan</strong> atau sampai
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
			
		$sql = "update tbl_transaksi_paket set status='2',paymentinfo='$input' where invoiceid='$orderid'";
		$hasil	= sql($sql);
	
		if($hasil)
		{
			$sql = sql("select transaksiid,totalinvoice,userid,paketid from tbl_transaksi_paket where invoiceid='$orderid'");
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
			
			$paket = sql_get_var_row("select nama,harga,berlaku,voucher60,voucher60podcast from tbl_paket_voucher where paketid='$paketid'");
			$harga = $paket['harga'];
			$harga1 = rupiah($paket['harga']);
			$nama = $paket['nama'];
			$voucher60 = $paket['voucher60'];
			$berlaku = $paket['berlaku'];
			$diskon10konsulfs = $paket['diskon10konsulfs'];
			$voucher60podcast = $paket['voucher60podcast'];
			
					
			$user = sql_get_var_row("select userfullname,useremail,userphonegsm from tbl_member where userid='$userid'");
			$userfullname = $user['userfullname'];
			$useremail = $user['useremail'];
			$userphonegsm = $user['userphonegsm'];
			
			$subject			= "Transaksi Pending: $orderid";
			$html = "Yth. $userfullname.<br><br>Ini merupakan tagihan proses pembelian paket anda. Berikut ini adalah
			informasi tagihan anda:<br><br>
			<strong>Nomor Tagihan:</strong> $orderid<br>
			<strong>Paket Voucher:</strong> $nama<br>
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