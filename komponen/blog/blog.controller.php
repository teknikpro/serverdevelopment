<?php
if(file_exists($lokasiweb."/komponen/blog/blog.$aksi.php")) include("blog.$aksi.php");
else{ $aksi = "list";  include("blog.list.php"); }

include("blog.menu.php");
include($lokasiweb."/komponen/home/home.all.php");

include($lokasiweb."/komponen/blog/blog.rekomendasi.php");
include($lokasiweb."/komponen/blog/blog.populer.php");

$tpl->display("$kanal.html");

?>
