<?php 
	$tanggal = date("Y-m-d H:i:s");
	$query=("update tbl_member set userlastactive='$tanggal' where username='$_SESSION[username]'");
	$hasil = sql($query);
	
	$query= "delete from tbl_sesi where sesi='$_COOKIE[cookses]'";
	$hasil = sql($query);

	 unset($_COOKIE['cookses']);
	 setcookie("cookses", '', time() - 3600);

	
	unset($_SESSION['userid'],$_SESSION['username'],$_SESSION['userfullname'],$_SESSION['userdirname']);
	session_destroy();
	
	
	header("location: $fulldomain/konsultan");	
	exit();
?>
