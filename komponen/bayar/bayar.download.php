<?php
	$kodeinvoice = $var[4];
		
	$invoiceid = base64_decode($kodeinvoice);
	
	/*if(!file_exists("$pathfile/pdf/$invoiceid.pdf"))
	{*/
		// ambil data toko
		$tk	= sql_get_var_row("select nama,alamat,telp,gsm from tbl_about_post limit 1");
		$namatk		= $tk['nama'];
		$alamattk	= $tk['alamat'];
		$telptk		= $tk['telp'];
		$gsmtk		= $tk['gsm'];
						
		// kueri tabel konfirmasi
		$sql1	= "select transaksiid, invoiceid, orderid, totaltagihan, pengiriman, pembayaran, userid, namalengkap, alamatpengiriman, warehouseid, voucherid, totaldiskon, 
				totaltagihanafterdiskon, ongkosid, ongkoskirim, status, tipe,email, tanggaltransaksi, batastransfer, bank_tujuan from tbl_transaksi where invoiceid='$invoiceid'";
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
		$totaldiskon		= $row1['totaldiskon'];
		$totaltagihanafterdiskon	= $row1['totaltagihanafterdiskon'];
		$ongkosid			= $row1['ongkosid'];
		$ongkoskirim		= $row1['ongkoskirim'];
		$status				= $row1['status'];
		$tipe				= $row1['tipe'];
		$email				= $row1['email'];
		$tanggaltransaksi	= tanggal($row1['tanggaltransaksi']);
		$datetransfer		= tanggal($row1['batastransfer']);
		$bank_tujuan		= $row1['bank_tujuan'];
		
		if($pengiriman != "Pickup Point")
		{
			$namaagen	= sql_get_var("select nama from tbl_agen where agenid='$pengiriman'");
			$services	= sql_get_var("select service from tbl_ongkos where agenid='$pengiriman' and id = '$ongkosid'");
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
		$totaltagihanakhir2 		= "IDR. ".number_format($totaltagihanakhir,0,".",".");
		$totaltagihan2	= "IDR. ".number_format($totaltagihan,0,".",".");
		$totaldiskon2 		= "IDR. ".number_format($totaldiskon,0,".",".");
		
		if($status=="0") {$statusorder = "ORDER";$warnafont = "#e41b1a";}
		elseif($status=="1") {$statusorder = "BILLED";$warnafont = "#e41b1a";}
		elseif($status=="2") {$statusorder = "CONFIRMED";$warnafont = "#59B210";}
		elseif($status=="3") {$statusorder = "PAID";$warnafont = "#59B210";}
		elseif($status=="4") {$statusorder = "SHIPPING";$warnafont = "#59B210";}
		elseif($status=="5") {$statusorder = "VOID";$warnafont = "#e41b1a";}
		
		$services 		= sql_get_var("select service from tbl_ongkos where id='$ongkosid'");
			
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
			
			$dt_keranjang[$transaksidetailid] = array("transaksidetailid"=>$transaksidetailid,"xx"=>$xx,"nama"=>$namaprod,"harga"=>$harga,"totalharga"=>$total,"qty"=>$jumlah,"kodeproduk"=>$kodeproduk);
			$xx++;
			
			
		}
		sql_free_result($hsl);
		
		$totberat = sql_get_var(" SELECT SUM(berat) as total_berat from tbl_transaksi_detail where transaksiid='$transaksiid'");
		// kueri kode voucher
		if(!empty($voucherid))
		{
			$cekdis = 1;
			$sql4	= "select nama, jenis, jumlah from tbl_voucher where id='$voucherid'";
			$res4	= sql($sql4);
			$row4	= sql_fetch_data($res4);
				$namadiskon		= $row4['nama'];
				$jenisdiskon	= $row4['jenis'];
				$jumlahdiskon	= $row4['jumlah'];
				
				if($jenisdiskon=="persen") $diskonnya = $jumlahdiskon ." %";
				else $diskonnya = "IDR ". number_format($jumlahdiskon,0,".",".");
			sql_free_result($res4);
		}
		$kodevoucher = sql_get_var("select kodevoucher from tbl_voucher_kode where voucherid='$voucherid'");
			
		
		// kueri bank
		if($pembayaran == 'Transfer')
		{
			$perintah = "select * from tbl_norek where status='1'";
			$hasil = sql($perintah);
			$rekening = array();
			while ($data=sql_fetch_data($hasil)) 
			{
				$id			= $data['id'];
				$id_bank	= $data['bankid'];
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
	
				$userfullname	= $data['userfullname'];
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
	
				$userfullname	= $data['userfullname'];
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
		
		$urlconfirm = "$fulldomain/bayar/confirm/$kodeinvoice";
		
		include("cetak.invoice.php");
		
		/*header("location: $fulldomain/gambar/pdf/$invoiceid.pdf");
	}
	else
	{
		header("location: $fulldomain/gambar/pdf/$invoiceid.pdf");
	}*/
