<?php
	include("../../setingan/web.config.inc.php");
	
	$q 		= strtolower($_GET["q"]);
	$sql	= "select id,kota,service from tbl_ongkos where kota like '%$q%' and agenid='1'";
	$query	= sql($sql);
	while($row = sql_fetch_data($query))
	{
		$ongkosid	= $row['id'];
		$kota		= $row['kota'];
		$service	= $row['service'];
		
		echo "$kota($service)|$ongkosid\n";
	}
	sql_free_result($query);
?>