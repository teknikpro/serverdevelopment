<?php

	$judul_per_hlm 	= 10;
	$batas_paging 	= 5;
	$hlm 			= $var[5];
	$sql = "select count(*) as jml from tbl_ticketing where userid='$_SESSION[userid]' $where";
	$hsl = sql( $sql);
	$tot = mysql_result($hsl, 0, jml);
	$hlm_tot = ceil($tot / $judul_per_hlm);
	if (empty($hlm)){
		$hlm = 1;
		}
	if ($hlm > $hlm_tot){
		$hlm = $hlm_tot;
		}

	$ord = ($hlm - 1) * $judul_per_hlm;
	if ($ord < 0 ) $ord=0;

	$mysql 		= "select ticketingid,secid,judul,create_date,update_date,isipertanyaan,status,is_closed,reply_user from tbl_ticketing where userid='$_SESSION[userid]' $where
				order by ticketingid desc limit $ord, $judul_per_hlm";
	$hasil 		= sql($mysql);
	$dataticketing = array();
	$i 			= 1;
	while ($data =  sql_fetch_data($hasil))
	{
		$create_date		= $data['create_date'];
		$update_date		= $data['update_date'];
		$secid 	= $data['secid'];
		$ticketingid 		= $data['ticketingid'];
		$judul 				= $data['judul'];
		$alias				= getAlias($judul);
		$isipertanyaan 		= $data['isipertanyaan'];
		$status 			= $data['status'];
		$is_closed 			= $data['is_closed'];
		$reply_user			= $data['reply_user'];
		$tanggal 			= tanggalonly($update_date);
		
		if ($is_closed == 1)
		{
			$stats	= "Pertanyaan sudah selesai diproses";
		}
		else
		{
			if($reply_user == 2 and $status == 1)
			{
				$stats	= "Sudah dibalas oleh Admin.";

			}
			else if($reply_user == 1 and $status == 1)
			{
				$stats	= "Menunggu balasan dari Admin.";
			}
			else if($reply_user == 0 and $status == 0)
			{
				$stats	= "Menunggu balasan dari Admin";

			}
		}	

		$r 			= explode(" ",$isipertanyaan);
		$ringkas1 	= "";
		for($a=0; $a<20; $a++)
		{
			$ringkas1 .= $r[$a]." ";
		}
		$ringkas 	= $ringkas1;

		if(!empty($gambar)) $gambar = "$fulldomain/gambar/news/$gambar";
		else $gambar = "";

		$link 		= "$fulldomain/user/detailticketing/$ticketingid/$alias";

		$kategori = sql_get_var("select namasec from tbl_ticketing_sec where secid='$secid'");

		$dataticketing[$ticketingid] = array("ticketingid"=>$ticketingid,"no"=>$i,"judul"=>$judul,"ringkas"=>$ringkas,"tanggal"=>$tanggal,"link"=>$link,"kategori"=>$kategori,
						"stats"=>$stats,"is_closed"=>$is_closed);
		$i++;
	}
	sql_free_result($hasil);
	$tpl->assign("dataticketing",$dataticketing);
	$tpl->assign("totalticketing",$tot);

	//Paging
	$batas_page = 5;
	$stringpage = array();
	$pageid 	= 0;

	if ($hlm > 1){
		$prev = $hlm - 1;
		$stringpage[$pageid] = array("nama"=>"&laquo;","link"=>"$fulldomain/user/ticketing/list/1");
		$pageid++;
		$stringpage[$pageid] = array("nama"=>"&lsaquo;","link"=>"$fulldomain/user/ticketing/list/$prev");

	}
	else {
		$stringpage[$pageid] = array("nama"=>"&laquo;","link"=>"");
		$pageid++;
		$stringpage[$pageid] = array("nama"=>"&lsaquo;","link"=>"");
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
			$stringpage[$pageid] = array("nama"=>"$ii","link"=>"$fulldomain/user/ticketing/list/$ii");
		}
		$pageid++;
	}
	if ($hlm < $hlm_tot){
		$next = $hlm + 1;
		$stringpage[$pageid] = array("nama"=>"&rsaquo;","link"=>"$fulldomain/user/ticketing/list/$next");
		$pageid++;
		$stringpage[$pageid] = array("nama"=>"&raquo;","link"=>"$fulldomain/user/ticketing/list/$hlm_tot");
	}
	else
	{
		$stringpage[$pageid] = array("nama"=>"&rsaquo;","link"=>"");
		$pageid++;
		$stringpage[$pageid] = array("nama"=>"&raquo;","link"=>"");
	}

	$tpl->assign("stringpage",$stringpage);
	
	$tpl->assign("namarubrik","Ticketing");
?>