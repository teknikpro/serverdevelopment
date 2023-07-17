<?php
$where = "";
// List Produk
if ($_POST['page']) {
	$judul_per_hlm 	= $_POST['page'];
	$_SESSION['judul_per_hlm'] = $judul_per_hlm;
} elseif ($_SESSION['judul_per_hlm']) $judul_per_hlm = $_SESSION['judul_per_hlm'];
else $judul_per_hlm = 12;

if ($_POST['sorting']) {
	$sorting 	= $_POST[sorting];
	$_SESSION['sorting'] = $sorting;
} elseif ($_SESSION['sorting']) $sorting = $_SESSION['sorting'];
else $sorting = "produkpostid";


$batas_paging 	= 5;

if ($_POST['aksi'] == "cari" or $aksi == "list-search")
	$aksipage		= "list-search/";

if ($_POST['aksi'] == "cari" or preg_match("/search/i", $aksi)) {
	$hlm 			= $var[4];
	if ($_POST['aksi'] == "cari") {
		unset($_SESSION['secid'], $_SESSION['subid'], $_SESSION['brandid'], $_SESSION['kata']);

		if (!empty($_POST['secid'])) {
			$secids = $_POST['secid'];
			$_SESSION['secid'] = $secids;
		}
		if (!empty($_POST['subid'])) {
			$subids = $_POST['subid'];
			$_SESSION['subid'] = $subids;
		}
		if (!empty($_POST['brandid'])) {
			$brandids = $_POST['brandid'];
			$_SESSION['brandid'] = $brandids;
		}
		if (!empty($_POST['kata'])) {
			$kata = $_POST['kata'];
			$_SESSION['kata'] = $kata;
		}
	}

	$secids		= $_SESSION['secid'];
	$subids		= $_SESSION['subid'];
	$brandids	= $_SESSION['brandid'];
	$kata		= $_SESSION['kata'];

	if ($key == "")
		$where	.= "";
	if ($secids != "")
		$where	.= " and secid='$secids'";
	if ($subids != "")
		$where	.= " and subid='$subids'";
	if ($brandids != "")
		$where	.= " and brandid='$brandids'";
	if ($kata != "") {
		$tempk	= explode(" ", $kata);
		for ($i = 0; $i < count($tempk); $i++) {
			$wherearray[] = "title$lang like '%$tempk[$i]%'";
		}

		$impwhere = implode("or ", $wherearray);
		$where	.= " and ($impwhere)";
	}

	$tpl->assign("kata", $kata);
} else {
	unset($_SESSION['secid'], $_SESSION['subid'], $_SESSION['brandid'], $_SESSION['kata']);

	if ($aksi == "list") {
		$aliassec		= $var[4];
		$aliassub		= $var[5];

		$secid			= getSecId($aliassec);
		$subId			= getSubId($aliassub);

		if ($hlm == "?callback=$getdomain") $hlm = "";

		if (!empty($secid)) {
			$hlm 			= $var[6];
			$where			= "and secid='$secid'";
			$aksipage		= "$aksi/$aliassec/$aliassub/";
		} else {
			$hlm 			= $var[5];
			$aksipage		= "$aksi/$aliassec/";
		}

		if (!empty($subId)) {
			$nama_sub = getNamaSub($subId, $lang);
			$tpl->assign("namasub", $nama_sub);
		}
		if (!empty($secId)) {
			$nama_cat = getNamaSec($secId, $lang);
			$tpl->assign("link_sec", "$fulldomain/$kanal/$aksi/$aliassec.html");
			$tpl->assign("namasec", $nama_cat);
		}
	} elseif ($aksi == "terlaris") {
		$hlm 			= $var[4];
		$where 			= "and topseller='1'";
		$aksipage		= "$aksi/";
	} elseif ($aksi == "list-brand") {
		$hlm 			= $var[5];
		$aliasbrand		= $var[4];
		$brandid		= sql_get_var("select brandid from tbl_product_brand where alias='$aliasbrand'");
		$where 			= "and brandid='$brandid'";
		$aksipage		= "$aksi/$aliasbrand/";
	} else {
		$hlm 			= $var[4];
		$where			= "";
		$aksipage		= "all/";
	}
}

$tpl->assign("subidxxx", $subId);
$tpl->assign("secidxxx", $secId);

if (!empty($subId)) $whereref = "secid='$secId' and subid='$subId'";
else $whereref = "secid='$secId'";

$sqlb	= "select brandid, nama from tbl_product_brand where published='1'";
$resb	= sql($sqlb);
$brandd	= array();
while ($rowb = sql_fetch_data($resb)) {
	$brandid	= $rowb['brandid'];
	$namabrand	= $rowb['nama'];

	$brandd[$brandid] = array("brandid" => $brandid, "namabrand" => $namabrand);
}
sql_free_result($resb);
$tpl->assign("brandd", $brandd);

$sql = "select count(*) as jml from tbl_product_post where status='1' and published='1' $where";
$hsl = sql($sql);
$tot = sql_result($hsl, 0, "jml");
$hlm_tot = ceil($tot / $judul_per_hlm);
if (empty($hlm)) {
	$hlm = 1;
}
if ($hlm > $hlm_tot) {
	$hlm = $hlm_tot;
}

$ord = ($hlm - 1) * $judul_per_hlm;
if ($ord < 0) $ord = 0;
$tpl->assign("tot", $tot);
$tpl->assign("hlm_tot", $hlm_tot);
$tpl->assign("hlm", $hlm);
$tpl->assign("judul_per_hlm", $judul_per_hlm);
$tpl->assign("ord", $ord + 1);

if ($tot == 1) {
	$sql = "select produkpostid,title from tbl_product_post where published='1' $where order by produkpostid desc limit 1";
	$hsl = sql($sql);
	$data = sql_fetch_data($hsl);
	$produkpostid = $data['produkpostid'];
	$nama = $data['title'];
	$alias = getalias($nama);

	header("location: $fulldomain/product/read/$produkpostid/$alias");
	exit();
}

$datauserid = $_SESSION['userid'];
$kosong = "";

if (datauserid) {
	$tpl->assign("iduser", $datauserid);
} else {
	$tpl->assign("iduser", $kosong);
}


$sqlp	= "select produkpostid,secid,subid,brandid,title,ringkas,harga,icon,numviews,vidio,pembuat from tbl_product_post where status='1' and published='1' and simposium='1' and buat='3' $where order by pembuat asc limit $ord, $judul_per_hlm";
$queryp	= sql($sqlp);
$nump	= sql_num_rows($queryp);

$list_post	= array();
$no	= 1;
while ($row2 = sql_fetch_data($queryp)) {
	$produkpostid	= $row2['produkpostid'];
	$secId			= $row2['secid'];
	$subId			= $row2['subid'];
	$aliasSecc		= getAliasSec($secId);
	$aliasSubb		= getAliasSub($subId);
	$namaprod		= ucwords($row2["title"]);
	$alias			= getAlias($namaprod);
	$ringkas		= bersih($row2['ringkas']);
	$ringkas = ringkas($ringkas, 20);
	$misc_matauang	= $row2['misc_matauang'];
	$pilihan		= $row2['pilihan'];
	$jenisvideo		= $row2['jenisvideo'];
	$screenshot		= $row2['screenshot'];
	$harga 	= $row2['harga'];
	$misc_diskon 	= $row2['misc_diskon'];
	$misc_harga 	= $row2['misc_harga'];
	$icon		 	= $row2['icon'];
	$numviews		 = $row2['numviews'];
	$vidio		 	= $row2['vidio'];
	$pembuat		 = $row2['pembuat'];

	if (!empty($icon))
		$icons	= "$fulldomain/gambar/produk/$produkpostid/$icon";
	else
		$icons	= "";

	$sDiskon		= 0;
	$hDiskon		= 0;
	if ($misc_diskon != 0) {
		//$sDiskon		= ceil(($misc_diskon/100)*$misc_harga);
		$hDiskon		= $misc_diskon;
		$sDiskon		= $misc_harga - $misc_diskon;
	}

	$misc_harga_reseller = 0;
	if ($_SESSION['secid'] == '2') {
		$misc_harga_reseller = $row2['misc_harga_reseller'];
		$misc_hargares1 = number_format($misc_harga_reseller, 0, ".", ".");
		$misc_hargares2 = "$misc_matauang $misc_hargares1";
	}

	$misc_harga		= $misc_matauang . " " . number_format($misc_harga, 0, ".", ".");
	$misc_diskonnya	= $misc_matauang . " " . number_format($hDiskon, 0, ".", ".");
	$savenya		= $misc_matauang . " " . number_format($sDiskon, 0, ".", ".");

	if ($jenisvideo == 1) {
		if (!empty($screenshot)) {
			$image_m	= "$fulldomain/gambar/produk/$produkpostid/$screenshot";
			$image_l	= "$fulldomain/gambar/produk/$produkpostid/$screenshot";
		} else {
			$image_m	= $fulldomain . $lokasiwebtemplate . "images/no_photo.gif";
			$image_l	= $fulldomain . $lokasiwebtemplate . "images/no_photo.gif";
		}
	} else {
		$sql3	= "select albumid,gambar_m,gambar_l from tbl_product_image where produkpostid='$produkpostid' order by albumid asc limit 1";
		$query3	= sql($sql3);
		$row3	= sql_fetch_data($query3);
		$albumid	= $row3['albumid'];
		$gambar_m	= $row3['gambar_m'];
		$gambar_l	= $row3['gambar_l'];
		sql_free_result($query3);

		if (!empty($gambar_m))
			$image_m	= "$fulldomain/gambar/produk/$produkpostid/$gambar_m";
		else
			$image_m	= $fulldomain . $lokasiwebtemplate . "images/no_photo.gif";

		if (!empty($gambar_l))
			$image_l	= "$fulldomain/gambar/produk/$produkpostid/$gambar_l";
		else
			$image_l	= $fulldomain . $lokasiwebtemplate . "images/no_photo.gif";
	}

	$link_detail	= "$fulldomain/exhibition/read/$produkpostid/$alias?menu=exhibition/read&id=$produkpostid";
	$link_buy		= "$fulldomain/quickbuy/addpost/$produkpostid/$alias";

	$harga = rupiah($harga);

	$jumlahlike = sql_get_var("SELECT COUNT(id_like) FROM tbl_like WHERE post_id='$produkpostid' ");

	if (!$jumlahlike) {

		$jumlahlike = 0;
	}

	$statuslike = sql_get_var("SELECT id_like FROM tbl_like WHERE post_id='$produkpostid' AND userid='$datauserid' ");

	if ($statuslike) {
		$ceklikeid = "logolike.png";
	} else {
		$ceklikeid = "logolike2.png";
	}




	$list_post[$produkpostid]	= array(
		"produkpostid" => $produkpostid, "namaprod" => $namaprod, "image_m" => $image_m, "link_detail" => $link_detail, "ringkas" => $ringkas,
		"price" => $misc_harga, "misc_diskon" => $misc_diskonnya, "savenya" => $savenya, "save" => $sDiskon, "misc_matauang" => $misc_matauang, "no" => $no, "harga" => $harga,
		"pilihan" => $pilihan, "diskon" => $misc_diskon, "link_buy" => $link_buy, "link_compare" => $link_compare, "misc_diskonn" => $sDiskon, "secid" => $secId,
		"misc_harga_reseller" => $misc_harga_reseller, "hargares" => $misc_hargares2, "wishlist" => $wishlist, "icon" => $icons, "numviews" => $numviews, "jumlahlike" => $jumlahlike, "statuslike" => $ceklikeid, "vidio" => $vidio, "pembuat" => $pembuat
	);
	$no++;
}
sql_free_result($queryp);
$tpl->assign("no", $no);
$tpl->assign("nump", $nump);
$tpl->assign("list_post", $list_post);
$tpl->assign("kata", $kata);



//Paging 

$batas_page = 5;
$stringpage = array();
$pageid 	= 0;

if ($hlm > 1) {
	$prev = $hlm - 1;
	$pageid++;
	$stringpage[$pageid] = array("nama" => "Prev", "link" => "$fulldomain/$kanal/$aksipage" . "$prev");
} else {
	$pageid++;
}

$hlm2 = $hlm - (ceil($batas_page / 2));
$hlm4 = $hlm + (ceil($batas_page / 2));

if ($hlm2 <= 0) $hlm3 = 1;
else $hlm3 = $hlm2;
$pageid++;

for ($ii = $hlm3; $ii <= $hlm_tot and $ii <= $hlm4; $ii++) {
	if ($ii == $hlm) {
		$stringpage[$pageid] = array("nama" => "$ii", "link" => "", "class" => "current");
	} else {
		$stringpage[$pageid] = array("nama" => "$ii", "link" => "$fulldomain/$kanal/$aksipage" . "$ii", "class" => "");
	}
	$pageid++;
}

if ($hlm < $hlm_tot) {
	$next = $hlm + 1;
	$stringpage[$pageid] = array("nama" => "Next", "link" => "$fulldomain/$kanal/$aksipage" . "$next"); //,"class"=>"next"
	$pageid++;
} else {
	$pageid++;
}

$tpl->assign("stringpage", $stringpage);
//Selesai Paging
