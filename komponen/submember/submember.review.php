<?php
$tpl->assign("namarubrik","Review Produk");
	$batas_paging 	= 5;
	$hlm 			= $var[4];
	$judul_per_hlm	= 10;
	$aksipage		= "review/";
	
	$sql = "select count(*) as jml from tbl_transaksi where userid='$_SESSION[userid]'";
	$hsl = sql($sql);
	$tot = sql_result($hsl, 0, jml);
	$hlm_tot = ceil($tot / $judul_per_hlm);		
	if (empty($hlm)){
		$hlm = 1;
		}
	if ($hlm > $hlm_tot){
		$hlm = $hlm_tot;
		}
	
	$ord = ($hlm - 1) * $judul_per_hlm;
	if ($ord < 0 ) $ord=0;
	$tpl->assign("numc",$tot);
	$tpl->assign("hlm_tot",$hlm_tot);
	$tpl->assign("hlm",$hlm);
	$tpl->assign("judul_per_hlm",$judul_per_hlm);

	// tbl transaksi

	$abc = "select transaksiid from tbl_transaksi where userid='$_SESSION[userid]' and status='4' order by transaksiid desc";
	$acb = sql($abc);
	$no		= 1;
	$list_transaksi	= array();
	while ($bca = sql_fetch_data($acb))
	{
		$transaksiid = $bca['transaksiid'];
		
		$sql = "select produkpostid from tbl_transaksi_detail where transaksiid='$transaksiid'";
		$qry = sql($sql);
		while($row = sql_fetch_data($qry))
		{

			$produkpostid = $row['produkpostid'];
			
			$sql2	= "select title,secid,subid,misc_harga_reseller,misc_harga, misc_diskon from tbl_product_post where produkpostid='$produkpostid'";
			$query2	= sql($sql2);
			$row2	= sql_fetch_data($query2);
			$nama			= $row2["title"];
			$alias			= getAlias($nama);
			$secid			= $row2['secid'];
			$subid			= $row2['subid'];
			$aliasSecc		= getAliasSec($secid);
			$aliasSubb		= getAliasSub($subid);
	
			$jml_review = sql_get_var("select count(*) from tbl_product_comment where produkpostid='$produkpostid'");
			
			$gambar_m	= sql_get_var("select gambar_m from tbl_product_image where produkpostid='$produkpostid' order by albumid asc limit 1");
	
			$link = "$fulldomain/$kanal/addreview/$produkpostid/$alias";
			
			$link_produk	= "$fulldomain/product/read/$produkpostid/$alias";
			// $link_produk	= "$fulldomain/product/read/$aliasSecc/$aliasSubb/$produkpostid/$alias";
	
			
			if(!empty($gambar_m))
				$gambar_m	= "$fulldomain/gambar/produk/$produkpostid/$gambar_m";
			else
				$gambar_m	= $fulldomain.$lokasiwebtemplate."images/no_photo.gif";
				
			$list_transaksi[$no]	= array("transaksiid"=>$transaksiid,"commentid"=>$commentid,"nama"=>$nama,"komentar"=>$komentar,"tanggal"=>$tanggal,"no"=>$no,"gambar_m"=>$gambar_m,"jumlah_review"=>$jml_review,"link"=>$link,"link_produk"=>$link_produk);
			$no++;
		}
	
	}
	sql_free_result($acb);
	$tpl->assign("numc",$numc);
	$tpl->assign("jml_review",$jml_review);
	if($tot==1) $nums = "1 Ulasan";
	else $nums = "$tot Ulasan";
	$tpl->assign("nums",$nums);
	$tpl->assign("list_transaksi",$list_transaksi);
	
	$tpl->assign("namarubrik","Ulasan Produk");
	
	//Paging 
	$batas_page = 5;
	$stringpage = array();
	$pageid 	= 0;
	
	if ($hlm > 1){
	$prev = $hlm - 1;
	$pageid++;
	$stringpage[$pageid] = array("nama"=>"Prev","link"=>"$fulldomain/$kanal/$aksipage"."$prev"); //,"class"=>"previous"

	}
	else {
		$pageid++;
	}
	
	$hlm2 = $hlm - (ceil($batas_page/2));
	$hlm4 = $hlm+(ceil($batas_page/2));
	
	if($hlm2 <= 0 ) $hlm3=1;
	   else $hlm3 = $hlm2;
	$pageid++;

	for ($ii=$hlm3; $ii <= $hlm_tot and $ii<=$hlm4; $ii++){
		if ($ii==$hlm){
			$stringpage[$pageid] = array("nama"=>"$ii","link"=>"","class"=>"current");
		}else{
			$stringpage[$pageid] = array("nama"=>"$ii","link"=>"$fulldomain/$kanal/$aksipage"."$ii","class"=>""); 
		}
		$pageid++;
	}

	if ($hlm < $hlm_tot){
		$next = $hlm + 1;
		$stringpage[$pageid] = array("nama"=>"Next","link"=>"$fulldomain/$kanal/$aksipage"."$next"); //,"class"=>"next"
		$pageid++;
	}
	else
	{
		$pageid++;
	}
	$tpl->assign("stringpage",$stringpage);
?>