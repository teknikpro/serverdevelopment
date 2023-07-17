<?php
if (file_exists($lokasiweb . "/komponen/tiketdetail/tiketdetail.$aksi.php")) include("$kanal.$aksi.php");
else {
    $aksi = "list";
    include("$kanal.list.php");
}

$tpl->display("tiketdetail.html");
