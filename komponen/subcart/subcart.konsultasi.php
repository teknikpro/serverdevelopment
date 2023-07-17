<?php
$subaksi = $var[4];
$userid = $var[5];
$chatid = $var[6];
//https://www.serverdevelopment2020.my.id/cart/voucher/list/1478/482/gopay/?fromapps=1%27
//https://www.serverdevelopment2020.my.id/cart/konsultasi/finish/sukses/?order_id=KDFS20200717482&status_code=201&transaction_status=pending
$tpl->assign("subaksi",$subaksi);
$tpl->assign("userid",$userid);

if($subaksi=="finish") {
	$pesan = $var[5];
	$ordernumber = $_GET['order_id'];
	$statuscode = $_GET['status_code'];
	$transstatus = $_GET['transaction_status'];
	
	$chat = sql_get_var_row("select chat_id,to_userid,paid from tbl_chat where chat_id='$chatid'");
	$touserid = $chat['to_userid'];

	// Cari transaksi
	$transaksi = sql_get_var_row("select userid,voucherid from tbl_transaksi_world where invoiceid='$ordernumber' ");
	$userid = $transaksi['userid'];
	$voucherid = $transaksi['voucherid'];

	// cari data user
	$datauser = sql_get_var_row("select userfullname,useremail from tbl_member where userid='$userid' ");
	$userfullname = $datauser['userfullname'];
	$useremail = $datauser['useremail'];

	// cari produk
	$productvo = sql_get_var_row("select nama,harga from tbl_world_voucher where voucherid='$voucherid' ");
	$namaproduk = $productvo['nama'];
	$hargaproduk = $productvo['harga'] + 4000;


	if($pesan=="sukses") {
		if($statuscode=="200") {
			if($transstatus=="capture") {
				$message = "Terima kasih sudah bertransaksi di $title, Transaksi anda dengan nomor transaksi <strong>$ordernumber</strong> telah berhasil dilakukan. <br> Kami akan segera memproses transaksi anda lebih lanjut.";

				// Kirim email
				$subject			= "$title, Pemberitahuan pembayaran #$ordernumber";
				$html = "Yth. $userfullname.<br><br>Terimakasih telah melakukan pembayaran untuk:<br><br>
				<strong>Nomor Tagihan:</strong> $ordernumber<br>
				<strong>Pembayaran: Untuk</strong> $namaproduk<br>
				<strong>Total Tagihan:</strong> $hargaproduk<br><br>
				";
				$sendmail			= sendmail($userfullname, $useremail, $subject, $html, emailhtml($html));

				$sukses = 1;
				$insert  = "update tbl_chat set paid='1' where chat_id='$chatid'";
				$sinsert = sql($insert);
				$insert2  = "update tbl_member set busy='1' where userid='$touserid'";
				$sinsert2 = sql($insert2);
			} elseif($transstatus=="settlement") {
				$message = "Terima kasih sudah bertransaksi di $title, Transaksi anda dengan nomor transaksi <strong>$ordernumber</strong> telah berhasil dilakukan. <br> Kami akan segera memproses transaksi anda dan memproses lebih lanjut<br>.";

				// Kirim email
				$subject			= "$title, Pemberitahuan pembayaran #$ordernumber";
				$html = "Yth. $userfullname.<br><br>Terimakasih telah melakukan pembayaran untuk:<br><br>
				<strong>Nomor Tagihan:</strong> $ordernumber<br>
				<strong>Pembayaran: Untuk</strong> $namaproduk<br>
				<strong>Total Tagihan:</strong> $hargaproduk<br><br>
				";
				$sendmail			= sendmail($userfullname, $useremail, $subject, $html, emailhtml($html));

				$sukses = 1;
				$insert  = "update tbl_chat set paid='1' where chat_id='$chatid'";
				$sinsert = sql($insert);
				$insert2  = "update tbl_member set busy='1' where userid='$touserid'";
				$sinsert2 = sql($insert2);
			} else {
				$message = "Transaksi anda dengan nomor <strong>$ordernumber</strong> belum selesai atau tertunda, <br>kemungkinan anda belum melakukan pembayaran melalui channel pembayaran yang anda
				pilih.<br> Kami akan memproses transaksi anda jika sudah anda melakukan pembayaran terlebih dahulu.";
				$sukses = 1;
			}
			
		} else if ($statuscode=="202") {
			$message = "Transaksi anda dengan nomor <strong>$ordernumber</strong>  gagal dilakukan,<br>Transaksi sudah dilakukan tetapi terindikasi fraud. <br>Silahkan untuk mencoba bertransaksi lagi";

				$subject			= "$title, Transaksi Gagal #$ordernumber";
				$html = "Yth. $userfullname.<br><br>Transaksi dengan :<br><br>
				<strong>Nomor Tagihan:</strong> $ordernumber<br>
				<strong>Pembayaran: Untuk</strong> $namaproduk<br>
				<strong>Total Tagihan:</strong> $hargaproduk<br><br>
				Telah gagal, Silahkan untuk mencoba bertransaksi lagi
				";
				$sendmail			= sendmail($userfullname, $useremail, $subject, $html, emailhtml($html));

			$sukses = 0;
		} else {
			$message = "Mohon maaf transaksi anda dengan nomor transaksi <strong>$ordernumber</strong> belum selesai atau pending,<br> 
			silahkan lakukan pembayaran terlebih dahulu melalui chanel pembayaran yang telah anda pilih
			<br> kami akan membatalkan transaksi anda jika dalam waktu $jedatransfer jam tidak melakukan pembayaran";
			$sukses = 0;
		}
		unset($_SESSION['ordernumber']);
	} else if($pesan=="gagal") {
		if($statuscode=="200") {
			if($transstatus=="capture") {
				$message = "Terima kasih sudah bertransaksi di $title, Transaksi anda dengan nomor transaksi <strong>$ordernumber</strong> telah berhasil dilakukan. <br> Kami akan segera memproses transaksi anda lebih lanjut.";

				// Kirim email
				$subject			= "$title, Pemberitahuan pembayaran #$ordernumber";
				$html = "Yth. $userfullname.<br><br>Terimakasih telah melakukan pembayaran untuk:<br><br>
				<strong>Nomor Tagihan:</strong> $ordernumber<br>
				<strong>Pembayaran: Untuk</strong> $namaproduk<br>
				<strong>Total Tagihan:</strong> $hargaproduk<br><br>
				";
				$sendmail			= sendmail($userfullname, $useremail, $subject, $html, emailhtml($html));

				$sukses = 1;
				$insert  = "update tbl_chat set paid='1' where chat_id='$chatid'";
				$sinsert = sql($insert);
				$insert2  = "update tbl_member set busy='1' where userid='$touserid'";
				$sinsert2 = sql($insert2);
			} else {
				$message = "Transaksi anda dengan nomor <strong>$ordernumber</strong> belum selesai atau tertunda, <br>kemungkinan anda belum melakukan pembayaran melalui channel pembayaran yang anda
				pilih.<br> Kami akan memproses transaksi anda jika sudah anda melakukan pembayaran terlebih dahulu.";
				$sukses = 1;
			}
		} else if ($statuscode=="202") {
			$message = "Transaksi anda dengan nomor <strong>$ordernumber</strong>  gagal dilakukan,<br>Transaksi sudah dilakukan tetapi terindikasi fraud. <br>Silahkan untuk mencoba bertransaksi lagi";
			$sukses = 0;
				$subject			= "$title, Transaksi Gagal #$ordernumber";
				$html = "Yth. $userfullname.<br><br>Transaksi dengan :<br><br>
				<strong>Nomor Tagihan:</strong> $ordernumber<br>
				<strong>Pembayaran: Untuk</strong> $namaproduk<br>
				<strong>Total Tagihan:</strong> $hargaproduk<br><br>
				Telah gagal, Silahkan untuk mencoba bertransaksi lagi
				";
				$sendmail			= sendmail($userfullname, $useremail, $subject, $html, emailhtml($html));
		} else {
			$message = "Mohon maaf transaksi anda dengan nomor transaksi <strong>$ordernumber</strong> gagal dilakukan,<br> 
			silahkan untuk mencoba kembali melakukan transaksi";
			$sukses = 0;
				$subject			= "$title, Transaksi Gagal #$ordernumber";
				$html = "Yth. $userfullname.<br><br>Transaksi dengan :<br><br>
				<strong>Nomor Tagihan:</strong> $ordernumber<br>
				<strong>Pembayaran: Untuk</strong> $namaproduk<br>
				<strong>Total Tagihan:</strong> $hargaproduk<br><br>
				Telah gagal, Silahkan untuk mencoba bertransaksi lagi
				";
				$sendmail			= sendmail($userfullname, $useremail, $subject, $html, emailhtml($html));
		}
	} else if($pesan=="error") {
		if($statuscode=="200") {
			if($transstatus=="capture"){
				$message = "Terima kasih sudah bertransaksi di $title, Transaksi anda dengan nomor transaksi <strong>$ordernumber</strong> telah berhasil dilakukan. <br> Kami akan segera memproses transaksi anda lebih lanjut.";
			
				// Kirim email
				$subject			= "$title, Pemberitahuan pembayaran #$ordernumber";
				$html = "Yth. $userfullname.<br><br>Terimakasih telah melakukan pembayaran untuk:<br><br>
				<strong>Nomor Tagihan:</strong> $ordernumber<br>
				<strong>Pembayaran: Untuk</strong> $namaproduk<br>
				<strong>Total Tagihan:</strong> $hargaproduk<br><br>
				";
				$sendmail			= sendmail($userfullname, $useremail, $subject, $html, emailhtml($html));
				
				$sukses = 1;
				$insert  = "update tbl_chat set paid='1' where chat_id='$chatid'";
				$sinsert = sql($insert);
				$insert2  = "update tbl_member set busy='1' where userid='$touserid'";
				$sinsert2 = sql($insert2);
			} else {
				$message = "Transaksi anda dengan nomor <strong>$ordernumber</strong> belum selesai atau tertunda, <br>kemungkinan anda belum melakukan pembayaran melalui channel pembayaran yang anda
				pilih.<br> Kami akan memproses transaksi anda jika sudah anda melakukan pembayaran terlebih dahulu.";
				$sukses = 1;
			}
		} else if ($statuscode=="202") {
			$message = "Transaksi anda dengan nomor <strong>$ordernumber</strong>  gagal dilakukan,<br>Transaksi sudah dilakukan tetapi terindikasi fraud. <br>Silahkan untuk mencoba bertransaksi lagi";
			$sukses = 0;
				$subject			= "$title, Transaksi Gagal #$ordernumber";
				$html = "Yth. $userfullname.<br><br>Transaksi dengan :<br><br>
				<strong>Nomor Tagihan:</strong> $ordernumber<br>
				<strong>Pembayaran: Untuk</strong> $namaproduk<br>
				<strong>Total Tagihan:</strong> $hargaproduk<br><br>
				Telah gagal, Silahkan untuk mencoba bertransaksi lagi
				";
				$sendmail			= sendmail($userfullname, $useremail, $subject, $html, emailhtml($html));
		} else {
			$message = "Mohon maaf transaksi anda dengan nomor transaksi <strong>$ordernumber</strong> gagal dilakukan,<br> 
			silahkan untuk mencoba kembali melakukan transaksi";
			$sukses = 0;
				$subject			= "$title, Transaksi Gagal #$ordernumber";
				$html = "Yth. $userfullname.<br><br>Transaksi dengan :<br><br>
				<strong>Nomor Tagihan:</strong> $ordernumber<br>
				<strong>Pembayaran: Untuk</strong> $namaproduk<br>
				<strong>Total Tagihan:</strong> $hargaproduk<br><br>
				Telah gagal, Silahkan untuk mencoba bertransaksi lagi
				";
				$sendmail			= sendmail($userfullname, $useremail, $subject, $html, emailhtml($html));
		}
	} else {
		$message = "Mohon maaf transaksi anda dengan nomor transaksi <strong>$ordernumber</strong> gagal dilakukan,<br> 
			silahkan untuk mencoba kembali melakukan transaksi";
			$sukses = 0;
				$subject			= "$title, Transaksi Gagal #$ordernumber";
				$html = "Yth. $userfullname.<br><br>Transaksi dengan :<br><br>
				<strong>Nomor Tagihan:</strong> $ordernumber<br>
				<strong>Pembayaran: Untuk</strong> $namaproduk<br>
				<strong>Total Tagihan:</strong> $hargaproduk<br><br>
				Telah gagal, Silahkan untuk mencoba bertransaksi lagi
				";
				$sendmail			= sendmail($userfullname, $useremail, $subject, $html, emailhtml($html));
	}
	$tpl->assign("chat",$chatid);
	$tpl->assign("user",$userid);
	$tpl->assign("to",$touserid);
	$tpl->assign("message",$message);
	$tpl->assign("pesan",$pesan);
	$tpl->assign("sukses",$sukses);
} else {
	$chat = sql_get_var_row("select chat_id,to_userid,paid,harga,hargax,diskon,quantity,total,invoice from tbl_chat where chat_id='$chatid'");
	$konsultanid = $chat['to_userid'];
	$paid = $chat['paid'];

	$konsultan = sql_get_var_row("select userfullname,hargakonsultasi from tbl_member where userid='$konsultanid'");
	$harga = $chat['hargax'];
	$harga1 = rupiah($harga);
	$nama = "Biaya Konsultasi";
	$harga2 = $harga+4000;
	$bea = rupiah('4000');
	$bayar = rupiah($harga2);
	//$qty = $chat['paid'] + 1;

	$user = sql_get_var_row("select userfullname,useremail,userphonegsm from tbl_member where userid='$userid'");
	$userfullname = $user['userfullname'];
	$useremail = $user['useremail'];
	$userphonegsm = $user['userphonegsm'];

	$orderid = "KDFS".date("Ymd").$chatid;

	$datetransfer = date("Y-m-d H:i:s", strtotime("+$jedatransfer hour"));

	if(isset($_POST['chatid'])) {
		$chatid = $_POST['chatid'];
		$userid = $_POST['userid'];

		$new = newid("transaksiid","tbl_transaksi_voucher");
			
		$query	= "insert into tbl_transaksi_voucher(`chat_id`,`invoiceid`,`status`,userid,create_date, create_userid,update_userid,adminfee,totaltagihan,totalinvoice,batastransfer) 
						values (`$chatid','$orderid','0','$userid','$date','$userid','$userid','4000','$harga2','$harga','$datetransfer')";
		$hasil = sql($query);
		
		if($hsl) {
			/* $subject= "$title, Tagihan Pembelian Voucher #$orderid";
			$html = "Yth. $userfullname.<br><br>, berikut ini merupakan tagihan proses transaksi konsultasi Anda : <br><br>
			<strong>Nomor Tagihan :</strong> $orderid<br>
			<strong>Nama Transaksi :</strong> $nama dengan $konsultan[userfullname]<br>
			<strong>Harga :</strong> $harga1<br><br>
			<strong>Biaya Admin :</strong> $bea<br><br>
			<strong>Total Pembayaran :</strong> $bayar<br><br>
			Silahkan lanjutkan pembayaran anda. Lakukan pembayaran sebelum ".tanggal($datetransfer).". Terima kasih";
						
			$sendmail			= sendmail($userfullname,$useremail,$subject,$html,emailhtml($html)); */

			if(empty($chat['invoice'])) {
				$insertx  = "update tbl_chat set invoice='$orderid' where chat_id='$chatid'";
				$sinsertx = sql($insertx);
			}
			
			require_once($lokasiweb."librari/veritrans2/Veritrans.php");
			//Set Your server key
			Veritrans_Config::$serverKey = "$serverkey";
			Veritrans_Config::$isProduction = $isProduction;
			
			// Uncomment to enable sanitization
			// Veritrans_Config::$isSanitized = true;
			
			// Uncomment to enable 3D-Secure
			Veritrans_Config::$is3ds = true;
			
			// Required
			$transaction_details = array(
			  'order_id' => $orderid,
			  'gross_amount' => $harga2, 
		  	);
			
			$item_details = array();
			$item_details[] = array('id' => "1",'price' => $harga,'quantity' => 1,'name' => "$nama");
			$item_details[] = array('id' => "2",'price' => 4000,'quantity' => 1,'name' => "Biaya Administrasi");
			
						 // Optional
			$customer_details = array(
				'first_name'    => "$userfullname",
				'last_name'     => "$userfullname",
				'email'         => "$useremail",
				'phone'         => "$userphonegsm"
				);
			
			/* $custom_expiry = array(
			'expiry_duration'    => "60",
			'unit'     => "minute"
			);*/
			
			// Optional
			$customer_details = array(
			    'first_name'    => "$userfullname",
				'last_name'     => "$userfullname",
				'email'         => "$useremail",
				'phone'         => "$userphonegsm"
			    //'billing_address'  => $billing_address,
			    //'shipping_address' => $shipping_address
			    );
				
			$time = time();
	        $custom_expiry = array(
	            'start_time' => date("Y-m-d H:i:s O",$time),
	            'unit' => 'minute',
	            'duration' => 15
	        );
		
			// Fill SNAP API parameter
			$params = array(
			    'transaction_details' => $transaction_details,
			    'customer_details' => $customer_details,
			    'item_details' => $item_details,
				'expiry' => $custom_expiry
			    );
			
			try {
			  // Get Snap Payment Page URL
			  $paymentUrl = Veritrans_Snap::createTransaction($params)->redirect_url;
			  
			  // Redirect to Snap Payment Page
			  header('Location: ' . $paymentUrl);
			}
			catch (Exception $e) {
			  echo $e->getMessage();
			  /* if(strpos ($e->getMessage(), "Access denied due to unauthorized")){
				  echo "<code>";
				  echo "<h4>Please set real server key from sandbox</h4>";
				  echo "In file: " . __FILE__;
				  echo "<br>";
				  echo "<br>";
				  echo htmlspecialchars('Veritrans_Config::$serverKey = \'<your server key>\';');
				  die();
			  }*/
			}	
		}
		exit();
	}
	if($paid<1) {
		$tpl->assign("userfullname",$userfullname);
		$tpl->assign("harga1",$harga1);
		$tpl->assign("orderid",$orderid);
		$tpl->assign("bea",$bea);
		$tpl->assign("bayar",$bayar);
		$tpl->assign("date",tanggal($datetransfer));
		$tpl->assign("nama",$nama." dengan ".$konsultan['userfullname']);
		$tpl->assign("chatid",$chatid);
	} else {
		$message = "Mohon maaf saat ini voucher yang anda pilih telah dibayar atau menunggu proses pembayaran. Transaksi anda tidak akan kami lanjutkan. Terima kasih";
		$tpl->assign("message",$message);
	}
}


$tpl->display("payment-konsultasi.html");
exit();
