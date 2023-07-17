<?php
$q = cleaninsert($_GET['q']);
$hlm = cleaninsert(antisql($_GET['hlm']));

$tpl->assign("q",$q);

$judul_per_hlm = 25;

$sql = "select count(*) as jml from tbl_search where nama like '%$q%'  or ringkas like '%$q%' ";
$hsl = sql( $sql);
$tot = sql_result($hsl, 0, jml);
$hlm_tot = ceil($tot / $judul_per_hlm);		
if (empty($hlm)){
	$hlm = 1;
	}
if ($hlm > $hlm_tot){
	$hlm = $hlm_tot;
	}

$tpl->assign("tot",$tot);
$tpl->assign("hlm",$hlm);
$tpl->assign("hlm_tot",$hlm_tot);

$ord = ($hlm - 1) * $judul_per_hlm;
if ($ord < 0 ) $ord=0;

$mysql = "select id,ringkas,nama,create_date,gambar,url,kanal from tbl_search where nama like '%$q%' or ringkas like '%$q%' order by urutan asc, create_date desc, views desc limit $ord, $judul_per_hlm";
$hasil = sql( $mysql);

$datadetail = array();		
$i = 0;
while ($data =  sql_fetch_data($hasil))
{	
	$tanggal = $data['create_date'];
	$nama = $data['nama'];
	$id = $data['id'];
	$ringkas = ringkas($data['ringkas'],20);
	$alias = $data['alias'];
	$tanggal1 = tanggal($tanggal);
	$gambar = $data['gambar'];
	$namasec = $data['kanal'];
	$url = $data['url'];
	
	$link = $url;
	

	$datadetail[$id] = array("id"=>$id,"no"=>$i,"nama"=>$nama,"ringkas"=>$ringkas,"tanggal"=>$tanggal1,"date"=>$tanggal,"link"=>$link,"url"=>$url,"gambar"=>$gambar,"namasec"=>$namasec);
	$i++;
		
}
sql_free_result($hasil);
$tpl->assign("datadetail",$datadetail);

//Paging 
$batas_page =5;

$stringpage = array();
$pageid =0;

if ($hlm > 1){
	$prev = $hlm - 1;
	$stringpage[$pageid] = array("nama"=>"Awal","link"=>"$domainfull/$kanal/?q=$q&hlm=1");
	$pageid++;
	$stringpage[$pageid] = array("nama"=>"Sebelumnya","link"=>"$domainfull/$kanal/?q=$q&hlm=$prev");

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
		$stringpage[$pageid] = array("nama"=>"$ii","link"=>"$domainfull/$kanal/?q=$q&hlm=$ii");
	}
	$pageid++;
}
if ($hlm < $hlm_tot){
	$next = $hlm + 1;
	$stringpage[$pageid] = array("nama"=>"Selanjutnya","link"=>"$domainfull/$kanal/?q=$q&hlm=$next");
	$pageid++;
	$stringpage[$pageid] = array("nama"=>"Akhir","link"=>"$domainfull/$kanal/?q=$q&hlm=$hlm_tot");
}
else
{
	$stringpage[$pageid] = array("nama"=>"Selanjutnya","link"=>"");
	$pageid++;
	$stringpage[$pageid] = array("nama"=>"Akhir","link"=>"");
}
$tpl->assign("stringpage",$stringpage);

if($i>0)
{
	//Rekam
	$j = sql_get_var("select count(*) as jml from tbl_cari where keyword='$q'");
	
	if($j<1)
	{
		$sql = sql("insert into tbl_cari(keyword,views) values('$q','1')");
	}
	else
	{
		$sql = sql("update tbl_cari set views=views+1 where keyword='$q'"); 
	}
}
