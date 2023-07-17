<?php
if(file_exists($lokasiweb."/komponen/donasimain/donasimain.$aksi.php")) include("donasimain.$aksi.php");
else{ $aksi = "list";  include("donasimain.list.php"); }

$tpl->display("$kanal.html");

?>
