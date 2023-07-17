<?php
$tpl->assign("namarubrik", "Histori Transaksi");

$sql1 	= "SELECT * FROM tbl_transaksi_all WHERE userid='$_SESSION[userid]' ORDER BY tanggal DESC ";
$hsl1 	= sql($sql1);
$listtransaksi	= array();
$no	= 1;
while ($row1 = sql_fetch_data($hsl1)) {
	$idtransaksi			 = $row1['id_transaksi'];
	$invoice 				 = $row1['invoice'];
	$nama_produk			 = $row1['nama_produk'];
	$jumlah					 = rupiah($row1['jumlah']);
	$tanggal				 = tanggal($row1['tanggal']);
	$status_transaksi		 = $row1['status_transaksi'];

	$listtransaksi[] = array(
		"invoice" => $invoice, "nama_produk" => $nama_produk, "jumlah" => $jumlah, "tanggal" => $tanggal, "status_transaksi" => $status_transaksi, "no" => $no
	);
	$no++;
}
sql_free_result($hsl1);
$tpl->assign("listtransaksi", $listtransaksi);
