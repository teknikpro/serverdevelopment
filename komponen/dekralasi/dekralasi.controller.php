<?php
if (file_exists($lokasiweb . "/komponen/dekralasi/dekralasi.$aksi.php")) include("$kanal.$aksi.php");
else {
    $aksi = "list";
    include("$kanal.list.php");
}

$tpl->display("$kanal.html");
