<?php 
include("./komponen/home/home.tags.php");
include("./komponen/radio/radio.depan.php");
include("./komponen/konten/konten.menu.php");

//Banner
include($lokasiweb."/komponen/banner/A1.php");
include($lokasiweb."/komponen/banner/C1.php");
/*include($lokasiweb."/komponen/banner/C2.php");
*/
if ($_SESSION['avatar'])
	$linkphoto="$fulldomain/uploads/avatars/$_SESSION[avatar]";
else
	$linkphoto="$lokasiwebtemplate/images/no_pic.jpg";

	$tpl->assign("linkphoto",$linkphoto);


/*if(isset($_COOKIE['cookses']) and empty($_SESSION['username']) ){

	$user = sql_get_var("select username from tbl_sesi where sesi='$_COOKIE[cookses]' ");
	
	if (!empty($user))
	{

		$perintah	= "select userid,userfullname,username,userdirname,avatar,verified,fbcid,twcid,gpcid from tbl_member where username='$user'	and useractivestatus='1'";
		$hasil 		= sql($perintah);
		
		if(sql_num_rows($hasil)<1)
		{
			header("location: $fulldomain/member/usernameerror");
			exit();
		}
		else
		{
			$row 	= sql_fetch_data($hasil);
			$userid  		= $row['userid'];
			$username 		= $row['username'];
			$userfullname 	= $row['userfullname'];
			$userdirname 	= $row['userdirname'];
			$avatar 		= $row['avatar'];
			$verified 		= $row['verified'];
			$fbcid 			= $row['fbcid'];
			$twcid 			= $row['twcid'];
			$gpcid 			= $row['gpcid'];
	
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

			$userlastloggedin = date("Y-m-d H:i:s");
			
			
			$views = "update tbl_member_stats set login=login+1 where userid='$userid'";
			$hsl = sql( $views);
			
			$views = "update tbl_member set userlastloggedin='$userlastloggedin',userlastactive='$userlastloggedin' where userid='$userid'";
			$hsl = sql( $views);
			
			if((!$_SESSION['last']) || ($_SESSION['last']=="/")) $pelempar = "$fulldomain/member";
			else $pelempar = $_SESSION['last'];
			
			header("Location: $pelempar");
			exit();
		}	
			
	}
	else
	{
	 unset($_COOKIE['cookses']);
	 setcookie("cookses", '', time() - 3600);
	}

}
*/
?>
