<?php
$tpl->assign("namarubrik","Daftar eVoucher");
		
	$sql1 	= "select * from tbl_world_evoucher where mitrauserid='$_SESSION[userid]' order by create_date desc";
	$hsl1 	= sql($sql1);
	$listtransaksi	= array();
	$no	= 1;
	while ($row1 = sql_fetch_data($hsl1))
	{
		$vouchercodeid             = $row1['vouchercodeid'];
		$kode               = $row1['kode'];
		$orderid                 = $row1['orderid'];
		$nama              = $row1['nama'];
		$pengiriman              = $row1['pengiriman'];
		$tanggaltransaksi        = tanggal($row1['create_date']);
		$batastransfer           = tanggal($row1['batastransfer']);
		$totaltagihan            = $row1['totaltagihan'];
		$totaltagihanafterdiskon = $row1['totaltagihanafterdiskon'];
		$userid             = $row1['userid'];
		$status                  = $row1['status'];
		
		$userfullname = sql_get_var("select userfullname from tbl_member where userid='$userid'");
		
		if($status=="0") $stat = "Order";
		elseif($status=="1") $stat = "Invoice";
		elseif($status=="2") $stat = "Pending";
		elseif($status=="3") $stat = "Lunas";
		elseif($status=="4") $stat = "Pengiriman";
		elseif($status=="5") $stat = "Batal";
	
		
		$urldetail = "$fulldomain/cart/detailinvoice/$invoiceid"; 
		
		$listtransaksi[] = array("vouchercodeid"=>$vouchercodeid,"kode"=>$kode,"stat"=>$stat,"status"=>$status,"userfullname"=>$userfullname,"nama"=>$nama,"link"=>$link,
					"tanggaltransaksi"=>$tanggaltransaksi,"totaltagihan"=>$totaltagihan,"urldetail"=>$urldetail,"no"=>$no);
		$no++;
	}
	sql_free_result($hsl1);
	$tpl->assign("listtransaksi",$listtransaksi);
?>