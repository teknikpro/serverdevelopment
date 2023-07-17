<?php 
$id = $var[4];
$perintah = "select id,nama,ringkas,lengkap,gambar1,gambar,waktu,alias,views from tbl_$kanal where id='$id'";
$hasil = sql($perintah);
$data =  sql_fetch_data($hasil);

$idcontent = $data['id'];
$nama=$data['nama'];
$oleh = $data['oleh'];
$lengkap= $data['lengkap'];
$tanggal = tanggaltok($data['waktu']); 
$gambar = $data['gambar1'];
$gambarshare = $data['gambar'];
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
$tpl->assign("detailcreator",$oleh);
$tpl->assign("detailtanggal",$tanggal);
$tpl->assign("detaildate",$data['create_date']);

if(!empty($gambar)) $tpl->assign("detailgambar","$domain/gambar/$kanal/$gambar");
if(!empty($gambarshare)) $tpl->assign("detailgambarshare","$domain/gambar/$kanal/$gambarshare");


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
$tpl->assign("detailviews",$stats);
$view = 1;

$views = "update tbl_$kanal set views=views+1 where id='$id'";
$hsl = sql($views);

// Terkait
$mysql = "select id,ringkas,nama,create_date,alias,gambar,gambar1,views from tbl_event where published='1'  and id!='$idcontent' order by id desc limit 6";
$hasil = sql($mysql);

$terkait = array();
$a =1;
while ($data = sql_fetch_data($hasil)) {	
		$tanggal = $data['create_date'];
		$nama = $data['nama'];
		$ids = $data['id'];
		$ringkas = $data['ringkas'];
		$ringkas = ringkas($data['ringkas'],22);
		$alias = $data['alias'];
		$tanggal1 = tanggal($tanggal);
		$gambar = $data['gambar'];
		$gambar1 = $data['gambar1'];
		$views = number_format($data['views'],0,",",".");
		
		if($a=="1") $gambar = $gambar1;
		else $gambar = $gambar;
		
		

	
		if(!empty($gambar)) $gambar = "$domain/gambar/event/$gambar";
			 else $gambar = "";
		$link = "$fulldomain/event/read/$ids/$alias";
			
		$terkait[$ids] = array("id"=>$ids,"no"=>$a,"nama"=>$nama,"ringkas"=>$ringkas,"namasec"=>$namasec,"tanggal"=>$tanggal1,"views"=>$views,"alias"=>$alias,"link"=>$link,"gambar"=>$gambar);
		$a++;	
}
$a = 0;
sql_free_result($hasil);
$tpl->assign("terkait",$terkait);



?>
