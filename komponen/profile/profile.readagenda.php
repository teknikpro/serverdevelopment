<?php 
$id 	= $var[5];

if($tipe == '2')
		$wherepages = "and masjidid='$uid'";
	else
		$wherepages = "and ulamaid='$uid'";

$perintah 	= "select id,nama,oleh,ringkas,lengkap,gambar1,gambar,create_date,alias,tanggal from tbl_agenda where id='$id' $wherepages";//echo $perintah;die();
$hasil 		= sql($perintah);
$data 		= sql_fetch_data($hasil);
if(sql_num_rows($hasil)<1)
{
	header("location: $fulldomain/$username");
	exit();
}
else
{
	$idcontent 		= $data['id'];
	$nama			= $data['nama'];
	$oleh 			= $data['oleh'];
	$lengkap		= bersih($data['lengkap']);
	$tanggal		= tanggal($data['tanggal']);
	$gambar 		= $data['gambar1'];
	$gambarshare 	= $data['gambar'];
	$ringkas 		= $data['ringkas'];
	$alias 			= $data['alias'];
	$stats 			= $data['views'];
	
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
	
	if(!empty($gambar)) $tpl->assign("detailgambar","$domain/gambar/$tipe/$gambar");
	if(!empty($gambarshare)) $tpl->assign("detailgambarshare","$domain/gambar/$tipe/$gambarshare");
	
	sql_free_result($hasil);

	// Masukan data kedalam statistik
	$stats = number_format($stats,0,"",".");
	$tpl->assign("detailstats",$stats);
}
?>
