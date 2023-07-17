<?php
if(file_exists($lokasiweb."/komponen/kreasimain/kreasimain.$aksi.php")) include("kreasimain.$aksi.php");
else{ $aksi = "list";  include("kreasimain.list.php"); }

$tpl->display("$kanal.html");

?>
