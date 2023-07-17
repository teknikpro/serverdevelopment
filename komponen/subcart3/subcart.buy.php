<?php
// Detail Produk
$produkpostid	= $_POST['produkpostid'];
$qty			= $_POST['qty'];
$id_qty 		= $_POST['id_qty'];

$date = date("Y-m-d H:i:s");

if(empty($_SESSION['orderid']))
{
	$orderid = "FS".date("U");
	$_SESSION['orderid'] = $orderid;
}

if(isset($produkpostid))
{	
	
	$transaksiid = sql_get_var("select transaksiid from tbl_transaksi where orderid='$_SESSION[orderid]'");
	$iscart = sql_get_var("select produkpostid from tbl_transaksi_detail where transaksiid='$transaksiid' and transaksiid!='' and produkpostid='$produkpostid'");
	
	if(!empty($iscart))
	{
				
		$stokawal	= sql_get_var("select stock from tbl_product_post where produkpostid='$produkpostid'");
		$namaprod	= sql_get_var("select title from tbl_product_post where produkpostid='$produkpostid'");
		
		$stokakhir	= $stokawal;
		
		if($qty > $stokakhir)
		{
			$benar = false;
		}
		else
			$benar = true;
			
	
		if($benar)
		{			
			$lastqty = sql_get_var("select jumlah from tbl_transaksi_detail where transaksiid='$transaksiid' and transaksiid!='' and produkpostid='$produkpostid'");
			$qty = $lastqty+1;
			
			$sql = "select title,harga,body_weight,harga from tbl_product_post where produkpostid = '$produkpostid'";
			$hsl = sql($sql);
			$row = sql_fetch_data($hsl);
			$title 		= $row["title"];
			$berat 		= $row['body_weight'];
			$harga 		= $row['harga'];
					
			$berat 	= $berat * $qty;
			$total 	= $harga * $qty;
			
			sql_free_result($hsl);
			
			$qty = intval($qty);			

			$query1	= "update tbl_transaksi_detail set  berat='$berat',jumlah='$qty',harga='$harga',totalharga='$total' where  transaksiid='$transaksiid' and transaksiid!='' and produkpostid='$produkpostid'";
			$hasil1 = sql($query1);
			
			
		}
		else
		{
			$salah = "Jumlah kuantitas produk $namaprod yang dibeli tidak mencukupi. Silahkan kurangi jumlah kuantitas produk anda untuk melanjutkan belanja.<br><br>\n";
			$tpl->assign("style","alert-danger");
			$tpl->assign("salah",$salah);
		}

	}
	else
	{
		
		$stokawal	= sql_get_var("select stock from tbl_product_post where produkpostid='$produkpostid'");
		$namaprod	= sql_get_var("select title from tbl_product_post where produkpostid='$produkpostid'");
		
		$stokakhir	= $stokawal;
		
		if($qty > $stokakhir)
		{
			$benar = false;
			//break;
		}
		else
			$benar = true;
			
		if($benar)
		{			
			$sql = "select title,harga,body_weight,harga from tbl_product_post where produkpostid = '$produkpostid'";
			$hsl = sql($sql);
			$row = sql_fetch_data($hsl);
			$title 		= $row["title"];
			$berat 		= $row['body_weight'];
			$harga 		= $row['harga'];
					
			$berat 	= $berat * $qty;
			$total 	= $harga * $qty;
			
			sql_free_result($hsl);
			
			$qty = intval($qty);
			
			$cv = $qty*$cv;
			
			
			// Input kedalam database
			$transaksiid = sql_get_var("select transaksiid from tbl_transaksi where orderid='$_SESSION[orderid]'");
			
			if (empty($transaksiid))
			{
				$new = newid("transaksiid","tbl_transaksi");
				
			
				$query	= ("insert into tbl_transaksi (`transaksiid`,`orderid`,`status`,userid,create_date) 
							values ('$new','$_SESSION[orderid]','0','$_SESSION[userid]','$date')");
				$hasil = sql($query);
				
				
				$newdetail = newid("transaksidetailid","tbl_transaksi_detail");
				$query1	= ("insert into tbl_transaksi_detail (`transaksidetailid`,`transaksiid`,`produkpostid`,`harga`,`matauang`,`totalharga`,`jumlah`,`berat`,cv) 
							values ('$newdetail','$new','$produkpostid','$harga','$matauang','$total','$qty','$berat','$cv')");
				$hasil1 = sql($query1);
			}
			else
			{
				
				
				if (empty($transaksidetailid))
				{
					$newdetail = newid("transaksidetailid","tbl_transaksi_detail");
					$query1	= ("insert into tbl_transaksi_detail (`transaksidetailid`,`transaksiid`,`produkpostid`,`harga`,`matauang`,`totalharga`,`jumlah`,`berat`) 
								values ('$newdetail','$transaksiid','$produkpostid','$harga','$matauang','$total','$qty','$berat')");
					$hasil1 = sql($query1);
				}
			}
			
			
		}
		else
		{
			$salah = "Jumlah kuantitas produk $namaprod yang dibeli tidak mencukupi. Silahkan kurangi jumlah kuantitas produk anda untuk melanjutkan belanja.<br><br>\n";
			$tpl->assign("style","alert-danger");
			$tpl->assign("salah",$salah);
		}
	}
}

elseif(isset($id_qty) && isset($_POST[update]))
{
	$transaksiid = sql_get_var("select transaksiid from tbl_transaksi where orderid='$_SESSION[orderid]'");
	$idqty       = sql_get_var("select max(transaksidetailid) as idqty from tbl_transaksi_detail");

	for($aq=1; $aq<=$idqty; $aq++)
	{
		$kode_qty 	= $_POST["kode_qty_$aq"];
		$jum_qty	= $_POST["qty_$aq"];
		
		$sql = "select title,harga,body_weight,harga,produkpostid from tbl_product_post where produkpostid = '$kode_qty'";
		$hsl = sql($sql);
		$row = sql_fetch_data($hsl);
		$produkpostid 		= $row['produkpostid'];
		$berat 				= $row['body_weight'];
		$harga 				= $row['harga'];
		$diskon				= $row['harga'];

	
		$subtotal 	= $harga * $jum_qty;
		sql_free_result($hsl);
		
		$berat 		= $berat * $jum_qty;
		$cv = $cv*$jum_qty;

		$query	= "update tbl_transaksi_detail set `jumlah`='$jum_qty',`berat`='$berat',`totalharga`='$subtotal' where transaksidetailid='$aq' and transaksiid='$transaksiid'";
		$hasil	= sql($query);
	}
}

$tpl->assign("namarubrik","Transaksi Keranjang");

include "subcart.data.php";

?>