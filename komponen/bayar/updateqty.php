<?php
	header('Content-Type: application/json');
	include("../../setingan/web.config.inc.php");
	session_start();
	
	$transaksidetailid 	= $_POST["id"];
	$produkpostid 	= $_POST["productId"];
    $jum_qty		= $_POST["qty"];
	$upselling		= $_POST["upselling"];
	$transaksiid	= $_POST["transaksiid"];
	
	if($upselling == 1)
	{
		$harga	= $_POST["harga"];
		$sql = "select body_weight,misc_matauang from tbl_product_post where produkpostid = '$produkpostid'";
		$hsl = sql($sql);
		$row = sql_fetch_data($hsl);
		$berat		= $row['body_weight'];
		$matauang		= $row['misc_matauang'];
		
		$berat 		= $berat * $jum_qty;
		$subtotal 	= $harga * $jum_qty;
		
		$transaksidetailid = sql_get_var("select transaksidetailid from tbl_transaksi_detail where transaksiid='$transaksiid' and produkpostid='$produkpostid'");
		
		if(empty($transaksidetailid))
		{
			$newdetail = newid("transaksidetailid","tbl_transaksi_detail");
			$query1	= ("insert into tbl_transaksi_detail (`transaksidetailid`,`transaksiid`,`produkpostid`,`harga`,`matauang`,`totalharga`,`jumlah`,`berat`,`upselling`) 
						values ('$newdetail','$transaksiid','$produkpostid','$harga','$matauang','$subtotal','$jum_qty','$berat','1')");
		}
		else
		{
			if($jum_qty == 0)
				$query1	= "delete from tbl_transaksi_detail where transaksidetailid='$transaksidetailid' and transaksiid='$transaksiid' and produkpostid='$produkpostid'";
			else
				$query1	= "update tbl_transaksi_detail set `jumlah`='$jum_qty',`berat`='$berat',`totalharga`='$subtotal' where transaksidetailid='$transaksidetailid'";
		}
		$hasil1 = sql($query1);
	}
	else
	{
		$up = sql_get_var("select upselling from tbl_transaksi_detail where transaksidetailid='$transaksidetailid'");
    	
		$sql = "select produkpostid,body_weight,misc_diskon,misc_matauang,misc_harga_reseller,misc_harga from tbl_product_post where produkpostid = '$produkpostid'";
		$hsl = sql($sql);
		$row = sql_fetch_data($hsl);
		$produkpostid = $row['produkpostid'];
		$berat		= $row['body_weight'];
		$matauang		= $row['misc_matauang'];
		
		if($up == 1)
		{
			$diskon		= 0;
			if($row['misc_diskon'] == 0)
				$harga 		= $row['misc_harga'];
			else
				$harga 		= $row['misc_diskon'];
		}
		else
		{
			$diskon		= $row['misc_diskon'];
			$harga 		= $row['misc_harga'];
		}
		
		if($diskon!=0)
			$harga			= $diskon;
	
		if($_SESSION['secid'] == 2)
			$harga = $row['misc_harga_reseller'];  
			
		sql_free_result($hsl);
		
		$berat 		= $berat * $jum_qty;
		$subtotal 	= $harga * $jum_qty;
		
		if($jum_qty == 0)
			$query	= "delete from tbl_transaksi_detail where transaksidetailid='$transaksidetailid' and transaksiid='$transaksiid' and produkpostid='$produkpostid'";
		else
			$query	= "update tbl_transaksi_detail set `jumlah`='$jum_qty',`berat`='$berat',`totalharga`='$subtotal' where transaksidetailid='$transaksidetailid'";
			
		$hasil	= sql($query);
	}
	
	$totaltagihan = sql_get_var(" SELECT SUM(totalharga) as total_subtotal from tbl_transaksi_detail where transaksiid='$transaksiid'");
	
	//tampilkan diskon voucher
	$qryv = sql(" SELECT totaldiskon, totaltagihanafterdiskon from tbl_transaksi where transaksiid='$transaksiid'");
	$rowv = sql_fetch_data($qryv);
	$totaldiskon = $rowv['totaldiskon'];
	$totaltagihanafterdiskon = $totaltagihan-$totaldiskon;
	
	//update total tagihan di tbl_transaksi
	sql("update tbl_transaksi set totaltagihan='$totaltagihan', totaltagihanafterdiskon='$totaltagihanafterdiskon' where transaksiid='$transaksiid'");
	
	if($totaltagihanafterdiskon==0)
		$totaltagihanakhir = $totaltagihan;
	else
		$totaltagihanakhir = $totaltagihanafterdiskon;
	
	$totaltagihan1 = number_format($totaltagihan,0,",",".");
	$totaltagihan2 = "Rp. $totaltagihan1";
	
	$totaltagihanakhir1 = number_format($totaltagihanakhir,0,",",".");
	$totaltagihanakhir2 = "Rp. $totaltagihanakhir1";
	
	$harga1 = number_format($subtotal,0,".",".");
	$harga2 = "Rp. $harga1";
	
	$desharga[]	= array("harga"=>$harga2,"subtotal"=>$totaltagihanakhir2,"subtotalval"=>$totaltagihanakhir);
	
	echo json_encode($desharga);
?>