<?php 
//data cart
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

if($jumlah_cart > 0)
{
	$data2			= " SELECT transaksidetailid,produkpostid,jumlah,matauang,totalharga from tbl_transaksi_detail where transaksiid='$transaksiidc' order by transaksidetailid desc limit 3";
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
		$sql	= "select title,secid,subid from tbl_product_post where status='1' and produkpostid='$idprodukd' and published='1'";
		$query	= sql($sql);
		$row	= sql_fetch_data($query);
		$namaprodukd		= $row["title"];
		$subidd				= $row['subid'];
		$secidd				= $row['secid'];
		$aliasd				= getAlias($namaprodukd);
		sql_free_result($query);
		
		// album
		$gambarprodukd		= sql_get_var("select gambar_s from tbl_product_image where produkpostid='$idprodukd' and gambar_s!='' order by albumid asc limit 1");
		
		if(!empty($gambarprodukd))
			$gambarprodukd	= "$fulldomain/gambar/produk/$idprodukd/$gambarprodukd";
		else
			$gambarprodukd	= $fulldomain.$lokasiwebtemplate."images/no_photo.gif";
		
		$aliassecd		= getAliasSec($secidd);
		$aliassubd		= getAliasSub($subidd);
		
		$urld			= "$fulldomain/katalog/detail/$aliassecd/$aliassubd/$idprodukd/$aliasd.html";
		
		$topcart[$idd] = array("idd"=>$idd,"namaproduk"=>$namaprodukd,"qtyd"=>$qtyd,"gambarprodukd"=>$gambarprodukd,"misc_hargaprodukd"=>"$misc_matauangd $misc_hargaprodukd",
						"idprodukd",$idprodukd,"classd"=>$classd,"urld"=>$urld);
	}
	sql_free_result($hasil2);
	$tpl->assign("topcart",$topcart);
}
?>