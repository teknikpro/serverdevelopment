<?php
$subaksi = $var[4];
$userid = $var[5];
$chatid = $var[6];
$tipe = $var[7];

if($tipe=="gopay"){  $metode = array("gopay"); }
else if($tipe=="transfer"){ $metode =  array("bank_transfer","mandiri_ecash"); }
else{ $metode =  array("gopay","bank_transfer","mandiri_ecash"); }


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
	
	$voucherid = $_POST['voucherid'];
	$jml = $_POST['jml'];
	
	if($_SESSION['userid'])
	{
		$userid = $_SESSION['userid'];
	}
	
	$voucher = sql_get_var_row("select voucherid,nama,ringkas,alias,published,id,term,harga,startdate,enddate,startusedate,endusedate,qty,views,gambar from tbl_world_voucher where voucherid='$voucherid'");

	if(isset($_POST['bayar']))
	{
		$chatid = $_POST['chatid'];
		
		$harga = $voucher['harga'];
		$wid = $voucher['id'];
		$harga1 = rupiah($harga);
		$nama = "Pembelian $jml $voucher[nama]";
		
		$harga2 = $harga*$jml;
		
		$user = sql_get_var_row("select userfullname,useremail,userphonegsm from tbl_member where userid='$userid'");
		$userfullname = $user['userfullname'];
		$useremail = $user['useremail'];
		$userphonegsm = $user['userphonegsm'];

		$orderid = "EVDFS".date("U");
		
		$datetransfer = date("Y-m-d H:i:s", strtotime("+$jedatransfer hour"));
		$new = newid("transaksiid","tbl_transaksi_world");
		
		$wuserid = sql_get_var("select userid from tbl_world where id='$wid'");
		
		$query	= "insert into tbl_transaksi_world(`transaksiid`,worlduserid,voucherid,`invoiceid`,`status`,userid,jml,create_date, create_userid,update_userid,adminfee,totaltagihan,totalinvoice,batastransfer) 
						values ('$new','$wuserid','$voucherid','$orderid','0','$userid','$jml','$date','0','0','4000','$harga2','$harga2','$datetransfer')";
		$hasil = sql($query);
		
		if($hsl)
		{
		
			$subject			= "$title, Tagihan eVoucher #$orderid";
			$html = "Yth. $userfullname.<br><br>Ini merupakan tagihan proses pembelian evoucher anda. Berikut ini adalah
			informasi tagihan anda:<br><br>
			<strong>Nomor Tagihan:</strong> $orderid<br>
			<strong>Voucher:</strong> $nama<br>
			<strong>Total Tagihan:</strong> $harga2<br><br>
			Silahkan lanjutkan pembayaran anda. Lakukan pembayaran sebelum $datetransfer. Terima kasih";
						
			$sendmail			= sendmail($userfullname,$useremail,$subject,$html,emailhtml($html));
			
			$item_details = array();
			$item_details[] = array('id' => "1",'price' => $harga2,'quantity' => 1,'name' => "$nama");
			//$item_details[] = array('id' => "2",'price' => 4000,'quantity' => 1,'name' => "Biaya Administrasi");
			
		
			require_once($lokasiweb."librari/veritrans/Veritrans.php");
				
			Veritrans_Config::$serverKey = "$serverkey";
			Veritrans_Config::$isProduction = $isProduction;
			Veritrans_Config::$is3ds = true;
		
			// Required
			$transaction_details = array(
			  'order_id' => $orderid,
			  'gross_amount' => $harga2, 
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
			
			if($_GET['fromapps'])
			{	
			// Fill transaction details
			$transaction = array(
				"vtweb" => array (
				  "enabled_payments" => $metode,
				  "finish_redirect_url" => "$fulldomain/cart/evoucher/finish/sukses/?fromapps=1",
				  "unfinish_redirect_url" => "$fulldomain/cart/evoucher/finish/gagal/?fromapps=1",
				  "error_redirect_url" => "$fulldomain/cart/evoucher/finish/error/?fromapps=1"
				),
				'transaction_details' => $transaction_details,
				'customer_details' => $customer_details,
				'item_details' => $item_details,
				'custom_expiry' => $custom_expiry,
				);
			}
			else
			{
				$transaction = array(
				"vtweb" => array (
				 "enabled_payments" => $metode,
				  "finish_redirect_url" => "$fulldomain/cart/evoucher/finish/sukses/",
				  "unfinish_redirect_url" => "$fulldomain/cart/evoucher/finish/gagal/",
				  "error_redirect_url" => "$fulldomain/cart/evoucher/finish/error/"
				),
				'transaction_details' => $transaction_details,
				'customer_details' => $customer_details,
				'item_details' => $item_details,
				'custom_expiry' => $custom_expiry,
				);
			}
			
			
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
	
	
	
	if($paid<1)
	{
		
		if($_GET['fromapps'])
		{
			$voucherid = $var[6];
			$jml = 1;
			$userid = $var[5];
		}
		else
		{
		
			$voucherid = $_POST['id'];
			$jml = $_POST['jml'];
			
			if($_SESSION['userid'])
			{
				$userid = $_SESSION['userid'];
			}
		}
		$voucher = sql_get_var_row("select voucherid,nama,ringkas,alias,published,id,term,harga,startdate,enddate,startusedate,endusedate,qty,views,gambar from tbl_world_voucher where voucherid='$voucherid'");
		
		
		$harga =$voucher['harga'];
		$nama = $voucher['nama'];
		$total = $harga*$jml;
		
		$tpl->assign("harga",$voucher['harga']);
		$tpl->assign("hargarp",rupiah($harga));
		$tpl->assign("jml",$jml);
		$tpl->assign("total",$total);
		$tpl->assign("totalrp",rupiah($total));
		$tpl->assign("userid",$userid);
		$tpl->assign("nama","Pembelian $voucher[nama]");
		$tpl->assign("voucherid",$voucherid);
		
	}
	else
	{
		$message = "Mohon maaf saat ini voucher yang anda pilih telah dibayar atau menunggu proses pembayaran. Transaksi anda tidak akan kami lanjutkan. Terima kasih";
		$tpl->assign("message",$message);
	}
	
	
}


$tpl->display("payment-evoucher.html");
exit();
?>