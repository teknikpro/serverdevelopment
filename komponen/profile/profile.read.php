<?php 
$id = $var[4];

$perintah = "select id,nama,oleh,ringkas,lengkap,gambar1,gambar,create_date,alias,file1,file2 from tbl_$kanal where id='$id'";
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

$file1 = $data['file1'];
$file2 = $data['file2'];

$extfile1 = str_replace(".","",substr($file1,-4,4));
$extfile2 = str_replace(".","",substr($file2,-4,4));
$namafile1 = str_replace("$kanal-$id-1-","",$file1);
$namafile2 = str_replace("$kanal-$id-2-","",$file2);

if(!empty($file1)){ $tpl->assign("extfile1",$extfile1); $tpl->assign("namafile1",$namafile1); $tpl->assign("file1","$domain/gambar/$kanal/upload/$file1"); } 
if(!empty($file2)){ $tpl->assign("extfile2",$extfile2); $tpl->assign("namafile2",$namafile2); $tpl->assign("file2","$domain/gambar/$kanal/upload/$file2"); } 


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


?>
