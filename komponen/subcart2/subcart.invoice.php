<?php
session_start();
if($_SESSION['orderid'])
{
	$tpl->assign("tipe",$_SESSION['tipeid']);
	
	if($_POST['jenis']) $jenis = $_POST['jenis'];
	else $jenis = $_SESSION['jenis']; 
	
	if($_SESSION['username'] or ($jenis==1))
	{
		$userid			= $_POST['userid'];
		$userfullname	= $_POST['userfullname'];
		$useraddress	= $_POST['useraddress'];
		$userpostcode	= $_POST['userpostcode'];
		$userphonegsm 	= $_POST['userphonegsm'];
		$propinsiid 	= $_POST['propinsiid'];
		$negaraid		= $_POST['negaraid'];
		$kotaid		 	= $_POST['kotaid'];
		$pengiriman		= $_POST['pengiriman'];
		$email 			= $_POST['email'];
		$pesan	 		= $_POST['pesan'];
		$pembayaran		= $_POST['pembayaran'];
		$ongkirid		= $_POST['ongkiridnya'];
		$warehouseid	= $_POST['warehouseid'];
		$bank			= $_POST['bank'];
		$orderid		= $_SESSION[orderid];
		$tipeid			= $_SESSION[tipeid];
		$pesan			= $_POST['pesan'];
		$dropship		= $_POST['dropship'];
		$namapengirim	= $_POST['namapengirim'];
		$telppengirim	= $_POST['telppengirim'];
		
		
		$ongkir = base64_decode($ongkirid);
		
		$data = explode("***",$ongkir);
		$ongkosinfo = $data[0];
		$ongkoskirim = $data[1];
		
		
		$transaksiid 	= sql_get_var("SELECT transaksiid FROM tbl_transaksi WHERE orderid='$_SESSION[orderid]'");
		$jumlah 	= sql_get_var("SELECT count(*) as jumlah FROM tbl_transaksi WHERE invoiceid!=''");
		
		$jedatransfer 	= sql_get_var("SELECT nama FROM tbl_jeda_pembayaran WHERE tipeid='$tipeid'");
		$expireminute = $jedatransfer*60;
		
		$kota 	= sql_get_var("SELECT namakota FROM tbl_kota WHERE kotaid='$kotaid'");
		$jumlah++;
		$i 		= 10000000 + $jumlah;
		$kode 	= substr($i,1,7);
		
		$invoiceid = $_SESSION['orderid'];
				
		$isi			= "";
		$tanggal		= date("Y-m-d H:i:s");
		$datetransfer = date("Y-m-d H:i:s", strtotime("+$jedatransfer hour"));
		
	
		$email 	= sql_get_var("SELECT useremail FROM tbl_member WHERE userfullname='$userfullname'");
			
		$alamat_kirim = "$useraddress<br>$kota<br>$userpostcode<br>Telp. $userphonegsm";
		
		$totalberat = sql_get_var(" SELECT SUM(berat) as total_berat from tbl_transaksi_detail where transaksiid='$transaksiid'");
		
		$jumlahberatkirim = $totalberat;
		
		$sql = sql("select transaksiid,totaltagihanafterdiskon from tbl_transaksi where transaksiid='$transaksiid'");
		$dt = sql_fetch_data($sql);
		$transaksiid = $dt['transaksiid'];
		$totaltagihanafterdiskon = $dt['totaltagihanafterdiskon'];
		
		
		if($pembayaran == "Transfer")
		{
			$confee = substr($orderid,-3,3);
			
			$total = $totaltagihanafterdiskon;
			$total = $total-$confee;
			$totalinvoice = $total+$ongkoskirim;
		}
		else
		{
			$confee = 0;
			$total = $totaltagihanafterdiskon;
			$total = $total-$confee;
			$totalinvoice = $total+$ongkoskirim;

		}
		
		
		// Input Transaksi
		$perintah 	= "SELECT invoiceid FROM tbl_transaksi WHERE orderid='$_SESSION[orderid]' and invoiceid='$invoiceid'";
		$hasil 		= sql($perintah);
		if(sql_num_rows($hasil)>0)
		{
			$salah = "Transaksi dengan No Invoice : $invoiceid telah dilakukan<br><br>\n";
			$tpl->assign("style","alert-danger");
			unset($_SESSION['orderid'], $_SESSION['last'],$_SESSION['jenis']);
			$benar = 2;
		}
		else
		{		
			$perintah 	= "update tbl_transaksi set invoiceid='$invoiceid', namalengkap='$userfullname', email='$email', alamatpengiriman='$alamat_kirim', ongkosinfo='$ongkosinfo',propid='$propinsiid',kotaid='$kotaid',kecid='$kecid',
						 ongkoskirim='$ongkoskirim', pengiriman='$pengiriman',agen='$pengiriman',confee='$confee', pembayaran='$pembayaran', bank_tujuan='$bank', tanggaltransaksi='$tanggal', batastransfer='$datetransfer', 
						 warehouseid='$warehouseid', pesan='$pesan', dropship='$dropship', namapengirim='$namapengirim', telppengirim='$telppengirim', status='1', userid='$_SESSION[userid]'  
						 where transaksiid='$transaksiid' and orderid='$_SESSION[orderid]'";
			$hasil 		= sql($perintah);
			if (!$hasil)
			{
				$salah = $bahasa['failsave'] . "<br>\n";
				$tpl->assign("style","alert-danger");
				unset($_SESSION['orderid'], $_SESSION['last'],$_SESSION['jenis']);
				$benar = 2;
			}
			else
			{ 
				// Cek Alamat member Ada atau Tidak
					if($_SESSION['userid'])
					{
						$ck          = sql_get_var_row("select useraddress,cityname,propinsiid,userpostcode,kotaid from tbl_member where userid='$_SESSION[userid]'");
						$cekalamat   = $ck['useraddress'];
						$cekkota     = $ck['cityname'];
						$cekkotaid   = $ck['kotaid'];
						$cekpropinsi = $ck['propinsiid'];
						$cekkodepos  = $ck['userpostcode'];
						
						if(empty($cekalamat))
							sql("update tbl_member set useraddress='$useraddress' where userid='$_SESSION[userid]'");
							
						if(empty($cekkota))
							sql("update tbl_member set cityname='$kota' where userid='$_SESSION[userid]'");

						if(empty($cekkotaid))
							sql("update tbl_member set kotaid='$kotaid' where userid='$_SESSION[userid]'");
						
						if(empty($cekpropinsi))
							sql("update tbl_member set propinsiid='$propinsiid' where userid='$_SESSION[userid]'");
						
						if(empty($cekkodepos))
							sql("update tbl_member set userpostcode='$userpostcode' where userid='$_SESSION[userid]'");
							

					}


				
				
				if($pembayaran == "Transfer")
				{
					
					$sql = "select * from tbl_transaksi_detail where transaksiid='$transaksiid' order by transaksidetailid desc ";
					$hsl = sql($sql);
					$i = 1;
					$a = 1;
					while ($row = sql_fetch_data($hsl))
					{
						$transaksidetailid 	= $row['transaksidetailid'];
						$produkpostid	= $row['produkpostid'];
						$qty 			= $row['jumlah'];
						$berat			= $row['berat'];
						$matauang		= $row['matauang'];
						$harga	 		= "$matauang. ". number_format($row['harga'],"0",".",".");
						$total			= "$matauang. ". number_format($row['totalharga'],"0",".",".");
						
						$sql2	= "select title,kodeproduk from tbl_product_post where produkpostid='$produkpostid'";
						$query2	= sql($sql2);
						$row2	= sql_fetch_data($query2);
						$nama			= $row2["title"];
						$namap			= $row2["title"];
						$kodeproduk		= $row2['kodeproduk'];
						
						$perintahst 	= "insert into tbl_product_stokonhold (`invoiceid`, `produkpostid`, `jumlah`, `tipe`) values ('$invoiceid', '$produkpostid', '$qty', '$tipeid')";
						$hasilst 		= sql($perintahst);
						
						// album
						$gambar_s	= sql_get_var("select gambar_s from tbl_product_image where produkpostid='$produkpostid' order by albumid asc limit 1");
						
						if(!empty($gambar_s))
							$image_s	= "$fulldomain/gambar/produk/$produkpostid/$gambar_s";
						else
							$image_s	= $fulldomain.$lokasiwebtemplate."images/no_photo.gif";
						
						$berattotal	.= $berattotal+$row['berat'];
						
						$dt_keranjang[$transaksidetailid] = array("transaksidetailid"=>$transaksidetailid,"nama"=>$nama,"kodeproduk"=>$kodeproduk,"image_s"=>$image_s,"berat"=>$berat,"totalharga"=>$total,"qty"=>$qty,"harga"=>$harga,"xx"=>$a);
	
						$i %= 2;
						$i++;
						$a++;
					}
					
					$tpl->assign("detailproduk",$dt_keranjang);
					
	
					$ongkoskirim2	= "$matauang. ". number_format($ongkoskirim,0,".",".");
						
				
					$totaldiskon = $rowv['totaldiskon'];
					$totaltagihanafterdiskon = $rowv['totaltagihanafterdiskon'];
					$totaltagihan = $rowv['totaltagihan'];
					
					
					if($totaltagihanafterdiskon==0)
						$totaltagihanakhir = $totaltagihan+$ongkoskirim;
					else
						$totaltagihanakhir = $totaltagihanafterdiskon+$ongkoskirim;
					
					$totaltagihan1 = number_format($totaltagihan,0,",",".");
					$totaltagihan2 = "$matauang. $totaltagihan1";
					
					$totaltagihanakhir1 = number_format($totaltagihanakhir,0,",",".");
					$totaltagihanakhir2 = "$matauang. $totaltagihanakhir1";
					
					$totaldiskon1 = number_format($totaldiskon,0,",",".");
					$totaldiskon2 = "$matauang. $totaldiskon1";
						
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
					
					$kodeinvoice = base64_encode($invoiceid);
		
					$urlconfirm = "$fulldomain/cart/confirm/$kodeinvoice";
					$urldownload = "$fulldomain/cart/print/$kodeinvoice";
					
					$statusorder = "BILLED";
					
					$warnafont = "#e41b1a";
					
						
					$tpl->assign("totaltagihan",$totaltagihan2);
					$tpl->assign("total_diskon",$totaldiskon);
					$tpl->assign("total_diskon2",$totaldiskon2);
					$tpl->assign("ongkos_kirim",$ongkoskirim);
					$tpl->assign("ongkos_kirim2",$ongkoskirim2);
					$tpl->assign("totaltagihanakhird",$totaltagihanakhir2);
					$tpl->assign("totberat",$jumlahberatkirim);
					$tpl->assign("namadiskon",$namavoucher);
					$tpl->assign("diskonnya",$totaldiskon2);
					$tpl->assign("kode_voucher",$kodevoucher);
					
					// ambil data toko
					$tk	= sql_get_var_row("select nama,alamat,telp,gsm from tbl_static where alias='kontak' limit 1");
					$namatk		= $tk['nama'];
					$alamattk	= $tk['alamat'];
					$telptk		= $tk['telp'];
					$gsmtk		= $tk['gsm'];
				
					include("invoice.pdf.php");
					
					$pengirim 			= "$owner <$support_email>";
					$webmaster_email 	= "Support <$support_email>"; 
					$userEmail			= "$email"; 
					$userFullName		= "$userfullname"; 
					$headers 			= "From : $owner";
					$subject			= "Informasi Peminjaman Nomor $invoiceid";
					
					$sendmail			= sendmail($userfullname,$userEmail,$subject,$html,$html,1);
								
					$salah = "Terima kasih, pengajuan peminjaman yang anda lakukan telah berhasil, kami telah mengirimkan sebuah email untuk anda. Silakan periksa email Anda untuk melihat rincian pesanan.
								Nomor transaksi peminjaman anda adalah <strong><a href='$urldownload' target='_blank'>$invoiceid</a></strong>. Kami akan segera menghubungi anda.";
					$tpl->assign("style","alert-success");
						
					//kirim email ke admin
					$to 		= "$support_email";
					$from 		= "$support_email";
					$subject 	= "Informasi Peminjaman $invoiceid";
					$message 	= $message;
					$headers 	= "From : $owner";
					
				
					$sendmail	= sendmail($title,$to,$subject,$html,$html,1);
					
					//kirim sms
					
					if($pembayaran == "Transfer")
					{
						$infotransfer = "Silahkan transfer ke salah satu rekening berikut :";
						$i= 1;
						foreach($rekening as $data)
						{
							$infotransfer .= $i.". ".$data['bank']." (".$data['akun']. "a.n ".$data['namaak'].") ";
							$i++;
						}
					}

					
				
					
					setlog($userfullname,"system","Melakukan Transaksi Peminjaman.","$fulldomain/panel/index.php?tab=5&tabsub=9&kanal=transaksi&aksi=detail&invoiceid=$invoiceid","buy");
					unset($_SESSION['orderid'], $_SESSION['last'],$_SESSION['jenis']);
					

			
									
				}

			
			}
		}
		$tpl->assign("benar",$benar);	
		$tpl->assign("salah",$salah);
		$tpl->assign("agen",$agen);
		$tpl->assign("trans_id",$trans_id);
		$tpl->assign("kotatujuan2",$kotatujuan2);
		$tpl->assign("namarubrik","Transaksi Sukses");
	}
	else
	{
		$last = "$fulldomain/cart/checkout";
		$_SESSION['last']	= $last;
		header("location: $fulldomain/login");
	}

}
else
{
	$salah = "<center>Pesanan anda tidak memiliki nomor transaksi. Silahkan ulangi kembali proses belanja <br> Anda dengan memilih
	berbagai produk unggulan kami.<br>
	<br><a href='$fulldomain/product' class='btn btn-warning'>Pilih Produk Terlebih Dahulu</a></center>";
	$tpl->assign("style","alert-danger");
	$tpl->assign("salah",$salah);
}

?>