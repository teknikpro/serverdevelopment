<?php
	$tpl->assign("namarubrik","Tambah Review");
	
	if($_POST['aksi']=="save")
	{

		$komentar = cleanInsert($_POST['komentar']);
		$produkpostid = $_POST['produkpostid'];
		$userid = $_SESSION['userid'];

		$sql = "select userfullname,useremail from tbl_member where userid='$userid'";
		$hsl = sql($sql);
		$data = sql_fetch_data($hsl);
		$userfullname = $data['userfullname'];
		$useremail = $data['useremail'];
		
		$query	= "INSERT INTO tbl_product_comment (commentid, produkpostid, userid, nama, email, komentar, create_date,published) 
				VALUES ('$newid', '$produkpostid', '$_SESSION[userid]', '$userfullname', '$useremail', '$komentar', '$date','1')";
		$query1 	= sql($query);
		if($query1)
		{
			$userFullName = sql_get_var("SELECT userFullName from tbl_member where userid='$_SESSION[userid]'");
			//Setlog kedalam Sistem
			//setsyslog($userid,"0","$userFullName membuat ticketing baru di $title","index.php?tab=18&tabsub=98&kanal=ticketing&aksi=view&status=0");

			//header("location: $fulldomain/user/ticketing");
			
			$error 		= "Penambahan data review berhasil dilakukan. Terima kasih.";
			$style		= "alert-success";
			$backlink	= "$fulldomain/user/review.html";
		}
		else
		{
			$error 		= "Penyimpanan data gagal dilakukan ada beberapa kesalahan yang harus diperbaiki, silahkan periksa kembali.";
			$style		= "alert-danger";
			$backlink	= "$fulldomain/user/addreview.html";
		}

		$tpl->assign("error",$error);
		$tpl->assign("style",$style);
		$tpl->assign("backlink",$backlink);
	}

	$produkpostid = $var[4];
	$jml_review = sql_get_var("select count(*) from tbl_product_comment where produkpostid='$produkpostid' ");
	$namaproduk = sql_get_var("select title from tbl_product_post where produkpostid='$produkpostid' ");
	$tpl->assign("jml_review",$jml_review);
	$tpl->assign("produkpostid",$produkpostid);
	$tpl->assign("namaproduk",$namaproduk);
	

	$aku = "select commentid,komentar,userid,create_date from tbl_product_comment where produkpostid='$produkpostid' order by create_date desc";
	$aka = sql($aku);
	$list_comment = array();
	while ($aki = sql_fetch_data($aka)) {
		$commentid = $aki['commentid'];
		$userid = $aki['userid'];
		$create_date = waktu_lalu($aki['create_date']);
		$kom = $aki['komentar'];

		$userfullname = sql_get_var("select userfullname from tbl_member where userid='$userid'");
		$useremail = sql_get_var("select useremail from tbl_member where userid='$userid'");

		$gambar		= get_gravatar($useremail);

		$list_comment[$commentid] = array("commentid"=>$commentid,"userfullname"=>$userfullname,"komentar"=>$kom,"gambar"=>$gambar,"create_date"=>$create_date);
	}
	sql_free_result($aka);
	$tpl->assign("list_comment",$list_comment);
	
?>