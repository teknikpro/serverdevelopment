<?php
if (file_exists($lokasiweb . "/komponen/artikel/artikel.$aksi.php")) include("artikel.$aksi.php");
else {
    $aksi = "list";
    include("artikel.list.php");
}

// include("blog.menu.php");
// include($lokasiweb."/komponen/home/home.all.php");

// include($lokasiweb."/komponen/blog/blog.rekomendasi.php");
// include($lokasiweb."/komponen/blog/blog.populer.php");

$tpl->display("$kanal.html");
