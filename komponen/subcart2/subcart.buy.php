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
		$diskon		= $row['harga'];
		$matauang	= "Rp";
		$harga 		= $row['harga'];
		$cv 		= $row['cv'];

		$sDiskon		= 0;
		$hDiskon		= 0;
		if($diskon!=0)
		{
			$harga			= $diskon;
		}
			
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
			
			//Get Refuserid
			$refresellerid = sql_get_var("select distributorid from tbl_member where userid='$contactid'");
		
			
			$query	= ("insert into tbl_transaksi (`transaksiid`,`orderid`,`status`,userid,create_date,resellerid,refresellerid) 
						values ('$new','$_SESSION[orderid]','0','$_SESSION[userid]','$date','$contactid','$refresellerid')");
			$hasil = sql($query);
			
/*			
			$subject = "Ada Transaksi di SentraDetox";
			$message = "Hai $contactname<br><br>
			Happy Selling... cek order baru di SentraDetox dari $_SESSION[userfullname] untuk produk $title, Rp $total <br><br>
		
			Agar segera di tindak lanjuti dan followup informasi ditas dengan cara mengakses sistem back office SentraDetox klik disini
			http://www.sentradetox.com/member";
			
			sendmail($contactname,$contactuseremail,$subject,$message,emailhtml($message));
			kirimSMS($contactuserphone,"Happy Selling... cek order baru di SentraDetox dari $_SESSION[userfullname] untuk produk $title, Rp $total. Silahkan login ke BackOffice untuk info selengkapnya");
			
*/			
			
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
				$query1	= ("insert into tbl_transaksi_detail (`transaksidetailid`,`transaksiid`,`produkpostid`,`harga`,`matauang`,`totalharga`,`jumlah`,`berat`,cv) 
							values ('$newdetail','$transaksiid','$produkpostid','$harga','$matauang','$total','$qty','$berat','$cv')");
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
		$cv				= $row['cv'];
	
		$subtotal 	= $harga * $jum_qty;
		sql_free_result($hsl);
		
		$berat 		= $berat * $jum_qty;
		$cv = $cv*$jum_qty;

		$query	= "update tbl_transaksi_detail set `jumlah`='$jum_qty',`berat`='$berat',`totalharga`='$subtotal',cv='$cv' where transaksidetailid='$aq' and transaksiid='$transaksiid'";
		$hasil	= sql($query);
	}
}

$tpl->assign("namarubrik","Transaksi Keranjang");

include "subcart.data.php";

?>