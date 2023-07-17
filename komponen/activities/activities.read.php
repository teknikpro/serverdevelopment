<?php 
$id = $var[4];

$perintah = "select id,nama,oleh,ringkas,lengkap,gambar1,gambar,create_date,alias,views from tbl_$kanal where id='$id'";
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
$lengkap = str_replace("../","$domain/",$lengkap);


$tpl->assign("detailid",$idcontent);
$tpl->assign("detailnama",$nama);
$tpl->assign("detaillengkap",$lengkap);
$tpl->assign("detailringkas",$ringkas);
$tpl->assign("detailcreator",$oleh);
$tpl->assign("detailtanggal",$tanggal);

if(!empty($gambar)) $tpl->assign("detailgambar","$domain/gambar/$kanal/$gambar");
if(!empty($gambarshare)) $tpl->assign("detailgambarshare","$domain/gambar/$kanal/$gambarshare");


//assign fasilitas penunjang
if($fasilitasprint) $tpl->assign("urlprint","$fulldomain/$kanal/print/$katid/$idcontent");
if($fasilitaspdf) $tpl->assign("urlpdf","$fulldomain/$kanal/pdf/$katid/$idcontent");
if($fasilitasemail) $tpl->assign("urlemail","$fulldomain/$kanal/kirim/$katid/$idcontent");
if($fasilitasarsip) $tpl->assign("urlarsip","$fulldomain/$kanal/arsip/$secalias");
if($fasilitasrss) $tpl->assign("urlrss","$fulldomain/$kanal/rss");

sql_free_result($hasil);

//Berita Terkait
$mysql = "select id,nama,alias,gambar,ringkas from tbl_$kanal where published='1' and id!='$id' $where order by create_date desc limit 5";
$hasil = sql( $mysql);

$terkait = array();
$a =1;		
while ($data =  sql_fetch_data($hasil)) 
{	
$nama = $data['nama'];
	$id = $data['id'];
	$alias = $data['alias'];
	$gambar = $data['gambar'];
	$ringkas = $data['ringkas'];
	
	$ringkas = substr($ringkas,0,120);
	
	if(!empty($gambar)) $gambar = "$domain/gambar/$kanal/$gambar";
	 else $gambar = "";
	
	$link = "$fulldomain/$kanal/read/$id/$alias.html";
		
	$terkait[$id] = array("id"=>$id,"a"=>$a,"nama"=>$nama,"url"=>$link,"gambar"=>$gambar,"ringkas"=>$ringkas);
	$a++;	
}
sql_free_result($hasil);
$tpl->assign("terkait",$terkait);


?>
