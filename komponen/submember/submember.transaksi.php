<?php
		$judul_per_hlm = 10;
		$batas_paging  = 5;
		
		if ($kanal=="profile")
		{
			$hlm     = $var[6];
			$subaksi = $var[5];
		
			$uid     = sql_get_var("select userid from tbl_member where username='$username'");
			$whereb  = "and resellerid='$uid'";
			$useridd = $uid; 
			$kanal   = $username;
		}
		else
		{
			$hlm     = $var[5];
			$subaksi = $var[4];
		
			$whereb  = "and (resellerid='$_SESSION[userid]' or refresellerid='$_SESSION[userid]')";
			$useridd = $_SESSION['useridresel'];
			$kanal   = $kanal;
		}
		
		$sql = "select count(*) as jml from tbl_transaksi where 1 $whereb";
		$hsl = sql($sql);
		$tot = sql_result($hsl, 0, jml);

		$tpl->assign("jml_post",$tot);
		
		$hlm_tot = ceil($tot / $judul_per_hlm);		
		if (empty($hlm)){
			$hlm = 1;
		}
		if ($hlm > $hlm_tot){
		$hlm = $hlm_tot;
		}
		$ord = ($hlm - 1) * $judul_per_hlm;
		if ($ord < 0 ) $ord=0;
		
		$perintah	= "select  transaksiid, userid, orderid, invoiceid, pembayaran, pengiriman, totaltagihan, namalengkap, alamatpengiriman, email, warehouseid, voucherid, kodevoucher, 
					totaldiskon, totaltagihanafterdiskon, ongkosinfo, ongkoskirim, noresi, status, statuskirim, flag, tanggaltransaksi from tbl_transaksi where invoiceid!='' $whereb order by tanggaltransaksi desc limit $ord, $judul_per_hlm";// and status > 0
		$hasil		= sql($perintah);
		$datadetail	= array(); 
		$no = 0;
		while($data = sql_fetch_data($hasil))
		{
			$transaksiid             = $data['transaksiid'];
			
			
			$userid                  = $data['userid'];
			$orderid                 = $data['orderid'];
			$invoiceid               = $data['invoiceid'];
			$pembayaran              = $data['pembayaran'];
			$pengiriman              = $data['pengiriman'];
			$totaltagihan            = $data['totaltagihan'];
			$namalengkap             = $data['namalengkap'];
			$alamatpengiriman        = $data['alamatpengiriman'];
			$email                   = $data['email'];
			$warehouseid             = $data['warehouseid'];
			$voucherid               = $data['voucherid'];
			$kodevoucher             = $data['kodevoucher'];
			$tgl_trans               = $data['tgl_trans'];
			$alamat11                = $data['alamat'];
			$kode_voucher            = $data['kode_voucher'];
			$totaldiskon             = $data['totaldiskon'];
			$totaltagihanafterdiskon = $data['totaltagihanafterdiskon'];
			$ongkosid                = $data['ongkosid'];
			$ongkoskirim             = $data['ongkoskirim'];
			$noresi                  = $data['noresi'];
			$status                  = $data['status'];
			$statuskirim             = $data['statuskirim'];
			$no_resi                 = $data['no_resi']; 
			$flag                    = $data['flag'];
			$tanggaltransaksi        = tanggal($data['tanggaltransaksi']);

			if($totaltagihanafterdiskon==0)
				$totaltagihanakhir = $totaltagihan+$ongkoskirim;
			else
				$totaltagihanakhir = $totaltagihanafterdiskon+$ongkoskirim;
				
			$ongkos_kirim		= number_format($ongkoskirim,0,".",".");	
			$total_bayar 		= number_format($totaltagihanakhir,0,".",".");
			$totaltagihan 		= number_format($totaltagihan,0,".",".");
			$total_diskon 		= number_format($totaldiskon,0,".",".");
			
			if(!empty($kodevoucher))
			{
				$cekdis = 1;
				$sqlvid	= "select voucherid from tbl_voucher_kode where kodevoucher='$kodevoucher'";
				$resvid	= sql($sqlvid);
				$rowvid	= sql_fetch_data($resvid);

					$voucherid		= $rowvid['voucherid'];

					$sql4	= "select nama, jenis, jumlah from tbl_voucher where id='$voucherid'";
					$res4	= sql($sql4);
					$row4	= sql_fetch_data($res4);
						$namadiskon		= $row4['nama'];
						$jenisdiskon	= $row4['jenis'];
						$jumlahdiskon	= $row4['jumlah'];
						
						if($jenisdiskon=="persen") $diskonnya = $jumlahdiskon ." %";
						else $diskonnya = "Rp. ". number_format($jumlahdiskon,0,".",".");
					sql_free_result($res4);

				sql_free_result($resvid);
			}
			
			
				$perintah 	= "SELECT * FROM tbl_member WHERE userid='$userid'";
				$hasils 		= sql($perintah);
				$data 		= sql_fetch_data($hasils);
	
				$userFullName	= $data['userfullname'];
				$userid			= $data['userid'];
				$propinsiid		= $data['propinsiid'];
				$useraddress 	= $data['useraddress'];
				$kotaid 		= $data['kotaid'];
				// $kotaid 		= $data['cityname'];
				$userpostcode	= $data['userpostcode'];
				$email 			= $data['useremail'];
				$telephone 		= $data['userphone'];
				$userphonegsm	= $data['userphonegsm'];
			// } 
			
			//kota
			$kota 		= sql_get_var("select namakota from tbl_kota where kotaid='$kotaid'");
			
			$billingalamat = "$useraddress<br>$kota<br>$userpostcode<br>Telp. $userphonegsm";
			
			$billingnama = $userFullName;
			$billingalamat = $billingalamat;
			$billingemail = $email;
			
			if($status=="0") $stat = "Cart";
			elseif($status=="1") $stat = "Invoiced";
			elseif($status=="2") $stat = "Confirmed";
			elseif($status=="3") $stat = "Paid";
			elseif($status=="4") $stat = "Shipped";
			elseif($status=="5") $stat = "Expire";
			elseif($status=="6") $stat = "Void";
		
			$sqlbank 	= "select id,bank,norek from tbl_norek where status='1'";
			$hasilb 		= sql($sqlbank);
			while ($datab=sql_fetch_data($hasilb)) 
			{
				$idrek		= $datab['id'];
				$id_bank	= $datab['bank'];
				$norek2		= $datab['norek'];
				
				$nama_bank = sql_get_var("select namabank from tbl_bank where bankid='$id_bank'");
				
				$nama = "$nama_bank ($norek2)";
				
				$bank[$idrek] = array("idrek"=>$idrek, "nama"=>$nama, "id_bank"=>$id_bank);
			}
			sql_free_result($hasilb);

			$datatransaksi[$transaksiid] = array();

			$sql1	= "select produkpostid, jumlah, harga, totalharga, transaksidetailid,matauang from tbl_transaksi_detail where transaksiid='$transaksiid'";
			$res1	= sql($sql1);
			$xx		= 1;
			$nomer	= 1;
			while ($row1 = sql_fetch_data($res1))
			{
				$produkpostid	= $row1['produkpostid'];
				$qty			= $row1['jumlah'];
				$matauang		= $row1['matauang'];
				$harga			= "$matauang. ". number_format($row1['harga'],0,".","."). ",-";
				$total			= "$matauang. ". number_format($row1['totalharga'],0,".","."). ",-";
				$transaksidetailid			= $row1['transaksidetailid'];
				
				$sql3	= "select title,kodeproduk from tbl_product_post where produkpostid='$produkpostid'";
				$res3	= sql($sql3);
				$row3	= sql_fetch_data($res3);
					$namabarang	= $row3['title'];
					$namaalias	= getalias($namabarang);
					$kodeproduk	= $row3['kodeproduk'];
				sql_free_result($res3);
				
				$datatransaksi[$transaksiid][$ids] = array("transaksidetailid"=>$transaksidetailid,"kodeproduk"=>$kodeproduk,"produkpostid"=>$produkpostid,"qty"=>$qty,"harga"=>$harga,"total"=>$total,"xx"=>$xx,"nomer"=>$nomer,
												"namabarang"=>$namabarang);
				$nomer++;
				$xx++;
				$xx = $xx%2;
			}
			sql_free_result($res1);

			// Logo
			$logo = sql_get_var("select logo from tbl_konfigurasi limit 1");

			$userfullname = sql_get_var("select userfullname from tbl_member where userid='$userid'");
			
			if($flag == 1)
				$jenistransaksi = "Upgrade Member";
			else
				$jenistransaksi = "Pembelian Produk";
			
			if($pembayaran == "COD")
				$pembayaran = "Cash On Delivery";
			elseif($pembayaran == "Transfer")
				$pembayaran = "Transfer Bank";
			elseif($pembayaran=="klikbca")
			{
				if(!empty($code))
				{
				
					$pembayaran = "BCA KlikPay (CreditCard)<br>Appcode: $code";
				}
				else
				{
				
					$pembayaran = "BCA KlikPay (Debit)";
				}
			}
			else
				$pembayaran = sql_get_var("select nama from tbl_payment_method where paymentid='$pembayaran'");
			
			$kodeinvoice = base64_encode($invoiceid);
			
			if($status=="0"){ $stat = "Keranjang"; $alert = "warning"; }
			elseif($status=="1"){ $stat = "Invoice"; $alert = "warning"; }
			elseif($status=="2"){ $stat = "Konfirmasi"; $alert = "warning"; }
			elseif($status=="3"){ $stat = "Bayar"; $alert = "warning"; }
			elseif($status=="4"){ $stat = "Terkirim"; $alert = "success"; }
			elseif($status=="5"){ $stat = "Batal"; $alert = "danger"; }
	
			 
			$link = "$fulldomain/$kanal/readtransaksi/$transaksiid/$namaalias.html";

			 
			$no++;
			$datadetail[$transaksiid] = array("transaksiid"=>$transaksiid,"no"=>$no,"invoiceid"=>$invoiceid,"billingnama"=>$billingnama,"billingalamat"=>$billingalamat,
												"billingemail"=>$billingemail,"totaltagihan"=>$totaltagihan,"total_diskon"=>$total_diskon,"ongkos_kirim"=>$ongkos_kirim,
												"total_bayar"=>$total_bayar,"pembayaran"=>$pembayaran,"tanggaltransaksi"=>$tanggaltransaksi,"urldetail"=>$link,"status"=>$stat,"alert"=>$alert);
		}
		sql_free_result($hasil);
		$tpl->assign("datadetail",$datadetail);
		
		//Paging 
		$batas_page =5;
		
		$stringpage = array();
		$pageid =0;
		
		$Selanjutnya 	= "&rsaquo;";
		$Sebelumnya 	= "&lsaquo;";
		$Akhir			= "&raquo;";
		$Awal 			= "&laquo;";
		
		if ($hlm > 1){
			$prev = $hlm - 1;
			$stringpage[$pageid] = array("nama"=>"$Awal","link"=>"$fulldomain/$kanal/$aksi/$subaksi/1");
			$pageid++;
			$stringpage[$pageid] = array("nama"=>"$Sebelumnya","link"=>"$fulldomain/$kanal/$aksi/$subaksi/$prev");

		}
		else {
			$stringpage[$pageid] = array("nama"=>"$Awal","link"=>"");
			$pageid++;
			$stringpage[$pageid] = array("nama"=>"$Sebelumnya","link"=>"");
		}
		
		$hlm2 = $hlm - (ceil($batas_page/2));
		$hlm4= $hlm+(ceil($batas_page/2));
		
		if($hlm2 <= 0 ) $hlm3=1;
		   else $hlm3 = $hlm2;
		$pageid++;
		for ($ii=$hlm3; $ii <= $hlm_tot and $ii<=$hlm4; $ii++){
			if ($ii==$hlm){
				$stringpage[$pageid] = array("nama"=>"$ii","link"=>"","class"=>"active");
			}else{
				$stringpage[$pageid] = array("nama"=>"$ii","link"=>"$fulldomain/$kanal/$aksi/$subaksi/$ii");
			}
			$pageid++;
		}
		if ($hlm < $hlm_tot){
			$next = $hlm + 1;
			$stringpage[$pageid] = array("nama"=>"$Selanjutnya","link"=>"$fulldomain/$kanal/$aksi/$subaksi/$next");
			$pageid++;
			$stringpage[$pageid] = array("nama"=>"$Akhir","link"=>"$fulldomain/$kanal/$aksi/$subaksi/$hlm_tot");
		}
		else
		{
			$stringpage[$pageid] = array("nama"=>"$Selanjutnya","link"=>"");
			$pageid++;
			$stringpage[$pageid] = array("nama"=>"$Akhir","link"=>"");
		}
		$tpl->assign("stringpage",$stringpage);
		//Selesai Paging

?>