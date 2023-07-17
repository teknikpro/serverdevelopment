<?php
if($_POST['jenis']) $jenis = $_POST['jenis'];
else $jenis = $_SESSION['jenis'];

$transaksiid = sql_get_var("select transaksiid from tbl_transaksi where orderid='$_SESSION[orderid]'");

// Tampilkan dalam database
$i = 1;
$a = 1;
$sql = "select transaksidetailid, produkpostid, jumlah from tbl_transaksi_detail where transaksiid='$transaksiid' order by transaksidetailid desc ";
// die($sql);
$hsl = sql($sql);
$jumlah_keranjang = sql_num_rows($hsl);
$i = 1;
$dt_keranjang = array();
while ($row = sql_fetch_data($hsl))
{
	$id 			= $row['transaksidetailid'];
	$produkpostid	= $row['produkpostid'];
	$qty 			= $row['jumlah'];
	
	$stock	= sql_get_var("select stock from tbl_product_post where produkpostid='$produkpostid'");
	$namaprod	= sql_get_var("select title from tbl_product_post where produkpostid='$produkpostid'");
	
	$stokakhir	= $stock;
	
	if($qty > $stokakhir)
	{
		$benar = false;
		break;
	}
	else
		$benar = true;
}

if($benar)
{		
	$total_subtotal		= $_POST['total_subtotal'];
	$kode_voucher		= $_POST['kode_voucher'];
	$total_diskon		= $_POST['diskon'];
	
	if(empty($_SESSION['total_subtotal']))
		$_SESSION['total_subtotal'] = $total_subtotal;
		
	if(empty($_SESSION['kode_voucher']))
		$_SESSION['kode_voucher'] = $kode_voucher;
		
	if(empty($_SESSION['total_diskon']))
		$_SESSION['total_diskon'] = $total_diskon;
	
	$tpl->assign("total_subtotal",$total_subtotal);
	$tpl->assign("kode_voucher",$kode_voucher);
	$tpl->assign("total_diskon",$total_diskon);
	
	if($_SESSION['username'] or ($jenis == 1))
	{
		$perintah = "select agenid,nama from tbl_agen order by nama asc";
		$hasil = sql($perintah);
		
		$dt_agen = array();
		
		while ($data = sql_fetch_data($hasil))
		{
			$agenid = $data['agenid'];
			$nama = $data['nama'];
			
			$dt_agen[$agenid]	= array ("agenid"=>$agenid,"nama"=>$nama);
		}
		
		sql_free_result($hasil);
		$tpl->assign ("dt_agen",$dt_agen);
		$tpl->assign ("jenis",$jenis);
		
		if($jenis == 1)
		{
			$perintah 	= "SELECT * FROM tbl_tamu WHERE orderid='$_SESSION[orderid]'";
			$hasil 		= sql($perintah);
			$data 		= sql_fetch_data($hasil);

			$userFullName	= $data['userfullname'];
			$idtamu			= $data['id'];
			$userAddress 	= $data['useraddress'];
			$propinsiid		= $data['propinsiid'];
			$kotaid 		= $data['kotaid'];
			$kodepos 		= $data['userpostcode'];
			$email 			= $data['useremail'];
			$telephone 		= $data['userphone'];
			$handphone 		= $data['userphonegsm'];
			
			$urlconfirm = "$fulldomain/cart/transaction/$_SESSION[invoice]";
		}
		else
		{
			$perintah 	= "SELECT * FROM tbl_member WHERE userid='$_SESSION[userid]'";
			$hasil 		= sql($perintah);
			$data 		= sql_fetch_data($hasil);
			
			$namaalamat		= $data['useremail'];
			$email 			= $data['useremail'];
			$userFullName	= $data['userfullname'];
			$userid			= $data['userid'];
			$propinsiid		= $data['propinsiid'];
			$userAddress 	= $data['useraddress'];
			$cityName 		= $data['cityname'];
			$kodepos 		= $data['userpostcode'];
			$telephone 		= $data['userphone'];
			$handphone 		= $data['userphonegsm'];
			$negaraid		= $data['negaraid'];
			
			//kota
			$pkota 		= "select kotaid,namakota from tbl_kota where kotaid='$kotaid'";
			$hkota 		= sql($pkota);
			$dkota		= sql_fetch_data($hkota);
			sql_free_result($hkota);
			
			$urlconfirm = "$fulldomain/cart/transaction";
		}
		
		$propinsi 		= sql_get_var("select namapropinsi from tbl_propinsi where propid='$propinsiid'");
		
		$tpl->assign("userid",$userid);
		$tpl->assign("namaalamat",$namaalamat);
		$tpl->assign("pesan",$pesan);
		$tpl->assign("cekkirim",$cekkirim);
		$tpl->assign("namapenerima",$userFullName);
		$tpl->assign("perusahaan",$perusahaan);
		$tpl->assign("pekerjaan",$pekerjaan);
		$tpl->assign("useraddress",$userAddress);
		$tpl->assign("kodepos",$kodepos);
		$tpl->assign("cityName",$cityName);
		$tpl->assign("kotaidnya",$kotaid);
		$tpl->assign("propinsi",$propinsi);
		$tpl->assign("propinsiidnya",$propinsiid);
		$tpl->assign("email",$email);
		$tpl->assign("tlp",$telephone);
		$tpl->assign("hp",$handphone);
		$tpl->assign("jeniskelamin",$jeniskelamin);
		$tpl->assign("jenis",$jenis);
		
		$tpl->assign("pengiriman",$pengiriman);
		$tpl->assign("alamatpengiriman",$alamatpengiriman);
		$tpl->assign("ongkos_kirim",$ongkoskirim);
		$tpl->assign("ongkos_kirim2",$ongkoskirim2);
		$tpl->assign("warehouse",$warehouse);
		
		//propinsi
		$datapropinsi = array();
		$ppropinsi = "select propid,namapropinsi from tbl_propinsi order by namapropinsi asc";
		$hpropinsi = sql($ppropinsi);
		while($dpropinsi=sql_fetch_data($hpropinsi))
		{
			if ($dpropinsi['propid'] == $propinsiid)
				$select = "selected";
			else
				$select	= "";
				
			$datapropinsi[$dpropinsi['propid']] = array("id"=>$dpropinsi['propid'],"namapropinsi"=>$dpropinsi['namapropinsi'],"select"=>$select);
		}
		sql_free_result($hpropinsi);
		$tpl->assign("datapropinsi",$datapropinsi);
		
		// kotaecho 
		$sqlk	= "select kotaid,namakota,tipe from tbl_kota where propid='$propinsiid' order by namakota asc";
		$queryk	= sql($sqlk);
		$datakota	= array();
		while($rowk = sql_fetch_data($queryk))
		{
			$kotaidd	= $rowk['kotaid'];
			$namakota	= $rowk['namakota'];
			$tipe	= $rowk['tipe'];
			
			if($tipe=="Kota") $namakota = "$namakota (Kota)";
			
			if ($kotaid == $kotaidd)
				$select = "selected";
			else
				$select	= "";
			
			$datakota[$kotaidd]	= array("kotaid"=>$kotaidd,"namakota"=>$namakota,"select"=>$select);
		}
		sql_free_result($queryk);
		$tpl->assign("datakota",$datakota);
		
		// kecamatan 
		$sqlk	= "select kecid,namakecamatan from tbl_kecamatan where propid='$propinsiid' and kotaid='$kotaid' order by namakecamatan asc";
		$queryk	= sql($sqlk);
		$datakecamatan	= array();
		while($rowk = sql_fetch_data($queryk))
		{
			$kecidd	= $rowk['kecid'];
			$namakecamatan	= $rowk['namakecamatan'];
			
			if ($kecid == $kecidd)
				$select = "selected";
			else
				$select	= "";
			
			$datakecamatan[$kecidd]	= array("kecid"=>$kecidd,"namakecamatan"=>$namakecamatan,"select"=>$select);
		}
		sql_free_result($queryk);
		$tpl->assign("datakecamatan",$datakecamatan);

		
		
		
		//negara
		$datanegara 	= array();
		$pnegara 		= "select id,namanegara from tbl_negara order by namanegara asc";
		$hnegara 		= sql($pnegara);
		while($dnegara	= sql_fetch_data($hnegara))
		{
			$id			= $dnegara['id'];
			$namanegara	= $dnegara['namanegara'];
			
			if ($id == $negaraid)
				$select = "selected";
			else
			{
				if($id == "ID")
				$select = " selected ='selected'";
				else
				$select	= "";
			}
					
			$datanegara[$id] = array("id"=>$id,"namanegara"=>$namanegara,"select"=>$select);
		}
		sql_free_result($hnegara);
		$tpl->assign("datanegara",$datanegara);
		
		//Data Toko
		$datatoko 	= array();
		$ptoko 		= "select warehouseid,nama from tbl_warehouse order by nama asc";
		$htoko 		= sql($ptoko);
		while($dtoko	= sql_fetch_data($htoko))
		{
			$warehouseid = $dtoko['warehouseid'];
			$nama 		 = $dtoko['nama'];
					
			$datatoko[$warehouseid] = array("warehouseid"=>$warehouseid,"nama"=>$nama);
		}
		sql_free_result($htoko);
		$tpl->assign("datatoko",$datatoko);
		
		//data Payment
		$datapayment = array();
		$ppayment = "select paymentid,nama from tbl_payment_method where status='1' order by nama asc";
		$hpayment = sql($ppayment);
		while($dpayment=sql_fetch_data($hpayment))
		{						
			$datapayment[$dpayment['paymentid']] = array("paymentid"=>$dpayment['paymentid'],"namapayment"=>$dpayment['nama']);
		}
		sql_free_result($hpayment);
		$tpl->assign("datapayment",$datapayment);
		
		//no rekening
		$perintah 	= "select * from tbl_norek where status='1'";
		$hasil 		= sql($perintah);
		while ($data=sql_fetch_data($hasil)) 
		{
			$id			= $data['id'];
			$id_bank	= $data['bank'];
			$norek		= $data['norek'];
			$atasnama	= $data['atasnama'];
			
			$sql8	= "select namabank from tbl_bank where bankid='$id_bank'";
			$res8	= sql($sql8);
			$row8	= sql_fetch_data($res8);
			$namabank	= $row8['namabank'];
			
			sql_free_result($res8);
			
			$nama = "$namabank ($norek)";
			
			$bank[$id] = array("id"=>$id, "nama"=>$nama, "bankid"=>$id_bank,"atasnama"=>$atasnama);
		}
		sql_free_result($hasil);
		$tpl->assign("databank",$bank);
		
		//no rekening
		$perintah 	= "select useralamatid,nama,userfullname from tbl_member_alamat where userid='$_SESSION[userid]'";
		$hasil 		= sql($perintah);
		while ($data=sql_fetch_data($hasil)) 
		{
			$useralamatid	= $data['useralamatid'];
			$namaalamat		= $data['nama'];
			$namapenerima	= $data['userfullname'];
			
			$nama = "$namaalamat ($namapenerima)";
			
			$dataalamat[$useralamatid] = array("useralamatid"=>$useralamatid, "nama"=>$nama);
		}
		sql_free_result($hasil);
		$tpl->assign("dataalamat",$dataalamat);
	}
	else
	{
		$last = "$fulldomain/cart/checkout";
		$_SESSION['last']	= $last;
		$tpl->assign("last",$last);
		header("location: $fulldomain/user/cart");
	}
}
else
{
	$salah = "Jumlah kuantitas produk yang dibeli tidak mencukupi. Silahkan kurangi jumlah kuantitas produk anda untuk melanjutkan belanja.<br><br>\n";
	$tpl->assign("style","alert-danger");
	$tpl->assign("salah",$salah);
}

$tpl->assign("namarubrik","Transaksi Checkout");

include "subcart.data.php";
?>