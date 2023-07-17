<?php 
if(isset($_POST['submit']))
{
	if($_FILES["userfiles"][size]>0)
	{
		$userDirName		= $_SESSION[userDirName];
		$date	= date("Ym");
		$simpan_file		= "$lokasimember"."userfiles/$date";
		if(!file_exists("$lokasimember"."userfiles/$date")) mkdir("$lokasimember"."userfiles/$date");
		
		$filetmpname	= $_FILES['userfiles']['tmp_name'];
				
		$imageinfo 		= getimagesize($filetmpname);
		$imagewidth 	= $imageinfo[0];
		$imageheight 	= $imageinfo[1];
		$imagetype 		= $imageinfo[2];
		
		$ext = getimgext($_FILES['userfiles']);
			
		$namagambarl		= "tema-$date-".$_SESSION['userid'].".$ext";
		$namagambars		= "tema-$date-".$_SESSION['userid']."-kecil.$ext";
		$uploadGambar 		= resizeimg($_FILES['userfiles']['tmp_name'],"$simpan_file/$namagambarl",$imagewidth,$imageheight);
		$uploadGambars 		= resizeimg($_FILES['userfiles']['tmp_name'],"$simpan_file/$namagambars",250,250);
		if($uploadGambar)
		{
			$lokasibackground	= "$fulldomain/uploads/userfiles/$date/$namagambarl";
			
			$lokasibackground2	= "$fulldomain/uploads/userfiles/$date/$namagambars";
		}
	}
	else
	{
		if($_POST[gambar])
		{
			$lokasibackground = $_POST[gambar];
			$lokasibackground2 = $_POST[gambarthumb];
			$lokasibackground = str_replace("-kecil","",$lokasibackground2);
		}
		else
		{
			$lokasibackground = $_POST[gambar1];
			$lokasibackground2 = $_POST[gambarthumb1];
			$lokasibackground = str_replace("-kecil","",$lokasibackground2);
		}
	}
	
	
	if($_FILES["header"][size]>0)
	{
		$userDirName		= $_SESSION[userDirName];
		$date	= date("Ym");
		$simpan_file		= "$lokasimember"."userfiles/$date";
		if(!file_exists("$lokasimember"."userfiles/$date")) mkdir("$lokasimember"."userfiles/$date");
		
		$filetmpname	= $_FILES['header']['tmp_name'];
				
		$imageinfo 		= getimagesize($filetmpname);
		$imagewidth 	= $imageinfo[0];
		$imageheight 	= $imageinfo[1];
		$imagetype 		= $imageinfo[2];
		
		$ext = getimgext($_FILES['header']);
			
		$namagambarl		= "header-$date-".$_SESSION['userid'].".$ext";
		$namagambars		= "header-$date-".$_SESSION['userid']."-kecil.$ext";
		$uploadGambar 		= resizeimg($_FILES['header']['tmp_name'],"$simpan_file/$namagambarl",$imagewidth,$imageheight);
		$uploadGambars 		= resizeimg($_FILES['header']['tmp_name'],"$simpan_file/$namagambars",250,250);
		if($uploadGambar)
		{
			$lokasiheader	= "$fulldomain/uploads/userfiles/$date/$namagambarl";
			
			$lokasiheader2	= "$fulldomain/uploads/userfiles/$date/$namagambars";
		}
	}
	else
	{
		$lokasiheader = $_POST[header1];
		$lokasiheader2 = $_POST[headerthumb1];
	}
	

	$sql	= "update tbl_member set tema='$lokasibackground' ,thumbtema='$lokasibackground2', header='$lokasiheader' ,thumbheader='$lokasiheader2' where username='$_SESSION[username]'";
	$hasil	= sql($sql);
	
   if($hasil)
   {
						   
	$pesanhasil = "Selamat Data anda di $title telah berhasil diupdate, Lakukan perubahan profil secara berkala disesuaikan dengan kondisi anda saat ini.";
	$berhasil = "1";
	}
				
	
$tpl->assign("pesan",$pesan);
$tpl->assign("pesanhasil",$pesanhasil);
$tpl->assign("berhasil",$berhasil);

}

	$query	= sql("select tema,thumbtema,header,thumbheader from tbl_member where username='$_SESSION[username]'");
	$row = sql_fetch_data($query);
	
	$lokasibackground = $row['thumbtema'];
	$lokasibackground2 = $row['tema'];
	$lokasiheader = $row['thumbheader'];
	$lokasiheader2 = $row['header'];
	
	$tpl->assign("backgroundprofile",$lokasibackground);
	$tpl->assign("backgroundbesar",$lokasibackground2);
	$tpl->assign("backgroundheader",$lokasiheader);
	$tpl->assign("headerbesar",$lokasiheader2);
	
	$sql1	= "select id, nama, gambar, gambar1 from tbl_tema where published='1' order by create_date desc";
	$res1	= sql($sql1);
	$bgData	= array();
	while ($row1 = sql_fetch_data($res1))
	{
		$id 	= $row1['id'];
		$nama 	= $row1['nama'];
		$gambar	= $row1['gambar'];
		$gambar2	= $row1['gambar1'];
		
		if(!empty($gambar))
			$lokasibackground = "$fulldomain/uploads/backgrounds/$gambar";
		else
			$lokasibackground = "";
		
		if(!empty($gambar2))
			$lokasibackground2 = "$fulldomain/uploads/backgrounds/$gambar2";
		else
			$lokasibackground2 = "";
		
		$bgData[$id]	= array("backgroundId"=>$id,"namabackground"=>$nama,"lokasibackground"=>$lokasibackground,"lokasibackground2"=>$lokasibackground2);
	}
	sql_free_result($res1);
	$tpl->assign("bgData",$bgData);
?>