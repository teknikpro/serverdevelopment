<?php
$tpl->assign("namarubrik","Histori Transaksi");
		
	$sql1 	= "select * from tbl_chat where to_userid='$_SESSION[userid]' order by create_date desc";
	$hsl1 	= sql($sql1);
	$listtransaksi	= array();
	$no	= 1;
	while ($row1 = sql_fetch_data($hsl1))
	{
		$transaksiid             = $row1['chat_id'];
		$from_userid               = $row1['from_userid'];
		$orderid                 = $row1['orderid'];
		$pembayaran              = $row1['pembayaran'];
		$pengiriman              = $row1['pengiriman'];
		$tanggaltransaksi        = tanggal($row1['create_date']);
		$batastransfer           = tanggal($row1['batastransfer']);
		$finish            = $row1['finish'];
		$voucherid = $row1['voucherid'];
		$is_free             = $row1['is_free'];
		$status                  = $row1['status'];
		
		$nama = sql_get_var("select userfullname from tbl_member where userid='$from_userid'");
		$voucher = sql_get_var("select nama from tbl_voucher where voucherid='$voucherid'");
		
		
		if($is_free=="0") $stat = "Free";
		else $stat = "Paid";
		
		if($finish=="1") $status = "Finish";
		elseif($finish=="0") $status = "Active";
	
		
		$urldetail = "$fulldomain/cart/detailinvoice/$invoiceid"; 
		
		$listtransaksi[$transaksiid] = array("transaksiid"=>$transaksiid,"nama"=>$nama,"stat"=>$stat,"voucher"=>$voucher,"pembayaran"=>$pembayaran,"link"=>$link,
					"tanggaltransaksi"=>$tanggaltransaksi,"status"=>$status,"voucher"=>$voucher,"no"=>$no);
		$no++;
	}
	sql_free_result($hsl1);
	$tpl->assign("listtransaksi",$listtransaksi);
?>