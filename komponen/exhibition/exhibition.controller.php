<?php
include($lokasiweb . "/komponen/home/home.all.php");
if (file_exists($lokasiweb . "/komponen/exhibition/exhibition.$aksi.php")) include("exhibition.$aksi.php");
else {
    $aksi = "list";
    include("exhibition.list.php");
}

include("exhibition.pilihan.php");

$tpl->display("$kanal.html");
