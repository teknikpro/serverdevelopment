<?php
$pesan = $var[4];
if($pesan=="sukses")
{
	$ordernumber = $_GET['order_id'];
	$statuscode = $_GET['status_code'];
	$transstatus = $_GET['transaction_status'];
	
	$tpl->assign("urlpdf","$fulldomain/cart/print/".base64_encode($ordernumber));
	
	if($statuscode=="200")
	{
		if($transstatus=="capture")
		{
			$message = "Terima kasih sudah berbelanja di $title, Transaksi anda dengan nomor transaksi <strong>$ordernumber</strong> telah berhasil dilakukan
			. <br> Kami akan segera memproses transaksi anda dan melakukan pengiriman pesanan, kami akan menginformasikan nomor resi pengiriman
			dan informasi lainnya melalui email maupun SMS. <br>";
			$sukses = 1;
		}
		elseif($transstatus=="settlement")
		{
			$message = "Terima kasih sudah berbelanja di $title, Transaksi anda dengan nomor transaksi <strong>$ordernumber</strong> telah berhasil dilakukan
			. <br> Kami akan segera memproses transaksi anda dan melakukan pengiriman pesanan, kami akan menginformasikan nomor resi pengiriman
			dan informasi lainnya melalui email maupun SMS. <br>.";
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
			$message = "Terima kasih sudah berbelanja di $title, Transaksi anda dengan nomor transaksi <strong>$ordernumber</strong> telah berhasil dilakukan
			. <br> Kami akan segera memproses transaksi anda dan melakukan pengiriman pesanan, kami akan menginformasikan nomor resi pengiriman
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
			$message = "Terima kasih sudah berbelanja di $title, Transaksi anda dengan nomor transaksi <strong>$ordernumber</strong> telah berhasil dilakukan
			. <br> Kami akan segera memproses transaksi anda dan melakukan pengiriman pesanan, kami akan menginformasikan nomor resi pengiriman
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
else
{
	$message = "Mohon maaf transaksi anda dengan nomor transaksi <strong>$ordernumber</strong> gagal dilakukan,<br> 
		silahkan untuk mencoba kembali melakukan transaksi";
		$sukses = 0;
}

$tpl->assign("message",$message);
$tpl->assign("pesan",$pesan);
$tpl->assign("sukses",$sukses);

unset($_SESSION['orderid']);

?>