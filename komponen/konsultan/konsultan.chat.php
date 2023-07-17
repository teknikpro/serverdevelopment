<?php
$subaksi = $var[4];
$lastid = $var[7];
$tpl->assign("subaksi",$subaksi);
		

if($subaksi=="stream")
{
	header('Content-Type: application/json');
	header('Access-Control-Allow-Origin:*');
	header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
	header("Cache-Control: post-check=0, pre-check=0", false);
	header("Pragma: no-cache");

	$chatid = $var[5];

	$chat = sql_get_var_row("select chat_id,from_userid,to_userid from tbl_chat where chat_id='$chatid' and isdelete='0'");
	$chatid = $chat['chat_id'];
	$from_userid = $chat['from_userid'];
	$to_userid = $chat['to_userid'];

	$pg = sql_get_var_row("select userfullname,avatar from tbl_member where userid='$from_userid'");
	$pn = sql_get_var_row("select userfullname,avatar from tbl_member where userid='$to_userid'");
	
	$pengirim = $pg['userfullname'];
	$penerima = $pn['userfullname'];
	
	$avatarpengirim = "$fulldomain/uploads/avatar/$pg[avatar]";
	$avatarpenerima = "$fulldomain/uploads/avatar/$pn[avatar]";
	
	
	$function = $_POST['function'];

	$log = array();

	switch($function) {

		 case('getState'):
			 $jml = sql_get_var("select count(*) as jml from tbl_chat_message where chat_id='$chatid'");
			 $log['state'] = $jml; 
			 break;	
		
		 case('update'):
			
			if($lastid==0)
			{
				$sql = "select id,pesan,create_date,status,to_userid,from_userid,jenis,media,thumb,isread from tbl_chat_message where chat_id='$chatid' order by id asc";
			}
			else
			{
				$sql = "select id,pesan,create_date,status,to_userid,from_userid,jenis,media,thumb,isread from tbl_chat_message where chat_id='$chatid' and id > $lastid order by id asc";
			}
			
			$hsl = sql($sql);
			
			
			$text= array();
			while($dt=sql_fetch_data($hsl))
			{
				$id = $dt['id'];
				$pesan = $dt['pesan'];
				$tanggal = $dt['create_date'];
				$to_userid = $dt['to_userid'];
				$from_userid = $dt['from_userid'];
				$status = $dt['status'];
				$jenis = $dt['jenis'];
				$media = $dt['media'];
				$thumb = $dt['thumb'];
				$isread = $dt['isread'];
				
				if($isread=="1") $isread = "<span style=\"color:#09c5be\">&radic;&radic;</span>";
				else  $isread = "<span>&radic;&radic;</span>";
				
				sql("update tbl_chat_message set isread='1' where chat_id='$chatid' and id='$id' and to_userid='$to_userid'");
				
				if($jenis=="image")
				{
					$pesan = "<a href=\"$fulldomain/$media\" target=\"_blank\"><img src=\"$fulldomain/$thumb\"></a><br>$pesan";
				}
				else if($jenis=="audio")
				{
					$pesan = "<audio controls src=\"$fulldomain/$media\" style=\"width:100%\"></audio><br>$pesan";
				}
				else
				{
					$pesan = $dt['pesan'];
				}
			
				
				//explode tanggal
				$tgl		= explode(" ",$tanggal);
				$tegeel		= $tgl[0];
				$tegeel1	= tanggalsingkat($tegeel);
				$jam		= $tgl[1];
				$jam1		= $jam;
				//explode waktu
				$time		= explode(":",$jam1);
				$tm1		= $time[0];
				$tm2		= $time[1];
				$tm3		= $time[2];
				
				$skr		= date("Y-m-d");
			
				if($tm1>12)
					$ket	= "pm";
				else
					$ket	= "am";
			
				if($tegeel==$skr)
					$tgltampil	= $tm1.":".$tm2." ".$ket;
				else
					$tgltampil	= $tegeel1." at ".$tm1.":".$tm2." ".$ket;
				
				$avatar = sql_get_var("select avatar from tbl_member where userid='$from_userid'");
			
				if(empty($avatar)){ $gambar = "$fulldomain/images/no_pic.jpg"; }
				else
				{				
					$gambar = "$fulldomain/uploads/avatars/$avatar";
				}
				
				if($from_userid!=$_SESSION[userid])
				{
					$text[] = array("id"=>$id,"pesan"=>"<li class=\"message-right animated fadeinleft\"><img alt=\"\" src=\"$gambar\"><div class=\"message first\"><p>$pesan</p></div><span>$tgltampil $isread</span>");
				}
				else
				{
					$text[] = array("id"=>$id,"pesan"=>"<li class=\"message-left animated fadeinright\"><img alt=\"\" src=\"$gambar\"><div class=\"message first\"><p>$pesan</p></div><span>$tgltampil $isread</span>");
				}
			}
			$log['text'] = $text; 
			  
			 break;
		 
		 case('send'):
			 
			 $nickname = htmlentities(strip_tags($_POST['nickname']));
			 $reg_exUrl = "/(http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?/";
			 $message = htmlentities(strip_tags($_POST['message']));
			 if(($message) != "\n")
			 {
			
				 if(preg_match($reg_exUrl, $message, $url))
				 {
					$message = preg_replace($reg_exUrl, '<a href="'.$url[0].'" target="_blank">'.$url[0].'</a>', $message);
				 } 
			 
		
				$sql = "insert into tbl_chat_message(chat_id,from_userid,to_userid,create_date,pesan,status) values('$chatid','$_SESSION[userid]','$from_userid','$date','$message','0')";
				$hsl = sql($sql);
				
				$sql = "update tbl_chat set update_date=now() where chat_id='$chatid'";
				$hsl = sql($sql);
				
				sql("update tbl_chat set jmlchat=jmlchat+1 where chat_id='$chatid'");
				
				$pesan = substr($message,0,100);
				$title = "Pesan masuk untuk anda";		
			
				$data = array("title"=>$title,"message"=>$pesan,"tipe"=>$tipe,"kanal"=>"chat","aksi"=>"chat","postid"=>$chatid,"touserid"=>$_SESSION['userid']);
				
				sendgcmbyuserid($title,$pesan, $from_userid, $data);
				
			}
			 break;
		
	}

	echo json_encode($log);

	exit();
}
else if($subaksi=="start")
{
	$chatid = $var[5];
	$tpl->assign("userid",$_SESSION['userid']);
	$tpl->assign("chatid",$chatid);

	
}
else
{		
		$judul_per_hlm = 8;
		$batas_paging = 5;
		
		$hlm = $var[5];
		
		$sql = "select count(*) as jml from tbl_chat where to_userid='$_SESSION[userid]'";
		$hsl = sql($sql);
		$tot = sql_result($hsl, 0, jml);

		$tpl->assign("jml_post",$tot);
		
		$hlm_tot = ceil($tot / $judul_per_hlm);		
		if (empty($hlm)){
			$hlm = 1;
		}
		if ($hlm > $hlm_tot){
		$hlm = $hlm_tot;
		}
		$ord = ($hlm - 1) * $judul_per_hlm;
		if ($ord < 0 ) $ord=0;
		
		$perintah	= "select from_userid,chat_id,to_userid,create_date,finish from tbl_chat where to_userid='$_SESSION[userid]' and jmlchat>1 order by create_date desc limit $ord, $judul_per_hlm";
		$hasil		= sql($perintah);
		$datadetail	= array();
		$no = 0;
		while($data = sql_fetch_data($hasil))
		{
			$tanggal = $data['create_date'];
			$nama = $data['nama'];
			$id = $data['chat_id'];
			$ringkas = $data['ringkas'];
			$alias = $data['alias'];
			$tanggal = tanggal($tanggal);
			$from_userid = $data['from_userid'];
			$finish = $data['finish'];
			
			$user = getprofileid($from_userid);
			
			 
			$jml = sql_get_var("select count(*) as jml from tbl_chat_message where to_userid='$_SESSION[userid]' and chat_id='$id'");
			$jmlbelum = sql_get_var("select count(*) as jml from tbl_chat_message where to_userid='$_SESSION[userid]' and chat_id='$id' and isread='0'");
			
			if($jml>0)
			{ 
				
				$link = "$fulldomain/konsultan/chat/start/$id";
				 
				$no++;
				$datadetail[$id] = array("id"=>$id,"no"=>$i,"user"=>$user,"tanggal"=>$tanggal,"link"=>$link,"finish"=>$finsih,"gambar"=>$gambar,"status"=>$publish,"jmlchat"=>$jml,"jmlnoread"=>$jmlbelum);
			}
		}
		sql_free_result($hasil);
		$tpl->assign("datadetail",$datadetail);
		
		//Paging 
		$batas_page =5;
		
		$stringpage = array();
		$pageid =0;
		
		$Selanjutnya 	= "&rsaquo;";
		$Sebelumnya 	= "&lsaquo;";
		$Akhir			= "&raquo;";
		$Awal 			= "&laquo;";
		
		if ($hlm > 1){
			$prev = $hlm - 1;
			$stringpage[$pageid] = array("nama"=>"$Awal","link"=>"$fulldomain/konsultan/$aksi/list/1");
			$pageid++;
			$stringpage[$pageid] = array("nama"=>"$Sebelumnya","link"=>"$fulldomain/konsultan/$aksi/list/$prev");

		}
		else {
			$stringpage[$pageid] = array("nama"=>"$Awal","link"=>"");
			$pageid++;
			$stringpage[$pageid] = array("nama"=>"$Sebelumnya","link"=>"");
		}
		
		$hlm2 = $hlm - (ceil($batas_page/2));
		$hlm4= $hlm+(ceil($batas_page/2));
		
		if($hlm2 <= 0 ) $hlm3=1;
		   else $hlm3 = $hlm2;
		$pageid++;
		for ($ii=$hlm3; $ii <= $hlm_tot and $ii<=$hlm4; $ii++){
			if ($ii==$hlm){
				$stringpage[$pageid] = array("nama"=>"$ii","link"=>"","class"=>"active");
			}else{
				$stringpage[$pageid] = array("nama"=>"$ii","link"=>"$fulldomain/konsultan/$aksi/list/$ii");
			}
			$pageid++;
		}
		if ($hlm < $hlm_tot){
			$next = $hlm + 1;
			$stringpage[$pageid] = array("nama"=>"$Selanjutnya","link"=>"$fulldomain/konsultan/$aksi/list/$next");
			$pageid++;
			$stringpage[$pageid] = array("nama"=>"$Akhir","link"=>"$fulldomain/konsultan/$aksi/list/$hlm_tot");
		}
		else
		{
			$stringpage[$pageid] = array("nama"=>"$Selanjutnya","link"=>"");
			$pageid++;
			$stringpage[$pageid] = array("nama"=>"$Akhir","link"=>"");
		}
		$tpl->assign("stringpage",$stringpage);
		//Selesai Paging
}

?>