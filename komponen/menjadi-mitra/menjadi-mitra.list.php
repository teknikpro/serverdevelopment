<?php
$perintah ="select nama,alias,ringkas,lengkap,gambar1 from tbl_static where alias='menjadi-mitra'";
$hasil = sql($perintah);
$data=sql_fetch_data($hasil);
$detailnama = $data["nama"];
$alias = $data["alias"];
$detailringkas= $data["ringkas"];
$detaillengkap= $data["lengkap"];
$gambar = $data['gambar1'];

$tpl->assign("detailnama",$detailnama);
$tpl->assign("alias",$alias);
$tpl->assign("detailringkas",$detailringkas);
$tpl->assign("detaillengkap",$detaillengkap);

if(!empty($gambar)) $tpl->assign("detailgambar","$fulldomain/gambar/$kanal/$gambar");

//Simpan Komentar
if(!empty($_POST['judul']))
{
	$judul = cleanInsert($_POST['judul']);
	$pesan = cleanInsert($_POST['pesan']);
	$nama = cleanInsert($_POST['nama']);
	$email = $_POST['email'];
	$handphone = cleanInsert($_POST['handphone']);
	$ip = $_SERVER['REMOTE_ADDR'];
	$notifikasi = $_POST['notifikasi'];
	$kata = $_POST['kata'];
	
	if($notifikasi) $notify = 1;
		else $notify = 0;
	
	//$error_back = "<b><a href=\"javascript:history.back();\" class=\"button\">  Kembali </a></b>";

		
	if(isset($_POST['g-recaptcha-response']))
	{
		$api_url = 'https://www.google.com/recaptcha/api/siteverify?secret=' . $secret_key . '&response='.$_POST['g-recaptcha-response'];
		$response = file_get_contents($api_url);
		$data = json_decode($response, true);
		
		if($data['success'])
		{
			$spam = 0;
		}
		else
		{
			$spam = 1;
		}
	}
	else
	{
		$spam = 1;
	}

	
    if($spam=="1")
	{	
      $salah .= ("Anda belum menceklis antispam, silahkan pilih terlebih dahulu<br />\n");
	  $benar = 2;
	} 
 	if (empty($judul))
	{
		$salah .= ("- Mohon untuk mengisi nama lengkap<br />\n");
		$benar = 2;
	}
	if (empty($nama))
	{
		$salah .= ("- Mohon untuk mengisi nama lengkap<br />\n");
		$benar = 2;
	}	
	if (empty($email))
	{
		$salah .= ("- Email belum anda masukan<br />\n");
		$benar = 2;
	}
	if (empty($handphone))
	{
		$salah .= ("- Nomor Handphone yang bisa dihubungi belum anda masukan<br />\n");
		$benar = 2;
	}
	if (empty($pesan) )
	{
		$salah .= ("- Pesan  tidak boleh kosong<br />\n");
		$benar = 2;
	 }
 
	 $badip = explode(",",$badip);
	 $totalip = count($badip);
	
	 for($i=0;$i < $totalip;$i++){
		if ($ip=="$badip[$i]")
			{                
				$salah .= ("Komputer anda tidak diperkenankan untuk mengisi komentar<br />\n");
				$benar = 2;
			}
	 }

	 if ($benar == 2)
	 {
		$error = "Mohon ma'af ada kesalahan memasukan data anda atau kurang dalam mengisi form yang telah disediakan <br />$salah $error_back ";
		$tpl->assign("error",$error); 
	 }	 
	else
	 {	 
 
	$sql = "select count(*) as jml from tbl_contact where judul='$judul' and nama='$nama' and ip='$ip' and pesan='$pesan' and email='$email'";
	$hsl = sql( $sql);
	$tot = sql_result($hsl, 0, jml);
	
	if ($tot=="0")
	{
		
		$query=("insert into tbl_contact(judul,nama,create_date,pesan,ip,email,handphone) values ('$judul','$nama','$date','$pesan','$ip','$email','$handphone')");
		$hasil = sql($query);
		
		if($hasil)
		{
			//Kirim
			$isi = "Terima Kasih Sudah Menghubungi $title
=========================================================================<br />
Yth. $nama,
Terima kasih sudah menghubungi kami $title. Pesan anda:

$pesan

Kami akan membalas pesan anda sesegera mungkin. Terima kasih
dan sukses selalu untuk anda.

Terima Kasih
$owner";

$isihtml = "<br />
<strong>Terima Kasih Sudah Menghubungi $title</strong>
=========================================================================<br />
Yth. $nama,<br />
Terima kasih sudah menghubungi kami $title. Pesan anda:<br />
<br />

$pesan
<br />
<br />

Kami akan membalas pesan anda sesegera mungkin. Terima kasih
dan sukses selalu untuk anda.
.
<br />
<br />

Terima Kasih<br />

$owner";

$subject = "Terima Kasih Sudah Menghubungi $title";

sendmail($nama,$email,$subject,$isi,$isihtml);

$isi1 = "Ada pesan masuk melalui kontak dari <strong>$nama</strong> dengan email <strong>$email</strong> dan nomor handphone <strong>$handphone</strong>:<br />
<br />
$pesan<br />
<br />
";


sendmail($title,$support_email,"Ada Pesan Masuk Melalui Kontak",$isi1,$isi1);

			
			$tpl->assign("msg","Terima kasih telah mengirimkan pesan kepada kami, kami akan menindaklanjuti pesan anda sesegera mungkin melalui email atau melalui telephone yang
				telah anda berikan kepada kami.<br />  ");
		}
		else
		{ 
			$tpl->assign("error","Pesan anda gagal tersimpan kemungkinan ada beberapa kesalahan. Silahkan untuk mengulangi.<br />. ");
		}
	 }
}
}

?>