<?php
	$ticketingid		= $var[4];
	
	$perintah 	= "update tbl_ticketing SET is_closed='1' WHERE ticketingid='$ticketingid'";
	$hasil 		= sql($perintah);

	if($hasil)
	{
		header("location: $fulldomain/user/ticketing.html");
		exit();
	}
	else
	{
		header("location: $fulldomain/user/ticketing.html");
		exit();
	}
?>