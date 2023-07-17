<?php
if($_POST['action'] == "savedonasi")
{
	$nama		= cleaninsert($_POST['nama']);
	$ringkas	= cleaninsert($_POST['ringkas']);
	$tanggal	= date('Y-m-d H:m:s');
	$ip 		= $_SERVER['REMOTE_ADDR'];

	$tpl->assign("nama","$nama");
	$tpl->assign("ringkas","$ringkas");
	
	$cek = sql_get_var("select count(*) as jml from tbl_donasi where nama='$nama' and userid='$_SESSION[userid]'");
	
	if($_FILES['file']['size']>0)
	{
		$tipe = $_FILES['file']['type'];
		$tipe = str_replace('/',"-",$tipe);
		
		if($tipe=='application-pdf' || $tipe=='application-vnd.openxmlformats-officedocument.wordprocessingml.document' || $tipe=='application-msword')
		{
			$errorfile = false;
		}
		else
		{
			$errorfile = true;
		}
	}
	
	if($errorfile)
	{
		$pesanhasil = "Penyimpanan pesan gagal dilakukan, format file yang dikirimkan harus PDF atau Microsoft Word.";
		$berhasil = "0";
	}
	else
	{
	
		if($cek<1)
		{
			$idbaru	= newid("id","tbl_donasi");
		
			$perintah	= "insert into tbl_donasi (`id`,`nama`,`ringkas`,`ip`, `create_date`,userid) values ('$idbaru','$nama','$ringkas','$ip', '$tanggal','$_SESSION[userid]')";
			$hasil		= sql($perintah);
		
			if($hasil)
			{
				
				if($_FILES['file']['size']>0)
				{
					$namafile = $_FILES['file']['name'];
					$namafile = strtolower($namafile);
					$namafile = str_replace(" ","-",$namafile);
					$namafile = "$idbaru-$namafile";
					move_uploaded_file($_FILES['file']['tmp_name'],"$lokasiweb/gambar/donasimain/$namafile");
					
					if(file_exists("$lokasiweb/gambar/donasimain/$namafile"))
					{
						sql("update tbl_donasi set file='$namafile' where userid='$_SESSION[userid]' and id='$idbaru'");
					}
				}
				
				$pesanhasil = "Selamat Pesan anda di $title telah berhasil disimpan. Kami akan terlebih dahulu mempelajari donasi anda sebelum ditampilkan diwebsite";
				$berhasil = "1";
			}
			else
			{
				$pesanhasil = "Penyimpanan pesan gagal dilakukan.";
				$berhasil = "0";
			}
		}
		else
		{
			$pesanhasil = "Penyimpanan pesan gagal dilakukan, duplikasi data. Anda telah mengirimkan donasi dengan nama mainan yang sama.";
			$berhasil = "0";
		}
	}
	
		
	$tpl->assign("pesan",$pesan);
	$tpl->assign("pesanhasil",$pesanhasil);
	$tpl->assign("berhasil",$berhasil);
}
	
		
		
$perintah ="select nama,alias,ringkas,lengkap,gambar1 from tbl_static where alias='donasimain'";
$hasil = sql($perintah);
$data=sql_fetch_data($hasil);
$detailnama = $data["nama"];
$alias = $data["alias"];
$detailringkas= $data["ringkas"];
$detaillengkap= $data["lengkap"];
$detailgambar = $data['gambar1'];

$tpl->assign("detailnama",$detailnama);
$tpl->assign("alias",$alias);
$tpl->assign("detailringkas",$detailringkas);
$tpl->assign("detaillengkap",$detaillengkap);
if(!empty($gambar)) $tpl->assign("detailgambar","$fulldomain/gambar/$kanal/$gambar");




?>