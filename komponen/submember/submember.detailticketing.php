<?php
	$ticketingid	= $var[4];
	$tpl->assign("ticketingid",$ticketingid);
	
	$sql1	= "select * from tbl_ticketing where ticketingid='$ticketingid'";
	$hsl1 	= sql($sql1);
	$row1 	= sql_fetch_data($hsl1);

	$userId 		= $row1['userid'];
	$pesan 			= $row1['isipertanyaan'];
	$pengirim 		= sql_get_var("select userfullname from tbl_member where userid='$userId'");
	$judul 			= $row1['judul'];
	$pesan 			= nl2br($pesan);
	$pesan 			= str_replace("#","<hr>",$pesan);
	$balas 			= $row1['status'];
	$close 			= $row1['is_closed'];
	$tanggal 		= tanggal($row1['update_date']);
	// $tanggal 		= tanggal($row1['create_date']);
	$filetanya		= $row1['filetanya'];
	$reply_user		= $row1['reply_user'];
	
	$tpl->assign("detailnama",$judul);
	$tpl->assign("close",$close);
	$tpl->assign("detailpesan",$pesan);
	$tpl->assign("tanggalpesan",$tanggal);
	$tpl->assign("detailpengirim",$pengirim);
	$tpl->assign("alias",getAlias($judul));
	
	if($_POST['aksi']=="save")
	{

		$komentar = cleanInsert($_POST['pesan']);
		$ticketingid = $_POST['ticketingid'];
		$userid = $_SESSION['userid'];
		
		$sql1	= "select * from tbl_ticketing where ticketingid='$ticketingid'";
		$hsl1 	= sql($sql1);
		$row1 	= sql_fetch_data($hsl1);
	
		$userId 		= $row1['userid'];
		$pesan 			= $row1['isipertanyaan'];
		$pengirim 		= sql_get_var("select userfullname from tbl_member where userid='$userId'");
		$judul 			= $row1['judul'];
		
		$newid			= newid("ticketingbalasid","tbl_ticketing_balas");
		
		$query	= "INSERT INTO tbl_ticketing_balas (ticketingbalasid, ticketingid, userid, judul, isijawaban, is_answer, create_date) 
				VALUES ('$newid', '$ticketingid', '$_SESSION[userid]', '$judul', '$komentar', '1', '$date')";
		$query1 	= sql($query);
		if($query1)
		{
			/*$userFullName = sql_get_var("SELECT userfullname from tbl_member where userid='$_SESSION[userid]'");
			$useremail = sql_get_var("SELECT useremail from tbl_member where userid='$_SESSION[userid]'");
			
			$contentemail	= sql_get_var("select keterangan from tbl_wording where alias='ticketing-baru' and jenis = 'email' limit 1");
			$contentemail	= str_replace("[userfullname]","$userFullName",$contentemail);
			$contentemail	= str_replace("[title]","$title",$contentemail);
			$contentemail	= str_replace("[judul]","$judul",$contentemail);
			$contentemail	= str_replace("[pertanyaan]","$isipertanyaan",$contentemail);
			$contentemail	= str_replace("[owner]","$owner",$contentemail);
			$contentemail	= str_replace("[fulldomain]","$fulldomain",$contentemail);
			
			//kirim email ke admin
			$to 		= "$support_email";
			$from 		= "$support_email";
			$subject 	= "Ticketing Respon, $judul";
			$message 	= "$contentemail";
			$headers 	= "From : $owner";
			
		
			$sendmail	= sendmail($title,$to,$subject,$message,$message,1);
			$sendmail	= sendmail($userFullName,$useremail,$subject,$message,$message,1);*/
			
			$perintah2	= "update tbl_ticketing set status='1',reply_user='1' where ticketingid='$ticketingid'";
			$hasil2		= sql($perintah2);
			
			$error 		= "Penambahan data ticketing berhasil dilakukan. Silahkan tunggu jawaban dari Admin $title. Terima kasih.";
			$style		= "alert-success";
			$backlink	= "$fulldomain/user/ticketing.html";
		}
		else
		{
			if(!$cekfile)
				$error 		= "Penyimpanan data gagal dilakukan ada beberapa kesalahan yang harus diperbaiki, silahkan periksa kembali. File tidak diizinkan untuk diupload, silahkan file yang berekstensi $fileallow";
			else
				$error 		= "Penyimpanan data gagal dilakukan ada beberapa kesalahan yang harus diperbaiki, silahkan periksa kembali.";
			$style		= "alert-danger";
			$backlink	= "$fulldomain/user/detailticketing.html";
		}

		$tpl->assign("error",$error);
		$tpl->assign("style",$style);
		$tpl->assign("backlink",$backlink);
	}
	
	$perintah 	= "select ticketingbalasid,isijawaban,create_date,judul,filejawab,is_answer,ticketingid,create_userid,userid from tbl_ticketing_balas where ticketingid='$ticketingid' order by ticketingbalasid asc";
	$hasil 		= sql($perintah);
	$jumlahpesan= sql_num_rows($hasil);
	while ($row = sql_fetch_data($hasil))
	{
		$ticketingbalasid 		= $row['ticketingbalasid'];
		$isijawaban 		= $row['isijawaban'];
		$tanggal 	= $row['create_date'];
		$userid 	= $row['userid'];
		$create_userid 	= $row['create_userid'];
		$datetime	= explode(" ",$tanggal);
		$date		= explode("-",$datetime[0]);
		
		if($datetime[0]==date("Y-m-d")) 
		{
			$tgl1	= date('h:i a', strtotime($tanggal));
		}
		elseif($date[0]==date("Y"))
		{
			$tgl1	= $date[2]."/".$date[1].", ".date('h:i a', strtotime($tanggal));
		}
		else
		{
			$tgl1	= $date[2]."/".$date[1]."/".substr($date[0],2,2).", ".date('h:i a', strtotime($tanggal));
		}
		
		if($userid == '0')
			$oleh		= sql_get_var("select userfullname from tbl_cms_user where userid='$create_userid'");
		else
			$oleh		= sql_get_var("select userfullname from tbl_member where userid='$userid'");
		
		if($pengirim==$_SESSION['userid']) $inboxnya = "outbox";
		else $inboxnya = "inbox";
		
		$listpesan[$ticketingbalasid] = array("ticketingbalasid"=>$ticketingbalasid,"judul"=>$judul,"tanggal"=>$tgl1,"oleh"=>$oleh,"isijawaban"=>$isijawaban,"baca"=>$baca,"inboxnya"=>$inboxnya);
	}
	sql_free_result($hasil);
	$tpl->assign("listpesan",$listpesan);
	
	if($pengirim==$_SESSION['userid']) $kirimuntuk = $penerima;
	else $kirimuntuk = $pengirim;
	$kirimnama = sql_get_var("select userfullname from tbl_member where userid='$kirimuntuk'");
	$tpl->assign("kirimuntuk",$kirimuntuk);
	$tpl->assign("kirimnama",$kirimnama);
	$tpl->assign("jumlahpesan",$jumlahpesan);
	
	$tpl->assign("namarubrik","Ticketing");
?>
