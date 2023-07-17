<?php
if(file_exists($lokasiweb."/komponen/berita/berita.$aksi.php")) include("berita.$aksi.php");
else{ $aksi = "list";  include("berita.list.php"); }

include("berita.menu.php");
include($lokasiweb."/komponen/berita/berita.populer.php");

$tpl->display("$kanal.html");

?>
