<?php 
if(file_exists($lokasiweb."/komponen/$kanal/$kanal.$aksi.php")) include("$kanal.$aksi.php");
else{ $aksi = "list";  include("$kanal.list.php"); }

include("$kanal.menu.php");
include("$kanal.populer.php");

$tpl->display("$kanal.html");

?>
