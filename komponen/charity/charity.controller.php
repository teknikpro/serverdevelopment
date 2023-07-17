<?php
include($lokasiweb . "/komponen/home/home.all.php");
if (file_exists($lokasiweb . "/komponen/charity/charity.$aksi.php")) include("charity.$aksi.php");
else {
    $aksi = "list";
    include("charity.list.php");
}

include("charity.pilihan.php");

$tpl->display("$kanal.html");
