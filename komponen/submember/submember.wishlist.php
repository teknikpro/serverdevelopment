<?php
$tpl->assign("namarubrik","Wishlist Produk");

	session_start();
	// if($aksi=="delete")
	if($var[4]=="delete")
	{
		$id = $var[5];
		// $id = $var[4];
		
		$sql	= "delete from tbl_wishlist where id='$id'";
		$query = sql($sql);
		
		if($query)
			header("location: $fulldomain/user/wishlist");
	}
	elseif($aksi == "buy")
	{
		$tipeid             = sql_get_var("SELECT id FROM tbl_product_tipe WHERE nama='Reguler'");
		$_SESSION['tipeid'] = $tipeid;

		// Detail Produk
		$produkpostid	= $_POST['produkpostid'];
		$qty			= 1;
		$id_qty 		= $_POST['id_qty'];
		$id				= $_POST['id'];
		
		$date = date("Y-m-d H:i:s");
		
		if(empty($_SESSION['orderid']) and $aksi == "buy")
		{
			$orderid = "SYGM-".date("U");
			session_start();
			$_SESSION['orderid'] = $orderid;
		}
		
		if(isset($produkpostid))
		{		
			$sql = "select title,misc_harga,body_weight,misc_diskon,misc_matauang,misc_harga_reseller from tbl_product_post where produkpostid = '$produkpostid'";
			$hsl = sql($sql);
			$row = sql_fetch_data($hsl);
			$title 		= $row["title"];
			$berat 		= $row['body_weight'];
			$diskon		= $row['misc_diskon'];
			$matauang	= $row['misc_matauang'];
			
			if($_SESSION['secid'] == '2')
			{
				$misc_harga = $row['misc_harga_reseller'];
			}
			else
			{
				$misc_harga = $row['misc_harga'];
			}
				
			if($diskon!=0)
			{
				$misc_harga		= $diskon;
			}
				
			$berat 	= $berat * $qty;
			$total 	= $misc_harga*$qty;	
			
			
			sql_free_result($hsl);
			
			// Input kedalam database
			$transaksiid = sql_get_var("select transaksiid from tbl_transaksi where orderid='$_SESSION[orderid]'");
			
			if (empty($transaksiid))
			{
				$new = newid("transaksiid","tbl_transaksi");
				
				$query	= ("insert into tbl_transaksi (`transaksiid`,`orderid`,`status`,userid,create_date) 
							values ('$new','$_SESSION[orderid]','0','$_SESSION[userid]','$date')");
				$hasil = sql($query);
				
				$newdetail = newid("transaksidetailid","tbl_transaksi_detail");
				$query1	= ("insert into tbl_transaksi_detail (`transaksidetailid`,`transaksiid`,`produkpostid`,`harga`,`matauang`,`totalharga`,`jumlah`,`berat`) 
							values ('$newdetail','$new','$produkpostid','$misc_harga','$matauang','$total','$qty','$berat')");
				$hasil1 = sql($query1);
			}
			else
			{
				$transaksidetailid = sql_get_var("select transaksidetailid from tbl_transaksi_detail where transaksiid='$transaksiid' and produkpostid='$produkpostid'");
				if (empty($transaksidetailid))
				{
					$newdetail = newid("transaksidetailid","tbl_transaksi_detail");
					$query1	= ("insert into tbl_transaksi_detail (`transaksidetailid`,`transaksiid`,`produkpostid`,`harga`,`matauang`,`totalharga`,`jumlah`,`berat`) 
								values ('$newdetail','$transaksiid','$produkpostid','$misc_harga','$matauang','$total','$qty','$berat')");
					$hasil1 = sql($query1);
				}
			}
		}
		
		$sql	= "delete from tbl_wishlist where id='$id'";
		$query = sql($sql);
		
		if($query)
			header("location: $fulldomain/cart/buy");
	}
			
	// Tampilkan dalam database
	$i = 1;
	$a = 1;
	$sql = "select id,produkpostid from tbl_wishlist where userid='$_SESSION[userid]' order by produkpostid desc ";
	$hsl = sql($sql);
	$jumlah_keranjang = sql_num_rows($hsl);
	$i = 1;
	$dt_keranjang = array();
	while ($row = sql_fetch_data($hsl))
	{
		$id 			= $row['id'];
		$produkpostid	= $row['produkpostid'];
		
		$sql2        = "select title,secid,subid,jenisvideo,screenshot,misc_harga_reseller,misc_harga, misc_diskon, misc_matauang from tbl_product_post where produkpostid='$produkpostid'";
		$query2      = sql($sql2);
		$row2        = sql_fetch_data($query2);
		$nama        = $row2["title"];
		$alias       = getAlias($nama);
		$secId       = $row2['secid'];
		$subId       = $row2['subid'];
		$upsellingid = $row2['upsellingid'];
		$aliasSecc   = getAliasSec($secId);
		$aliasSubb   = getAliasSub($subId);
		$jenisvideo  = $row2['jenisvideo'];
		$screenshot  = $row2['screenshot'];
		$upsellingid = $row2['upsellingid'];
		$diskon      = $row2['misc_diskon'];
		$matauang    = $row2['misc_matauang'];
		$misc_harga  = $row2['misc_harga'];
		
		$sDiskon		= "";
		
		if($diskon!=0)
		{
			$hDiskon		= $diskon;
			$sDiskon		= $misc_harga - $diskon;
		}
		
		$misc_harga_reseller = 0;
		if($_SESSION['secid'] == '2')
		{
			$misc_harga_reseller = $row2['misc_harga_reseller'];
			$misc_hargares1 = number_format($misc_harga_reseller,0,".",".");
			$misc_hargares2 = "$misc_matauang $misc_hargares1";
		}
		
	
		$harga2		= number_format($misc_harga,0,".",".");
		
		$harga2		= "$matauang. $harga2";
		
		$hargadiskon		= number_format($hDiskon,0,".",".");
		
		$hargadiskon		= "$matauang. $hargadiskon";
	
		// album
		$gambar_s	= sql_get_var("select gambar_s from tbl_product_image where produkpostid='$produkpostid' order by albumid asc limit 1");

		if(!empty($gambar_s))
			$image_s	= "$fulldomain/gambar/produk/$produkpostid/$gambar_s";
		else
			$image_s	= $fulldomain.$lokasiwebtemplate."images/no_photo.gif";

		/*$harga1 = number_format($harga,0,".",".");
		$harga2 = "$matauang. $harga1";*/
		
		$totalstok	= sql_get_var("select totalstok from tbl_product_total where produkpostid='$produkpostid'");
		
		$link_detail	= "$fulldomain/product/read/$produkpostid/$alias";
		// $link_detail	= "$fulldomain/produk/read/$aliasSecc/$aliasSubb/$produkpostid/$alias";

		$dt_keranjang[$id] = array("id"=>$id,"nama"=>$nama,"produkpostid"=>$produkpostid,"berat"=>$berat,"volume"=>$volume,"image_s"=>$image_s,"harga"=>$harga2,"qty"=>$qty,
							"subtotal"=>$total,"diskon"=>$diskon,"a"=>$a,"hargadiskon"=>$hargadiskon,"link_produk"=>$link_detail,"totalstok"=>$totalstok,"misc_harga_reseller"=>$misc_harga_reseller,"hargares"=>$misc_hargares2,);
		$i %= 2;
		$i++;
		$a++;
	}
	sql_free_result ($hsl);
	$tpl->assign("dt_keranjang",$dt_keranjang);
	$tpl->assign("jumlah_keranjang",$jumlah_keranjang);

?>