<?php 
$section = array();
$h = 1;
$perintah = "select secid,nama,keterangan,alias from tbl_blog_sec order by secid asc";
$hasil = sql($perintah);
while ($data =  sql_fetch_data($hasil))
{
	$secid = $data['secid'];
	$namamenu = $data['nama'];
	$aliasmenu = $data['alias'];
	$ketmenu = $data['keterangan'];
	
	$urlmenu = "$fulldomain/blog/list/$aliasmenu";

	$section[$secid] = array("id"=>$secid,"h"=>$h,"nama"=>$namamenu,"urlmenu"=>$urlmenu);
	$h %= 2;
	$h++;

}
sql_free_result($hasil);
$tpl->assign("blogsection",$section);
unset($section);

//Kategori Konsultasi
$section = array();
$h = 1;
$perintah = "select secid,nama,keterangan,alias from tbl_konsultasi_sec order by secid asc";
$hasil = sql($perintah);
while ($data =  sql_fetch_data($hasil))
{
	$secid = $data['secid'];
	$namamenu = $data['nama'];
	$aliasmenu = $data['alias'];
	$ketmenu = $data['keterangan'];
	
	$urlmenu = "$fulldomain/konsultasi/list/$aliasmenu";

	$section[$secid] = array("id"=>$secid,"h"=>$h,"nama"=>$namamenu,"urlmenu"=>$urlmenu);
	$h %= 2;
	$h++;

}
sql_free_result($hasil);
$tpl->assign("konsultasisection",$section);
unset($section);


//Kategori Direktori
$mysql = "select secid,namasec,alias from tbl_world_sec order by secid  asc";
$hasil = sql( $mysql);

$worldsec = array();		
$i = 0;
while ($data =  sql_fetch_data($hasil)) {	
	$namasec = $data['namasec'];
	$secids = $data['secid'];
	$alias = $data['alias'];

	$urlsec = "$fulldomain/world/cat/$alias";
	
		//Sub Kategori Direktori
		$mysql2 = "select subid,namasub,alias from tbl_world_sub where secid='$secids' order by subid  asc";
		$hasil2 = sql($mysql2);
		
		$worldsub = array();		
		while ($dt =  sql_fetch_data($hasil2)) {	
			$namasub = $dt['namasub'];
			$subids = $dt['subid'];
			$aliassub = $dt['alias'];
		
			$urlsub = "$fulldomain/world/cat/$alias/$aliassub";
			
				
			$worldsub[] = array("subid"=>$subids,"namasub"=>$namasub,"urlsub"=>$urlsub);
		}
		sql_free_result($hasil2);
	
		
	$worldsec[$secids] = array("secid"=>$secids,"namasec"=>$namasec,"urlsec"=>$urlsec,"sub"=>$worldsub);
	$i++;
	unset($worldsub);
		
}
sql_free_result($hasil);
$tpl->assign("worldsec",$worldsec);

//Kategori Direktori
$mysql = "select secid,namasec,alias from tbl_product_sec order by secid  asc";
$hasil = sql( $mysql);

$productsec = array();		
$i = 0;
while ($data =  sql_fetch_data($hasil)) {	
	$namasec = $data['namasec'];
	$secids = $data['secid'];
	$alias = $data['alias'];

	$urlsec = "$fulldomain/product/category/$alias";
	
		//Sub Kategori Direktori
		$mysql2 = "select subid,namasub,alias from tbl_product_sub where secid='$secids' order by subid  asc";
		$hasil2 = sql($mysql2);
		
		$productsub = array();		
		while ($dt =  sql_fetch_data($hasil2)) {	
			$namasub = $dt['namasub'];
			$subids = $dt['subid'];
			$aliassub = $dt['alias'];
		
			$urlsub = "$fulldomain/product/cat/$alias/$aliassub";
			
				
			$productsub[] = array("subid"=>$subids,"namasub"=>$namasub,"urlsub"=>$urlsub);
		}
		sql_free_result($hasil2);
	
		
	$productsec[$secids] = array("secid"=>$secids,"namasec"=>$namasec,"urlsec"=>$urlsec,"sub"=>$productsub);
	$i++;
	unset($productsub);
		
}
sql_free_result($hasil);
$tpl->assign("productsec",$productsec);


/*$mysql = "select produkpostid,ringkas,title,create_date,alias from tbl_world_post where published='1' order by produkpostid  asc";
$hasil = sql( $mysql);

$menuproduk = array();		
$i = 0;
while ($data =  sql_fetch_data($hasil)) {	
	$tanggal = $data['create_date'];
	$nama    = $data['title'];
	$ids     = $data['produkpostid'];
	$ringkas = $data['ringkas'];
	$alias   = $data['alias'];
	$tanggal = tanggal($tanggal);
	$secid   = $data['secid'];

		 

	$link = "$fulldomain/produk/read/$ids/$alias";
		
	$menuproduk[$ids] = array("id"=>$ids,"no"=>$i,"namasec"=>$namasec,"urlsec"=>$urlsec,"nama"=>$nama,"ringkas"=>$ringkas,"tanggal"=>$tanggal,"alias"=>$alias,"link"=>$link,"gambar"=>$gambar);
	$i++;
		
}
sql_free_result($hasil);
$tpl->assign("menuproduk",$menuproduk);*/




?>