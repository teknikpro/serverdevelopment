<?php
	
	$perintah 	= "select userid,userpob,userdob,usergender,username,userfullname,useraddress,cityname,userphone,userphonegsm,
					userhomepage,useremail,aboutme,userreligion,userpostcode,negaraid,propinsiid,affiliations,companies,schools,
						wiseword,marriagestatusid,ymid,fbid,twitterid,userhobi,pvbirth,pvemail,pvaddress,pvphonegsm,pvprofile,
						pvmessage,posting,follower,following,tema,header,tipe,statsupdate,avatar from tbl_member where username='$username'";

	$hasil		= sql($perintah);
	$data 		= sql_fetch_data($hasil);
	$uid	 			= $data['userid'];
	$userpob 			= $data['userpob'];
	$userdob 			= tanggalonly($data['userdob']);
	$usergender 		= $data['usergender'];
	$name	 			= $data['username'];
	$userfullname		= $data['userfullname'];
	$useraddress		= $data['useraddress'];
	$cityname			= $data['cityname'];
	$userphone			= $data['userphone'];
	$userphonegsm		= $data['userphonegsm'];
	$userhomepage		= $data['userhomepage'];
	$useremail			= $data['useremail'];
	$aboutme			= nl2br($data['aboutme']);
	$userreligion  	 	= $data['userreligion'];
	$userpostcode		= $data['userpostcode'];
	$negaraid			= $data['negaraid'];
	$propinsiid			= $data['propinsiid'];
	$affiliations		= $data['affiliations'];
	$companies			= $data['companies'];
	$schools 			= $data['schools'];
	$wiseword 			= $data['wiseword'];
	$marriagestatusid	= $data['marriagestatusid'];
	$ymid				= $data['ymid'];
	$fbid				= $data['fbid'];
	$twitterid			= $data['twitterid'];
	$userhobi			= $data['userhobi'];
	$pvbirth			= $data['pvbirth'];
	$pvemail			= $data['pvemail'];
	$pvaddress			= $data['pvaddress'];
	$pvphonegsm			= $data['pvphonegsm'];
	$pvprofile			= $data['pvprofile'];
	$pvmessage			= $data['pvmessage'];
	$gpcid				= $data['gpcid'];
	$fbcid				= $data['fbcid'];
	$twid				= $data['twid'];
	//$posting 			= $data['posting'];
	$follower 			= $data['follower'];
	$following 			= $data['following'];
	$tema	 			= $data['tema'];
	$header	 			= $data['header'];
	$tipe	 			= $data['tipe'];
	$statsupdate		= $data['statsupdate'];
	$avatar				= $data['avatar'];
	
	$posting	= sql_get_var("select count(*) as jml from tbl_post where userid='$uid'");
	
	if(empty($avatar)){ $avatar = "$domain/images/no_pic.jpg"; 
	//$avatar = "$domain/uploads/avatar/$uid.jpg";
	}
	else
	{
		$avatar = str_replace("-m","-f",$avatar);				
		$avatar = "$lokasiwebmember/avatars/$avatar";
		//$avatar = "$domain/uploads/avatar/$uid.jpg";
	}
	
	if($statsupdate==0)
	{
		
		$follower	= sql_get_var("select count(*) as jml from tbl_follow where fid='$uid'");
		$following	= sql_get_var("select count(*) as jml from tbl_follow where userid='$uid'");
		
		
		if(!empty($uid))
		{
			$insert  = "update tbl_member set follower='$follower',following='$following',posting='$posting',statsupdate='1' where userid='$uid'";
			$sinsert = sql($insert);
		}
	}
	
	if(empty($header))
		$header = "$fulldomain/images/no-cover.jpg";
	
	//Negara belum ada tabel nya
	/*$namanegara = sql_get_var("select namanegara from tbl_negara where id='$negaraid'");
	$tpl->assign("namanegara",$namanegara);*/
	
	$skr = date("Y-m-d");
	$umur = $skr - $data['userDOB'];
	
	
	
	$tpl->assign("userdob",$userdob);
	$tpl->assign("userpob",$userpob);
	$tpl->assign("usergender",$usergender);
	$tpl->assign("nama",$name);
	$tpl->assign("namefull",$userfullname);
	$tpl->assign("useraddress",$useraddress);
	$tpl->assign("cityname",$cityname);
	$tpl->assign("userphone",$userphone);
	$tpl->assign("userphonegsm",$userphonegsm);
	$tpl->assign("userFlexiPhone",$userFlexiPhone);
	$tpl->assign("userhomepage",$userhomepage);
	$tpl->assign("useremail",$useremail);
	$tpl->assign("aboutme",$aboutme);
	$tpl->assign("userreligion",$userreligion);
	$tpl->assign("userpostcode",$userpostcode);
	$tpl->assign("negaraid",$negaraid);
	$tpl->assign("propinsiid",$propinsiid);
	$tpl->assign("marriagestatusid",$marriagestatusid);
	$tpl->assign("companies",$companies);
	$tpl->assign("schools",$schools);
	$tpl->assign("wiseword",$wiseword);
	$tpl->assign("affiliations",$affiliations);
	$tpl->assign("ymid",$ymid);
	$tpl->assign("userhobi",$userhobi);
	$tpl->assign("fbcid",$fbcid);
	$tpl->assign("twid",$twid);
	$tpl->assign("gpcid",$gpcid);
	$tpl->assign("twitterid",$twitterid);
	$tpl->assign("fbid",$fbid);
	$tpl->assign("umur",$umur);
	$tpl->assign("pvbirth",$pvbirth);
	$tpl->assign("pvemail",$pvemail);
	$tpl->assign("pvaddress",$pvaddress);
	$tpl->assign("pvphonegsm",$pvphonegsm);
	$tpl->assign("pvprofile",$pvprofile);
	$tpl->assign("pvmessage",$pvmessage);
	$tpl->assign("posting",$posting);
	$tpl->assign("follower",$follower);
	$tpl->assign("following",$following);
	$tpl->assign("tema",$tema);
	$tpl->assign("header",$header);
	$tpl->assign("tipe",$tipe);
	
	if($uid==$_SESSION[userId]) $showmenu=1;
	else $showmenu=0;
	$tpl->assign("showmenu",$showmenu);
	
	
	if($name==$_SESSION['username'])
		$tampilPost = "1";
	else
	{
		if($tipe != 0)
			$tampilPost = "0";
		else
			$tampilPost = "1";
	}
	$tpl->assign("tampilPost",$tampilPost);
		
		
	$tpl->assign("stat",$stat);
	$tpl->assign("linkadd",$linkadd);
	$tpl->assign("linkapp",$linkapp);
	$tpl->assign("linkdel",$linkdel);
	$tpl->assign("app",$app);
	$tpl->assign("ada",$ada);
	$tpl->assign("addfriends",$addfriends);
	
	$music = 0;
	$video = 0;
?>