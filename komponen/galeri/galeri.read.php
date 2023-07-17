<?php 
$katid = $var[4];
$id = $var[5];

$perintah = "select id,nama,oleh,ringkas,gambar1,create_date,alias,views from tbl_$kanal where id='$id'";
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
$views = $data['views'];

if(empty($katid)) $katid="0";

//Sesuaikan dengan path
$lengkap = str_replace("jscripts/tiny_mce/plugins/emotions","$domain/plugin/emotion",$lengkap);
$lengkap = str_replace("../../","/",$lengkap);


$tpl->assign("detailid",$idcontent);
$tpl->assign("detailnama",$nama);
$tpl->assign("detaillengkap",$lengkap);
$tpl->assign("detailringkas",$ringkas);
$tpl->assign("detailoleh",$oleh);
$tpl->assign("detailtanggal",$tanggal);

if(!empty($gambar)) $tpl->assign("detailgambar","$domain/gambar/galeri/$gambar");


//assign fasilitas penunjang
if($fasilitasprint) $tpl->assign("urlprint","$fulldomain/$kanal/print/$katid/$idcontent");
if($fasilitaspdf) $tpl->assign("urlpdf","$fulldomain/$kanal/pdf/$katid/$idcontent");
if($fasilitasemail) $tpl->assign("urlemail","$fulldomain/$kanal/kirim/$katid/$idcontent");
if($fasilitasarsip) $tpl->assign("urlarsip","$fulldomain/$kanal/arsip/$secalias");
if($fasilitasrss) $tpl->assign("urlrss","$fulldomain/$kanal/rss");

sql_free_result($hasil);

// Masukan data kedalam statistik
$stats = number_format($views,0,0,".");
$tpl->assign("detailviews",$stats);
$view = 1;

$views = "update tbl_$kanal set views=views+1 where id='$id'";
$hsl = sql($views);

//Berita Terkait
$mysql = "select id,nama,alias,gambar,ringkas,create_date from tbl_$kanal where published='1' and id != '$id' and secid='$katid' $where order by create_date desc";
$hasil = sql( $mysql);

$terkait = array();
$a =1;		
while ($data =  sql_fetch_data($hasil)) {	
		$tanggal = $data['create_date'];
		$nama = $data['nama'];
		$id = $data['id'];
		$ringkas = $data['ringkas'];
		$alias = getalias($data['nama']);
		$tanggal = tanggal($tanggal);
		$gambar = $data['gambar'];
		
		if(!empty($gambar)) $gambar = "$domain/gambar/$kanal/$gambar";
			 else $gambar = "";
		
		$link = "$fulldomain/$kanal/read/$katid/$id/$alias";
			
		$terkait[$id] = array("id"=>$id,"a"=>$a,"nama"=>$nama,"ringkas"=>$ringkas,"namasec"=>$namasec,"tanggal"=>$tanggal,"alias"=>$alias,"link"=>$link,"gambar"=>$gambar);
		$a++;	
}
sql_free_result($hasil);
$tpl->assign("terkait",$terkait);
?>
