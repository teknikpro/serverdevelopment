<?php
$input = file_get_contents('php://input');
$data = date('Y-m-d H:i:s')." | Transaksi eVoucher $order_id\r\n";
$file = "logs/backlog-evoucher.txt";
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
			
		$sql = "update tbl_transaksi_world set status='3',paymentinfo='$input' where invoiceid='$orderid'";
		$hasil	= sql($sql);
	
		if($hasil)
		{
			$sql = sql("select transaksiid,jml,totalinvoice,userid,voucherid from tbl_transaksi_world where invoiceid='$orderid'");
			$dt = sql_fetch_data($sql);
			
			$transaksiid = $dt['transaksiid'];
			$totalinvoice = $dt['totalinvoice'];
			$userid = $dt['userid'];
			$voucherid = $dt['voucherid'];
			$jml = $dt['jml'];
			
			echo "eVoucher Proses $voucherid<br>";
			
			$voucher = sql_get_var_row("select voucherid,nama,ringkas,alias,published,id,term,harga,startdate,enddate,startusedate,endusedate,qty,views,gambar from tbl_world_voucher where voucherid='$voucherid'");
		
		
			
			$harga =$voucher['harga'];
			$nama = $voucher['nama'];
			$ringkas = $voucher['ringkas'];
			$term = $voucher['term'];
			$wid = $voucher['id'];
			$startusedate = tanggalvoucher($voucher['startusedate']);
			$endusedate = tanggalvoucher($voucher['endusedate']);
			
			$wuserid = sql_get_var("select userid from tbl_world where id='$wid'");
			
			$tgl = "$startusedate - $endusedate";
			
			$kodes = "";
			
			include($lokasiweb."/librari/qrcode/qrlib.php");
			
			$html1 = "";
			
			for($i=0;$i<$jml;$i++)
			{
				
				//CreateVoucher
				$kode = date("Ym").generateCode(6);
				$kode = strtoupper($kode);
				$vouchercodeid = newid("vouchercodeid","tbl_world_evoucher");
				
				$namav = "eVoucher $nama";
				
				$expire = date("Y-m-d H:i:s", strtotime("+1 months"));
				$texpire = tanggal($expire);
				
				$query	= "insert into tbl_world_evoucher(`vouchercodeid`,`voucherid`,kode,nama,userid,`transid`,`status`,create_date, create_userid,update_userid,menit,expiredate,mitrauserid) 
							values ('$vouchercodeid','$voucherid','$kode','$namav','$userid','$orderid','0','$date','$userid','$userid','60','$expire','$wuserid')";
				$hasil = sql($query);
				$kodes .= "<br>$kode<br>";
				
				QRcode::png("$kode","$lokasiweb/gambar/barcode/evoucher-$vouchercodeid.png","H",4,4);
				
				$html1 .=' <table width="100%" border="0" style="border:none; background:#fff; border:1px solid #ccc;">
						  <tr>
							<td width="116"  valign="top"  style="padding:20px;"><center><p><img src="'.$fulldomain.'/gambar/barcode/evoucher-'.$vouchercodeid.'.png" /></p>
							  <p>'.$kode.'<br />
							  </p></center></td>
							<td width="518" valign="top"  style="padding:20px;"><p><strong style="font-size:20px">'.$namav.'</strong></p>
							  <p><br /><br>
								'.$ringkas.'<br>
								<strong>Ketentuan</strong><br>'.$term.'
							  </p>
							  <p><br /><br>
								<em>Berlaku untuk: '.$tgl.'</em> </p></td>
						  </tr>
						  </table>';
				echo "$kode<br>";
				
				unset($kode);
				
			}
				
			$user = sql_get_var_row("select userfullname,useremail,userphonegsm from tbl_member where userid='$userid'");
			$userfullname = $user['userfullname'];
			$useremail = $user['useremail'];
			$userphonegsm = $user['userphonegsm'];
			
			echo "$userfullname<br>";
			
			$subject			= "Pembelian eVoucher Berhasil";
			$html = "Yth. $userfullname.<br><br>Terima kasih telah melakukan Pembelian eVoucher $title.
			Berikut ini adalah Voucher yang dapat anda gunakan:  <br><br>$html1
			 <br><br>Gunakan voucher sesuai dengan masa berlaku voucher di mitra-mitra Kami.Terima kasih";
					
			$sendmail	= sendmail($userfullname,$useremail,$subject,$html,emailhtml($html));
			
			//kirim sms ke admin
		/*	$sqlh     = "select gsm from tbl_static where alias='kontak' limit 1";
			$queryh   = sql($sqlh);
			$rowh     = sql_fetch_data($queryh);
			$gsmadmin = $rowh['gsm'];*/
		
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
			
		$sql = "update tbl_transaksi_world set status='3',paymentinfo='$input' where invoiceid='$orderid'";
		$hasil	= sql($sql);
	
		if($hasil)
		{
			$sql = sql("select transaksiid,jml,totalinvoice,userid,voucherid from tbl_transaksi_world where invoiceid='$orderid'");
			$dt = sql_fetch_data($sql);
			
			$transaksiid = $dt['transaksiid'];
			$totalinvoice = $dt['totalinvoice'];
			$userid = $dt['userid'];
			$voucherid = $dt['voucherid'];
			$jml = $dt['jml'];
			
			echo "eVoucher Proses $voucherid<br>";
			
			$voucher = sql_get_var_row("select voucherid,nama,ringkas,alias,published,id,term,harga,startdate,enddate,startusedate,endusedate,qty,views,gambar from tbl_world_voucher where voucherid='$voucherid'");
		
		
			$harga =$voucher['harga'];
			$nama = $voucher['nama'];
			$ringkas = $voucher['ringkas'];
			$term = $voucher['term'];
			$wid = $voucher['id'];
			$startusedate = tanggalvoucher($voucher['startusedate']);
			$endusedate = tanggalvoucher($voucher['endusedate']);
			
			$wuserid = sql_get_var("select userid from tbl_world where id='$wid'");
			
			$tgl = "$startusedate - $endusedate";
			
			$kodes = "";
			
			include($lokasiweb."/librari/qrcode/qrlib.php");
			
			$html1 = "";
			
			for($i=0;$i<$jml;$i++)
			{
				
				//CreateVoucher
				$kode = date("Ym").generateCode(6);
				$kode = strtoupper($kode);
				$vouchercodeid = newid("vouchercodeid","tbl_world_evoucher");
				
				$namav = "eVoucher $nama";
				
				$expire = date("Y-m-d H:i:s", strtotime("+1 months"));
				$texpire = tanggal($expire);
				
				$query	= "insert into tbl_world_evoucher(`vouchercodeid`,`voucherid`,kode,nama,userid,`transid`,`status`,create_date, create_userid,update_userid,menit,expiredate,mitrauserid) 
							values ('$vouchercodeid','$voucherid','$kode','$namav','$userid','$orderid','0','$date','$userid','$userid','60','$expire','$wuserid')";
				$hasil = sql($query);
				$kodes .= "<br>$kode<br>";
				
				QRcode::png("$kode","$lokasiweb/gambar/barcode/evoucher-$vouchercodeid.png","H",4,4);
				
				$html1 .=' <table width="100%" border="0" style="border:none; background:#fff; border:1px solid #ccc;">
						  <tr>
							<td width="116"  valign="top"  style="padding:20px;"><center><p><img src="'.$fulldomain.'/gambar/barcode/evoucher-'.$vouchercodeid.'.png" /></p>
							  <p>'.$kode.'<br />
							  </p></center></td>
							<td width="518" valign="top"  style="padding:20px;"><p><strong style="font-size:20px">'.$namav.'</strong></p>
							  <p><br /><br>
								'.$ringkas.'<br>
								<strong>Ketentuan</strong><br>'.$term.'
							  </p>
							  <p><br /><br>
								<em>Berlaku untuk: '.$tgl.'</em> </p></td>
						  </tr>
						  </table>';
				echo "$kode<br>";
				
				unset($kode);
				
			}
				
			$user = sql_get_var_row("select userfullname,useremail,userphonegsm from tbl_member where userid='$userid'");
			$userfullname = $user['userfullname'];
			$useremail = $user['useremail'];
			$userphonegsm = $user['userphonegsm'];
			
			echo "$userfullname<br>";
			
			$subject			= "Pembelian eVoucher Berhasil";
			$html = "Yth. $userfullname.<br><br>Terima kasih telah melakukan Pembelian eVoucher $title.
			Berikut ini adalah Voucher yang dapat anda gunakan:  <br><br>$html1
			 <br><br>Gunakan voucher sesuai dengan masa berlaku voucher di mitra-mitra Kami.Terima kasih";
					
			$sendmail	= sendmail($userfullname,$useremail,$subject,$html,emailhtml($html));
			
			//kirim sms ke admin
		/*	$sqlh     = "select gsm from tbl_static where alias='kontak' limit 1";
			$queryh   = sql($sqlh);
			$rowh     = sql_fetch_data($queryh);
			$gsmadmin = $rowh['gsm'];*/
		
			//$kirimsms	= kirimSMS($gsmadmin,"Info: Informasi Upgade $orderid - $title, silahkan login ke $fulldomain/panel utk melihat detail pemesanan. Terimakasih");
			
		}
		
		$message = "Payment using " . $type . " for transaction order_id: " . $order_id . " is succsess.";
    }
} 
else if($transaction == 'pending')
{
  
  		$orderid = $order_id;
			
		$sql = "update tbl_transaksi_world set status='2',paymentinfo='$input' where invoiceid='$orderid'";
		$hasil	= sql($sql);
	
		if($hasil)
		{
			$sql = sql("select transaksiid,totalinvoice,userid,chatid from tbl_transaksi_world where invoiceid='$orderid'");
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