<?php
if(file_exists($lokasiweb."/komponen/tiket/tiket.$aksi.php")) include("$kanal.$aksi.php");
else{ $aksi = "list";  include("$kanal.list.php"); }

$tpl->display("$kanal.html");

?>