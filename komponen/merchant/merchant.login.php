<?php
if(isset($_POST['user']))
{
	$user 		= $_POST['user'];
	$user 		= str_replace("'","`",$user);
	$pass 		= $_POST['pass'];
	$password 	= md5($pass);	
	$perintah	= "select userid,userfullname,username,userdirname,avatar,verified,fbcid,twcid,gpcid,tipe,userlastloggedin,merchant from tbl_member where (username='$user' or useremail='$user') and userpassword='$password' and useractivestatus='1'";
	$hasil 		= sql($perintah);
	
	if(sql_num_rows($hasil)<1)
	{
		header("location: $fulldomain/merchant/usernameerror");
		exit();
	}
	else
	{

		$row 			= sql_fetch_data($hasil);
 		$userid  		= $row['userid'];
		$username 		= $row['username'];
		$userfullname 	= $row['userfullname'];
		$userdirname 	= $row['userdirname'];
		$avatar 		= $row['avatar'];
		$verified 		= $row['verified'];
		$fbcid 			= $row['fbcid'];
		$twcid 			= $row['twcid'];
		$gpcid 			= $row['gpcid'];
		$refuserid 		= $row['refuserid'];
		$tipe 		= $row['tipe'];
		$lastlogin = $row['userlastloggedin'];
		$merchant	= $row['merchant'];

		if($merchant == 0){
			header("location: $fulldomain/merchant/usernameerror");
			exit();
		}else {

		session_start();	
		$_SESSION['userid'] 		= $userid;
		$_SESSION['userfullname'] 	= $userfullname;
		$_SESSION['username'] 		= $username;
		$_SESSION['userdirname'] 	= $userdirname;
		$_SESSION['verified'] 		= $verified;
		$_SESSION['avatar'] 		= $avatar;
		$_SESSION['pss'] 			= $pass;
		$_SESSION['fbcid'] 			= $fbcid;
		$_SESSION['twcid'] 			= $twcid;
		$_SESSION['gpcid'] 			= $gpcid;
		$_SESSION['contactid'] 		= $refuserid;
		$_SESSION['usertipe'] 		= $tipe;
		
		
		$userlastloggedin = date("Y-m-d H:i:s");
		
		$tnow = substr($userlastloggedin,0,10);
		$tlast = substr($lastlogin,0,10);
		
		if($tnow!=$tlast)
		{
			earnpoin("login",$_SESSION['userid']);
		}
		
		$views = "update tbl_member_stats set login=login+1 where userid='$userid'";
		$hsl = sql( $views);
		
		$views = "insert into tbl_member_log(tipe,userid,create_date,via) values('login','$userid','$userlastloggedin','web')";
		$hsl = sql( $views);
		
		$views = "update tbl_member set userlastloggedin='$userlastloggedin',userlastactive='$userlastloggedin' where userid='$userid'";
		$hsl = sql( $views);

				
		if((!$_SESSION['last']) || ($_SESSION['last']=="/")) $pelempar = "$fulldomain/merchant"; 
		else $pelempar = $_SESSION['last'];
		
		
		
		header("Location: $pelempar");
		exit();
			
		}


	}
}
?>	
