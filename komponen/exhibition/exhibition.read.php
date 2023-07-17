<?php
$produkpostid	= $var[4];
$posterre		= $var[8];

if (!empty($subId)) {
	$nama_sub = getNamaSub($subId, $lang);
	$tpl->assign("namasub", $nama_sub);
	$tpl->assign("link_sub", "$fulldomain/$kanal/list/$aliasSec/$aliasSub.html");
}
if (!empty($secId)) {
	$nama_cat = getNamaSec($secId, $lang);
	$tpl->assign("link_sec", "$fulldomain/$kanal/list/$aliasSec.html");
	$tpl->assign("namasec", $nama_cat);
}

$sql	= "select kodeproduk, title, content, ringkas, harga,stock,cart, body_dimension, body_weight,  create_date, userid,ispinjam,ispesan, brandid,icon,numviews,vidio,pembuat,id_profile from tbl_product_post where status='1' and produkpostid='$produkpostid' and published='1'";
$query	= sql($sql);
$row	= sql_fetch_data($query);
$namaprod			= $row["title$lang"];
$content			= $row["content"];
$ringkas			= $row["ringkas"];
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
$stock		= $row['stock'];
$alatbahan 		= $row['alatbahan'];
$penggunaan 		= $row['penggunaan'];
$icon		 		= $row['icon'];
$ispinjam		 	= $row['ispinjam'];
$ispesan		 	= $row['ispesan'];
$userid		 		= $row['userid'];
$numviews		 	= $row['numviews'];
$vidio		 		= $row['vidio'];
$pembuat		 	= $row['pembuat'];
$id_profile		 	= $row['id_profile'];
$alias 				= getalias($namaprod);

$cekpekarya = "SELECT id_perkarya,foto,nama,penjelasan,umur,asal_provinsi,alamat_lengkap,diagnosa_pekarya,institusi FROM tbl_pekarya WHERE id_perkarya='$id_profile' ";
$pekarya = sql($cekpekarya);
$value = sql_fetch_data($pekarya);
$id_pekarya = $value["id_perkarya"];
$fotopekarya = $value["foto"];
$namapekarya = $value["nama"];
$pejelasanpekarya = $value["penjelasan"];
$umur = $value["umur"];
$asal_provinsi = $value["asal_provinsi"];
$alamat_lengkap = $value["alamat_lengkap"];
$diagnosa_pekarya = $value["diagnosa_pekarya"];
$institusi = $value["institusi"];

$tpl->assign("id_pekarya", $id_pekarya);
$tpl->assign("fotopekarya", $fotopekarya);
$tpl->assign("namaperkarya", $namapekarya);
$tpl->assign("penjelasanpekarya", $pejelasanpekarya);
$tpl->assign("umur", $umur);
$tpl->assign("asal_provinsi", $asal_provinsi);
$tpl->assign("alamat_lengkap", $alamat_lengkap);
$tpl->assign("diagnosa_pekarya", $diagnosa_pekarya);
$tpl->assign("institusi", $institusi);



$linkproduk = "$fulldomain/exhibition/read/$produkpostid/$alias";
$tpl->assign("linkproduk", $linkproduk);

if (!empty($icon))
	$icons	= "$fulldomain/gambar/produk/$produkpostid/$icon";
else
	$icons	= "";

$sDiskon		= 0;
$hDiskon		= 0;
if ($harga != 0) {
	//$sDiskon		= ceil(($harga/100)*$misc_harga);
	$hDiskon		= $harga;
	$sDiskon		= $misc_harga - $harga;
}

$misc_harga_reseller = 0;
if ($_SESSION['secid'] == '2') {
	$misc_harga_reseller = $row['misc_harga_reseller'];
	$misc_hargares1      = number_format($misc_harga_reseller, 0, ".", ".");
	$misc_hargares2      = "$misc_matauang $misc_hargares1";
}

$misc_harga1 = number_format($misc_harga, 0, ".", ".");
$misc_harga2 = "$misc_matauang $misc_harga1";
$harganya	= "$misc_matauang " . number_format($hDiskon, 0, ".", ".");
$savenya	= "$misc_matauang " . number_format($sDiskon, 0, ".", ".");

//Data Brand
$brand     = sql("select brandid,nama from tbl_product_brand where brandid='$brandidnya'");
$dtb       = sql_fetch_data($brand);
$namabrand = $dtb['nama'];
$tpl->assign("namabrand", $namabrand);

sql_free_result($query);

$sql	= "update tbl_product_post set numviews=numviews+1 where produkpostid='$produkpostid'";
$query	= sql($sql);

$tpl->assign("body_dimension", $body_dimension);
$tpl->assign("body_weight", $body_weight);
$tpl->assign("numviews", $numviews);
$tpl->assign("vidio", $vidio);
$tpl->assign("pembuat", $pembuat);

$totalstok	= $stock;

$tpl->assign("totalstok", $totalstok);

// Gambar
$ambilgambar = sql_get_var("SELECT gambar_f FROM tbl_product_image WHERE produkpostid='$produkpostid' ORDER BY produkpostid ASC LIMIT 1 ");
$gambarutama = "$fulldomain/gambar/produk/$produkpostid/$ambilgambar";
$tpl->assign("gambarutama", $gambarutama);

$sql3		= "select albumid,nama,gambar_m,gambar_s,gambar_l,gambar_f from tbl_product_image where produkpostid='$produkpostid' order by albumid asc";
$query3		= sql($sql3);
$list_image	= array();
$ii			= 1;
$albumarr 	= array();
while ($row3		= sql_fetch_data($query3)) {
	$albumid	= $row3['albumid'];
	$nama_image	= $row3['nama'];
	$gambar_s	= $row3['gambar_s'];
	$gambar_m	= $row3['gambar_m'];
	$gambar_l	= $row3['gambar_l'];
	$gambar_f	= $row3['gambar_f'];

	if (!empty($gambar_m))
		$image_m	= "$fulldomain/gambar/produk/$produkpostid/$gambar_m";
	else
		$image_m	= $fulldomain . $lokasiwebtemplate . "images/no_photo.gif";

	if (!empty($gambar_s))
		$image_s	= "$fulldomain/gambar/produk/$produkpostid/$gambar_s";
	else
		$image_s	= $fulldomain . $lokasiwebtemplate . "images/no_photo.gif";

	if (!empty($gambar_l))
		$image_l	= "$fulldomain/gambar/produk/$produkpostid/$gambar_f";
	else
		$image_l	= $fulldomain . $lokasiwebtemplate . "images/no_photo.gif";

	if (!empty($gambar_f))
		$image_f	= "$fulldomain/gambar/produk/$produkpostid/$gambar_f";
	else
		$image_f	= $fulldomain . $lokasiwebtemplate . "images/no_photo.gif";

	if (($s == "m" or $s == "f") and ($var[7])) {
		if ($albumid == $var[7]) {
			$nama_image		= $nama_image;
			$firstImageId	= $albumid;
			$tpl->assign("detailgambar", $image_m);
		}
	} else if ($ii == 1) {
		$image_besar	= $image_f;
		$image_mm		= $image_m;
		$image_ss 		= $image_s;
		$image_ll		= $image_l;
		$nama_image		= $nama_image;

		$firstImageId	= $albumid;
	}

	$list_image[$albumid]	= array("index" => $ii, "albumid" => $albumid, "nama_image" => $nama_image, "image_m" => $image_m, "image_s" => $image_s, "image_l" => $image_l, "image_f" => $image_l);
	$albumarr[$ii] 			= $albumid;
	$ii++;
}
sql_free_result($query3);
$tpl->assign("list_image", $list_image);

$detailalias		= getAlias($namaprod);

// $views = "update tbl_product_post set view=view+1 where produkpostid='$produkpostid'";
// $hsl = sql($views);



$tpl->assign("secId", $secId);
$tpl->assign("subId", $subId);
$tpl->assign("produkpostid", $produkpostid);
$tpl->assign("detailnama", $namaprod);

$tpl->assign("price", $misc_harga2);
$tpl->assign("savenya", $savenya);
$tpl->assign("hargan", $sDiskon);
$tpl->assign("harga", $harganya);
$tpl->assign("misc_harga_reseller", $misc_harga_reseller);
$tpl->assign("hargares", $misc_hargares2);

$tpl->assign("image_m", $image_mm);
$tpl->assign("image_s", $image_ss);
$tpl->assign("image_l", $image_ll);
$tpl->assign("namaprod", $namaprod);
$tpl->assign("detailringkas", $ringkas);
$tpl->assign("detaillengkap", $content);
$tpl->assign("detailkinti", $kinti);
$tpl->assign("detailkdasar", $kdasar);
$tpl->assign("detailalatbahan", $alatbahan);
$tpl->assign("detailstock", $stock);
$tpl->assign("detailpenggunaan", $penggunaan);
$tpl->assign("cart", $cart);
$tpl->assign("detailalias", $detailalias);
$tpl->assign("secid1", $var[3]);
$tpl->assign("subid1", "$var[4]/$var[5]");
$tpl->assign("detailtanggal", $postTime);
$tpl->assign("detailgambar", $image_ll);
$tpl->assign("image_besar", $image_besar);
$tpl->assign("stock", $stock);
$tpl->assign("link_cat", "$fulldomain/lelang/list/$aliasSec");
$tpl->assign("gambarproduk", $gambarproduk);
$tpl->assign("videoproduk", $videoproduk);
$tpl->assign("jenisvideo", $jenisvideo);
$tpl->assign("link_buy", "$fulldomain/quickbuy/addpost/$produkpostid/$detailalias");
$tpl->assign("detailkode", $kodeproduk);
$tpl->assign("wishlist", $wishlist);
$tpl->assign("review", $review);
$tpl->assign("icon", $icons);
$tpl->assign("ispesan", $ispesan);
$tpl->assign("ispinjam", $ispinjam);
$tpl->assign("detailuserid", $userid);

if (!empty($userid)) {
	$owner = getprofileid($userid);
	$tpl->assign("detailowner", $owner);
}

// komentar
if ($_POST['aksi'] == "simpan") {
	if (preg_match("/href/i", $_POST[komentar])) {
		header("location: http://" . $_SERVER['HTTP_HOST'] . "");
	}

	$produkpostid	= $_POST['produkpostid'];
	$userid			= $_POST['userid'];
	$username		= $_POST['username'];
	$komentar		= str_replace("'", "`", $_POST['pesan']);
	$komentar		= str_replace("<!--", "", $komentar);
	$ip 			= $_SERVER['REMOTE_ADDR'];

	if ($_POST[callback]) $callback		= "/?callback=$_POST[callback]";

	
	if (empty($username)) {
		$salah .= ("Nama Lengkap wajib diisi <br>\n");
		$benar = 2;
	}

	if (empty($komentar)) {
		$salah .= ("Komentar belum anda masukan<br>\n");
		$benar = 2;
	}

	if ($benar == 2) {

		$error = "Mohon ma'af ada kesalahan memasukan data anda atau kurang dalam mengisi form yang telah disediakan
				<br>$salah ";
		$tpl->assign("error", $error);
		$tpl->assign("style", "error");
	} else {

		$perintah	= "insert into tbl_product_comment (produkpostid,userid,nama,email,komentar,ip,published,via,create_date,create_userid) 
					values ('$produkpostid','$userid','$username','','$komentar','$ip','1','" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] . "','$date','$cuserid')";
		$hasil		= sql($perintah);

		// setlog
		/*if ($_SESSION['userid'] != $userid)
			setlog($nama,$Username,"Mengomentari Produk Anda.","$fulldomain/katalog/detail/$aliasSec/$aliasSub/$produkpostid","commentproduct");*/

		if ($hasil)
			header("location: $fulldomain/exhibition/read/$produkpostid/$detailalias");
	}
}

// list comment
$sqlc	= "select commentid,nama,komentar,create_date,userid from tbl_product_comment where produkpostid='$produkpostid' and published='1' order by create_date desc";
$queryc	= sql($sqlc);
$numc	= sql_num_rows($queryc);
$list_comment	= array();
$no		= 1;
while ($rowc = sql_fetch_data($queryc)) {
	$commentid	= $rowc['commentid'];
	$nama		= $rowc['nama'];
	$userid		= $rowc['userid'];
	$komentar	= $rowc['komentar'];
	if ($s != "m") $tanggal	= tanggal($rowc['create_date']);
	else $tanggal	= tanggaltok($rowc['create_date']);

	$useremail = sql_get_var("select useremail from tbl_member where userid='$userid'");

	$gambar		= get_gravatar($useremail);

	$list_comment[$commentid]	= array("commentid" => $commentid, "nama" => $nama, "komentar" => $komentar, "tanggal" => $tanggal, "no" => $no, "gambar" => $gambar);
	$no++;
}
sql_free_result($queryc);
$tpl->assign("numc", $numc);
$tpl->assign("list_comment", $list_comment);

//Related Products/ Produk Lainnya
$sql2	= "select produkpostid,secid,subid,title,harga,ringkas from tbl_product_post where status='1' 
		and published='1' and produkpostid!='$produkpostid' and secid='$secId' and subid='$subId' order by update_date desc limit 6";
$query2	= sql($sql2);
$numprod = sql_num_rows($query2);
$produklain	= array();
while ($row2 = sql_fetch_data($query2)) {
	$produkpostid1	= $row2['produkpostid'];
	$secId1			= $row2['secid'];
	$subId1			= $row2['subid'];
	$aliasSecc1		= getAliasSec($secId1);
	$aliasSubb1		= getAliasSub($subId1);
	$namaprod		= $row2["title"];
	$misc_harga		= number_format($row2['misc_harga']);
	$content		= bersih(substr($row2["ringkas"], 0, 50));
	$aliasprod		= getAlias($namaprod);
	$misc_matauang	= $row2['misc_matauang'];
	$harga	= $row2['harga'];
	$jenisvideo		= $row2['jenisvideo'];
	$screenshot		= $row2['screenshot'];
	$misc_harga 	= $row2['misc_harga'];

	$sDiskon		= 0;
	$hDiskon		= 0;
	if ($harga != 0) {
		//$sDiskon		= ceil(($harga/100)*$misc_harga);
		$hDiskon		= $harga;
		$sDiskon		= $misc_harga - $harga;
	}

	$misc_harga_reseller = 0;
	if ($_SESSION['secid'] == '2') {
		$misc_harga_reseller = $row2['misc_harga_reseller'];
		$misc_hargares1 = number_format($misc_harga_reseller, 0, ".", ".");
		$misc_hargares2 = "$misc_matauang $misc_hargares1";
	}

	$misc_harga1 = number_format($misc_harga, 0, ".", ".");
	$misc_harga2 = "$misc_matauang $misc_harga1";
	$harganya	= "$misc_matauang " . number_format($hDiskon, 0, ".", ".");
	$savenya	= "$misc_matauang " . number_format($sDiskon, 0, ".", ".");

	$sql3	= "select albumid,gambar_s,gambar_m from tbl_product_image where produkpostid='$produkpostid1' order by albumid asc limit 1";
	$query3	= sql($sql3);
	$row3	= sql_fetch_data($query3);
	$albumid	= $row3['albumid'];
	$gambar_s	= $row3['gambar_s'];
	$gambar_m	= $row3['gambar_m'];
	sql_free_result($query3);

	if (!empty($gambar_m))
		$image_m	= "$fulldomain/gambar/produk/$produkpostid1/$gambar_m";
	else
		$image_m	= $fulldomain . $lokasiwebtemplate . "images/no_photo.gif";

	// $link_detail	= "$fulldomain/product/read/$aliasSecc1/$aliasSubb1/$produkpostid1/$aliasprod.html";
	$link_detail	= "$fulldomain/lelang/read/$produkpostid1/$aliasprod.html";
	$link_buy 		= "$fulldomain/quickbuy/addpost/$produkpostid1/$aliasprod.html";

	$produklain[$produkpostid1]	= array(
		"produkpostid" => $produkpostid1, "namaprod" => $namaprod, "image_m" => $image_m, "link_buy" => $link_buy,
		"link_detail" => $link_detail, "misc_matauang" => $misc_matauang, "price_related" => $misc_harga,
		"harga_related" => $harganya, "savenya_related" => $savenya, "save_related" => $sDiskon, "harga" => $harga,
		"misc_harga_reseller" => $misc_harga_reseller, "hargares" => $misc_hargares2
	);
}
sql_free_result($query2); //print_r($produklain);
$tpl->assign("numprod", $numprod);
$tpl->assign("produklain", $produklain);
