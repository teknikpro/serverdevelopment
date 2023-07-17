<?php	
$transaksiid = sql_get_var("select transaksiid from tbl_transaksi where orderid='$_SESSION[orderid]'");

$tpl->assign("orderid",$_SESSION['orderid']);
		
// Tampilkan dalam database
$i = 1;
$a = 1;
$sql = "select * from tbl_transaksi_detail where transaksiid='$transaksiid' order by transaksidetailid desc ";
$hsl = sql($sql);
$jumlah_keranjang = sql_num_rows($hsl);
$i = 1;
$dt_keranjang = array();
while ($row = sql_fetch_data($hsl))
{
	$id 			= $row['transaksidetailid'];
	$produkpostid	= $row['produkpostid'];
	$qty 			= $row['jumlah'];
	$berat			= $row['berat'];
	$harga	 		= $row['harga'];
	$tipe			= $row['tipe'];

	$sql2	= "select title,secid,subid,jenisvideo,screenshot,harga,ringkas from tbl_product_post where produkpostid='$produkpostid'";
	$query2	= sql($sql2);
	$row2	= sql_fetch_data($query2);
	$nama			= $row2["title"];
	$ringkas			= ringkas($row2["ringkas"],20);
	$alias			= getAlias($nama);
	$secId			= $row2['secid'];
	$subId			= $row2['subid'];
	$aliasSecc		= getAliasSec($secId);
	$aliasSubb		= getAliasSub($subId);
	$jenisvideo		= $row2['jenisvideo'];
	$screenshot		= $row2['screenshot'];
	$misc_harga		= $row2['harga'];
	$diskon		= $row2['misc_diskon'];
	$link_produk	= "$fulldomain/product/read/$aliasSecc/$aliasSubb/$produkpostid/$alias";
	
	$total		= number_format($row['totalharga'],0,".",".");
	
	
	$total		= "$total";

	// album
	$gambar_s	= sql_get_var("select gambar_s from tbl_product_image where produkpostid='$produkpostid' order by albumid asc limit 1");
	
	if(!empty($gambar_s))
		$image_s	= "$fulldomain/gambar/produk/$produkpostid/$gambar_s";
	else
		$image_s	= $fulldomain.$lokasiwebtemplate."images/no_photo.gif";

	$harga1 = number_format($harga,0,".",".");
	$harga2 = "$harga1";
	
	
	


	$dt_keranjang[$id] = array("id"=>$id,"nama"=>$nama,"produkpostid"=>$produkpostid,"berat"=>$berat,"ringkas"=>$ringkas,"volume"=>$volume,"image_s"=>$image_s,"harga"=>$harga2,"qty"=>$qty,
						"subtotal"=>$total,"diskon"=>$diskon,"a"=>$a,"hargadiskon"=>$hargadiskon,"link_produk"=>$link_produk,"tipe"=>$tipe,"numupselling"=>$numup,"cv"=>$cv);
	$i %= 2;
	$i++;
	$a++;
}
sql_free_result ($hsl);
$tpl->assign("dt_keranjang",$dt_keranjang);
$tpl->assign("numprodup",$numprodup);
		
$totalberat = sql_get_var(" SELECT SUM(berat) as total_berat from tbl_transaksi_detail where transaksiid='$transaksiid'");

$totaltagihan = sql_get_var(" SELECT SUM(totalharga) as total_subtotal from tbl_transaksi_detail where transaksiid='$transaksiid'");

//update total tagihan di tbl_transaksi
sql("update tbl_transaksi set totaltagihan='$totaltagihan' where transaksiid='$transaksiid'");


$totaldiskon = $rowv['totaldiskon'];
$totaltagihanafterdiskon = $totaltagihan-$totaldiskon;

//update total tagihan di tbl_transaksi
sql("update tbl_transaksi set totaltagihanafterdiskon='$totaltagihanafterdiskon' where transaksiid='$transaksiid'");


if($totaltagihanafterdiskon==0)
	$totaltagihanakhir = $totaltagihan+$ongkoskirim;
else
	$totaltagihanakhir = $totaltagihanafterdiskon+$ongkoskirim;

$totaltagihan1 = number_format($totaltagihan,0,",",".");
$totaltagihan2 = "$matauang. $totaltagihan1";

$totaltagihanakhir1 = number_format($totaltagihanakhir,0,",",".");
$totaltagihanakhir2 = "$matauang. $totaltagihanakhir1";

$totaldiskon1 = number_format($totaldiskon,0,",",".");
$totaldiskon2 = "$matauang. $totaldiskon1";

$tpl->assign("totalberat",$totalberat);
$tpl->assign("total_subtotal",$totaltagihan2);
$tpl->assign("total_subtotalx",$totaltagihan);
$tpl->assign("totaltagihanakhir",$totaltagihanakhir2);
$tpl->assign("total_subtotalxx",$totaltagihanakhir);
$tpl->assign("jumlah_keranjang",$jumlah_keranjang);
$tpl->assign("kodevoucher",$kodevoucher);
$tpl->assign("namavoucher",$namavoucher);
$tpl->assign("totaldiskonn",$totaldiskon);
$tpl->assign("totaldiskon",$totaldiskon2);
$tpl->assign("transaksiid",$transaksiid);

//data cart untuk header
$qrycart		= sql("select transaksiid, totaltagihan from tbl_transaksi where orderid='$_SESSION[orderid]'");
$rowcart		= sql_fetch_data($qrycart);
$transaksiidc	= $rowcart['transaksiid'];
$total_cart		= $rowcart['totaltagihan'];


$jumlah_cart 	= sql_get_var("select count(*) as jml from tbl_transaksi_detail where transaksiid='$transaksiidc'");

$total_cart1 	= number_format($total_cart,0,",",".");
$total_cart2 	= "$total_cart1";

$tpl->assign("total_cart",$total_cart2);
$tpl->assign("jumlah_cart",$jumlah_cart);	

$sekarang		= tanggaltok(date("Y-m-d H:i:s"));
$tpl->assign("sekarang",$sekarang);	

if($jumlah_cart!=0)
{
	$data2			= " SELECT transaksidetailid,produkpostid,jumlah,matauang,totalharga from tbl_transaksi_detail where transaksiid='$transaksiid' order by transaksidetailid desc limit 3";
	$hasil2			= sql($data2);
	$topcart	 	= array();
	$no				= 1;
	while ($row2	= sql_fetch_data($hasil2))
	{
		$idprodukd	 	= $row2['produkpostid'];
		$qtyd		 	= $row2['jumlah'];
		$idd		 	= $row2['transaksidetailid'];
		$misc_matauangd	= $row2['matauang'];
		$misc_hargaprodukd	= number_format($row2['totalharga'],0,".",".");
	
		//data produk
		$sql	= "select title,secid,subid from tbl_product_post where status='1' and 
				produkpostid='$idprodukd' and published='1'";
		$query	= sql($sql);
		$row	= sql_fetch_data($query);
		$namaprodukd		= $row["title"];
		$subidd				= $row['subid'];
		$secidd				= $row['secid'];
		$aliasd				= getAlias($namaprodukd);
		sql_free_result($query);
		
		// album
		$sql1		= "select albumid,gambar_s from tbl_product_image where produkpostid='$idprodukd' and gambar_s!='' order by albumid asc limit 1";
		$query1		= sql($sql1);
		$row1 		= sql_fetch_data($query1);
		$albumid		= $row1['albumid'];
		$gambarprodukd	= $row1['gambar_s'];
		
		if(!empty($gambarprodukd))
			$gambarprodukd	= "$fulldomain/gambar/produk/$idprodukd/$gambarprodukd";
		else
			$gambarprodukd	= $fulldomain.$lokasiwebtemplate."images/no_photo.gif";
		sql_free_result($query1);	
		
		$aliassecd		= getAliasSec($secidd);
		$aliassubd		= getAliasSub($subidd);
		
		$urld			= "$fulldomain/produk/read/$aliassecd/$aliassubd/$idprodukd/$aliasd.html";
		
		$topcart[$idd] = array("idd"=>$idd,"namaproduk"=>$namaprodukd,"qtyd"=>$qtyd,"gambarprodukd"=>$gambarprodukd,"misc_hargaprodukd"=>"$misc_matauangd $misc_hargaprodukd",
						"idprodukd",$idprodukd,"classd"=>$classd,"urld"=>$urld);
	}
	sql_free_result($hasil2);
	$tpl->assign("topcart",$topcart);
}
?>