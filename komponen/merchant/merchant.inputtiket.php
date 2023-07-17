<?php 

if(isset($_POST['invoice']))
{
	$invoice		= $_POST['invoice'];

	if(empty($invoice))
			{
			$pesan = "Invoice tidak boleh kosong";
			$salah = true;
			$error = "1";
		}


	if(!empty($invoice))
	{
		
		$cekinvoice = sql_get_var("SELECT invoiceid FROM tbl_transaksi_voucher WHERE invoiceid='$invoice' ");

		$chat = sql_get_var_row("SELECT * FROM tbl_transaksi_voucher WHERE invoiceid='$invoice' ");
		$penggunaan = $chat['penggunaan'];
		$kadaluarsa = $chat['kadaluarsa'];
		$date 		= date("Y-m-d"); 
		$date1		= new DateTime($kadaluarsa);	
		$date2 		= new DateTime($date);
		$userid		= $chat['userid'];
		$tanggal_barcode = tanggal($chat['tanggal_barcode']);
		$jenistiket	= $chat['jenistiket'];
		$paid		= $chat['paid'];
			  
		if(empty($cekinvoice))
			{
			$pesan = "Invoice Tidak ditemukan";
			$salah = true;
			$error = "1";
			}
		else if($paid != 1)
		{
			$pesan = "Tiket belum dibayar atau sudah expire " ;
			$salah = true;
			$error = "1";
		}
		else if($penggunaan == 1)
			{
			$pesan = "Tiket Sudah digunakan pada ". $tanggal_barcode ;
			$salah = true;
			$error = "1";
			}
		else if($date2 > $date1)
			{
			$pesan = "Tiket sudah kadaluarsa pada " . tanggal($kadaluarsa) ;
			$salah = true;
			$error = "1";
			}
		else
			{ $salah = false; }
		
		
		if(!$salah)
		{
			if($invoice)
			{
				$berhasil = "1";
				if($jenistiket == "tiket"){
					$userfullname 	= sql_get_var("SELECT nama FROM tbl_transaksi_tiket WHERE idtiket='$userid' ");
				}else {
					$userfullname 	= sql_get_var("SELECT userfullname FROM tbl_member WHERE userid='$userid' ");
				}
				$invoiceid 		= $chat['invoiceid'];
				$create_date	= tanggal($chat['create_date']);
				$nama			= $chat['nama'];
				$totaltagihan	= number_format($chat['totaltagihan']);

			}
		}
		else
		{
			$pesanhasil = "invoice ada";
			$berhasil = "0";
		}
	}

$tpl->assign("error",$error);
$tpl->assign("pesan",$pesan);
$tpl->assign("datainput",$invoice);
$tpl->assign("berhasil",$berhasil);
$tpl->assign("userfullname",$userfullname);
$tpl->assign("invoiceid",$invoiceid);
$tpl->assign("create_date",$create_date);
$tpl->assign("nama",$nama);
$tpl->assign("totaltagihan",$totaltagihan);


}

if(isset($_POST['insertivoice'])){
	
	$invoice		= $_POST['insertivoice'];

	$cekinput = sql_get_var_row("SELECT transaksiid,invoiceid FROM tbl_transaksi_voucher WHERE invoiceid='$invoice' ");
	$idtransaksi = $cekinput['transaksiid'];
	$invoiceid = $cekinput['invoiceid'];
	$merchant = $cekinput['merchant'];

	$id_merchant = sql_get_var("SELECT merchant FROM tbl_member WHERE userid='$_SESSION[userid]' ");

	if($invoiceid){
		if($merchant = $id_merchant ){
			$tanggal = date("Y-m-d H:i:s");
			sql("UPDATE tbl_transaksi_voucher SET penggunaan='1',tanggal_barcode='$tanggal' WHERE invoiceid='$invoiceid' ");
			$doneinput = '1';
		}else {
			$otoritas = '1';
		}
	}

	$tpl->assign("doneinput",$doneinput);
	$tpl->assign("otoritas",$otoritas);
	$tpl->assign("invoiceid",$invoiceid);
}

?>