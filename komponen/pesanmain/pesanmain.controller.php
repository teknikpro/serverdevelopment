<?php
if(file_exists($lokasiweb."/komponen/pesanmain/pesanmain.$aksi.php")) include("pesanmain.$aksi.php");
else{ $aksi = "list";  include("pesanmain.list.php"); }

$tpl->display("$kanal.html");

?>
