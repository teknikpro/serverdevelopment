<?php 
if(file_exists($lokasiweb."/komponen/$kanal/$kanal.$aksi.php")) include("$kanal.$aksi.php");
else{ 
    $aksi = "list";  include("$kanal.list.php");
    $aksi = "list";  include($lokasiweb . "/komponen/simposium/simposium.slide.php");
}

include("$kanal.menu.php");
//include("$kanal.populer.php");

$tpl->display("$kanal.html");
