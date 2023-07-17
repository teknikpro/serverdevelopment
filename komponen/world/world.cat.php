<?php 
//Cek Detail Atau Bukan
$alias = $var[3];
if($alias!="cat" && !empty($alias))
{
	$id = sql_get_var("select id from tbl_world where alias='$alias'");
	if(!empty($id))
	{
		$aksi = "read";
		$tpl->assign("aksi","read");
		
		include("$kanal.read.php");
	}
}
else
{
$katid = $var[4];
$katid = str_replace(".html","",$katid);

$subid = $var[5];
$subid = str_replace(".html","",$subid);

if(!empty($katid))
{
	$perintah1 = "select * from tbl_world_sec where alias='$katid'";
	$hasil1 = sql($perintah1);
	$row1 = sql_fetch_data($hasil1);
	$namasec = $row1['namasec'];
	$secid = $row1['secid'];
	$secalias = $row1['alias'];
	$where = "and secid='$secid'";
	$rubrik = "$namasec";
	$tpl->assign("rubrik","$rubrik");
	$tpl->assign("arsip","$fulldomain/world/arsip/$secalias/");
}
else
{
	$secalias = "all";
	$rubrik = "$rubrik";
	$tpl->assign("rubrik","$rubrik");
	$tpl->assign("arsip","$fulldomain/world/arsip/$secalias/");
}

$tpl->assign("secalias","$secalias");

if(!empty($subid) && $subid!="all")
{
	$perintah1 = "select * from tbl_world_sub where alias='$subid'";
	$hasil1 = sql($perintah1);
	$row1 = sql_fetch_data($hasil1);
	$namasub = $row1['namasub'];
	$subid = $row1['subid'];
	$subalias = $row1['alias'];
	$where .= "and subid='$subid'";
	$rubrik = "$namasec - $namasub";
	$tpl->assign("rubrik","$rubrik");
	$tpl->assign("arsip","$fulldomain/world/arsip/$secalias/");
}
else
{
	$subalias = "all";
	$rubrik = "$rubrik";
	$tpl->assign("rubrik","$rubrik");
	$tpl->assign("arsip","$fulldomain/world/arsip/$secalias/");
}
$tpl->assign("subalias","$subalias");


//Urut
if(isset($_POST['urut']))
{
	$urut = $_POST['urut'];
	
	if(!empty($urut))
	{
		if($urut=="1") { $orderby = "order by views desc"; }
		else if($urut=="2") {	$orderby = "order by id desc"; }
		else if($urut=="3") {	$orderby = "order by nama asc"; }
		else if($urut=="4") {	$orderby = "order by nama desc"; }
		else  { $orderby = "order by id desc"; }
	}
	$tpl->assign("urut",$urut);
	$_SESSION['urut'] = $urut;
}
else
{
	if(empty($_SESSION['urut'])) $urut = 1;
	else $urut = $_SESSION['urut'];
	
	if(!empty($urut))
	{
		if($urut=="1") { $orderby = "order by views desc"; }
		else if($urut=="2") {	$orderby = "order by id desc"; }
		else if($urut=="3") {	$orderby = "order by nama asc"; }
		else if($urut=="4") {	$orderby = "order by nama desc"; }
		else  { $orderby = "order by id desc"; }
	}
	$tpl->assign("urut",$urut);
}

$tpl->assign("par",$par);


$judul_per_hlm = 6;
$batas_paging = 5;
$hlm = $var[6];

$sql = "select count(*) as jml from tbl_$kanal where published='1' $where $orderby";
$hsl = sql( $sql);
$tot = sql_result($hsl, 0, 'jml');
$hlm_tot = ceil($tot / $judul_per_hlm);		
if (empty($hlm)){
	$hlm = 1;
	}
if ($hlm > $hlm_tot){
	$hlm = $hlm_tot;
	}

$ord = ($hlm - 1) * $judul_per_hlm;
if ($ord < 0 ) $ord=0;

$mysql = "select id,ringkas,nama,create_date,alias,secid,gambar,gambar1 from tbl_$kanal where published='1' $where $orderby  limit $ord, $judul_per_hlm";
$hasil = sql( $mysql);


$datadetail = array();		
$i = 0;
while ($data =  sql_fetch_data($hasil)) {	
	$tanggal = $data['create_date'];
	$nama = $data['nama'];
	$id = $data['id'];
	$ringkas = ringkas($data['ringkas'],20);
	$alias = $data['alias'];
	$tanggal1 = tanggal($tanggal);
	$gambar = $data['gambar'];
	$gambar1 = $data['gambar1'];
	$secid = $data['secid'];
	
	$perintah = "select alias,namasec from tbl_world_sec where secid='$secid'";
	$res = sql($perintah);
	$dt =  sql_fetch_data($res);
	$secalias = $dt['alias'];
	$namasec = $dt['namasec'];
	sql_free_result($res);

	
	if(!empty($gambar)) $gambar = "$domain/gambar/world/$gambar";
	 else $gambar = "";
		 

	$link = "$fulldomain/$kanal/$alias";
		
	$datadetail[$id] = array("id"=>$id,"no"=>$i,"nama"=>$nama,"ringkas"=>$ringkas,"tanggal"=>$tanggal1,"date"=>$tanggal,"alias"=>$alias,"link"=>$link,"gambar"=>$gambar,"namasec"=>$namasec);
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
	$stringpage[$pageid] = array("nama"=>"Awal","link"=>"$domainfull/$kanal/$aksi/$secalias/$subalias/1");
	$pageid++;
	$stringpage[$pageid] = array("nama"=>"Sebelumnya","link"=>"$domainfull/$kanal/$aksi/$secalias/$subalias/$prev");

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
		$stringpage[$pageid] = array("nama"=>"$ii","link"=>"$domainfull/$kanal/$aksi/$secalias/$subalias/$ii");
	}
	$pageid++;
}
if ($hlm < $hlm_tot){
	$next = $hlm + 1;
	$stringpage[$pageid] = array("nama"=>"Selanjutnya","link"=>"$domainfull/$kanal/$aksi/$secalias/$subalias/$next");
	$pageid++;
	$stringpage[$pageid] = array("nama"=>"Akhir","link"=>"$domainfull/$kanal/$aksi/$secalias/$subalias/$hlm_tot");
}
else
{
	$stringpage[$pageid] = array("nama"=>"Selanjutnya","link"=>"");
	$pageid++;
	$stringpage[$pageid] = array("nama"=>"Akhir","link"=>"");
}
$tpl->assign("stringpage",$stringpage);

}
		
?>
