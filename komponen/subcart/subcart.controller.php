<?php	
if(file_exists($lokasiweb."/komponen/subcart/subcart.$aksi.php")) include($lokasiweb."komponen/subcart/subcart.$aksi.php");
else{
	include "subcart.buy.php";
}

if($aksi=="pay")
{
	$tpl->display("pay.html");
}
elseif($aksi=="payklikbca")
{
	$tpl->display("payklikbca.html");
}
elseif($aksi=="print")
{
	$tpl->display("cetak.html");
}
else $tpl->display("cart.html");
?>