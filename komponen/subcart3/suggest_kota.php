<?php

	include("../../setingan/web.config.inc.php");
	
	$rs = sql('select id, kota, service from tbl_ongkos where kota like "%'. mysql_real_escape_string($_REQUEST['term']) .'%" order by kota asc');
	$data = array();
	if ( $rs && sql_num_rows($rs) )
	{
		while( $row = sql_fetch_data($rs, MYSQL_ASSOC) )
		{
			$data[] = array(
				'label' => $row['kota'] . "|". $row['service'],
				'value' => $row['kota'] . "|". $row['service'],
			);
		}
	}
	
	echo json_encode($data);
	flush();

?>