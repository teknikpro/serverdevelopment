<?php 
include($lokasiweb."/komponen/home/home.slide.php");
include($lokasiweb."/komponen/home/home.menu.php");
include($lokasiweb."/komponen/product/product.depan.php");
include($lokasiweb."/komponen/home/home.headline.php");
include($lokasiweb."/komponen/konsultasi/konsultasi.depan.php");
include($lokasiweb."/komponen/world/world.depan.php");
include($lokasiweb."/komponen/home/home.all.php");  

//Banner
include($lokasiweb."/komponen/banner/banner.homemain1.php"); 
include($lokasiweb."/komponen/banner/banner.homemain2.php"); 
include($lokasiweb."/komponen/banner/banner.homebawah1.php"); 

$tpl->display("index.html");
?>