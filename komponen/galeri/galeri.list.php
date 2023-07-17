<?php 
$hlm = $katid;
$judul_per_hlm = 6;
$batas_paging = 5;

$sql = "select count(*) as jml from tbl_$kanal"."_sec";
$hsl = sql($sql);
$tot = sql_result($hsl, 0, jml);
$hlm_tot = ceil($tot / $judul_per_hlm);		
if (empty($hlm)){
	$hlm = 1;
	}
if ($hlm > $hlm_tot){
	$hlm = $hlm_tot;
	}


$ord = ($hlm - 1) * $judul_per_hlm;
if ($ord < 0 ) $ord=0;


$mysql = "select secid,nama,ringkas,alias,create_date from tbl_$kanal"."_sec  order by secid desc limit $ord, $judul_per_hlm";
$hasil = sql($mysql);

$datagaleri = array();
while ($data = sql_fetch_data($hasil))
{

	$nama = $data['nama'];
	$secid = $data['secid'];
	$ringkas = ringkas($data['ringkas'],20);
	$alias = $data['alias'];
	$create_date = $data['create_date'];
	
	$mysql1 = "select gambar,id from tbl_$kanal where gambar!='' and secid='$secid' order by id desc limit 1";
	$hasil1 = sql($mysql1);
	$data1 = sql_fetch_data($hasil1);
	
	$gambar = $data1['gambar'];
	$idgambar = $data1['id'];
	if(!empty($gambar))
	{
		$datagaleri[$secid] = array("id"=>$id,"nama"=>$nama,"ringkas"=>$ringkas,"tanggal"=>tanggal($create_date),"gambar"=>"$domain/gambar/galeri/$gambar","url"=>"$fulldomain/$kanal/read/$secid/$idgambar/$alias");
	}

}
$tpl->assign("datadetail",$datagaleri);
sql_free_result($hasil);


//Paging 
$batas_page =5;

$stringpage = array();
$pageid =0;

if ($hlm > 1){
	$prev = $hlm - 1;
	$stringpage[$pageid] = array("nama"=>"Awal","link"=>"$domainfull/$kanal/list/1");
	$pageid++;
	$stringpage[$pageid] = array("nama"=>"Sebelumnya","link"=>"$domainfull/$kanal/list/$prev");

}
else {
	$stringpage[$pageid] = array("nama"=>"Awal","link"=>"");
	$pageid++;
	$stringpage[$pageid] = array("nama"=>"Sebelumnya","link"=>"");
}

$hlm2 = $hlm - (ceil($batas_page/2));
$hlm4= $hlm+(ceil($batas_page/2));

if($hlm2 <= 0 ) $hlm3=1;
   else $hlm3 = $hlm2;
$pageid++;
for ($ii=$hlm3; $ii <= $hlm_tot and $ii<=$hlm4; $ii++){
	if ($ii==$hlm){
		$stringpage[$pageid] = array("nama"=>"$ii","link"=>"");
	}else{
		$stringpage[$pageid] = array("nama"=>"$ii","link"=>"$domainfull/$kanal/list/$ii");
	}
	$pageid++;
}
if ($hlm < $hlm_tot){
	$next = $hlm + 1;
	$stringpage[$pageid] = array("nama"=>"Selanjutnya","link"=>"$domainfull/$kanal/list/$next");
	$pageid++;
	$stringpage[$pageid] = array("nama"=>"Akhir","link"=>"$domainfull/$kanal/list/$hlm_tot");
}
else
{
	$stringpage[$pageid] = array("nama"=>"Selanjutnya","link"=>"");
	$pageid++;
	$stringpage[$pageid] = array("nama"=>"Akhir","link"=>"");
}
$tpl->assign("stringpage",$stringpage);
//Selesai Paging

?>
