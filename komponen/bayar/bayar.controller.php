<?php
if (file_exists($lokasiweb . "/komponen/bayar/bayar.$aksi.php")) include($lokasiweb . "komponen/bayar/bayar.$aksi.php");
else {
	include "bayar.buy.php";
}

if ($aksi == "pay") {
	$tpl->display("pay.html");
} elseif ($aksi == "payklikbca") {
	$tpl->display("payklikbca.html");
} elseif ($aksi == "print") {
	$tpl->display("cetak.html");
} else $tpl->display("cart-simpo.html");
