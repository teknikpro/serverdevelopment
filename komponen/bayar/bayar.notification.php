<?php
require_once($lokasiweb . "librari/veritrans/Veritrans.php");

Veritrans_Config::$isProduction = $isProduction;
Veritrans_Config::$serverKey = "$serverkey";

$notif = new Veritrans_Notification();

$transaction = $notif->transaction_status;
$type = $notif->payment_type;
$order_id = $notif->order_id;
$fraud = $notif->fraud_status;


$ip = $_SERVER['REMOTE_ADDR'];

$input = file_get_contents('php://input');

$data = date('Y-m-d H:i:s') . " | $ip | $transaction | $type | $order_id | $fraud $input\r\n";
$file = "logs/backlog-pg.txt";
$open = fopen($file, "a+");
fwrite($open, "$data");
fclose($open);

if (preg_match("/UDFS/i", $order_id)) {
	include("bayar.notification-upgrade.php");
	exit();
}
if (preg_match("/VKDFS/i", $order_id)) {
	include("bayar.notification-voucher.php");
	exit();
}
if (preg_match("/EVDFS/i", $order_id)) {
	include("bayar.notification-evoucher.php");
	exit();
}
if (preg_match("/PVFS/i", $order_id)) {
	include("bayar.notification-paket.php");
	exit();
}




if ($transaction == 'capture') {
	if ($type == 'credit_card') {
		if ($fraud == 'challenge') {

			// TODO set payment status in merchant's database to 'Challenge by FDS'
			// TODO merchant should decide whether this transaction is authorized or not in MAP
			$message = "Transaction order_id: " . $order_id . " is challenged by FDS";
		} else {

			$orderid = $order_id;

			$sql = "update tbl_transaksi set status='3',paymentinfo='$input' where orderid='$orderid'";
			$hasil	= sql($sql);

			if ($hasil) {
				$sql = sql("select transaksiid,totaltagihanafterdiskon,ongkoskirim,totalinvoice,resellerid,userid,agen,ongkosinfo,pembayaran,batastransfer from tbl_transaksi where orderid='$orderid'");
				$dt = sql_fetch_data($sql);
				$transaksiid = $dt['transaksiid'];
				$totaltagihanafterdiskon = $dt['totaltagihanafterdiskon'];
				$ongkoskirim = $dt['ongkoskirim'];
				$ongkosinfo = $dt['ongkosinfo'];
				$totalinvoice = $dt['totalinvoice'];
				$resellerid = $dt['resellerid'];
				$userid = $dt['userid'];
				$agen = $dt['agen'];
				$pembayaran = $dt['pembayaran'];
				$batastransfer = tanggal($dt['batastransfer']);

				$namaagen = sql_get_var("select nama from tbl_agen where agenid='$agen'");

				$sql = "select * from tbl_transaksi_detail where transaksiid='$transaksiid' order by transaksidetailid desc ";
				$hsl = sql($sql);
				$i = 1;
				$a = 1;
				while ($row = sql_fetch_data($hsl)) {
					$transaksidetailid 	= $row['transaksidetailid'];
					$produkpostid	= $row['produkpostid'];
					$qty 			= $row['jumlah'];
					$berat			= $row['berat'];
					$matauang		= $row['matauang'];
					$harga	 		= "$matauang. " . number_format($row['harga'], "0", ".", ".");
					$total			= "$matauang. " . number_format($row['totalharga'], "0", ".", ".");
					$resellerid			= $row['resellerid'];
					$namatoko = sql_get_var("select userfullname from tbl_member where userid='$resellerid'");

					$sql2	= "select title,kodeproduk from tbl_product_post where produkpostid='$produkpostid'";
					$query2	= sql($sql2);
					$row2	= sql_fetch_data($query2);
					$nama			= $row2["title"];
					$namap			= $row2["title"];
					$kodeproduk		= $row2['kodeproduk'];


					// album
					$gambar_s	= sql_get_var("select gambar_s from tbl_product_image where produkpostid='$produkpostid' order by albumid asc limit 1");

					if (!empty($gambar_s))
						$image_s	= "$fulldomain/gambar/produk/$produkpostid/$gambar_s";
					else
						$image_s	= $fulldomain . $lokasiwebtemplate . "images/no_photo.gif";

					$berattotal	.= $berattotal + $row['berat'];

					$ongkosqry = sql("select ongkoskirim, agen, ongkosinfo from tbl_transaksi_ongkir where transaksiid='$transaksiid' and resellerid='$resellerid'");
					$ongkosrow = sql_fetch_data($ongkosqry);
					$ongkoskirimpertoko = $ongkosrow['ongkoskirim'];
					$agenpertoko = $ongkosrow['agen'];
					$ongkosinfopertoko = $ongkosrow['ongkosinfo'];

					$dt_toko[$resellerid] = array("resellerid" => $resellerid, "namatoko" => $namatoko, "ongkoskirim" => $ongkoskirimpertoko, "namaagen" => $agenpertoko, "ongkosinfo" => $ongkosinfopertoko);

					$dt_keranjang[$resellerid][$transaksidetailid] = array("transaksidetailid" => $transaksidetailid, "nama" => $nama, "kodeproduk" => $kodeproduk, "image_s" => $image_s, "berat" => $berat, "totalharga" => $total, "qty" => $qty, "harga" => $harga, "xx" => $a);

					$i %= 2;
					$i++;
					$a++;
				}


				$ongkoskirim2	= "$matauang. " . number_format($ongkoskirim, 0, ".", ".");

				//tampilkan diskon voucher
				$qryv                    = sql(" SELECT voucherid, totaldiskon, totaltagihanafterdiskon, vouchercodeid, kodevoucher, totaltagihan from tbl_transaksi where transaksiid='$transaksiid'");
				$rowv                    = sql_fetch_data($qryv);
				$voucherid               = $rowv['voucherid'];
				$vouchercodeid           = $rowv['vouchercodeid'];
				$kodevoucher             = $rowv['kodevoucher'];
				$totaldiskon             = $rowv['totaldiskon'];
				$totaltagihanafterdiskon = $rowv['totaltagihanafterdiskon'];
				$totaltagihan            = $rowv['totaltagihan'];

				$namavoucher = sql_get_var("select nama from tbl_voucher where id = '$voucherid'");

				if ($totaltagihanafterdiskon == 0)
					$totaltagihanakhir = $totaltagihan + $ongkoskirim;
				else
					$totaltagihanakhir = $totaltagihanafterdiskon + $ongkoskirim;

				$totaltagihan1 = number_format($totaltagihan, 0, ",", ".");
				$totaltagihan2 = "$matauang. $totaltagihan1";

				$totaltagihanakhir1 = number_format($totaltagihanakhir, 0, ",", ".");
				$totaltagihanakhir2 = "$matauang. $totaltagihanakhir1";

				$totaldiskon1 = number_format($totaldiskon, 0, ",", ".");
				$totaldiskon2 = "$matauang. $totaldiskon1";


				$kodeinvoice = base64_encode($orderid);

				$urlconfirm = "$fulldomain/bayar/confirm/$kodeinvoice";
				$urldownload = "$fulldomain/bayar/print/$kodeinvoice";

				$statusorder = "PAID";

				$warnafont = "#477603";

				// ambil data kontak admin
				$tk       = sql_get_var_row("select nama,alamat,telp,gsm from tbl_static where alias='kontak' limit 1");
				$namatk   = $tk['nama'];
				$alamattk = $tk['alamat'];
				$telptk   = $tk['telp'];
				$gsmtk    = $tk['gsm'];

				//Kirim Email ke Pembeli
				$sqlm = "select username,userfullname,useremail,userphonegsm from tbl_member where userid='$userid'";
				$hslm = sql($sqlm);
				$datam = sql_fetch_data($hslm);

				$userfullname = $datam['userfullname'];
				$useremail = $datam['useremail'];
				$userhandphone = $datam['userphonegsm'];

				sql_free_result($hslm);

				include("invoice.pdf.php");



				$pengirim 			= "$owner <$support_email>";
				$webmaster_email 	= "Support <$support_email>";
				$userEmail			= "$email";
				$userFullName		= "$userfullname";
				$headers 			= "From : $owner";
				$subject			= "$title, Bukti Belanja #$orderid";

				$sendmail			= sendmail($userfullname, $useremail, $subject, $html, $html, 1);

				$contentsms	= "Terima kasih sukses telah melakukan belanja dengan nomor $orderid, kami segera mengirimkan pesanan anda";
				$sendsms = kirimSMS($userphonegsm, $contentsms);


				//kirim email ke admin
				$to 		= "$support_email";
				$from 		= "$support_email";
				$headers 	= "From : $owner";
				$subject2 	= "Informasi Transaksi Lunas $orderid - $title";

				$sendmail	= sendmail($title, $to, $subject2, $html, $html);

				//kirim email ke reseller
				$perintah	= "select userid,userfullname,avatar,posting,follower,following,tema,usergender,useraddress,cityname,useremail,userphonegsm from tbl_member where userid='$resellerid' limit 1";
				$hasil = sql($perintah);
				$profil = sql_fetch_data($hasil);
				sql_free_result($hasil);

				$iduser = $profil['userid'];
				$contactname = $profil['userfullname'];
				$avatar = $profil['avatar'];
				$contactuseremail = $profil['useremail'];
				$contactuserphone = $profil['userphonegsm'];


				$tores    = "$support_email";
				$fromres  = "$support_email";
				$headers  = "From : $owner";
				$subject3 = "Informasi Pemesanan Nomor Order $orderid - $title";

				$sendmail	= sendmail($contactname, $contactuseremail, $subject3, $html, $html, 1);

				$contentsms	= "Ada transaksi lunas via CreditCard dengan nomor invoice $orderid, silahkan dicek ke halaman dashboard anda";
				$sendsms = kirimSMS($contactuserphone, $contentsms);

				//kirim sms ke admin
				$sqlh     = "select gsm from tbl_static where alias='kontak' limit 1";
				$queryh   = sql($sqlh);
				$rowh     = sql_fetch_data($queryh);
				$gsmadmin = $rowh['gsm'];

				$kirimsms	= kirimSMS($gsmadmin, "Info: Informasi Pemesanan Nomor $orderid - $title, silahkan login ke $fulldomain/panel utk melihat detail pemesanan. Terimakasih");
			}
		}
	}
} else if ($transaction == 'settlement') {



	if ($type != '') {
		$orderid = $order_id;

		$sql = "update tbl_transaksi set status='3',paymentinfo='$input' where orderid='$orderid'";
		$hasil	= sql($sql);

		if ($hasil) {
			$sql = sql("select transaksiid,totaltagihanafterdiskon,ongkoskirim,totalinvoice,resellerid,userid,agen,ongkosinfo,pembayaran,batastransfer from tbl_transaksi where orderid='$orderid'");
			$dt = sql_fetch_data($sql);
			$transaksiid = $dt['transaksiid'];
			$totaltagihanafterdiskon = $dt['totaltagihanafterdiskon'];
			$ongkoskirim = $dt['ongkoskirim'];
			$ongkosinfo = $dt['ongkosinfo'];
			$totalinvoice = $dt['totalinvoice'];
			$resellerid = $dt['resellerid'];
			$userid = $dt['userid'];
			$agen = $dt['agen'];
			$pembayaran = $dt['pembayaran'];
			$batastransfer = tanggal($dt['batastransfer']);

			$namaagen = sql_get_var("select nama from tbl_agen where agenid='$agen'");

			$sql = "select * from tbl_transaksi_detail where transaksiid='$transaksiid' order by transaksidetailid desc ";
			$hsl = sql($sql);
			$i = 1;
			$a = 1;
			while ($row = sql_fetch_data($hsl)) {
				$transaksidetailid 	= $row['transaksidetailid'];
				$produkpostid	= $row['produkpostid'];
				$qty 			= $row['jumlah'];
				$berat			= $row['berat'];
				$matauang		= $row['matauang'];
				$harga	 		= "$matauang. " . number_format($row['harga'], "0", ".", ".");
				$total			= "$matauang. " . number_format($row['totalharga'], "0", ".", ".");
				$resellerid			= $row['resellerid'];
				$namatoko = sql_get_var("select userfullname from tbl_member where userid='$resellerid'");

				$sql2	= "select title,kodeproduk from tbl_product_post where produkpostid='$produkpostid'";
				$query2	= sql($sql2);
				$row2	= sql_fetch_data($query2);
				$nama			= $row2["title"];
				$namap			= $row2["title"];
				$kodeproduk		= $row2['kodeproduk'];

				// album
				$gambar_s	= sql_get_var("select gambar_s from tbl_product_image where produkpostid='$produkpostid' order by albumid asc limit 1");

				if (!empty($gambar_s))
					$image_s	= "$fulldomain/gambar/produk/$produkpostid/$gambar_s";
				else
					$image_s	= $fulldomain . $lokasiwebtemplate . "images/no_photo.gif";

				$berattotal	.= $berattotal + $row['berat'];

				$ongkosqry = sql("select ongkoskirim, agen, ongkosinfo from tbl_transaksi_ongkir where transaksiid='$transaksiid' and resellerid='$resellerid'");
				$ongkosrow = sql_fetch_data($ongkosqry);
				$ongkoskirimpertoko = $ongkosrow['ongkoskirim'];
				$agenpertoko = $ongkosrow['agen'];
				$ongkosinfopertoko = $ongkosrow['ongkosinfo'];

				$dt_toko[$resellerid] = array("resellerid" => $resellerid, "namatoko" => $namatoko, "ongkoskirim" => $ongkoskirimpertoko, "namaagen" => $agenpertoko, "ongkosinfo" => $ongkosinfopertoko);

				$dt_keranjang[$resellerid][$transaksidetailid] = array("transaksidetailid" => $transaksidetailid, "nama" => $nama, "kodeproduk" => $kodeproduk, "image_s" => $image_s, "berat" => $berat, "totalharga" => $total, "qty" => $qty, "harga" => $harga, "xx" => $a);

				$i %= 2;
				$i++;
				$a++;
			}


			$ongkoskirim2	= "$matauang. " . number_format($ongkoskirim, 0, ".", ".");

			//tampilkan diskon voucher
			$qryv                    = sql(" SELECT voucherid, totaldiskon, totaltagihanafterdiskon, vouchercodeid, kodevoucher, totaltagihan from tbl_transaksi where transaksiid='$transaksiid'");
			$rowv                    = sql_fetch_data($qryv);
			$voucherid               = $rowv['voucherid'];
			$vouchercodeid           = $rowv['vouchercodeid'];
			$kodevoucher             = $rowv['kodevoucher'];
			$totaldiskon             = $rowv['totaldiskon'];
			$totaltagihanafterdiskon = $rowv['totaltagihanafterdiskon'];
			$totaltagihan            = $rowv['totaltagihan'];

			$namavoucher = sql_get_var("select nama from tbl_voucher where id = '$voucherid'");

			if ($totaltagihanafterdiskon == 0)
				$totaltagihanakhir = $totaltagihan + $ongkoskirim;
			else
				$totaltagihanakhir = $totaltagihanafterdiskon + $ongkoskirim;

			$totaltagihan1 = number_format($totaltagihan, 0, ",", ".");
			$totaltagihan2 = "$matauang. $totaltagihan1";

			$totaltagihanakhir1 = number_format($totaltagihanakhir, 0, ",", ".");
			$totaltagihanakhir2 = "$matauang. $totaltagihanakhir1";

			$totaldiskon1 = number_format($totaldiskon, 0, ",", ".");
			$totaldiskon2 = "$matauang. $totaldiskon1";


			$kodeinvoice = base64_encode($orderid);

			$urlconfirm = "$fulldomain/bayar/confirm/$kodeinvoice";
			$urldownload = "$fulldomain/bayar/print/$kodeinvoice";

			$statusorder = "PAID";

			$warnafont = "#477603";

			// ambil data kontak admin
			$tk       = sql_get_var_row("select nama,alamat,telp,gsm from tbl_static where alias='kontak' limit 1");
			$namatk   = $tk['nama'];
			$alamattk = $tk['alamat'];
			$telptk   = $tk['telp'];
			$gsmtk    = $tk['gsm'];

			//Kirim Email ke Pembeli
			$sqlm = "select username,userfullname,useremail,userphonegsm from tbl_member where userid='$userid'";
			$hslm = sql($sqlm);
			$datam = sql_fetch_data($hslm);

			$userfullname = $datam['userfullname'];
			$useremail = $datam['useremail'];
			$userhandphone = $datam['userphonegsm'];

			sql_free_result($hslm);

			include("invoice.pdf.php");



			$pengirim 			= "$owner <$support_email>";
			$webmaster_email 	= "Support <$support_email>";
			$userEmail			= "$email";
			$userFullName		= "$userfullname";
			$headers 			= "From : $owner";
			$subject			= "$title, Bukti Belanja #$orderid";

			$sendmail			= sendmail($userfullname, $useremail, $subject, $html, $html, 1);

			$contentsms	= "Terima kasih sukses telah melakukan belanja dengan nomor $orderid, kami segera mengirimkan pesanan anda";
			$sendsms = kirimSMS($userphonegsm, $contentsms);


			//kirim email ke admin
			$to 		= "$support_email";
			$from 		= "$support_email";
			$headers 	= "From : $owner";
			$subject2 	= "Informasi Transaksi Lunas $orderid - $title";

			$sendmail	= sendmail($title, $to, $subject2, $html, $html, 1);

			//kirim email ke reseller
			$perintah	= "select userid,userfullname,avatar,posting,follower,following,tema,usergender,useraddress,cityname,useremail,userphonegsm from tbl_member where userid='$resellerid' limit 1";
			$hasil = sql($perintah);
			$profil = sql_fetch_data($hasil);
			sql_free_result($hasil);

			$iduser = $profil['userid'];
			$contactname = $profil['userfullname'];
			$avatar = $profil['avatar'];
			$contactuseremail = $profil['useremail'];
			$contactuserphone = $profil['userphonegsm'];


			$tores    = "$support_email";
			$fromres  = "$support_email";
			$headers  = "From : $owner";
			$subject3 = "Informasi Pemesanan Nomor Order $orderid - $title";

			$sendmail	= sendmail($contactname, $contactuseremail, $subject3, $html, $html);

			$contentsms	= "Ada transaksi lunas via CreditCard dengan nomor invoice $orderid, silahkan dicek ke halaman dashboard anda";
			$sendsms = kirimSMS($contactuserphone, $contentsms);

			//kirim sms ke admin
			$sqlh     = "select gsm from tbl_static where alias='kontak' limit 1";
			$queryh   = sql($sqlh);
			$rowh     = sql_fetch_data($queryh);
			$gsmadmin = $rowh['gsm'];

			$kirimsms	= kirimSMS($gsmadmin, "Info: Informasi Pemesanan Nomor $orderid - $title, silahkan login ke $fulldomain/panel utk melihat detail pemesanan. Terimakasih");

			$perintah2 	= "update tbl_transaksi set settlement = '1',settlementdate='$date' where orderid='$orderid'";
			$hasil 		= sql($perintah2);
		}

		$message = "Payment using " . $type . " for transaction order_id: " . $order_id . " is succsess.";
	}
} else if ($transaction == 'pending') {

	$orderid = $order_id;

	$sql = "update tbl_transaksi set status='1',paymentinfo='$input' where orderid='$orderid'";
	$hasil	= sql($sql);

	if ($hasil) {
		$sql = sql("select transaksiid,totaltagihanafterdiskon,ongkoskirim,totalinvoice,resellerid,userid,agen,ongkosinfo,pembayaran,batastransfer from tbl_transaksi where orderid='$orderid'");
		$dt = sql_fetch_data($sql);
		$transaksiid = $dt['transaksiid'];
		$totaltagihanafterdiskon = $dt['totaltagihanafterdiskon'];
		$ongkoskirim = $dt['ongkoskirim'];
		$ongkosinfo = $dt['ongkosinfo'];
		$totalinvoice = $dt['totalinvoice'];
		$resellerid = $dt['resellerid'];
		$userid = $dt['userid'];
		$agen = $dt['agen'];
		$pembayaran = $dt['pembayaran'];
		$batastransfer = tanggal($dt['batastransfer']);

		$namaagen = sql_get_var("select nama from tbl_agen where agenid='$agen'");

		$sql = "select * from tbl_transaksi_detail where transaksiid='$transaksiid' order by transaksidetailid desc ";
		$hsl = sql($sql);
		$i = 1;
		$a = 1;
		while ($row = sql_fetch_data($hsl)) {
			$transaksidetailid 	= $row['transaksidetailid'];
			$produkpostid	= $row['produkpostid'];
			$qty 			= $row['jumlah'];
			$berat			= $row['berat'];
			$matauang		= $row['matauang'];
			$harga	 		= "$matauang. " . number_format($row['harga'], "0", ".", ".");
			$total			= "$matauang. " . number_format($row['totalharga'], "0", ".", ".");
			$resellerid			= $row['resellerid'];
			$namatoko = sql_get_var("select userfullname from tbl_member where userid='$resellerid'");

			$sql2	= "select title,kodeproduk from tbl_product_post where produkpostid='$produkpostid'";
			$query2	= sql($sql2);
			$row2	= sql_fetch_data($query2);
			$nama			= $row2["title"];
			$namap			= $row2["title"];
			$kodeproduk		= $row2['kodeproduk'];

			// album
			$gambar_s	= sql_get_var("select gambar_s from tbl_product_image where produkpostid='$produkpostid' order by albumid asc limit 1");

			if (!empty($gambar_s))
				$image_s	= "$fulldomain/gambar/produk/$produkpostid/$gambar_s";
			else
				$image_s	= $fulldomain . $lokasiwebtemplate . "images/no_photo.gif";

			$berattotal	.= $berattotal + $row['berat'];

			$ongkosqry = sql("select ongkoskirim, agen, ongkosinfo from tbl_transaksi_ongkir where transaksiid='$transaksiid' and resellerid='$resellerid'");
			$ongkosrow = sql_fetch_data($ongkosqry);
			$ongkoskirimpertoko = $ongkosrow['ongkoskirim'];
			$agenpertoko = $ongkosrow['agen'];
			$ongkosinfopertoko = $ongkosrow['ongkosinfo'];

			$dt_toko[$resellerid] = array("resellerid" => $resellerid, "namatoko" => $namatoko, "ongkoskirim" => $ongkoskirimpertoko, "namaagen" => $agenpertoko, "ongkosinfo" => $ongkosinfopertoko);

			$dt_keranjang[$resellerid][$transaksidetailid] = array("transaksidetailid" => $transaksidetailid, "nama" => $nama, "kodeproduk" => $kodeproduk, "image_s" => $image_s, "berat" => $berat, "totalharga" => $total, "qty" => $qty, "harga" => $harga, "xx" => $a);

			$i %= 2;
			$i++;
			$a++;
		}


		$ongkoskirim2	= "$matauang. " . number_format($ongkoskirim, 0, ".", ".");

		//tampilkan diskon voucher
		$qryv                    = sql(" SELECT voucherid, totaldiskon, totaltagihanafterdiskon, vouchercodeid, kodevoucher, totaltagihan from tbl_transaksi where transaksiid='$transaksiid'");
		$rowv                    = sql_fetch_data($qryv);
		$voucherid               = $rowv['voucherid'];
		$vouchercodeid           = $rowv['vouchercodeid'];
		$kodevoucher             = $rowv['kodevoucher'];
		$totaldiskon             = $rowv['totaldiskon'];
		$totaltagihanafterdiskon = $rowv['totaltagihanafterdiskon'];
		$totaltagihan            = $rowv['totaltagihan'];

		$namavoucher = sql_get_var("select nama from tbl_voucher where id = '$voucherid'");

		if ($totaltagihanafterdiskon == 0)
			$totaltagihanakhir = $totaltagihan + $ongkoskirim;
		else
			$totaltagihanakhir = $totaltagihanafterdiskon + $ongkoskirim;

		$totaltagihan1 = number_format($totaltagihan, 0, ",", ".");
		$totaltagihan2 = "$matauang. $totaltagihan1";

		$totaltagihanakhir1 = number_format($totaltagihanakhir, 0, ",", ".");
		$totaltagihanakhir2 = "$matauang. $totaltagihanakhir1";

		$totaldiskon1 = number_format($totaldiskon, 0, ",", ".");
		$totaldiskon2 = "$matauang. $totaldiskon1";


		$kodeinvoice = base64_encode($orderid);

		$urlconfirm = "$fulldomain/bayar/confirm/$kodeinvoice";
		$urldownload = "$fulldomain/bayar/print/$kodeinvoice";

		$statusorder = "INVOICED";

		$warnafont = "#477603";

		// ambil data kontak admin
		$tk       = sql_get_var_row("select nama,alamat,telp,gsm from tbl_static where alias='kontak' limit 1");
		$namatk   = $tk['nama'];
		$alamattk = $tk['alamat'];
		$telptk   = $tk['telp'];
		$gsmtk    = $tk['gsm'];

		//Kirim Email ke Pembeli
		$sqlm = "select username,userfullname,useremail,userphonegsm from tbl_member where userid='$userid'";
		$hslm = sql($sqlm);
		$datam = sql_fetch_data($hslm);

		$userfullname = $datam['userfullname'];
		$useremail = $datam['useremail'];
		$userphonegsm = $datam['userphonegsm'];

		sql_free_result($hslm);

		include("invoice.pdf.php");


		$pengirim 			= "$owner <$support_email>";
		$webmaster_email 	= "Support <$support_email>";
		$userEmail			= "$email";
		$userFullName		= "$userfullname";
		$headers 			= "From : $owner";
		$subject			= "$title, Transaksi Pending #$orderid";

		$sendmail			= sendmail($userfullname, $useremail, $subject, $html, $html);

		$contentsms	= "Transaksi anda dengan nomor $orderid masih PENDING, silahkan lakukan pembayaran";
		$sendsms = kirimSMS($userphonegsm, $contentsms);


		//kirim email ke admin
		$to 		= "$support_email";
		$from 		= "$support_email";
		$headers 	= "From : $owner";
		$subject2 	= "Informasi Transaksi Pending $orderid";

		$sendmail	= sendmail($title, $to, $subject2, $html, $html, 1);

		//kirim email ke reseller
		$perintah	= "select userid,userfullname,avatar,posting,follower,following,tema,usergender,useraddress,cityname,useremail,userphonegsm from tbl_member where userid='$resellerid' limit 1";
		$hasil = sql($perintah);
		$profil = sql_fetch_data($hasil);
		sql_free_result($hasil);

		$iduser = $profil['userid'];
		$contactname = $profil['userfullname'];
		$avatar = $profil['avatar'];
		$contactuseremail = $profil['useremail'];
		$contactuserphone = $profil['userphonegsm'];


		$tores    = "$support_email";
		$fromres  = "$support_email";
		$headers  = "From : $owner";
		$subject3 = "Informasi Pemesanan Nomor Order $orderid - $title";

		$sendmail	= sendmail($contactname, $contactuseremail, $subject3, $html, $html);

		$contentsms	= "Ada transaksi Pending via $pembayaran dengan nomor invoice $orderid, silahkan dicek ke halaman dashboard anda";
		$sendsms = kirimSMS($contactuserphone, $contentsms);

		//kirim sms ke admin
		$sqlh     = "select gsm from tbl_static where alias='kontak' limit 1";
		$queryh   = sql($sqlh);
		$rowh     = sql_fetch_data($queryh);
		$gsmadmin = $rowh['gsm'];

		$kirimsms	= kirimSMS($gsmadmin, "Info: Informasi Pemesanan Nomor $orderid - $title, silahkan login ke $fulldomain/panel utk melihat detail pemesanan. Terimakasih");
	}

	$message = "Waiting customer to finish transaction order_id: " . $order_id . " using " . $type;
} else if ($transaction == 'deny') {
	$message = "Payment using " . $type . " for transaction order_id: " . $order_id . " is deny.";
} else if ($transaction == 'cancel') {

	$message = "Payment using " . $type . " for transaction order_id: " . $order_id . " is canceled.";
}


echo $message;

$input = file_get_contents('php://input');
$data = date('Y-m-d H:i:s') . " | $message\r\n";
$file = "logs/backlog-pg.txt";
$open = fopen($file, "a+");
fwrite($open, "$data");
fclose($open);

exit();
