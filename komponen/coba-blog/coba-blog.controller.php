<?php
if(file_exists($lokasiweb."/komponen/coba-blog/coba-blog.$aksi.php")) include("coba-blog.$aksi.php");
else{ $aksi = "list";  include("coba-blog.list.php"); }

include("coba-blog.menu.php");
include($lokasiweb."/komponen/home/home.all.php");

include($lokasiweb."/komponen/coba-blog/coba-blog.rekomendasi.php");
include($lokasiweb."/komponen/coba-blog/coba-blog.populer.php");

$tpl->display("$kanal.html");
