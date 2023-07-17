<?php
$katid = str_replace(".html","",$katid);

if(!empty($katid) && $katid!="global")
{
	$perintah1 = "select * from tbl_berita_sec where alias='$katid'";
	$hasil1 = sql($perintah1);
	$row1 = sql_fetch_data($hasil1);
	$namasec = $row1['nama'];
	$secid = $row1['secid'];
	$secalias = $row1['alias'];
	$where = "and secid='$secid'";
	$rubrik = "$namasec";
	$tpl->assign("rubrik","$rubrik");
	$tpl->assign("arsip","$fulldomain/berita/list/$secalias/");
}
else
{
	$secalias = "global";
	$tpl->assign("rubrik","$rubrik");
	$tpl->assign("arsip","$fulldomain/berita/list/$secalias/");
}

$perintah = "select id,nama,oleh,ringkas,lengkap,gambar1,gambar,create_date,alias,views,tags from tbl_$kanal where published='1' and id='$id'";
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
$tag = $data['tags'];
$penulisid = $data['userid'];

		
$t = explode(",",$tag);
$tags = array();
for($c=0;$c<count($t);$c++)
{
	
	if(!empty($t[$c])){ $tags[$c] = array("tagid"=>$c,"tag"=>trim($t[$c]),"url"=>"$fulldomain/$kanal/tag/".urlencode(trim($t[$c]))); }
}
$tpl->assign("tags",$tags);
$tpl->assign("jmltags",count($tags));


if(empty($katid)) $katid="0";

//Sesuaikan dengan path
$lengkap = str_replace("jscripts/tiny_mce/plugins/emotions","$fulldomain/plugin/emotion",$lengkap);
$lengkap = str_replace("../../","/",$lengkap);


$tpl->assign("detailid",$idcontent);
$tpl->assign("detailnama",$nama);
$tpl->assign("detaillengkap",$lengkap);
$tpl->assign("detailringkas",$ringkas);
$tpl->assign("detailcreator",$oleh);
$tpl->assign("detailtanggal",$tanggal);
$tpl->assign("detaildate",$data['create_date']);

if(!empty($gambar)) $tpl->assign("detailgambar","$fulldomain/gambar/$kanal/$gambar");
if(!empty($gambarshare)) $tpl->assign("detailgambarshare","$fulldomain/gambar/$kanal/$gambarshare");


//SetAction
if($_SESSION['userid'] && $_SESSION['usertipe']!="0")
{
	setaction($_SESSION['userid'],"membaca artikel <b>$nama</b>","/berita/read/$secalias/$idcontent/$alias");
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


//Berita Pilihan
$mysql = "select id,ringkas,nama,create_date,alias,gambar,secid from tbl_$kanal where published='1' and id!='$id' order by rand() limit 4";
$hasil = sql($mysql);

$datapilihan = array();
$a =1;
while ($data = sql_fetch_data($hasil)) {	
		$tanggal = $data['create_date'];
		$nama = $data['nama'];
		$id = $data['id'];
		$ringkas = $data['ringkas'];
		$alias = $data['alias'];
		$tanggal = tanggal($tanggal);
		$gambar = $data['gambar'];
		$secid = $data['secid'];
		
		
		$mysql1 = "select nama,alias,secid from tbl_$kanal"."_sec where secid='$secid'";
		$hasil1 = sql($mysql1);
		$data1 = sql_fetch_data($hasil1);
		$secalias = $data1['alias'];

	
		if(!empty($gambar)) $gambar = "$fulldomain/gambar/$kanal/$gambar";
			 else $gambar = "";
		$link = "$fulldomain/$kanal/read/$secalias/$id/$alias";
			
		$datapilihan[$id] = array("id"=>$id,"a"=>$a,"nama"=>$nama,"ringkas"=>$ringkas,"namasec"=>$namasec,"tanggal"=>$tanggal,"alias"=>$alias,"link"=>$link,"gambar"=>$gambar);
		$a++;	
}
$a = 0;
sql_free_result($hasil);
$tpl->assign("terkait",$datapilihan);
?>
