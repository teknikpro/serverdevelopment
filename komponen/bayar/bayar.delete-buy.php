<?php

$transaksidetailid = $var[4];
		
$sql	= "delete from tbl_transaksi_detail where transaksidetailid='$transaksidetailid'";
$query = sql($sql);

if($query)
	header("location: $fulldomain/bayar/buy");
