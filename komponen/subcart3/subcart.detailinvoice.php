<?php
	$invoiceid = $var[4];
		
		// kueri tabel konfirmasi
		$sql1	= "select transaksiid, invoiceid, orderid, totaltagihan, pengiriman, pembayaran, userid, namalengkap, alamatpengiriman, warehouseid, voucherid, totaldiskon, 
				totaltagihanafterdiskon, ongkosinfo, ongkoskirim, status, tipe,email, tanggaltransaksi, batastransfer, bank_tujuan,confee, kodevoucher from tbl_transaksi where invoiceid='$invoiceid'";
		$hsl1 = sql($sql1);
		$row1 = sql_fetch_data($hsl1);
		
		$transaksiid		= $row1['transaksiid'];
		$invoiceid			= $row1['invoiceid'];
		$orderid			= $row1['orderid'];
		$totaltagihan		= $row1['totaltagihan'];
		$pengiriman			= $row1['pengiriman'];
		$pembayaran			= $row1['pembayaran'];
		$userid				= $row1['userid'];
		$namalengkap		= $row1['namalengkap'];
		$alamatpengiriman	= $row1['alamatpengiriman'];
		$warehouseid		= $row1['warehouseid'];
		$voucherid			= $row1['voucherid'];
		$kodevoucher		= $row1['kodevoucher'];
		$totaldiskon		= $row1['totaldiskon'];
		$totaltagihanafterdiskon	= $row1['totaltagihanafterdiskon'];
		$ongkosinfo			= $row1['ongkosinfo'];
		$ongkoskirim		= $row1['ongkoskirim'];
		$status				= $row1['status'];
		$tipe				= $row1['tipe'];
		$email				= $row1['email'];
		$tanggaltransaksi	= tanggal($row1['tanggaltransaksi']);
		$batastransfer		= tanggal($row1['batastransfer']);
		$bank_tujuan		= $row1['bank_tujuan'];
		$confee		= $row1['confee'];
		
		if($pengiriman != "Pickup Point")
		{
			$namaagen	= sql_get_var("select nama from tbl_agen where agenid='$pengiriman'");
			$pengiriman = "$namaagen - $ongkosinfo";
		}
		else
			$warehouse	= sql_get_var("select nama from tbl_warehouse where warehouseid='$warehouseid'");
		
		if($totaltagihanafterdiskon==0)
			$totaltagihanakhir = $totaltagihan+$ongkoskirim;
		else
			$totaltagihanakhir = $totaltagihanafterdiskon+$ongkoskirim;
			
		if($pembayaran != "COD" and $pembayaran != "Transfer" and $pembayaran != "klikbca")
			$pembayaran = sql_get_var("select nama from tbl_payment_method where paymentid='$pembayaran'");			
			
		$ongkoskirim2		= "IDR. ". number_format($ongkoskirim,0,".",".");	
		$total_bayar 		= "IDR. ".number_format($totaltagihanakhir,0,".",".");
		$totaltagihan 		= "IDR. ".number_format($totaltagihan,0,".",".");
		$total_diskon 		= "IDR. ".number_format($totaldiskon,0,".",".");
		
		if($status=="0") $stat = "Belanja";
		elseif($status=="1") $stat = "Invoice Telah Dikirimkan. Menunggu Konfirmasi Pembayaran.";
		elseif($status=="2") $stat = "Konfirmasi pembayaran telah diterima. Menunggu Approve dari Administrator";
		elseif($status=="3") $stat = "Pembayaran sudah diterima. Pesanan akan segera diproses";
		elseif($status=="4") $stat = "Pesanan sudah dikirim.";
		elseif($status=="5") $stat = "Pesanan telah dibatalkan.";
		
		$kodeinvoice = base64_encode($invoiceid);
		
		$urlconfirm = "$fulldomain/cart/confirm/$kodeinvoice";
		$urldownload = "$fulldomain/cart/print/$kodeinvoice";
		
		sql_free_result($hsl1);
		
		$sql	= "select transaksidetailid,produkpostid,jumlah,matauang,harga,totalharga,berat from tbl_transaksi_detail 
				where transaksiid='$transaksiid'";
		$hsl 	= sql($sql);
		$xx		= 1;
		$detailproduk = array();
		while ($row = sql_fetch_data($hsl))
		{
			$transaksidetailid	= $row['transaksidetailid'];
			$produkpostid 	= $row['produkpostid'];
			$jumlah 		= $row['jumlah'];
			$matauang		= $row['matauang'];
			$harga 			= $row['harga'];
			$totalharga 	= $row['totalharga'];
			$berat	 		= $row['berat'];
			$harga			= "$matauang ". number_format($row['harga'],0,".",".");
			$total			= "$matauang ". number_format($totalharga,0,".",".");
				
			$namaprod 		= sql_get_var("select title from tbl_product_post where produkpostid='$produkpostid'");
			$kodeproduk 		= sql_get_var("select kodeproduk from tbl_product_post where produkpostid='$produkpostid'");
			
			$detailproduk[$transaksidetailid] = array("id"=>$id,"xx"=>$xx,"namaprod"=>$namaprod,"harga"=>$harga,"total"=>$total,"qty"=>$jumlah,"kodeproduk"=>$kodeproduk);
			$xx++;
			
			
		}
		sql_free_result($hsl);
		
		$totberat = sql_get_var(" SELECT SUM(berat) as total_berat from tbl_transaksi_detail where transaksiid='$transaksiid'");
	
		
		// kueri bank
		if($pembayaran == 'Transfer')
		{
			$perintah = "select * from tbl_norek where status='1'";
			$hasil = sql($perintah);
			$rekening = array();
			while ($data=sql_fetch_data($hasil)) 
			{
				$id			= $data['id'];
				$id_bank	= $data['bank'];
				$norek		= $data['norek'];
				$atasnama	= $data['atasnama'];
				
				$sql8	= "select namabank from tbl_bank where bankid='$id_bank'";
				$res8	= sql($sql8);
				$row8	= sql_fetch_data($res8);
				$logo	= $row8['logobank'];
				$bank	= $row8['namabank'];
		
				$rekening[$id] = array("idr"=>$id,"akun"=>$norek,"bank"=>$bank,"namaak"=>$atasnama);
			
			}
		}
		
		if(empty($userid) or ($userid=='0'))
			{
				$perintah 	= "SELECT * FROM tbl_tamu WHERE orderid='$orderid'";
				$hasil 		= sql($perintah);
				$data 		= sql_fetch_data($hasil);
	
				$userFullName	= $data['userfullname'];
				$idtamu			= $data['id'];
				$useraddress 	= $data['useraddress'];
				$propinsiid		= $data['propinsiid'];
				$kotaid 		= $data['kotaid'];
				$userpostcode 	= $data['userpostcode'];
				$email 			= $data['useremail'];
				$telephone 		= $data['userphone'];
				$userphonegsm 	= $data['userphonegsm'];
			}
			else
			{
				$perintah 	= "SELECT * FROM tbl_member WHERE userid='$userid'";
				$hasil 		= sql($perintah);
				$data 		= sql_fetch_data($hasil);
	
				$userFullName	= $data['userfullname'];
				$userid			= $data['userid'];
				$propinsiid		= $data['propinsiid'];
				$useraddress 	= $data['useraddress'];
				$kotaid 		= $data['cityname'];
				$userpostcode	= $data['userpostcode'];
				$email 			= $data['useremail'];
				$telephone 		= $data['userphone'];
				$userphonegsm	= $data['userphonegsm'];
			}
			
			//kota
			$kota 		= sql_get_var("select namakota from tbl_kota where kotaid='$kotaid'");
			
			$billingalamat = "$useraddress<br>$kota<br>$userpostcode<br>Telp. $userphonegsm";
			
			$tpl->assign("billingnama",$userFullName);
			$tpl->assign("billingalamat",$billingalamat);
			$tpl->assign("billingemail",$email);
		
		//Tampilkan jumlah berat barang
		$beratkirim 	= $totberat;
		$beratkirim 	= explode(".",$beratkirim);
		$beratkirim1 	= "0.".$beratkirim[1];
		
		if(($beratkirim1 < 1 ) && ($beratkirim1 > 0)) 
			{ $beratkirim1 = 1; }
		else if(($beratkirim1 < 1 ) && ($beratkirim[0] < 1)) 
			{ $beratkirim1 = 1; }
		else 
			{ $beratkirim1 = 0; }
		
		$jumlahberatkirim = $beratkirim[0] + $beratkirim1;
		
		
		$tpl->assign("invoiceid",$invoiceid);
		$tpl->assign("orderid",$orderid);
		$tpl->assign("totaltagihan",$totaltagihan);
		$tpl->assign("pengiriman",$pengiriman);
		$tpl->assign("pembayaran",$pembayaran);
		$tpl->assign("userid",$userid);
		$tpl->assign("namalengkap",$namalengkap);
		$tpl->assign("alamatpengiriman",$alamatpengiriman);
		$tpl->assign("warehouseid",$warehouseid);
		$tpl->assign("confee",$confee);
		$tpl->assign("warehouse",$warehouse);
		$tpl->assign("voucherid",$voucherid);
		$tpl->assign("kodevoucher",$kodevoucher);
		$tpl->assign("totaltagihanakhird",$total_bayar);
		$tpl->assign("stats",$stat);
		$tpl->assign("status",$status);
		$tpl->assign("urlconfirm",$urlconfirm);
		$tpl->assign("tanggaltransaksi",$tanggaltransaksi);
		$tpl->assign("detailproduk",$detailproduk);
		$tpl->assign("ongkos_kirim",$ongkoskirim);
		$tpl->assign("ongkos_kirim2",$ongkoskirim2);
		$tpl->assign("total_diskon",$totaldiskon);
		$tpl->assign("total_diskon2",$total_diskon);
		$tpl->assign("totberat",$jumlahberatkirim);
		$tpl->assign("services",$ongkosinfo);
		$tpl->assign("rekening",$rekening);
		$tpl->assign("namaagen",$namaagen);
		$tpl->assign("urldownload",$urldownload);
		$tpl->assign("almttk",$alamattk);
		$tpl->assign("telptk",$telptk);
		
		
		$tpl->assign("namarubrik","Detail Transaksi");
?>