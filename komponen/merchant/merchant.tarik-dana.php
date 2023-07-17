<?php

$userid = sql_get_var("SELECT merchant FROM tbl_member WHERE userid=$_SESSION[userid] ");
$pendapatan = sql_get_var("SELECT SUM(totaltagihan) FROM tbl_transaksi_voucher WHERE merchant='$userid' AND paid='1' ");
$penarikan = sql_get_var("SELECT SUM(jumlah) FROM tbl_transaksi_tiket_penarikan WHERE userid='$userid' AND proses='1' ");

$sisa_saldo = ($pendapatan - $penarikan);

if($sisa_saldo < $minimaltarik){
	header('Location: https://www.dfunstation.com/merchant/pendapatan/saldokurang');
	exit;
}


if(isset($_POST['penarikan'])){

	$datainput = $_POST['penarikan'];

	if($datainput < $minimaltarik){
		$salah = true;
		header('Location: https://www.dfunstation.com/merchant/tarik-dana/minimal-penarikan');
		exit;
	}else {
		$salah = false;
	}

	if(!$salah){

		$tanggal = date('Y-m-d H:i:s');
		
		$perintah = "INSERT INTO tbl_transaksi_tiket_penarikan(tanggal,userid,jumlah,proses,status) VALUES ('$tanggal','$userid','$datainput','1','0')";
		$hasil = sql($perintah);

		// getdata merchan
		$idmerchant = sql_get_var("SELECT merchant FROM tbl_member WHERE userid=$_SESSION[userid] ");
		$merchant = sql_get_var_row("SELECT nama,email FROM tbl_world WHERE id='$idmerchant' ");
		$userfullname = $merchant['nama'];
		$useremail = $merchant['email'];

		$tanggalindo = tanggal($tanggal);
		$jumlahpengajuan = number_format($datainput);

		// kirim email
		$subject            = "$title, Konfirmasi Penarikan Dana";
		$html = "Yth. $userfullname.<br><br>Ini merupakan konfirmasi pengajuan penarikan dana. Berikut adalah informansinya:<br><br>
		<strong>Tanggal Pengajuan:</strong> $tanggalindo<br>
		<strong>Jumlah Pengajuan:</strong> $jumlahpengajuan<br><br>
		Silahkan tunggu pengajuan anda maksimal 2 hari kerja";
		$sendmail            = sendmail($userfullname, $useremail, $subject, $html, emailhtml($html));

		header('Location: https://www.dfunstation.com/merchant/history-penarikan/berhasil-proses');
		exit;

	}
}

	$pesan = $var[4];

	if($pesan == "minimal-penarikan"){
		$alert = "Minimal Penarikan Rp. $minimaltarik";
	}

		
	$tpl->assign("pesan", $alert);
	$tpl->assign("datainput", $datainput);

?>