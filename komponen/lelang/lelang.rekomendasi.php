<?php
if(!empty($rekomendasiproduk))
{
	$where = " and produkpostid='$rekomendasiproduk'";
}
else
{
	$where = "";
}
$productpil 	= array();
$h 				= 1;
$perintah 	= "select produkpostid,title,misc_harga,misc_diskon,misc_matauang,ringkas from tbl_product_post where  status='1' and published='1' $where order by rand() asc limit 1";


$hasil 		= sql($perintah);
while ($data =  sql_fetch_data($hasil))
{
	$produkpostid = $data['produkpostid'];
	$namaprod1    = ucwords($data["title"]);
	$namaprod     = substr($namaprod1, -100, 37);
	$alias        = getAlias($namaprod);
	$misc_diskon 	= $data['misc_diskon'];
	$misc_harga 	= $data['misc_harga'];
	$misc_matauang 	= $data['misc_matauang'];
	$ringkas = $data['ringkas'];
	
	$sDiskon		= 0;
	$hDiskon		= 0;
	
	if($misc_diskon!=0)
	{
		$hDiskon		= $misc_diskon;
		$sDiskon		= $misc_harga - $misc_diskon;
	}
	
	$misc_harga		= $misc_matauang." ". number_format($misc_harga,0,".",".");
	$misc_diskonnya	= $misc_matauang." ". number_format($hDiskon,0,".",".");
	$savenya		= $misc_matauang." ". number_format($sDiskon,0,".",".");
	
	$gambar_m	= sql_get_var("select gambar_m from tbl_product_image where produkpostid='$produkpostid' order by albumid asc limit 1");
	
	if(!empty($gambar_m))
		$image_m	= "$fulldomain/gambar/produk/$produkpostid/$gambar_m";
	else
		$image_m	= $fulldomain.$lokasiwebtemplate."images/no_photo.gif";
	
	$urlmenu	= "/product/read/$produkpostid/$alias";

	$productpil[$produkpostid] = array("id"=>$produkpostid,"h"=>$h,"nama"=>$namaprod,"ringkas"=>$ringkas,"urlmenu"=>$urlmenu,"price"=>$misc_harga,"misc_diskon"=>$misc_diskonnya,"savenya"=>$savenya,"save"=>$sDiskon,"misc_matauang"=>$misc_matauang,"diskon"=>$misc_diskon,"misc_diskonn"=>$sDiskon,"gambar"=>$image_m);
	$h %= 2;
	$h++;
}
sql_free_result($hasil);
$tpl->assign("productpil",$productpil);

?>