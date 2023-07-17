<?php
if($_SESSION[userid]) $where = "where userid='$_SESSION[userid]'";
		else
		{
			$where = "where invoiceid='$var[4]'";
		}
		
		$sql1 	= "select transaksiid, invoiceid, orderid, pembayaran, pengiriman, tanggaltransaksi, batastransfer, totaltagihan, totaltagihanafterdiskon, ongkoskirim, status from tbl_transaksi where userid='$_SESSION[userid]' order by transaksiid desc";
		$hsl1 	= sql($sql1);
		$trans	= array();
		while ($row1 = sql_fetch_data($hsl1))
		{
			$transaksiid		= $row1['transaksiid'];
			$invoiceid			= $row1['invoiceid'];
			$orderid			= $row1['orderid'];
			$pembayaran			= $row1['pembayaran'];
			$pengiriman			= $row1['pengiriman'];
			$tanggaltransaksi	= tanggal($row1['tanggaltransaksi']);
			$batastransfer		= tanggal($row1['batastransfer']);
			$totaltagihan		= $row1['totaltagihan'];
			$totaltagihanafterdiskon	= $row1['totaltagihanafterdiskon'];
			$ongkoskirim		= $row1['ongkoskirim'];
			$status				= $row1['status'];
			
			if($totaltagihanafterdiskon==0)
				$totaltagihanakhir = $totaltagihan;
			else
				$totaltagihanakhir = $totaltagihanafterdiskon;
				
			$total_bayar 		= "IDR. ".number_format($totaltagihanakhir,0,".",".");
			
			$kodeinvoice = base64_encode($invoiceid);
			
			$link = "$fulldomain/cart/confirm/$kodeinvoice";
			
			if($status=="0") $stat = "Keranjang";
			elseif($status=="1") $stat = "Invoice";
			elseif($status=="2") $stat = "Konfirmasi";
			elseif($status=="3") $stat = "Lunas";
			elseif($status=="4") $stat = "Pengiriman";
			elseif($status=="5") $stat = "Batal";
			
			$urldetail = "$fulldomain/cart/detailinvoice/$invoiceid"; 
			
			$trans[$transaksiid] = array("transaksiid"=>$transaksiid,"invoiceid"=>$invoiceid,"status"=>$status,"stat"=>$stat,"pembayaran"=>$pembayaran,"link"=>$link,
					"tanggaltransaksi"=>$tanggaltransaksi,"total_bayar"=>$total_bayar,"urldetail"=>$urldetail);
		}
		sql_free_result($hsl1);
		$tpl->assign("listtransaksi",$trans);
?>