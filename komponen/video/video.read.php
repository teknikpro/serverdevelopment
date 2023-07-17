<?php 
$id = $var[4];
$perintah = "select id,nama,oleh,ringkas,lengkap,gambar1,create_date,alias,video,jenis,youtubeid,views from tbl_$kanal where id='$id'";
$hasil = sql($perintah);
$data =  sql_fetch_data($hasil);

$idcontent = $data['id'];
$nama=$data['nama'];
$oleh = $data['oleh'];
$lengkap= $data['lengkap'];
$tanggal = tanggal($data['create_date']);
$gambar = $data['gambar1'];
$ringkas = $data['ringkas'];
$alias = $data['alias'];
$video = $data['video'];
$jenis = $data['jenis'];
$youtubeid = $data['youtubeid'];
$views = $data['views'];
$videourl = $data['videourl'];

if(empty($katid)) $katid="0";

$md5 = md5($idcontent);

//Sesuaikan dengan path
$lengkap = str_replace("jscripts/tiny_mce/plugins/emotions","$domain/plugin/emotion",$lengkap);
$lengkap = str_replace("../","$domain/",$lengkap);

if(empty($lengkap)) $lengkap = $ringkas;

$tpl->assign("detailid",$idcontent);
$tpl->assign("detailnama",$nama);
$tpl->assign("detaillengkap",$lengkap);
$tpl->assign("detailringkas",$ringkas);
$tpl->assign("detailcreator",$oleh);
$tpl->assign("detailtanggal",$tanggal);
$tpl->assign("detailjenis",$jenis);

if(!empty($gambar)) $tpl->assign("detailgambar","$domain/gambar/video/$gambar");

if($jenis=="youtube")
{ 
	$video = "https://www.youtube.com/embed/$youtubeid"; 
	$tpl->assign("detailvideo",$video);
}
else if($jenis=="videourl")
{ 
	$video = $videourl; 
	$tpl->assign("detailvideo",$video);
}
else 
{
	if(!empty($video)) $tpl->assign("detailvideo","$domain/medias/video/$md5/$video");
}




//assign fasilitas penunjang
if($fasilitasprint) $tpl->assign("urlprint","$fulldomain/$kanal/print/$katid/$idcontent");
if($fasilitaspdf) $tpl->assign("urlpdf","$fulldomain/$kanal/pdf/$katid/$idcontent");
if($fasilitasemail) $tpl->assign("urlemail","$fulldomain/$kanal/kirim/$katid/$idcontent");
if($fasilitasarsip) $tpl->assign("urlarsip","$fulldomain/$kanal/arsip/$secalias");
if($fasilitasrss) $tpl->assign("urlrss","$fulldomain/$kanal/rss");

sql_free_result($hasil);

// Masukan data kedalam statistik
$stats = number_format($views,0,0,".");
$tpl->assign("detailstats",$stats);
$view = 1;

$views = "update tbl_$kanal set views=views+1 where id='$id'";
$hsl = sql($views);

?>
