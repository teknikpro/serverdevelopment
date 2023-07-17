<?php 
include($lokasiweb."/komponen/home/home.all.php");
if(file_exists($lokasiweb."/komponen/lelang/lelang.$aksi.php")) include("lelang.$aksi.php");
else{ $aksi = "list";  include("lelang.list.php"); }

include("lelang.pilihan.php");

$tpl->display("$kanal.html");
