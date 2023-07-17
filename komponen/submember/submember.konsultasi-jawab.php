<?php 
if(isset($_POST['submit']) and $_SESSION['userid'])
{

	$lengkap	= desc($_POST['lengkap']);
	$id	= desc($_POST['id']);
	
	$perintah = "select id,nama,ringkas,lengkap,create_date,alias,views,secid,tags,userid from tbl_konsultasi where id='$id'";
	$hasil = sql($perintah);
	$data =  sql_fetch_data($hasil);
	
	$nama=$data['nama'];	
	

	$alias		= getAlias($nama);

	$filename	= $_FILES['gambar']['name'];
	$filesize	= $_FILES['gambar']['size'];
	$filetmpname	= $_FILES['gambar']['tmp_name'];
	
	if($filesize > 0)
	{
		$yearm	= date("Ym");
		$folder		= "$lokasiweb/gambar/konsultasi/";
		
		$imageinfo = getimagesize($filetmpname);
		$imagewidth = $imageinfo[0];
		$imageheight = $imageinfo[1];
		$imagetype = $imageinfo[2];
		
		switch($imagetype)
		{
			case 1: $imagetype="gif"; break;
			case 2: $imagetype="jpg"; break;
			case 3: $imagetype="png"; break;
		}
		
		$photofull = "konsultasi-$alias-".$_SESSION['userid']."-$id-l.".$imagetype;
		cropimg($filetmpname,"$folder/$photofull",900,600,80);
		
		
		$photosmall = "konsultasi-$bulan-".$_SESSION['userid']."-$id.".$imagetype;
		cropimg($filetmpname,"$folder/$photosmall",450,300,80);
		
		
		if(file_exists("$folder/$photofull")){ $vgmbr = ",gambar='$photosmall',gambar1='$photofull'"; }
		
	}
	
			
	$salah = false;
	$pesan = array();
	
	if(empty($lengkap))
	{
		$pesan[4] = array("pesan"=>"Deskripsi lengkap anda masih kosong, silahkan isi dengan lengkap terlebih dahulu");
		$salah = true;
	}
	
	if(!$salah)
	{
	
		$query= "update tbl_konsultasi set konsultanid='$_SESSION[userid]',lengkap='$lengkap',published='1' $vgmbr where id='$id'";
		$hasil = sql($query);
		
	
	   if($hasil)
	   {
			   
			$pesanhasil = "Selamat jawaban anda berhasil disimpan, untuk melihat hasil perubahan dari jawaban anda silahkan lihat di fasilitas konsultasi";
			$berhasil = "1";
		}
				
	}
	else
	{
		$pesanhasil = "Penyimpanan jawaban gagal dilakukan ada beberapa kesalahan yang mesti diperbaiki, silahkan periksa kembali kesalahan dibawah ini";
		$berhasil = "0";
	}
	
$tpl->assign("pesan",$pesan);
$tpl->assign("pesanhasil",$pesanhasil);
$tpl->assign("berhasil",$berhasil);

}

$katid = $var[4];
$id = $var[4];
$katid = str_replace(".html","",$katid);

$perintah = "select id,nama,ringkas,lengkap,create_date,alias,views,secid,tags,userid from tbl_konsultasi where id='$id'";
$hasil = sql($perintah);
$data =  sql_fetch_data($hasil);

$idcontent = $data['id'];
$nama=$data['nama'];
$oleh = $data['oleh'];
$lengkap= $data['lengkap'];
$tanggal = tanggal($data['create_date']);
$gambar = $data['gambar2'];
$ringkas = nl2br($data['ringkas']);
$alias = $data['alias'];
$lengkap = $data['lengkap'];
$userid = $data['userid'];
$waveform = $data['waveform'];
$youtubeid = $data['youtubeid'];
$views = $data['views'];
$folder = $data['folder'];
$secid = $data['secid'];
$tags = $data['tags'];

$penanya = sql_get_var("select userfullname from tbl_member where userid='$userid'");

if(empty($idcontent)) header("location: $fulldomain");

$tpl->assign("detailid",$idcontent);
$tpl->assign("detailnama",$nama);
$tpl->assign("detailringkas",$ringkas);
$tpl->assign("detailsecid",$secid);
$tpl->assign("detailtags",$tags);
$tpl->assign("detailjenis",$jenis);
$tpl->assign("detailuser",$penanya);
$tpl->assign("detaillengkap",$lengkap);

?>