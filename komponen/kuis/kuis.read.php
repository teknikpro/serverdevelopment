<?php
$perintah = "select id,nama,lengkap,gambar1,gambar from tbl_static where alias='kontak'";
$hasil = sql($perintah);
$data =  sql_fetch_data($hasil);

$idcontent = $data['id'];
$nama=$data['nama'];
$oleh = $data['oleh'];
$lengkap= $data['lengkap'];
$tanggal = tanggal($data['create_date']);
$gambar = $data['gambar1'];
$gambarshare = $data['gambar'];
$ringkas = $data['ringkas'];
$alias = $data['alias'];

if(empty($katid)) $katid="0";

//Sesuaikan dengan path
$lengkap = str_replace("jscripts/tiny_mce/plugins/emotions","$domain/plugin/emotion",$lengkap);
$lengkap = str_replace("../../","$fulldomain/",$lengkap);


$tpl->assign("detailid",$idcontent);
$tpl->assign("detailnama",$nama);
$tpl->assign("detailcontact",urlencode("$rubrik: $nama"));
$tpl->assign("detaillengkap",$lengkap);
$tpl->assign("detailringkas",$ringkas);
$tpl->assign("detailcreator",$oleh);
$tpl->assign("detailtanggal",$tanggal);

if(!empty($gambar)) $tpl->assign("detailgambar","$domain/gambar/kontak/$gambar");
if(!empty($gambarshare)) $tpl->assign("detailgambarshare","$domain/gambar/kontak/$gambarshare");


//assign fasilitas penunjang
if($fasilitasprint) $tpl->assign("urlprint","$fulldomain/$kanal/print/$katid/$idcontent");
if($fasilitaspdf) $tpl->assign("urlpdf","$fulldomain/$kanal/pdf/$katid/$idcontent");
if($fasilitasemail) $tpl->assign("urlemail","$fulldomain/$kanal/kirim/$katid/$idcontent");
if($fasilitasarsip) $tpl->assign("urlarsip","$fulldomain/$kanal/arsip/$secalias");
if($fasilitasrss) $tpl->assign("urlrss","$fulldomain/$kanal/rss");

sql_free_result($hasil);

$tpl->assign("judul",urldecode($aksi));

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

	include($lokasiweb."/librari/securimage/securimage.php");

    $securimage = new Securimage();
    if($securimage->check($kata) == false) {
      $salah .= ("- Kode antispam yang dimasukan kurang benar, silahkan periksa kembali<br />\n");
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
