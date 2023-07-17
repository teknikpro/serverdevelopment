<?php
$subaksi = $var[4];
$userid = $var[5];
$paketid = $var[6];

$tpl->assign("subaksi",$subaksi);
$tpl->assign("userid",$userid);

if($subaksi=="finish")
{
	$pesan = $var[5];
	if($pesan=="sukses")
	{
		$ordernumber = $_GET['order_id'];
		$statuscode = $_GET['status_code'];
		$transstatus = $_GET['transaction_status'];
		
		if($statuscode=="200")
		{
			if($transstatus=="capture")
			{
				$message = "Terima kasih sudah bertransaksi di $title, Transaksi anda dengan nomor transaksi <strong>$ordernumber</strong> telah berhasil dilakukan. <br> Kami akan segera memproses transaksi anda dan memproses lebih lanjut<br>";
				$sukses = 1;
			}
			elseif($transstatus=="settlement")
			{
				$message = "Terima kasih sudah bertransaksi di $title, Transaksi anda dengan nomor transaksi <strong>$ordernumber</strong> telah berhasil dilakukan. <br> Kami akan segera memproses transaksi anda dan memproses lebih lanjut<br>.";
				$sukses = 1;
			}
			else
			{
				$message = "Transaksi anda dengan nomor <strong>$ordernumber</strong> belum selesai atau tertunda, <br>kemungkinan anda belum melakukan pembayaran melalui channel pembayaran yang anda
				pilih.<br> Kami akan memproses transaksi anda jika sudah anda melakukan pembayaran terlebih dahulu.";
				$sukses = 1;
			}
			
		}
		else if ($statuscode=="202")
		{
			$message = "Transaksi anda dengan nomor <strong>$ordernumber</strong>  gagal dilakukan,<br>Transaksi sudah dilakukan tetapi terindikasi fraud. <br>Silahkan untuk mencoba bertransaksi lagi";
			$sukses = 0;
		}
		else
		{
			$message = "Mohon maaf transaksi anda dengan nomor transaksi <strong>$ordernumber</strong> belum selesai atau pending,<br> 
			silahkan lakukan pembayaran terlebih dahulu melalui chanel pembayaran yang telah anda pilih
			<br> kami akan membatalkan transaksi anda jika dalam waktu $jedatransfer jam tidak melakukan pembayaran";
			$sukses = 0;
		}
		unset($_SESSION['ordernumber']);
	}
	else if($pesan=="gagal")
	{
		$ordernumber = $_GET['order_id'];
		$statuscode = $_GET['status_code'];
		$transstatus = $_GET['transaction_status'];
		
		if($statuscode=="200")
		{
			if($transstatus=="capture")
			{
				$message = "Terima kasih sudah bertransaksi di $title, Transaksi anda dengan nomor transaksi <strong>$ordernumber</strong> telah berhasil dilakukan
				. <br> Kami akan segera memproses transaksi anda dan memproses lebih lanjut, kami akan menginformasikan nomor resi pengiriman
				dan informasi lainnya melalui email maupun SMS. <br>";
				$sukses = 1;
			}
			else
			{
			$message = "Transaksi anda dengan nomor <strong>$ordernumber</strong> belum selesai atau tertunda, <br>kemungkinan anda belum melakukan pembayaran melalui channel pembayaran yang anda
				pilih.<br> Kami akan memproses transaksi anda jika sudah anda melakukan pembayaran terlebih dahulu.";
				$sukses = 1;
			}
		}
		else if ($statuscode=="202")
		{
			$message = "Transaksi anda dengan nomor <strong>$ordernumber</strong>  gagal dilakukan,<br>Transaksi sudah dilakukan tetapi terindikasi fraud. <br>Silahkan untuk mencoba bertransaksi lagi";
			$sukses = 0;
		}
		else
		{
			$message = "Mohon maaf transaksi anda dengan nomor transaksi <strong>$ordernumber</strong> gagal dilakukan,<br> 
			silahkan untuk mencoba kembali melakukan transaksi";
			$sukses = 0;
		}
	}
	else if($pesan=="error")
	{
		$ordernumber = $_GET['order_id'];
		$statuscode = $_GET['status_code'];
		$transstatus = $_GET['transaction_status'];
		
		if($statuscode=="200")
		{
			if($transstatus=="capture")
			{
				$message = "Terima kasih sudah bertransaksi di $title, Transaksi anda dengan nomor transaksi <strong>$ordernumber</strong> telah berhasil dilakukan. <br> Kami akan segera memproses transaksi anda dan memproses lebih lanjut<br>";
				$sukses = 1;
			}
			else
			{
			$message = "Transaksi anda dengan nomor <strong>$ordernumber</strong> belum selesai atau tertunda, <br>kemungkinan anda belum melakukan pembayaran melalui channel pembayaran yang anda
				pilih.<br> Kami akan memproses transaksi anda jika sudah anda melakukan pembayaran terlebih dahulu.";
				$sukses = 1;
			}
		}
		else if ($statuscode=="202")
		{
			$message = "Transaksi anda dengan nomor <strong>$ordernumber</strong>  gagal dilakukan,<br>Transaksi sudah dilakukan tetapi terindikasi fraud. <br>Silahkan untuk mencoba bertransaksi lagi";
			$sukses = 0;
		}
		else
		{
			$message = "Mohon maaf transaksi anda dengan nomor transaksi <strong>$ordernumber</strong> gagal dilakukan,<br> 
			silahkan untuk mencoba kembali melakukan transaksi";
			$sukses = 0;
		}
	}
	else
	{
		$message = "Mohon maaf transaksi anda dengan nomor transaksi <strong>$ordernumber</strong> gagal dilakukan,<br> 
			silahkan untuk mencoba kembali melakukan transaksi";
			$sukses = 0;
	}
	
	$tpl->assign("message",$message);
	$tpl->assign("pesan",$pesan);
	$tpl->assign("sukses",$sukses);
}
else
{
	$paket = sql_get_var("select paketid from tbl_member where userid='$userid'");
	
	if(isset($_POST['paketid']))
	{
		$paketid = $_POST['paketid'];
		$userid = $_POST['userid'];
		
		$paket = sql_get_var_row("select nama,harga from tbl_paket_voucher where paketid='$paketid'");
		$harga = $paket['harga'];
		$harga1 = rupiah($paket['harga']);
		$nama = $paket['nama'];
		
		
		$user = sql_get_var_row("select userfullname,useremail,userphonegsm from tbl_member where userid='$userid'");
		$userfullname = $user['userfullname'];
		$useremail = $user['useremail'];
		$userphonegsm = $user['userphonegsm'];

		$orderid = "PVFS".date("U");
		
		$datetransfer = date("Y-m-d H:i:s", strtotime("+$jedatransfer hour"));
		$new = newid("transaksiid","tbl_transaksi_paket");
			
		$query	= "insert into tbl_transaksi_paket(`transaksiid`,paketid,`invoiceid`,`status`,userid,create_date, create_userid,update_userid,totaltagihan,totalinvoice,batastransfer) 
						values ('$new','$paketid','$orderid','0','$userid','$date','0','0','$harga','$harga','$datetransfer')";
		$hasil = sql($query);
		
		if($hsl)
		{
		
			$subject			= "$title, Tagihan Pembelian Paket #$orderid";
			$html = "Yth. $userfullname.<br><br>Ini merupakan tagihan pembelian paket voucher anda. Berikut ini adalah
			informasi tagihan anda:<br><br>
			<strong>Nomor Tagihan:</strong> $orderid<br>
			<strong>Paket Voucher:</strong> $nama<br>
			<strong>Nomor Tagihan:</strong> $harga1<br><br>
			Silahkan lanjutkan pembayaran anda. Lakukan pembayaran sebelum $datetransfer. Terima kasih";
						
			$sendmail			= sendmail($userfullname,$useremail,$subject,$html,emailhtml($html));
			
			$item_details = array();
			$item_details[] = array('id' => "1",'price' => $harga,'quantity' => 1,'name' => "Paket $nama");
			
			require_once($lokasiweb."librari/veritrans/Veritrans.php");
				
			Veritrans_Config::$serverKey = "$serverkey";
			Veritrans_Config::$isProduction = $isProduction;
			Veritrans_Config::$is3ds = true;
		
			// Required
			$transaction_details = array(
			  'order_id' => $orderid,
			  'gross_amount' => $harga, 
			  );
			 // Optional
			$customer_details = array(
				'first_name'    => "$userfullname",
				'last_name'     => "$userfullname",
				'email'         => "$useremail",
				'phone'         => "$userphonegsm"
				);
			
			$custom_expiry = array(
			'expiry_duration'    => "60",
			'unit'     => "minute"
			);
					
			// Fill transaction details
			$transaction = array(
				"vtweb" => array (
				  //"enabled_payments" => array("$metode"),
				  "finish_redirect_url" => "$fulldomain/bayar/paket/finish/sukses/",
				  "unfinish_redirect_url" => "$fulldomain/bayar/paket/finish/gagal/",
				  "error_redirect_url" => "$fulldomain/bayar/paket/finish/error/"
				),
				'transaction_details' => $transaction_details,
				'customer_details' => $customer_details,
				'item_details' => $item_details,
				'custom_expiry' => $custom_expiry,
				);
			
			try {
			  // Redirect to Veritrans VTWeb page
			  header('Location: ' . Veritrans_VtWeb::getRedirectionUrl($transaction));
			  exit();
			}
			catch (Exception $e) {
			  echo $e->getMessage();
			  if(strpos ($e->getMessage(), "Access denied due to unauthorized")){
				  echo "<code>";
				  echo "<h4>Please set real server key from sandbox</h4>";
				  echo "In file: " . __FILE__;
				  echo "<br>";
				  echo "<br>";
				  echo htmlspecialchars('Veritrans_Config::$serverKey = \'<your server key>\';');
				  die();
			}
			
			}
				
		}
			
		exit();
	}
	
	
	
	
		$paket = sql_get_var_row("select nama,harga from tbl_paket_voucher where paketid='$paketid'");
		$harga = rupiah($paket['harga']);
		$nama = $paket['nama'];
		
		$tpl->assign("harga",$harga);
		$tpl->assign("nama",$nama);
		$tpl->assign("paketid",$paketid);
		
	
	
	
}


$tpl->display("payment-paket.html");
exit();
