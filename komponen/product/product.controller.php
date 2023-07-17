<?php 
include($lokasiweb."/komponen/home/home.all.php");
if(file_exists($lokasiweb."/komponen/product/product.$aksi.php")) include("product.$aksi.php");
else{ $aksi = "list";  include("product.list.php"); }

include("product.pilihan.php");

$tpl->display("$kanal.html");

?>
