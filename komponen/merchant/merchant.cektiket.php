<?php 

if(isset($_POST['insertivoice']))
{

	echo "data barhasil diinput";
	die;
	$invoice		= $_POST['invoice'];
	
	$pesan = array();

	if(!empty($invoice))
	{
		
		$cekinvoice = sql_get_var("SELECT invoiceid FROM tbl_transaksi_voucher WHERE invoiceid='$invoice' ");

		$chat = sql_get_var_row("SELECT * FROM tbl_transaksi_voucher WHERE invoiceid='$invoice' ");
		$penggunaan = $chat['penggunaan'];
		$kadaluarsa = $chat['kadaluarsa'];
		$date 		= date("Y-m-d"); 
		$date1		= new DateTime($kadaluarsa);	
		$date2 		= new DateTime($date);
			  
		if(empty($cekinvoice))
			{
			$pesan[4] = array("pesan"=>"Invoice Tidak ditemukan");
			$salah = true;
			$error = "1";
			}
		else if($penggunaan == 1)
			{
			$pesan[5] = array("pesan"=>"Tiket Sudah digunakan");
			$salah = true;
			$error = "1";
			}
		else if($date2 > $date1)
			{
			$pesan[6] = array("pesan"=>"Tiket sudah kadaluarsa");
			$salah = true;
			$error = "1";
			}
		else
			{ $salah = false; }
		
		
		if(!$salah)
		{
			if($invoice)
			{
				$tanggal = date("Y-m-d H:i:s");   
				
				$query=("update tbl_member set userpassword='$userNewPassword',userlastactive='$tanggal' where username='$_SESSION[username]'");
				$hasil = sql($query);
				
				$pesanhasil = "Pergantian Password berhasil dilakukan, lakukan perubahan Password secara berkala dalam upaya membantu sistem mengamankan akun anda.";
				$berhasil = "1";
			}
		}
		else
		{
			$pesanhasil = "Penyimpanan setting gagal dilakukan ada beberapa kesalahan yang mesti diperbaiki, silahkan periksa kembali kesalahan dibawah ini";
			$berhasil = "0";
		}
	}

$tpl->assign("error",$error);
$tpl->assign("pesan",$pesan);

}
?>