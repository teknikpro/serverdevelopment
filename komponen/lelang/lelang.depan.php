<?php  
$sqlp	= "select produkpostid,secid,subid,brandid,title,ringkas,harga from tbl_product_post  where	published='1' and pilihan='1' order by rand() limit 4";
$queryp	= sql($sqlp);
$nump	= sql_num_rows($queryp);

$dataprodukdepan	= array();
$no	= 1;
while($row2 = sql_fetch_data($queryp))
{
		$produkpostid	= $row2['produkpostid'];
		$secId			= $row2['secid'];
		$subId			= $row2['subid'];
		$aliasSecc		= getAliasSec($secId);
		$aliasSubb		= getAliasSub($subId);
		$namaprod		= ucwords($row2["title"]);
		$alias			= getAlias($namaprod);
		$ringkas		= bersih($row2['ringkas']);
		$ringkas = ringkas($ringkas,20);
		$pilihan		= $row2['pilihan'];
		$harga		= $row2['harga'];
		$screenshot		= $row2['screenshot'];
		$harga 	= $row2['harga'];
		
		$harga = rupiah($harga);

	
		if(!empty($icon))
			$icons	= "$fulldomain/gambar/produk/$produkpostid/$icon";
		else
			$icons	= "";
		
		
		if($jenisvideo==1)
		{
			if(!empty($screenshot))
			{
				$image_m	= "$fulldomain/gambar/produk/$produkpostid/$screenshot";
				$image_l	= "$fulldomain/gambar/produk/$produkpostid/$screenshot";
			}
			else
			{
				$image_m	= $fulldomain.$lokasiwebtemplate."images/no_photo.gif";
				$image_l	= $fulldomain.$lokasiwebtemplate."images/no_photo.gif";
			}
		}
		else
		{
			$sql3	= "select albumid,gambar_m,gambar_l from tbl_product_image where produkpostid='$produkpostid' order by albumid asc limit 1";
			$query3	= sql($sql3);
			$row3	= sql_fetch_data($query3);
			$albumid	= $row3['albumid'];
			$gambar_m	= $row3['gambar_m'];
			$gambar_l	= $row3['gambar_m'];
			sql_free_result($query3);
			
			if(!empty($gambar_m))
				$image_m	= "$fulldomain/gambar/produk/$produkpostid/$gambar_m";
			else
				$image_m	= $fulldomain.$lokasiwebtemplate."images/no_photo.gif";
				
			if(!empty($gambar_l))
				$image_l	= "$fulldomain/gambar/produk/$produkpostid/$gambar_l";
			else
				$image_l	= $fulldomain.$lokasiwebtemplate."images/no_photo.gif";
		}
		
		$link_detail	= "$fulldomain/product/read/$produkpostid/$alias";
		$link_buy		= "$fulldomain/quickbuy/addpost/$produkpostid/$alias";
		
		$dataprodukdepan[$produkpostid]	= array("produkpostid"=>$produkpostid,"namaprod"=>$namaprod,"image_m"=>$image_m,"link_detail"=>$link_detail,"ringkas"=>$ringkas,
									"price"=>$misc_harga,"harga"=>$hargaya,"savenya"=>$savenya,"save"=>$sDiskon,"misc_matauang"=>$misc_matauang,"no"=>$no,
									"pilihan"=>$pilihan,"diskon"=>$harga,"link_buy"=>$link_buy,"harga"=>$harga,"harga"=>$harga,"secid"=>$secId,
									"misc_harga_reseller"=>$misc_harga_reseller,"hargares"=>$misc_hargares2,"wishlist"=>$wishlist,"icon"=>$icons);
		$no++;
	}
	sql_free_result($queryp);

$tpl->assign("dataprodukdepan",$dataprodukdepan);

?>