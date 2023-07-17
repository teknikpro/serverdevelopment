<?php
//kategori iklan
$mysql = "select secid,nama,alias,icon from tbl_panduan_sec order by secid asc";
$hsl = sql($mysql);
$a=1;
while($row=sql_fetch_data($hsl))
{
	$secid 	= $row['secid'];
	$nama	= $row['nama'];
	$alias	= $row['alias'];
	$icon	= $row['icon'];
		
	$url= "$fulldomain/panduan/list/$alias";
	
	$perintah = "select id,nama,alias from tbl_panduanbelanja where secid='$secid' and published='1' order by id asc limit 3";
	$hasil = sql($perintah);
	$x=1;
	while($data=sql_fetch_data($hasil))
	{
		$idpanduan = $data['id'];
		$namapanduan = $data['nama'];
		$aliaspanduan	= $row['alias'];
		
		$urlpanduan = "$fulldomain/panduan/detail/$alias/$idpanduan/$aliaspanduan.html";
		
		$datapanduan[$secid][$idpanduan]=array("id"=>$idpanduan,"nama"=>$namapanduan,"urlpanduan"=>$urlpanduan,"x"=>$x);
		$x++;
	}
	sql_free_result($hasil);
	
	$datasec[$secid]=array("secid"=>$secid,"nama"=>$nama,"a"=>$a,"url"=>$url,"icon"=>$icon);
	$a++;
}
sql_free_result($hsl);
$tpl->assign("datasec",$datasec);
$tpl->assign("datapanduan",$datapanduan);

$perintah = "select id,nama,alias,icon from tbl_panduanbelanja where flag='1' and published='1' order by id asc limit 3";
$hasil = sql($perintah);
$x=1;
while($data=sql_fetch_data($hasil))
{
	$idpanduan = $data['id'];
	$namapanduan = $data['nama'];
	$aliaspanduan	= $row['alias'];
	$iconpanduan	= $row['icon'];
	
	$urlpanduan = "$fulldomain/panduan/detail/$alias/$idpanduan/$aliaspanduan.html";
	
	$datapanduanreq[$idpanduan]=array("id"=>$idpanduan,"nama"=>$namapanduan,"urlpanduan"=>$urlpanduan,"icon"=>$iconpanduan);
	$x++;
}
sql_free_result($hasil);

$tpl->assign("datapanduanreq",$datapanduanreq);

if($aksi =="detail")
{
	$id	= $var[5];
	$katid = $var[4];
	
	if(!empty($katid) and $katid != "global")
	{
		$perintah1 = "select * from tbl_panduan_sec where alias='$katid'";
		$hasil1 = sql($perintah1);
		$row1 = sql_fetch_data($hasil1);
		$namasec = $row1['nama'];
		$secid = $row1['secid'];
		$secalias = $row1['alias'];
		$where = "and secid='$secid'";
		$tpl->assign("link_sec","$fulldomain/$kanal/$aksi/$aliassec.html");
		$tpl->assign("namasec",$namasec);
		$tpl->assign("secidd",$secid);
	}
	else
	{
		$secalias = "global";
		$rubrik = "$rubrik";
		$tpl->assign("rubrik","$rubrik");
	}
		
	$sql	= "select nama,lengkap,tanggal,alias from tbl_panduanbelanja where id='$id' and published='1' $where";
	$query	= sql($sql);
	$row	= sql_fetch_data($query);
	$numblognews	= sql_num_rows($query);
	$judulberita	= $row["nama"];
	$detailberita	= $row["lengkap"];
	$postTime		= tanggal($row['tanggal']);
	$alias			= $row['alias'];
	
	$tpl->assign("detailnama",$judulberita);
	$tpl->assign("detaillengkap",$detailberita);
	$tpl->assign("detailtanggal",$postTime);
}
else
{
	session_start();
		// List Produk
		
		$batas_paging 	= 5;
		$judul_per_hlm = 10;

		if($_POST['aksi'] == "cari" or $aksi == "list-search")
			$aksipage		= "list-search/";

		if($_POST['aksi'] == "cari" or preg_match("/search/i",$aksi))
		{
			$hlm 			= $var[4];
			if($_POST['aksi'] == "cari")
			{
				session_start();
				$_SESSION['kata']		= "";
				
				if(!empty($_POST['kata']))
				{
					$kata = $_POST['kata'];
					$_SESSION['kata'] = $kata;
				}
			}
			
			$kata		= $_SESSION['kata'];

			if($kata != "")
				$where	.= " and (nama like '%$kata%' or lengkap like '%$kata%')";
			
		}
		else
		{
			session_start();
			unset($_SESSION['kata']);
			
			$katid = $var[4];
			$katid = str_replace(".html","",$katid);
			
			if(!empty($katid) and $katid != "global")
			{
				$perintah1 = "select * from tbl_panduan_sec where alias='$katid'";
				$hasil1 = sql($perintah1);
				$row1 = sql_fetch_data($hasil1);
				$namasec = $row1['nama'];
				$secid = $row1['secid'];
				$secalias = $row1['alias'];
				$where = "and secId='$secid'";
				$tpl->assign("link_sec","$fulldomain/$kanal/$aksi/$aliassec.html");
				$tpl->assign("rubrik",$namasec);
				$tpl->assign("secidd",$secid);
			}
			else
			{
				$secalias = "global";
				$rubrik = "$rubrik";
				$tpl->assign("rubrik","$rubrik");
			}
		}
		
		$tpl->assign("secidxxx",$secId);
		
		$sql = "select count(*) as jml from tbl_panduanbelanja where published='1' $where";
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
		$tpl->assign("tot",$tot);
		$tpl->assign("hlm_tot",$hlm_tot);
		$tpl->assign("hlm",$hlm);
		$tpl->assign("judul_per_hlm",$judul_per_hlm);
		$tpl->assign("ord",$ord+1);
			
		$sqlp	= "select id,nama,lengkap,alias from tbl_panduanbelanja where published='1' $where order by id desc limit $ord, $judul_per_hlm";
		$queryp	= sql($sqlp);
		$nump	= sql_num_rows($queryp);

		$list_post	= array();
		$no	= 1;
		while($row2 = sql_fetch_data($queryp))
		{
			$id			= $row2['id'];
			$nama		= $row2['nama'];
			$lengkap 	= bersihHTML($row2['lengkap']);
			$ringkas 	= substr($lengkap,0,200);
			$alias		= $row2['alias'];
			$link_detail = "$fulldomain/panduan/detail/$secalias/$id/$alias";
			
			$list_post[$id]	= array("id"=>$id,"nama"=>$nama,"ringkas"=>$ringkas,"link_detail"=>$link_detail,"ringkas"=>$ringkas);
			$no++;
		}
		sql_free_result($queryp);
		$tpl->assign("no",$no);
		$tpl->assign("nump",$nump);
		$tpl->assign("list_post",$list_post);
		$tpl->assign("kata",$kata);
		
		//Paging 
		
		$batas_page = 5;
		$stringpage = array();
		$pageid 	= 0;
		
		if ($hlm > 1){
		$prev = $hlm - 1;
		$pageid++;
		$stringpage[$pageid] = array("nama"=>"Prev","link"=>"$fulldomain/$kanal/$aksipage"."$prev");
	
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
		//Selesai Paging
}
?>