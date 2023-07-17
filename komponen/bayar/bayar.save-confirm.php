<?php
	$invoiceid		= $_POST['invoiceid'];
	$bank			= $_POST['bank'];
	$total 			= $_POST['total'];
	$daribank 		= $_POST['bankdari'];
	$norek 			= $_POST['norek'];
	$name 			= $_POST['name'];
	$pesan 			= bersihHTML($_POST['pesan']);
	$tgl_konfirmasi	= date("Y-m-d H:i:s");
	$jumlah_bayar	= $_POST['jumlah_bayar'];
	
	$tgl_bayar	= $_POST['date'];
	
	$statustrans	= sql_get_var("select status from tbl_transaksi where invoiceid='$invoiceid'");
	
	if($statustrans == '5')
	{
		$pesanhasil ="Nomor Invoice <b>$invoiceid</b> yang anda masukan sudah dibatalkan karena melebihi batas waktu transfer yang ditentukan. Silahkan hubungi CS kami untuk melakukan invoice ulang.";
		$tpl->assign("style","alert-danger");
	}
	else
	{
		if(empty($statustrans))
		{
			$pesanhasil ="Nomor Invoice <b>$invoiceid</b> yang anda masukan tidak terdaftar di data transaksi kami. Silahkan periksa kembali no invoice Anda.";
			$tpl->assign("style","alert-danger");
		}
		elseif( $statustrans== '1')
		{
			$konfirmasiid	= sql_get_var("select id from tbl_konfirmasi where invoiceid='$invoiceid'");
				
			if(empty($konfirmasiid))
			{
				$sql	= "insert into tbl_konfirmasi (`bank_tujuan`, `bank_dari`, `norek`, `jumlah_bayar`, `total_bayar`, `metode_pembayaran`, `atas_nama`, `status`, `create_date`, 
							`tanggalbayar`,`invoiceid`,`pesan`) values ('$bank', '$daribank', '$norek', '$jumlah_bayar', '$total','$pembayaran', '$name', '1', '$tgl_konfirmasi', 
							'$tgl_bayar', '$invoiceid','$pesan')";
				$query = sql($sql);
					
				if($query)
				{
					$update	= sql("update tbl_transaksi set status='2' where invoiceid='$invoiceid'");
					
					//Info Ke Referensi 
					$subject = "Ada Konfimasi Pembayaran Transaksi";
					$message = "Hai $contactname<br><br>
					Informasi konfirmasi pembayaran:<br><br>
					Atas nama: $name<br>
					Total Bayar: $total<br>
					InvoiceID: $invoiceid<br><br>
										
				
					Agar segera di tindak lanjuti dan followup informasi diatas dengan cara mengakses sistem back office SentraDetox klik disini
					$fulldomain/member";
					
					//sendmail($contactname,$contactuseremail,$subject,$message,emailhtml($message));
					//kirimSMS($contactuserphone,"Informasi Konfimasi Pembayaran dari $name Total Rp. $total Silahkan login untuk info selengkapnya");
					
					/*$qrys	= sql("select userid,email,orderid,alamatpengiriman from tbl_transaksi where invoiceid='$invoiceid'");
					$rows	= sql_fetch_data($qrys);
					
					$userid = $rows['userid'];
					$email = $rows['email'];
					$orderid = $rows['orderid'];
					$alamatpengiriman = $rows['alamatpengiriman'];
					
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
					
					$contentemail	= sql_get_var("select keterangan from tbl_wording where alias='konfirmasi' and jenis = 'email' limit 1");
					$contentemail	= str_replace("[userfullname]","$userfullname",$contentemail);
					$contentemail	= str_replace("[title]","$title",$contentemail);
					$contentemail	= str_replace("[invoiceid]","$invoiceid",$contentemail);
					$contentemail	= str_replace("[alamatpengiriman]","$alamatpengiriman",$contentemail);
					$contentemail	= str_replace("[owner]","$owner",$contentemail);
					$contentemail	= str_replace("[fulldomain]","$fulldomain",$contentemail);
						
					$pengirim 			= "$owner <$support_email>";
					$webmaster_email 	= "Support <$support_email>"; 
					$headers 			= "From : $owner";
					$subject			= "$title, Konfirmasi Pembayaran";
					
					$sendmail			= sendmail($userfullname,$email,$subject,$contentemail,$contentemail,1);
					
					//kirim email ke admin
					$to 		= "$support_email";
					$from 		= "$support_email";
					$subject1 	= "Konfirmasi Pembayaran";
					$message 	= "Ada Informasi mengenai Konfirmasi online $title dengan no invoice $invoice";
					$headers 	= "From : $from";
					
					$sendmail1	= sendmail("Support",$support_email,$subject1,$message,$message,1);*/
					
					$pesanhasil = "Terimakasih telah melakukan konfirmasi. Barang akan segera dikirim ke tempat Anda.";
					$tpl->assign("style","alert-success");
				
					//kirim sms
					/*$sqlh	= "select gsm from tbl_about_post";
					$queryh	= sql($sqlh);
					$rowh	= sql_fetch_data($queryh);
					$gsm	= $rowh['gsm'];
				
					$kirimsms	= kirimSMS($gsm,"info store: pembeli dgn invoice $invoiceid tlah melakukan konfirmasi pembayaran,silahkan login ke $fulldomain/panel utk melihat detail pembayaran. Terimakasih");
					
					$contentsms	= sql_get_var("select keterangan from tbl_wording where alias='paid' and jenis = 'sms' limit 1");
			
					$contentsms	= str_replace("[owner]","$owner",$contentsms);
					$contentsms	= str_replace("[title]","$title",$contentsms);
					$contentsms	= str_replace("[invoiceid]","$invoiceid",$contentsms);
					$contentsms	= str_replace("[totaltagihan]","$jumlah_bayar",$contentsms);
					$contentsms	= str_replace("[pembayaran]","$pembayaran",$contentsms);
			
					$sendsms = kirimSMS($userphonegsm,$contentsms);*/
					
					setlog($_SESSION[userfullname],"system","Melakukan Konfirmasi Pembayaran.","$fulldomain/panel/index.php?tab=5&tabsub=9&kanal=konfirmasi&invoice=$invoice","confirm");
				}
			}
			else
			{
				$pesanhasil = "Nomor Invoice <b>{$invoiceid}</b> yang anda masukan sudah melakukan konfirmasi sebelumnya.<a href='$fulldomain/bayar/transaction.html'>Kembali</a>";
				$tpl->assign("style","alert-danger");
			}
		}
		else
		{
			$pesanhasil = "Nomor Invoice <b>{$invoiceid}</b> yang anda masukan sudah melakukan konfirmasi sebelumnya. <a href='$fulldomain/member/history.html'>" .$bahasa['back'] ."</a>";
			$tpl->assign("style","alert-danger");
		}
	}
		$tpl->assign("salah",$pesanhasil);
