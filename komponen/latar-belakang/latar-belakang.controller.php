<?php
if(file_exists($lokasiweb."/komponen/latar-belakang/latar-belakang.$aksi.php")) include("$kanal.$aksi.php");
else{ $aksi = "list";  include("$kanal.list.php"); }

$tpl->display("$kanal.html");
