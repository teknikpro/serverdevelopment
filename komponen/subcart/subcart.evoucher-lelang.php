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

	$useraddress		= $_POST['useraddress'];
	$kotaid		 		= $_POST['kotaid'];
	$userpostcode		= $_POST['userpostcode'];
	$kurir				= $_POST['kurir'];
	$pesan				= $_POST['pesan'];
	$namapengirim		= $_POST['namapengirim'];
	$telppengirim		= $_POST['telppengirim'];
	$resellerid			= $_POST['resellerid'];

	// Ambil nama Kota
	$kota 	= sql_get_var("SELECT namakota FROM tbl_kota WHERE kotaid='$kotaid'");

	// alamat pengiriman
	$alamat_kirim = "$useraddress<br>$kota<br>$userpostcode<br>Telp. $userphonegsm";

	// Tanggal Hari ini
	$tanggal		= date("Y-m-d H:i:s");

	// Jeda Transfer
	$datetransfer = date("Y-m-d H:i:s", strtotime("+$jedatransfer hour"));

	// Ambil id transaksi
	$transaksiid 	= sql_get_var("SELECT transaksiid FROM tbl_transaksi WHERE orderid='$_SESSION[orderid]'");

	// Data untuk inserta ke tabel_transaksi_world
	$new = newid("transaksiid", "tbl_transaksi_world");

	$userid = $_SESSION['userid'];

	$harga2 = $totalongkoskirim + $hargabarang;

	if($orderid) {

		// Edit tabel
		$perintah 	= "update tbl_transaksi set invoiceid='$orderid', namalengkap='$userfullname', email='$useremail', alamatpengiriman='$alamat_kirim', propid='$propinsiid',kotaid='$kotaid',kecid='$kecid',
						 ongkoskirim='$totalongkoskirim', pengiriman='$kurir',agen='$kurir', pembayaran='Midtrands', tanggaltransaksi='$tanggal', batastransfer='$datetransfer', pesan='$pesan', dropship='Bukan Dropship', namapengirim='$namapengirim', telppengirim='$telppengirim', status='1', userid='$_SESSION[userid]', resellerid = '$listresellerid'  
						 where transaksiid='$transaksiid' and orderid='$orderid'";
		$hasil 		= sql($perintah);

		// Insert ke tabel transaksi_world
		$query	= "insert into tbl_transaksi_world(`transaksiid`,worlduserid,voucherid,`invoiceid`,`status`,userid,jml,create_date, create_userid,update_userid,adminfee,totaltagihan,totalinvoice,batastransfer,lelang) 
						values ('$new','1','$voucherid','$orderid','0','$userid','$jumlahbeli','$tanggal','0','0','4000','$harga2','$harga2','$datetransfer','1')";
		$hasil = sql($query);

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