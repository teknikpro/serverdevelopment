<?php
if (file_exists($lokasiweb . "/komponen/about-simpo/about-simpo.$aksi.php")) include("$kanal.$aksi.php");
else {
    $aksi = "list";
    include("$kanal.list.php");
    $aksi = "slide";
    include($lokasiweb . "/komponen/simposium/simposium.slide.php");
    $aksi = "read";
    include($lokasiweb . "/komponen/simposium/simposium.read.php");
}

$tpl->display("$kanal.html");
