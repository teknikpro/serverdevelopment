<?php 

$produkpostid = $var[3];
//Informasi 
$sql	= "select kodeproduk, title, content, ringkas, harga, alatbahan,prosesprod,penggunaan,cart, body_dimension, body_weight,  create_date, userid,ispinjam,ispesan, brandid,icon from tbl_product_post where status='1' and produkpostid='$produkpostid' and published='1'";
$query	= sql($sql);
$row	= sql_fetch_data($query);
$namaprod			= $row["title$lang"];
$content			= $row["content"];
$ringkas			= bersihHTML($row["ringkas"]);
$cart				= $row['cart'];
$body_dimension		= $row['body_dimension'];
$body_weight		= $row['body_weight'];
$harga		= $row['harga'];
$postTime			= tanggaltok($row['create_date']);
$brandidnya			= $row['brandid'];
$misc_matauang		= $row['misc_matauang'];
$jenisvideo			= $row['jenisvideo'];
$screenshot			= $row['screenshot'];
$urlyoutube			= $row['urlyoutube'];
$kodeproduk			= $row['kodeproduk'];
$kinti 		= $row['kinti'];
$kdasar 		= $row['kdasar'];
$prosesprod		= $row['prosesprod'];
$alatbahan 		= $row['alatbahan'];
$penggunaan 		= $row['penggunaan'];
$icon		 		= $row['icon'];
$ispinjam		 	= $row['ispinjam'];
$ispesan		 	= $row['ispesan'];
$userid		 		= $row['userid'];



$tpl->assign("secId",$secId);
$tpl->assign("subId",$subId);
$tpl->assign("produkpostid",$produkpostid);
$tpl->assign("detailnama",$namaprod);

$tpl->assign("price",$misc_harga2);
$tpl->assign("savenya",$savenya);
$tpl->assign("hargan",$sDiskon);
$tpl->assign("harga",$harganya);
$tpl->assign("misc_harga_reseller",$misc_harga_reseller);
$tpl->assign("hargares",$misc_hargares2);

$tpl->assign("image_m",$image_mm);
$tpl->assign("image_s",$image_ss);
$tpl->assign("image_l",$image_ll);
$tpl->assign("namaprod",$namaprod);
$tpl->assign("detailringkas",$ringkas);
$tpl->assign("detaillengkap",$content);
$tpl->assign("detailkinti",$kinti);
$tpl->assign("detailkdasar",$kdasar);
$tpl->assign("detailalatbahan",$alatbahan);
$tpl->assign("detailprosesprod",$prosesprod);
$tpl->assign("detailpenggunaan",$penggunaan);
$tpl->assign("cart",$cart);
$tpl->assign("detailalias",$detailalias);
$tpl->assign("secid1",$var[3]);
$tpl->assign("subid1","$var[4]/$var[5]");
$tpl->assign("detailtanggal",$postTime);
$tpl->assign("detailgambar",$image_ll);
$tpl->assign("image_besar",$image_besar);
$tpl->assign("stock",$stock);
$tpl->assign("link_cat","$fulldomain/product/list/$aliasSec");
$tpl->assign("gambarproduk",$gambarproduk);
$tpl->assign("videoproduk",$videoproduk);
$tpl->assign("jenisvideo",$jenisvideo);
$tpl->assign("link_buy","$fulldomain/quickbuy/addpost/$produkpostid/$detailalias");
$tpl->assign("detailkode",$kodeproduk);
$tpl->assign("wishlist",$wishlist);
$tpl->assign("review",$review);
$tpl->assign("icon",$icons);
$tpl->assign("ispesan",$ispesan);
$tpl->assign("ispinjam",$ispinjam);
$tpl->assign("detailuserid",$userid);

if(!empty($userid))
{
	$owner = getprofileid($userid);
	$tpl->assign("detailowner",$owner);
}
	


include($lokasiweb."/librari/captcha/securimage.php");
$kodegen = md5(time());

$tpl->assign('kodegen', $kodegen);
if($_POST['action'] == "savepesan")
{
	$code 	= $_POST['code'];
	$novalidate 	= $_POST['novalidate'];
	$img 	= new Securimage();
	$valid 	=  $img->check($code);
	
	if($novalidate=="1") $valid = true;

	if($valid == false)
	{
	  $pesanhasil = "Kode antispam harap diisi dengan benar.";
	  $berhasil = "0";
	}
	else
	{
		$nama		= cleaninsert($_POST['userfullname']);
		$email		= cleaninsert($_POST['useremail']);
		$pesan		= cleaninsert($_POST['pesan']);
		$phone		= cleaninsert($_POST['userphone']);
		$tanggal	= DATE('Y-m-d H:m:s');
		$ip 		= $_SERVER['REMOTE_ADDR'];

		$tpl->assign("nama","$nama");
		$tpl->assign("email","$email");
		$tpl->assign("pesan","$pesan");


		$idbaru	= newid("id","tbl_contact_message");

		$perintah	= "insert into tbl_contact_message (`id`,`nama`,`email`,`pesan`,`ip`,phone,contactuserid, `create_date`,produkpostid) values ('$idbaru','$nama','$email','$pesan','$ip', '$phone','$userid','$tanggal','$produkpostid')";
		$hasil		= sql($perintah);

		if($hasil)
		{
			//Info Ke Referensi 
			$subject = "Ada Yang Bertanya atau Memesan Mainan";
			$message = "Hai $contactname<br>
			Pesan Baru dari $nama:<br><br>
			$pesan
			<br><br>
			Pesan dikirim oleh:<br>
			$nama<br>
			$phone<br>
			$email<br><br>
		
			Agar segera di tindak lanjuti dan followup informasi diatas dengan cara mengakses sistem back office {$title} di
			$fulldomain/user atau dengan mengubungi nomor kontak diatas";
			
			sendmail($owner['userfullname'],$owner['useremail'],$subject,$message,emailhtml($message));
			
			//Forward
			$sql = "select nama,useremail from tbl_forward order by nama";
			$hsl = sql($sql);
			while($dt = sql_fetch_data($hsl))
			{
				$fnama = $dt['nama'];
				$femail = $dt['useremail'];
				
				$subject = "Forward: $subject";
				sendmail($fnama,$femail,$subject,$message,emailhtml($message));
				
			}

				
			$pesanhasil = "Selamat Pesan anda di $title telah berhasil dikirimkan. Silahkan tunggu informasi dari kami selanjutnya melalui email ataupun nomor telephone anda. Terima Kasih";
			$berhasil = "1";
			
			header("location: $fulldomain/contactmessage/$produkpostid/?message=".base64_encode($pesanhasil)."");
			exit();
		}
		else
		{
			$pesanhasil = "Penyimpanan pesan gagal dilakukan.";
			$berhasil = "0";
		}
	}
	$tpl->assign("pesan",$pesan);
	$tpl->assign("pesanhasil",$pesanhasil);
	$tpl->assign("berhasil",$berhasil);
}

if(!empty($_GET['message']))
{
	$tpl->assign("pesan",$pesan);
	$tpl->assign("pesanhasil",base64_decode($_GET['message']));
	$tpl->assign("berhasil",1);
}

if($_POST['message'])
{
	$message = $_POST['message'];
	$userfullname1 = $_POST['userfullname'];
	
	$tpl->assign("message_isi",$message);
	$tpl->assign("message_userfullname",$userfullname1);
	
}


?>