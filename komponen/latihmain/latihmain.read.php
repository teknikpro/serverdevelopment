<?php
$katid = $var[4];
$id = $var[4];
$katid = str_replace(".html","",$katid);

$perintah = "select id,nama,oleh,ringkas,lengkap,gambar1,create_date,alias,video,jenis,youtubeid,views from tbl_video where id='$id'";
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

if($jenis=="youtube") { $video = "https://www.youtube.com/watch?v=$youtubeid"; }

if(empty($katid)) $katid="0";

//Sesuaikan dengan path
$lengkap = str_replace("jscripts/tiny_mce/plugins/emotions","$fulldomain/plugin/emotion",$lengkap);
$lengkap = str_replace("../../","/",$lengkap);


$tpl->assign("detailid",$idcontent);
$tpl->assign("detailnama",$nama);
$tpl->assign("detaillengkap",$ringkas);
$tpl->assign("detailringkas",$ringkas);
$tpl->assign("detailcreator",$oleh);
$tpl->assign("detailtanggal",$tanggal);
$tpl->assign("detailjenis",$jenis);

if(!empty($gambar)) $tpl->assign("detailgambar","$fulldomain/gambar/video/$gambar");
if(!empty($video)) $tpl->assign("detailvideo","$fulldomain/gambar/video/$video");
if(!empty($video)) $tpl->assign("detailyoutube","$video");


sql_free_result($hasil);

// Masukan data kedalam statistik
$stats = number_format($views,0,0,".");
$tpl->assign("detailstats",$stats);

$views = "update tbl_video set views=views+1 where id='$id'";
$hsl = sql($views);

//Berita Terkait
$mysql = "select id,nama,alias,gambar,jenis,youtubeid,views from tbl_video where published='1' and id !='$id' $where order by create_date desc limit 2";
$hasil = sql( $mysql);

$terkait = array();
$a =1;		
while ($data =  sql_fetch_data($hasil)) {	
$nama = $data['nama'];
$id = $data['id'];
$alias = $data['alias'];
$gambar = $data['gambar'];
$views = $data['views'];

$jenis = $data['jenis'];
$youtubeid = $data['youtubeid'];

if($jenis=="youtube" && empty($gambar))
{
	$gambar = "http://i.ytimg.com/vi/$youtubeid/maxresdefault.jpg";
}
else
{
	
	if($i==0){ $gambar= $gambar1; }
	else { $gambar = $gambar; }

	if(!empty($gambar)) $gambar = "$fulldomain/gambar/video/$gambar";
	 else $gambar = "";
	 

}

$link = "$fulldomain/video/read/$secalias/$id/$alias";
	
$terkait[$id] = array("id"=>$id,"a"=>$a,"nama"=>$nama,"url"=>$link,"gambar"=>$gambar,"views"=>$views);
$a++;	
}
sql_free_result($hasil);
$tpl->assign("terkait",$terkait);

?>
