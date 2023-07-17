<?php 
if(!$_SESSION['username'])
{
	$message = "Mohon maaf sebelum anda mengirimkan konsultasi, silahkan untuk login terlebih dahulu, dan bila anda belum menjadi anggota disarankan
	untuk melakukan pendaftaran terlebih dahu";
	$message = base64_encode($message);
	
	header("location: $fulldomain/user/?message=$message");
	exit();
}

if(isset($_POST['nama']))
{

	
	$nama		= cleaninsert($_POST['nama']);
	$tampilkan		= cleaninsert($_POST['tampilkan']);
	$pesan		= cleaninsert($_POST['pesan']);
	$tanggal	= date('Y-m-d H:m:s');
	$ip 		= $_SERVER['REMOTE_ADDR'];
	
	$tpl->assign("nama","$nama");
	$tpl->assign("email","$email");
	$tpl->assign("pesan","$pesan");
	
	if(empty($nama))
	{
		$notif[2] = array("pesan"=>"Judul pertanyaan masih kosong, silahkan isi Terlebih Dahulu");
		$salah = true;
	}
	else if(empty($pesan))
	{
		$notif[3] = array("pesan"=>"Pertanyaan anda masih kosong, silahkan isi Terlebih Dahulu");
		$salah = true;
	}
	else{ $salah = false; }
	
	
	if(!$salah)
	{
	
		$sql	= "select max(id) as idbaru from tbl_konsultasi";
		$query	= sql($sql);
		$idbaru	= sql_result($query,0,idbaru) + 1;
		
		$perintah	= "insert into tbl_konsultasi (id,nama,ringkas,ip,tampilkan,userid, `create_date`) values ('$idbaru','$nama','$pesan','$ip', '$tampilkan','$_SESSION[userid]','$tanggal')";
		$hasil		= sql($perintah);
		
		if($hasil)
		{
			//Info Ke Referensi 
			$subject = "Ada Konsultasi Masuk";
			$message = "Dear $contactname<br>Ada konsultasi masuk melalui dFunstation, silahkan ditindaklanjuti dengan membuka halaman panel";
			
			sendmail($support,$support,$subject,$message,emailhtml($message));
			
			//Forward
			$sql = "select nama,useremail from tbl_forward order by nama";
			$hsl = sql($sql);
			while($dt = sql_fetch_data($hsl))
			{
				$fnama = $dt['nama'];
				$femail = $dt['useremail'];
				
				$subject = "Forward: $subject";
				sendmail($fnama,$femail,$subject,$message,emailhtml($message));
				
			}
		
			$pesanhasil = "Selamat pertanyaan anda di $title telah berhasil dikirimkan. Silahkan tunggu informasi dari kami selanjutnya melalui email ataupun nomor telephone anda. Terima Kasih";
			$berhasil = "1";
			
			
		}
		else
		{
			$pesanhasil = "Penyimpanan pesan gagal dilakukan.";
			$berhasil = "0";
		}
	}
	
	$tpl->assign("pesan",$notif);
	$tpl->assign("pesanhasil",$pesanhasil);
	$tpl->assign("berhasil",$berhasil);

}
?>
