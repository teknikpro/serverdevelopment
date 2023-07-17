<?php
$subaksi = $var[4];
$userid = $var[5];
$chatid = $var[6];
//https://www.serverdevelopment2020.my.id/cart/voucher/list/1478/482/gopay/?fromapps=1%27
$tpl->assign("subaksi",$subaksi);
$tpl->assign("userid",$userid);

if($subaksi=="finish") {
	$pesan = $var[5];
	$ordernumber = $_GET['order_id'];
	$statuscode = $_GET['status_code'];
	$transstatus = $_GET['transaction_status'];
	
	$chat = sql_get_var_row("select chat_id,to_userid,paid from tbl_chat where chat_id='$chatid'");
	$touserid = $chat['to_userid'];

	if($pesan=="sukses") {
		if($statuscode=="200") {
			if($transstatus=="capture") {
				$message = "Terima kasih sudah bertransaksi di $title, Transaksi anda dengan nomor transaksi <strong>$ordernumber</strong> telah berhasil dilakukan. <br> Kami akan segera memproses transaksi anda lebih lanjut.";
				$sukses = 1;
				$insert  = "update tbl_chat set paid='1' where chat_id='$chatid'";
				$sinsert = sql($insert);
				$insert2  = "update tbl_member set busy='1' where userid='$touserid'";
				$sinsert2 = sql($insert2);
			} elseif($transstatus=="settlement") {
				$message = "Terima kasih sudah bertransaksi di $title, Transaksi anda dengan nomor transaksi <strong>$ordernumber</strong> telah berhasil dilakukan. <br> Kami akan segera memproses transaksi anda dan memproses lebih lanjut<br>.";
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
		} else {
			$message = "Mohon maaf transaksi anda dengan nomor transaksi <strong>$ordernumber</strong> gagal dilakukan,<br> 
			silahkan untuk mencoba kembali melakukan transaksi";
			$sukses = 0;
		}
	} else if($pesan=="error") {
		if($statuscode=="200") {
			if($transstatus=="capture"){
				$message = "Terima kasih sudah bertransaksi di $title, Transaksi anda dengan nomor transaksi <strong>$ordernumber</strong> telah berhasil dilakukan. <br> Kami akan segera memproses transaksi anda lebih lanjut.";
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
		} else {
			$message = "Mohon maaf transaksi anda dengan nomor transaksi <strong>$ordernumber</strong> gagal dilakukan,<br> 
			silahkan untuk mencoba kembali melakukan transaksi";
			$sukses = 0;
		}
	} else {
		$message = "Mohon maaf transaksi anda dengan nomor transaksi <strong>$ordernumber</strong> gagal dilakukan,<br> 
			silahkan untuk mencoba kembali melakukan transaksi";
			$sukses = 0;
	}
	
	$tpl->assign("message",$message);
	$tpl->assign("pesan",$pesan);
	$tpl->assign("sukses",$sukses);
} else {
	$chat = sql_get_var_row("select chat_id,to_userid,paid from tbl_chat where chat_id='$chatid'");
	$konsultanid = $chat['to_userid'];
	$paid = $chat['paid'];
	if(isset($_POST['chatid'])) {
		$chatid = $_POST['chatid'];
		$userid = $_POST['userid'];
		
		$chat = sql_get_var_row("select chat_id,to_userid,paid,harga,quantity,total from tbl_chat where chat_id='$chatid'");
		$konsultanid = $chat['to_userid'];
		
		$konsultan = sql_get_var_row("select userfullname,hargakonsultasi from tbl_member where userid='$konsultanid'");
		
		$harga = $chat['harga'];
		$harga1 = rupiah($harga);
		$nama = "Voucher Konsultasi $konsultan[userfullname]";
		
		$harga2 = $harga+4000;
		
		$user = sql_get_var_row("select userfullname,useremail,userphonegsm from tbl_member where userid='$userid'");
		$userfullname = $user['userfullname'];
		$useremail = $user['useremail'];
		$userphonegsm = $user['userphonegsm'];

		$orderid = "KDFS".date("Ymd").$chatid;
		
		$datetransfer = date("Y-m-d H:i:s", strtotime("+$jedatransfer hour"));
		$new = newid("transaksiid","tbl_transaksi_voucher");
			
		$query	= "insert into tbl_transaksi_voucher(`chat_id`,`invoiceid`,`status`,userid,create_date, create_userid,update_userid,adminfee,totaltagihan,totalinvoice,batastransfer) 
						values ($chatid','$orderid','0','$userid','$date','0','0','4000','$harga2','$harga','$datetransfer')";
		$hasil = sql($query);
		
		if($hsl) {
			$subject= "$title, Tagihan Pembelian Voucher #$orderid";
			$html = "Yth. $userfullname.<br><br>Ini merupakan tagihan proses pembelian voucher anda. Berikut ini adalah
			informasi tagihan anda:<br><br>
			<strong>Nomor Tagihan:</strong> $orderid<br>
			<strong>Voucher:</strong> $nama<br>
			<strong>Nomor Tagihan:</strong> $harga1<br><br>
			Silahkan lanjutkan pembayaran anda. Lakukan pembayaran sebelum $datetransfer. Terima kasih";
						
			$sendmail			= sendmail($userfullname,$useremail,$subject,$html,emailhtml($html));
			
			$item_details = array();
			$item_details[] = array('id' => "1",'price' => $harga,'quantity' => 1,'name' => "$nama");
			$item_details[] = array('id' => "2",'price' => 4000,'quantity' => 1,'name' => "Biaya Administrasi");
			
		
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
					
			// Fill transaction details
			$transaction = array(
				"vtweb" => array (
				  //"enabled_payments" => array("$metode"),
				  "finish_redirect_url" => "$fulldomain/bayar/voucher/finish/sukses/",
				  "unfinish_redirect_url" => "$fulldomain/bayar/voucher/finish/gagal/",
				  "error_redirect_url" => "$fulldomain/bayar/voucher/finish/error/"
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
	if($paid<1) {
		$konsultan = sql_get_var_row("select userfullname,hargakonsultasi from tbl_member where userid='$konsultanid'");
		$harga = rupiah($konsultan['hargakonsultasi']);
		$nama = $konsultan['nama'];
		$tpl->assign("harga",$harga);
		$tpl->assign("nama","Voucher Konsultasi dengan $nama");
		$tpl->assign("chatid",$chatid);
	} else {
		$message = "Mohon maaf saat ini voucher yang anda pilih telah dibayar atau menunggu proses pembayaran. Transaksi anda tidak akan kami lanjutkan. Terima kasih";
		$tpl->assign("message",$message);
	}
}


$tpl->display("payment-voucher.html");
exit();
