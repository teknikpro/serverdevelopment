<?php
if($_SESSION['username'])
{
	$hlm 			= $var[4];
	$judul_per_hlm 	= 10;
	$batas_paging 	= 5;
	
	$sql = "select count(*) as jml from tbl_notifikasi where tousername='$_SESSION[username]'";
	$hsl =sql($sql);
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


	//Notifikasi
	$perintah = "select id,fromusername,pesan,status,icon,tanggal from tbl_notifikasi where tousername='$_SESSION[username]' order by tanggal desc limit $ord, $judul_per_hlm";
	$hasil =sql($perintah);
	$jml = sql_num_rows($hasil);
	$notifikasi = array();
	while($data = sql_fetch_data($hasil))
	{
		$id 			= $data['id'];
		$id2 			= base64_encode($id);
		$base 			= md5($id2);
		$fromusername 	= $data['fromusername'];
		$pesan 			= $data['pesan'];
		$icon 			= $data['icon'];
		$status 		= $data['status'];
		if($status==0) $pesan = "<strong>$pesan</strong>";
		else
			$pesan = "$pesan";
		
		if(preg_match("/message/i",$url)) $icon = "envelope";
		else if(preg_match("/follower/i",$url)) $icon = "user";
		else 
		{
			if(preg_match("/dinding/i",$pesan)) $icon = "pencil-square";
			else $icon = "comments";
		}
		
		if($fromusername=="system") { $pesan1 = "<a href=\"$fulldomain/notifikasi/go/$id2/$base\">$pesan</a>"; }
		else { $pesan1 = "<a href=\"$fulldomain/$fromusername\"><strong>$fromusername</strong></a> <a href=\"$fulldomain/notifikasi/go/$id2/$base\">$pesan</a>"; }
		
		$dtuser	= getProfileName($fromUserName);
		$fname	= $dtuser['userFullName'];
		$avatar	= $dtuser['avatar'];
		$urluser	= "$fulldomain/$fromusername";
		$urlnotif	= "$fulldomain/notifikasi/go/$id2/$base";
		
		$tanggal = tanggal($data->tanggal);
		
		$tanggal = $data['tanggal'];
		//explode tanggal
		$tgl		= explode(" ",$tanggal);
		$tegeel		= $tgl[0];
		$tegeel1	= tanggalLahir($tegeel);
		$jam		= $tgl[1];
		$jam1		= $jam;
		//explode waktu
		$time		= explode(":",$jam1);
		$tm1		= $time[0];
		$tm2		= $time[1];
		$tm3		= $time[2];
	
		if($tm1>12)
			$ket	= "pm";
		else
			$ket	= "am";
	
		if($tegeel==$skr)
			$tgltampil	= $tm1.":".$tm2." ".$ket;
		else
			$tgltampil	= $tegeel1." at ".$tm1.":".$tm2." ".$ket;

		$notifikasi[$id] = array("pesan"=>$pesan1,"fname"=>$fname,"avatar"=>$avatar,"pesannotif"=>$pesan,"urluser"=>$urluser,"urlnotif"=>$urlnotif,"tgltampil"=>$tgltampil,"status"=>$status,
						"icon"=>$icon);
	}
	$tpl->assign("datanotifikasi",$notifikasi);
	$tpl->assign("jnoti",$jml);
	sql_free_result($hasil);
	
			//Paging 
		$batas_page =5;
		
		$stringpage = array();
		$pageid =0;
		
		if ($hlm > 1){
			$prev = $hlm - 1;
			$stringpage[$pageid] = array("nama"=>"Awal","link"=>"$domainfull/$kanal/$aksi/1");
			$pageid++;
			$stringpage[$pageid] = array("nama"=>"&laquo;","link"=>"$domainfull/$kanal/$aksi/$prev");

		}
		else {
			$stringpage[$pageid] = array("nama"=>"Awal","link"=>"");
			$pageid++;
			$stringpage[$pageid] = array("nama"=>"&laquo;","link"=>"");
		}
		
		$hlm2 = $hlm - (ceil($batas_page/2));
		$hlm4= $hlm+(ceil($batas_page/2));
		
		if($hlm2 <= 0 ) $hlm3=1;
		   else $hlm3 = $hlm2;
		$pageid++;
		for ($ii=$hlm3; $ii <= $hlm_tot and $ii<=$hlm4; $ii++){
			if ($ii==$hlm){
				$stringpage[$pageid] = array("nama"=>"$ii","link"=>"","class"=>"active"	);
			}else{
				$stringpage[$pageid] = array("nama"=>"$ii","link"=>"$domainfull/$kanal/$aksi/$ii");
			}
			$pageid++;
		}
		if ($hlm < $hlm_tot){
			$next = $hlm + 1;
			$stringpage[$pageid] = array("nama"=>"&raquo;","link"=>"$domainfull/$kanal/$aksi/$next");
			$pageid++;
			$stringpage[$pageid] = array("nama"=>"Akhir","link"=>"$domainfull/$kanal/$aksi/$hlm_tot");
		}
		else
		{
			$stringpage[$pageid] = array("nama"=>"&raquo;","link"=>"");
			$pageid++;
			$stringpage[$pageid] = array("nama"=>"Akhir","link"=>"");
		}
		$tpl->assign("stringpage",$stringpage);
	
}

?>