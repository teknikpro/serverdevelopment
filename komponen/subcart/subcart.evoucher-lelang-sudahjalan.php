<?php
if(isset($_POST['belanjakan'])){

	$orderid = $_POST['orderid'];
	$hargabarang = $_POST['hargabarang'];
	$namaproduk  = $_POST['namaproduk'];
	$jumlahbeli	 = $_POST['jumlahbeli'];
	$totalongkoskirim	= $_POST['totalongkoskirim'];
	$userfullname		= $_POST['userfullname'];
	$useremail			= $_POST['email'];
	$userphonegsm		= $_POST['userphonegsm'];

	if($orderid) {

		// Edit tabel
		

		// Ambil data untuk ke midtrand
		$harga = $hargabarang;
		$harga1 = rupiah($harga);
		$nama = "Pembelian $namaproduk";
		$harga22 = $harga * $jumlahbeli;
		$harga2 = $harga22 + 4000 + $totalongkoskirim;
		$orderid = $orderid;

		// Kirim Email
		$subject			= "Pembayaran tagihan dengan invoice #$orderid";
			$html = "Yth. $userfullname.<br><br>Ini merupakan tagihan proses pembelian evoucher anda. Berikut ini adalah
			informasi tagihan anda:<br><br>
			<strong>Nomor Tagihan:</strong> $orderid<br>
			<strong>Voucher:</strong> $nama<br>
			<strong>Total Tagihan:</strong> $harga2<br><br>
			Silahkan lanjutkan pembayaran anda. Lakukan pembayaran sebelum $datetransfer. Terima kasih";

			$sendmail			= sendmail($userfullname, $useremail, $subject, $html, emailsimpo($html));

			$item_details = array();
			$item_details[] = array('id' => "1", 'price' => $harga22, 'quantity' => 1, 'name' => "$nama");
			$item_details[] = array('id' => "2", 'price' => $totalongkoskirim, 'quantity' => 1, 'name' => "Ongkos Kirim");
			$item_details[] = array('id' => "3", 'price' => 4000, 'quantity' => 1, 'name' => "Fee Payment Gateway");


			require_once($lokasiweb . "librari/veritrans/Veritrans.php");

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
				// 'last_name'     => "$userfullname",
				'email'         => "$useremail",
				'phone'         => "$userphonegsm"
			);

			$custom_expiry = array(
				'expiry_duration'    => "1440",
				'unit'     => "minute"
			);

			if ($_GET['fromapps']) {
				// Fill transaction details
				$transaction = array(
					"vtweb" => array(
						//"enabled_payments" => array("$metode"),
						"finish_redirect_url" => "$fulldomain/cart/evoucher-simpo/finish/sukses/?fromapps=1",
						"unfinish_redirect_url" => "$fulldomain/cart/evoucher-simpo/finish/gagal/?fromapps=1",
						"error_redirect_url" => "$fulldomain/cart/evoucher-simpo/finish/error/?fromapps=1"
					),
					'transaction_details' => $transaction_details,
					'customer_details' => $customer_details,
					'item_details' => $item_details,
					'custom_expiry' => $custom_expiry,
				);
			} else {
				$transaction = array(
					"vtweb" => array(
						//"enabled_payments" => array("$metode"),
						"finish_redirect_url" => "$fulldomain/cart/evoucher-simpo/finish/sukses/",
						"unfinish_redirect_url" => "$fulldomain/cart/evoucher-simpo/finish/gagal/",
						"error_redirect_url" => "$fulldomain/cart/evoucher-simpo/finish/error/"
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
			} catch (Exception $e) {
				echo $e->getMessage();
				if (strpos($e->getMessage(), "Access denied due to unauthorized")) {
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
?>