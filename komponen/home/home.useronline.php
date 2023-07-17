<?php 
$timeoutseconds 	= 300;
$timestamp = time();                                                                                            
$timeout = $timestamp-$timeoutseconds; 

$ip = $_SERVER['REMOTE_ADDR'];

$perintah = "delete FROM tbl_useronline where waktu < $timeout";
$hasil = sql($perintah);

$perintah = "delete FROM tbl_guestonline where waktu < $timeout";
$hasil = sql($perintah);

if($_SESSION['username'])
{
	$perintah = "select count(*) as jumlah from tbl_useronline where username='$_SESSION[username]'";
	$hasil = sql($perintah);
	$data = sql_fetch_data($hasil);
	$jumlah = $data['jumlah'];
	
	if($jumlah > 0)
	{
		$perintah = "update tbl_useronline set waktu=$timestamp where username='$_SESSION[username]'";
		$hasil = sql($perintah);
	}
	else
	{
		$perintah = "insert into tbl_useronline(userid,username,ip,waktu) values ('$_SESSION[userid]','$_SESSION[username]','$ip','$timestamp')";
		$hasil = sql($perintah);
	}
}
else
{
	$perintah = "select count(*) as jumlah from tbl_guestonline where ip='$ip'";
	$hasil = sql($perintah);
	$data = sql_fetch_data($hasil);
	$jumlah = $data['jumlah'];
	
	if($jumlah > 0)
	{
		$perintah = "update tbl_guestonline set waktu=$timestamp where ip='$ip'";
		$hasil = sql($perintah);
	}
	else
	{
		$perintah = "insert into tbl_guestonline(ip,waktu) values ('$ip','$timestamp')";
		$hasil = sql($perintah);
	}
}
?>