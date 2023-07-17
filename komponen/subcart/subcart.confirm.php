<?php
	$kodeinvoice = $var[4];
		
		$invoiceid = base64_decode($kodeinvoice);
		
		if(!empty($kodeinvoice))
		{
			$sql1 	= "select transaksiid,invoiceid,totaltagihan,totaltagihanafterdiskon,ongkoskirim, bank_tujuan from tbl_transaksi where invoiceid='$invoiceid'";
			$hsl1 	= sql($sql1);
			$row1 	= sql_fetch_data($hsl1);
			$transaksiid			= $row1['transaksiid'];
			$invoiceid				= $row1['invoiceid'];
			$totaltagihan			= $row1['totaltagihan'];
			$totaltagihanafterdiskon= $row1['totaltagihanafterdiskon'];
			$ongkoskirim			= $row1['ongkoskirim'];
			$bank_tujuan			= $row1['bank_tujuan'];
			
			if($totaltagihanafterdiskon==0)
				$totaltagihanakhir = $totaltagihan+$ongkoskirim;
			else
				$totaltagihanakhir = $totaltagihanafterdiskon+$ongkoskirim;
	
			$tpl->assign("akses",$akses);
			$tpl->assign("jumlah_bayar",$jumlah_bayar);
			$tpl->assign("invoicenya",$invoiceid);
			$tpl->assign("subtotalnya",$totaltagihanakhir);
			$tpl->assign("subtotal2","IDR. ".number_format($totaltagihanakhir,0,".",".").",-");
		}
		
		if(!empty($bank_tujuan))
			 $where = "and id = '$bank_tujuan'";
		
		$perintah 	= "select * from tbl_norek where status='1' $where";
		$hasil 		= sql($perintah);
		while ($data=sql_fetch_data($hasil)) 
		{
			$id			= $data['id'];
			$id_bank	= $data['bank'];
			$norek		= $data['norek'];
			
			$sql8	= "select namabank from tbl_bank where bankid='$id_bank'";
			$res8	= sql($sql8);
			$row8	= sql_fetch_data($res8);
			$namabank	= $row8['namabank'];
			
			sql_free_result($res8);
			
			$nama = "$namabank ($norek)";
			
			$bank[$id] = array("id"=>$id, "nama"=>$nama, "id_bank"=>$id_bank);
		}
		sql_free_result($hasil);
		$tpl->assign("bank",$bank);
		$tpl->assign("now",date("Y-m-d"));
		
		// tampil bulan dan tahun
		$month	= array(1=>"Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember");
		while(list($bln,$namabulan) = each($month))
		{
			$list_bulan[$bln]	= array("bln"=>$bln,"namabulan"=>$namabulan);
		}
		$tpl->assign("list_bulan",$list_bulan);
		
		for($thn=2015; $thn<=date("Y"); $thn++)
		{
			$list_tahun[$thn]	= array("thn"=>$thn);
		}
		$tpl->assign("list_tahun",$list_tahun);
		
		for($tgl=1; $tgl<=31; $tgl++)
		{
			$list_tanggal[$tgl]	= array("tgl"=>$tgl);
		}
		$tpl->assign("list_tanggal",$list_tanggal);
?>