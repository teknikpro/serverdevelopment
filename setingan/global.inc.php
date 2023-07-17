<?php
//Konfigurasi Database
$hostname = "localhost";
$username = "q8001_dfun";
$password = "dfun@u1ng";
$database = "dfunstation";
//$database = "databasetest2";

//Konfigurasi Letak File dan Folder
$perintah_delete         = "rm";
$lokasiweb                = "/var/www/html/";
$pathfile                 = "$lokasiweb/gambar/";
$lokasimember             = "$lokasiweb/uploads/";
$lokasimedia             = "$lokasiweb/medias/";
$slash_jenis             = "/";
$webfolder                = "/rma";


//SMS
//$smshost    = "sms.dfunstation.com";
//$smshost    = "10.35.79.54";
$smshost      = "632eabd6f559.ngrok.io";
$smsapikey  = "b1smillah";

//GCaptcha
$sitekey    = "6LcedbsUAAAAANbYHS3hYdLMk2zxnEcf6t3_TnSM";
$secret_key = "6LcedbsUAAAAAJBYRK1ca32KJIi8x8O2917x1STU";

// midtrans percobaan
// $merchantid        = "G484705173";
// $serverkey        = "SB-Mid-server-7whkLV7OGrZW6UQn4NqLCdBn";
// $clientid        = "SB-Mid-client-2J8SaaUaz8fwzOCw";
// $isProduction    = false;


// midtrans percobaan 2
// $merchantid        = "G484705173";
// $serverkey        = "Mid-server-ndnD2050KfSNmO1yszUqJKGy";
// $clientid        = "Mid-client-7sOxkcVp98akKJeo";
// $isProduction    = true;


//Konfigurasi Veritrans Production
$merchantid        = "G758203936";
$serverkey        = "Mid-server-KCPKYSP2xw-W7Uvl_9Svbp3O";
$clientid        = "Mid-client-FfMLixYm6nWQjUdI";
$isProduction    = true;



// $merchantid        = "G758203936";
// $serverkey        = "SB-Mid-server-arfibzE6v_rqh0Rr2r0KGIqq";
// $clientid        = "SB-Mid-client-9I7ipTn48vTfv3tq";
// $isProduction    = false;



/*$merchantid		= "G127652355";
$serverkey		= "Mid-server-PFw2MJt3uLpWA4pQaZu8Y-pd";
$clientid		= "Mid-client-VBuSnOhMejI4wJKz";
$isProduction	= true;*/

/*$merchantid		= "G127652355";
$serverkey		= "SB-Mid-server-paXAoBTOxwT8B2oWLbkAI2Zz";
$clientid		= "SB-Mid-client-EljnXDu5zpJC8iad";
$isProduction	= false;*/



//coba

//Poin
$pointexpire = 12;

//Lakukan Koneksi dalam database
$connect = mysqli_connect($hostname, $username, $password);
if (!$connect) die("Could not establish connection");
mysqli_select_db($connect, $database);
