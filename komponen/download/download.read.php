<?php 
$katid = $var[4];
$id = $var[5];
$katid = $var[4];
$katid = str_replace(".html","",$katid);

if(!empty($katid) && $katid!="global")
{
	$perintah1 = "select * from tbl_$kanal"."_sec where alias='$katid'";
	$hasil1 = sql($perintah1);
	$row1 = sql_fetch_data($hasil1);
	$namasec = $row1['nama'];
	$secid = $row1['secid'];
	$secalias = $row1['alias'];
	$where = "and secid='$secid'";
	$rubrik = "$namasec";
	$tpl->assign("rubrik","$rubrik");
	$tpl->assign("arsip","$fulldomain/$kanal/arsip/$secalias/");
}
else
{
	$secalias = "global";
	$rubrik = "Berita";
	$tpl->assign("rubrik","$rubrik");
	$tpl->assign("arsip","$fulldomain/$kanal/arsip/$secalias/");
}


$perintah = "select id,nama,oleh,ringkas,lengkap,gambar1,gambar,create_date,alias,file1,file2,views from tbl_$kanal where id='$id'";
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
$views = $data['views'];





$file1 = $data['file1'];
$file2 = $data['file2'];

$extfile1 = str_replace(".","",substr($file1,-4,4));
$extfile2 = str_replace(".","",substr($file2,-4,4));
$namafile1 = str_replace("$kanal-$id-1-","",$file1);
$namafile2 = str_replace("$kanal-$id-2-","",$file2);

if(!empty($file1)){ $tpl->assign("extfile1",$extfile1); $tpl->assign("namafile1",$namafile1); $tpl->assign("file1","$domain/gambar/$kanal/upload/$file1"); } 

$download = $var[7];
if($download=="download")
{
	if(!empty($_SESSION['username']))
	{
		header("location: $domain/gambar/$kanal/upload/$file1");
		exit();
	}
	else
	{
		$msg = "Mohon maaf untuk mendownload <strong>$nama</strong>, anda harus login atau menjadi member $title terlebih dahulu. Silahkan daftar dan
		login menjadi member dan dapatkan banyak untungnya";
		$tpl->assign("msg",$msg);
	}
}

if(empty($katid)) $katid="0";

//Sesuaikan dengan path
$lengkap = str_replace("jscripts/tiny_mce/plugins/emotions","$domain/plugin/emotion",$lengkap);
$lengkap = str_replace("../../","/",$lengkap);


$tpl->assign("detailid",$idcontent);
$tpl->assign("detailnama",$nama);
$tpl->assign("detaillengkap",$lengkap);
$tpl->assign("detailringkas",$ringkas);
$tpl->assign("detailcreator",$oleh);
$tpl->assign("detailtanggal",$tanggal);

if(!empty($gambar)) $tpl->assign("detailgambar","$domain/gambar/$kanal/$gambar");
if(!empty($gambarshare)) $tpl->assign("detailgambarshare","$domain/gambar/$kanal/$gambarshare");


//assign fasilitas penunjang
$tpl->assign("urldownload","$fulldomain/$kanal/read/$secalias/$idcontent/$alias/download");
sql_free_result($hasil);


// Masukan data kedalam statistik
$stats = number_format($views,0,0,".");
$tpl->assign("detailstats",$stats);
$view = 1;

$views = "update tbl_$kanal set views=views+1 where id='$id'";
$hsl = sql($views);


?>
