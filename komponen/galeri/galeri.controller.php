<?php 
if(file_exists($lokasiweb."/komponen/$kanal/$kanal.$aksi.php")) include("$kanal.$aksi.php");
else{ $aksi = "list";  include("$kanal.list.php"); }

include($lokasiweb."/komponen/galeri/galeri.samping.php");


$tpl->display("$kanal.html");

?>
