<?php
// Detail Produk
$produkpostid	= $_POST['produkpostid'];
$qty			= $_POST['qty'];
$id_qty 		= $_POST['id_qty'];


$date = date("Y-m-d H:i:s");

if(empty($_SESSION['orderid']))
{
	$orderid = "DFS".date("U");
	$_SESSION['orderid'] = $orderid;
}

if(isset($produkpostid))
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
		
		
		$sql = "select title,harga,body_weight,harga, userid from tbl_product_post where produkpostid = '$produkpostid'";
		$hsl = sql($sql);
		$row = sql_fetch_data($hsl);
		$title 		= $row["title"];
		$berat 		= $row['body_weight'];
		$harga 		= $row['harga'];
		$resellerid	= $row['userid'];
				
		$berat 	= $berat * $qty;
		$total 	= $harga * $qty;
		
		sql_free_result($hsl);
		
		$qty = intval($qty);
		
		$cv = $qty*$cv;
		
		$userid = $_SESSION['userid'];
		
		if(empty($_SESSION['userid']))
			$userid = 0;
		
		
		// Input kedalam database
		$transaksiid = sql_get_var("select transaksiid from tbl_transaksi where orderid='$_SESSION[orderid]'");
		
		if (empty($transaksiid))
		{
			$new = newid("transaksiid","tbl_transaksi");
			
		
			$query	= ("insert into tbl_transaksi (`transaksiid`,`orderid`,`status`,userid,create_date, create_userid, update_userid) 
						values ('$new','$_SESSION[orderid]','0','$userid','$date','0','0')");
			$hasil = sql($query);
			
			
			$newdetail = newid("transaksidetailid","tbl_transaksi_detail");
			$query1	= ("insert into tbl_transaksi_detail (`transaksidetailid`,`transaksiid`,`produkpostid`,`harga`,`matauang`,`totalharga`,`jumlah`,`berat`,cv,create_date, create_userid, resellerid, update_userid) 
						values ('$newdetail','$new','$produkpostid','$harga','$matauang','$total','$qty','$berat','$cv','$date','0','$resellerid','0')");
			$hasil1 = sql($query1);
		}
		else
		{
			
			
			if (empty($transaksidetailid))
			{
				$newdetail = newid("transaksidetailid","tbl_transaksi_detail");
				$query1	= ("insert into tbl_transaksi_detail (`transaksidetailid`,`transaksiid`,`produkpostid`,`harga`,`matauang`,`totalharga`,`jumlah`,`berat`,create_date, create_userid, resellerid) 
							values ('$newdetail','$transaksiid','$produkpostid','$harga','$matauang','$total','$qty','$berat','$date','0','$resellerid')");
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

include "bayar.data.php";
