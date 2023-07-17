<?php
if (file_exists($lokasiweb . "/komponen/sasaran-kegiatan/sasaran-kegiatan.$aksi.php")) include("$kanal.$aksi.php");
else {
    $aksi = "list";
    include("$kanal.list.php");
}

$tpl->display("$kanal.html");
