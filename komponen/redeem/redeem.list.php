<?php 
$hlm = $var[5];
$judul_per_hlm = 100;

$sql = "select count(*) as jml from tbl_$kanal where published='1' $where";
$hsl = sql( $sql);
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

$mysql = "select id,ringkas,nama,create_date,alias,gambar,gambar1,startdate,enddate,poin from tbl_$kanal where published='1' order by id  desc limit $ord, $judul_per_hlm";
$hasil = sql( $mysql);


$datadetail = array();		
$i = 0;
$no  = 1;
while ($data =  sql_fetch_data($hasil)) {	
	$tanggal = $data['create_date'];
	$nama = $data['nama'];
	$id = $data['id'];
	$ringkas = ringkas($data['ringkas'],20);
	$alias = $data['alias'];
	$poin = $data['poin'];
	
	$tanggal = tanggal($tanggal);
	$gambar = $data['gambar'];
	$secid = $data['secid'];
	$startdate = tanggalonly($data['startdate']);
	$enddate =tanggalonly( $data['enddate']);
	
	if(!empty($gambar)) $gambar = "$domain/gambar/$kanal/$gambar";
	 else $gambar = "";
		 

	$link = "$fulldomain/$kanal/read/$id/$alias";
		
	$datadetail[$id] = array("id"=>$id,"i"=>$i,"no"=>$no,"nama"=>$nama,"ringkas"=>$ringkas,"tanggal"=>$tanggal,"startdate"=>$startdate,"enddate"=>$enddate,"poin"=>$poin,"link"=>$link,"gambar"=>$gambar);
	$i++;
	$no++;
		
}
sql_free_result($hasil);
$tpl->assign("datadetail",$datadetail);

//Paging 
$batas_page =5;

$stringpage = array();
$pageid =0;

if ($hlm > 1){
	$prev = $hlm - 1;
	$stringpage[$pageid] = array("nama"=>"Awal","link"=>"$domainfull/$kanal/$aksi/$secalias/1");
	$pageid++;
	$stringpage[$pageid] = array("nama"=>"Sebelumnya","link"=>"$domainfull/$kanal/$aksi/$secalias/$prev");

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
		$stringpage[$pageid] = array("nama"=>"$ii","link"=>"$domainfull/$kanal/$aksi/$secalias/$ii");
	}
	$pageid++;
}
if ($hlm < $hlm_tot){
	$next = $hlm + 1;
	$stringpage[$pageid] = array("nama"=>"Selanjutnya","link"=>"$domainfull/$kanal/$aksi/$secalias/$next");
	$pageid++;
	$stringpage[$pageid] = array("nama"=>"Akhir","link"=>"$domainfull/$kanal/$aksi/$secalias/$hlm_tot");
}
else
{
	$stringpage[$pageid] = array("nama"=>"Selanjutnya","link"=>"");
	$pageid++;
	$stringpage[$pageid] = array("nama"=>"Akhir","link"=>"");
}
$tpl->assign("stringpage",$stringpage);
		
?>