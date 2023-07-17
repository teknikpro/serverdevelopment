<?php

$transaksiid = sql_get_var("select transaksiid from tbl_transaksi where orderid='$_SESSION[orderid]'");
		
$sql	= "delete from tbl_transaksi_detail where transaksiid='$transaksiid'";
$query = sql($sql);

$sql	= "delete from tbl_transaksi where transaksiid='$transaksiid'";
$query = sql($sql);

unset($_SESSION['orderid'], $_SESSION['last']);

if($query)
	header("location: $fulldomain/index.php");
	
?>