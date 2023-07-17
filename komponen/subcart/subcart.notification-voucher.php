<?php
$input = file_get_contents('php://input');
$data = date('Y-m-d H:i:s')." | Transaksi Voucher $order_id\r\n";
$file = "logs/backlog-voucher.txt";
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
			
		$sql = "update tbl_transaksi_voucher set status='3',paymentinfo='$input' where invoiceid='$orderid'";
		$hasil	= sql($sql);
	
		if($hasil)
		{
			$sql = sql("select transaksiid,totalinvoice,userid,chat_id from tbl_transaksi_voucher where invoiceid='$orderid'");
			$dt = sql_fetch_data($sql);
			
			$transaksiid = $dt['transaksiid'];
			$totalinvoice = $dt['totalinvoice'];
			$userid = $dt['userid'];
			$chatid = $dt['chat_id'];
			
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
			
			$user = sql_get_var_row("select userfullname,useremail,userphonegsm from tbl_member where userid='$userid'");
			$userfullname = $user['userfullname'];
			$useremail = $user['useremail'];
			$userphonegsm = $user['userphonegsm'];
			
			$subject			= "Pembelian Voucher Berhasil";
			$html = "Yth. $userfullname.<br><br>Terima kasih telah melakukan Pembelian Voucher $title.
			Voucher anda akan langsung digunakan untuk Chating dan akan expire pada $texpire. Terima kasih";
						
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
			$sql = sql("select transaksiid,totalinvoice,userid,chat_id from tbl_transaksi_voucher where invoiceid='$orderid'");
			
			$dt = sql_fetch_data($sql);
			
			$transaksiid = $dt['transaksiid'];
			$totalinvoice = $dt['totalinvoice'];
			$userid = $dt['userid'];
			$chatid = $dt['chat_id'];
			
			//CreateVoucher
			$kode = date("Yn").generateCode(6);
			$kode = strtoupper($kode);
			$voucherid = newid("voucherid","tbl_chat_voucher");
			
			$nama = "Voucher Chat 60 Menit";
			
			$expire = date("Y-m-d H:i:s", strtotime("+1 months"));
			$texpire = tanggal($expire);
			
			$query	= "insert into tbl_chat_voucher(`voucherid`,nama,kode,userid,`transid`,`status`,create_date, create_userid,update_userid,menit,expiredate) 
						values ('$voucherid','$nama','$kode','$userid','$orderid','0','$date','$userid','$userid','60','$expire')";
			$hasil = sql($query);
			
			$user = sql_get_var_row("select userfullname,useremail,userphonegsm from tbl_member where userid='$userid'");
			$userfullname = $user['userfullname'];
			$useremail = $user['useremail'];
			$userphonegsm = $user['userphonegsm'];
			
			$subject			= "Pembelian Voucher Berhasil";
			$html = "Yth. $userfullname.<br><br>Terima kasih telah melakukan Pembelian Voucher $title.
			Voucher anda akan langsung digunakan untuk Chating dan akan expire pada $texpire. Terima kasih";
						
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
			
		$sql = "update tbl_transaksi_voucher set status='2',paymentinfo='$input' where invoiceid='$orderid'";
		$hasil	= sql($sql);
	
		if($hasil)
		{
			$sql = sql("select transaksiid,totalinvoice,userid,chatid from tbl_transaksi_voucher where invoiceid='$orderid'");
			$dt = sql_fetch_data($sql);
			
			$transaksiid = $dt['transaksiid'];
			$totalinvoice = $dt['totalinvoice'];
			$userid = $dt['userid'];
			$chatid = $dt['chatid'];
		
			
			// ambil data kontak admin
			$tk       = sql_get_var_row("select nama,alamat,telp,gsm from tbl_static where alias='kontak' limit 1");
			$namatk   = $tk['nama'];
			$alamattk = $tk['alamat'];
			$telptk   = $tk['telp'];
			$gsmtk    = $tk['gsm'];
			sql_free_result($hslm);
			
		
					
			$user = sql_get_var_row("select userfullname,useremail,userphonegsm from tbl_member where userid='$userid'");
			$userfullname = $user['userfullname'];
			$useremail = $user['useremail'];
			$userphonegsm = $user['userphonegsm'];
			
			$subject			= "Transaksi Pending: $orderid";
			$html = "Yth. $userfullname.<br><br>Ini merupakan tagihan proses pembelian voucher. Berikut ini adalah
			informasi tagihan anda:<br><br>
			<strong>Nomor Tagihan:</strong> $orderid<br>
			<strong>Tahihan:</strong>Rp. $totalinvoice<br><br>
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