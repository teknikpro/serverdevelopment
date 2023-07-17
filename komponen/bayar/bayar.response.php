<?php
$ip = $_SERVER['REMOTE_ADDR'];
$uri = $_SERVER['REQUEST_URI'];
$ref = $_SERVER['HTTP_REFERER'];
$merchantcode = $_REQUEST["MerchantCode"];
$paymentid = $_REQUEST["PaymentId"];
$refno = $_REQUEST["RefNo"];
$amount = $_REQUEST["Amount"];
$ecurrency = $_REQUEST["Currency"];
$remark = $_REQUEST["Remark"];
$transid = $_REQUEST["TransId"];
$authcode = $_REQUEST["AuthCode"];
$estatus = $_REQUEST["Status"];
$errdesc = $_REQUEST["ErrDesc"];
$signature = $_REQUEST["Signature"];

//var dari klikbca
$transactionNo = $_REQUEST["transactionNo"];echo $transactionNo;die();

$input = $HTTP_RAW_POST_DATA;
$data = date('Y-m-d H:i:s')." | $ip | $uri | $estatus | $errdesc | $authcode | $ref\r\n";
$file = "logs/log.txt";
$open = fopen($file, "a+"); 
fwrite($open, "$data"); 
fclose($open);



$response = array("merchantcode"=>$_REQUEST["MerchantCode"],
					"paymentid"=>$_REQUEST["PaymentId"],
					"refno"=>$_REQUEST["RefNo"],
					"amount"=>$_REQUEST["Amount"],
					"ecurrency"=>$_REQUEST["Currency"],
					"remark"=>$_REQUEST["Remark"],
					"transid"=>$_REQUEST["TransId"],
					"authcode"=>$_REQUEST["AuthCode"],
					"estatus"=>$_REQUEST["Status"],
					"errdesc"=>$_REQUEST["ErrDesc"],
					"signature"=>$_REQUEST["Signature"]);
$response1 = serialize($response);



if($estatus=="1")
{
	
	if(empty($transid))
	{	
		$kode1	= base64_encode($refno);
		$pesan = "<br />Sorry your reservation is invalid or some mistakes with your payment. Please try to
				  reserve our rooms again. No Transaction number. $errdesc<br /><br />
				  <button type=\"button\"  class=\"btnsub_back\" onclick=\"window.location='$fulldomain/bayar/pembayaran/$kode1'\">Methode Pembayaran</button>";
		$tpl->assign("salah",$pesan);
	}
	else
	{
	
		//Proses Pembayaran Berhasil.
		$kode1	= $refno;
		
		// kueri tabel konfirmasi
		$sql1	= "select transaksiid, invoiceid, orderid, totaltagihan, pengiriman, pembayaran, userid, namalengkap, alamatpengiriman, warehouseid, voucherid, totaldiskon, 
				totaltagihanafterdiskon, ongkosid, ongkoskirim, status, tipe,email, tanggaltransaksi, batastransfer from tbl_transaksi where invoiceid='$kode1'";
		$hsl1 = sql($sql1);
		$row1 = sql_fetch_data($hsl1);
		
		$transaksiid		= $row1['transaksiid'];
		$invoiceid			= $row1['invoiceid'];
		$orderid			= $row1['orderid'];
		$totaltagihan		= $row1['totaltagihan'];
		$pengiriman			= $row1['pengiriman'];
		$pembayaran			= $row1['pembayaran'];
		$userid				= $row1['userid'];
		$namalengkap		= $row1['namalengkap'];
		$alamatpengiriman	= $row1['alamatpengiriman'];
		$warehouseid		= $row1['warehouseid'];
		$voucherid			= $row1['voucherid'];
		$totaldiskon		= $row1['totaldiskon'];
		$totaltagihanafterdiskon	= $row1['totaltagihanafterdiskon'];
		$ongkosid			= $row1['ongkosid'];
		$ongkoskirim		= $row1['ongkoskirim'];
		$status				= $row1['status'];
		$tipe				= $row1['tipe'];
		$email				= $row1['email'];
		$tanggaltransaksi	= tanggal($row1['tanggaltransaksi']);
		$batastransfer		= tanggal($row1['batastransfer']);
		
		if($totaltagihanafterdiskon==0)
			$totaltagihanakhir = $totaltagihan+$ongkoskirim;
		else
			$totaltagihanakhir = $totaltagihanafterdiskon+$ongkoskirim;
			
		$total_bayar 		= $totaltagihanakhir;
				
		$services 		= sql_get_var("select service from tbl_ongkos where id='$ongkosid'");
	
		// ambil data toko
		$tk	= sql_get_var_row("select nama,alamat,telp,gsm from tbl_about_post limit 1");
		$namatk		= $tk['nama'];
		$alamattk	= $tk['alamat'];
		$telptk		= $tk['telp'];
		$gsmtk		= $tk['gsm'];
				
		$sql = "select * from tbl_transaksi_detail where transaksiid='$transaksiid' order by transaksidetailid desc ";
		$hsl = sql($sql);
		$dt_keranjang = array();
		$i = 1;
		$a = 1;
		while ($row = sql_fetch_data($hsl))
		{
			$tarnsaksidetailid 	= $row['tarnsaksidetailid'];
			$produkpostid	= $row['produkpostid'];
			$qty 			= $row['jumlah'];
			$berat			= $row['berat'];
			$harga	 		= "$matauang. ". number_format($row['harga'],"0",".",".");
			$matauang		= $row['matauang'];
			$total			= "$matauang. ". number_format($row['totalharga'],"0",".",".");
			
			$sql2	= "select title,kodeproduk from tbl_product_post where produkpostid='$produkpostid'";
			$query2	= sql($sql2);
			$row2	= sql_fetch_data($query2);
			$nama			= $row2["title"];
			$kodeproduk		= $row2['kodeproduk'];
			
			$perintahst 	= "insert into tbl_product_stokonhold (`invoiceid`, `produkpostid`, `jumlah`, `tipe`) values ('$invoiceid', '$produkpostid', '$qty', '$tipeid')";
			$hasilst 		= sql($perintahst);
	
			$i %= 2;
			$i++;
			$a++;
			
			$berattotal	.= $berattotal+$row['berat'];
			
			$dt_keranjang[$tarnsaksidetailid] = array("id"=>$tarnsaksidetailid,"nama"=>$nama,"kodeproduk"=>$kodeproduk,"berat"=>$berat,"totalharga"=>$total,"qty"=>$qty);
		}
		
	
		$ongkoskirim2	= "$matauang. ". number_format($ongkoskirim,0,".",".");
			
		//tampilkan diskon voucher
		$qryv = sql(" SELECT voucherid, totaldiskon, totaltagihanafterdiskon, totaltagihan from tbl_transaksi where transaksiid='$transaksiid'");
		$rowv = sql_fetch_data($qryv);
		$voucherid = $rowv['voucherid'];
		$totaldiskon = $rowv['totaldiskon'];
		$totaltagihanafterdiskon = $rowv['totaltagihanafterdiskon'];
		$totaltagihan = $rowv['totaltagihan'];
		
		$kodevoucher = sql_get_var("select kodevoucher from tbl_voucher_kode where voucherid='$voucherid'");
		
		if($totaltagihanafterdiskon==0)
			$totaltagihanakhir = $totaltagihan+$ongkoskirim;
		else
			$totaltagihanakhir = $totaltagihanafterdiskon+$ongkoskirim;
		
		$totaltagihan1 = number_format($totaltagihan,0,",",".");
		$totaltagihan2 = "$matauang. $totaltagihan1";
		
		$totaltagihanakhir1 = number_format($totaltagihanakhir,0,",",".");
		$totaltagihanakhir2 = "$matauang. $totaltagihanakhir1";
		
		$totaldiskon1 = number_format($totaldiskon,0,",",".");
		$totaldiskon2 = "$matauang. $totaldiskon1";

		$merchantkey = "O2dyuJ1HKd";
		$merchantcode = "ID00230";
		$currency	= "IDR";
		$paymentid = $pembayaran;
		
		$total_harga2	= $totaltagihanakhir."00";
		
		$sign = "$merchantkey$merchantcode$paymentid$kode1$total_harga2$currency"."1";
		$hash = iPay88_signature($sign);
		
		if($signature!=$hash)
		{
			$kodeinvoice = base64_encode($invoiceid);
			$pesan = "<br />Maaf untuk saat ini transaksi gagal dilakukan atau ada beberapa kesalahan dalam pembayaran. Silahkan ulangi pesanan Anda Kembali. 
					  Signature is not match. $errdesc<br /><br />
					  <button type=\"button\"  class=\"btnsub_back\" onclick=\"window.location='$fulldomain/bayar/pembayaran/$kodeinvoice'\">Kembali</button>";
			$tpl->assign("salah",$pesan);
			$tpl->assign("style","alert-error");
		}
		else
		{
		
			$sql = "update tbl_transaksi set status='3',ipaystatus='1',ipaytransid='$transid',ipayresponse='$response1' where invoiceid='$kode1'";
			$hasil	= sql($sql);
		
			if($hasil)
			{
			
			$kodeinvoice = base64_encode($invoiceid);
		
			$urlconfirm = "$fulldomain/bayar/confirm/$kodeinvoice";
			
			$statusorder = "PAID";
			$warnafont = "#59B210";
			
			if($pengiriman != "Pickup Point")
			{
				$namaagen	= sql_get_var("select nama from tbl_agen where agenid='$pengiriman'");
				$services	= sql_get_var("select service from tbl_ongkos where agenid='$pengiriman' and id = '$ongkosid'");
			}
			
			if($pembayaran == 1)
				$pembayaran = "Kartu Kredit";
			elseif($pembayaran == 9)	
				$pembayaran = "Virtual Account";
		
			include("invoice.pdf.php");
			
			$pengirim 			= "$owner <$support_email>";
			$webmaster_email 	= "Support <$support_email>"; 
			$userEmail			= "$email"; 
			$userFullName		= "$userfullname"; 
			$headers 			= "From : $owner";
			$subject			= "$title, Invoice Belanja #$invoiceid";
			
			$sendmail			= sendmail($userFullName,$userEmail,$subject,$html,$html,1);
						
			//kirim email ke admin
			$to 		= "$support_email";
			$from 		= "$support_email";
			$subject 	= "Informasi Pemesanan No Invoice $invoiceid - $title";
			$message 	= $message;
			$headers 	= "From : $owner";
			
		
			$sendmail	= sendmail($title,$to,$subject,$html,$html,1);
			
			$salah = "Terima kasih banyak, transaksi telah berhasil dilakukan. Semua informasi yang berkaitan dengan transaksi ini telah Kami dikirim ke email Anda,
								silahkan buka email Anda. Nomor invoice belanja Anda adalah <strong>$invoiceid</strong> / nomor invoice akan digunakan ketika transfer / konfirmasi pembayaran.";
			$tpl->assign("salah",$salah);
			$tpl->assign("style","alert-success");
			
			//kirim sms
			
			setlog($userfullname,"system","Melakukan Transaksi Pembelian.","$fulldomain/panel/index.php?tab=5&tabsub=9&kanal=transaksi&aksi=detail&invoiceid=$invoiceid","buy");
			session_start();
			$_SESSION['orderid']		= "";
			$_SESSION['last']			= "";
			$_SESSION['jenis']			= "";
			}
			else
			{
				$salah	= "Maaf untuk saat ini transaksi gagal dilakukan. <br><input type=\"button\" value=\"Kembali\"  class=\"btnsub_back\  onclick=\"window.location='$fulldomain'\" />";
				$tpl->assign("salah",$salah);
				$tpl->assign("style","alert-error");
				
				session_start();
				$_SESSION['orderid']		= "";
				$_SESSION['last']			= "";
				$_SESSION['jenis']			= "";
			}
		}
	
	}

}
else
{
	$kode1	= base64_encode($refno);
	$pesan = "<br />Maaf untuk saat ini transaksi gagal dilakukan atau ada beberapa kesalahan dalam pembayaran. Silahkan ulangi pesanan Anda Kembali. $errdesc<br /><br />
			  <button type=\"button\"  class=\"btnsub_back\" onclick=\"window.location='$fulldomain/bayar/pembayaran/$kode1'\">Methode Pembayaran</button>";
	$tpl->assign("salah",$pesan);
	$tpl->assign("style","alert-error");
}

//Cek Signature
/*$merchantkey = "7ynRcOfyxL";
$merchantcode = "ID00074";
$currency	= "IDR";
$paymentid = 1;
$sign = "$merchantkey$merchantcode$kode1$total_harga2$currency$estatus";
$hash = iPay88_signature($sign);

if($signature!==$hash)
{
	$pesan = "<br />Sorry your reservation is invalid or some mistakes with your payment. Please try to
			  reserve our rooms again. Signature is not match. $errdesc<br /><br />
			  <button type=\"button\"  class=\"btnsub_back\" onclick=\"window.location='$fulldomain'\">Back To Home</button>";
	$tpl->assign("pesan",$pesan);
}*/
