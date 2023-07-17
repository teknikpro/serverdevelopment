<?php 
	$urlnya=$_SERVER["REQUEST_URI"];
	$tpl->assign("urlnya",$urlnya);
		
	if($kanal=="profile" || $aksi=="cari") $hlm = $var[5];
	else
	{ 
		$hlm = $var[4];
		$subaksi = $var[5];
		
		if(empty($subaksi)) $subaksi = "terbaru";
	}
	
	if($subaksi=="populer") $urut = "order by jmlkomen desc";
	else $urut = "order by postid desc";
	
	
	if($aksi=="feed" || $aksi=="cari" ) $judul_per_hlm = 10;
	else $judul_per_hlm = 8;
	
	
	$batas_paging 	= 5;
	
	
	if($kanal=="profile")
	{
		if($aksi=="readpost" || $aksi=="post")
			$wherex 	= "where  postid='$var[5]'";
		else
			$wherex 	= "where  username='$username' or tousername='$username'";
			
	}
	else
	{
		$limit 		= "2";
		if($aksi=="post")
		{	
			$wherex 	= "where postid='$var[4]'";
		}
		elseif($aksi=="cari" && !empty($var[4]))
		{
			$kata = str_replace(".","",$var[4]);
			$wherex 	= "where  isi like '%#$kata%'";
		}
		elseif($aksi=="stream")
		{
			$wherex 	= "";
		}
		else
		{
			//Untuk Menampilkan yang sudah mengikuti saja
			$ids[] = "'$_SESSION[userid]'";
			$ids[] = "'0'";
			$res = sql("select fid from tbl_follow where userid='$_SESSION[userid]'");
			$x = 0;
			while($dt=sql_fetch_data($res))
			{
				$ids[] = "'$dt[fid]'";
				$x++;
			}
			
			$ids1 = implode(',',$ids);
			$wherex 	= " where  userid in ({$ids1})";
			
		}
	
	}
	
	$sql 	= "select count(*) as jml from tbl_post $wherex";
	$hsl	= sql($sql);
	$tot 	= sql_result($hsl, 0, jml);
	
	$hlm_tot= ceil($tot / $judul_per_hlm);		
	if (empty($hlm)){
		$hlm = 1;
		}
	if ($hlm > $hlm_tot){
		$hlm = $hlm_tot;
		}
	
	$ord = ($hlm - 1) * $judul_per_hlm;
	if ($ord < 0 ) $ord=0;
	
	$tpl->assign("tot",$tot);
	
	$datapost	= array();
	$perintah	= "select postid,isi,username,userid,userfullname,touserid,tousername,touserfullname,tanggal,jmlLike,groupnya,type,media,via,jmlkomen from tbl_post $wherex $urut limit $ord, $judul_per_hlm";
	$hasil		= sql($perintah);//echo $perintah;
	$u			= 1;
	while($data	= sql_fetch_data($hasil))
	{
		$postid		= $data['postid'];
		$isi		= $data['isi'];
		$isi		= geturl(trim($isi));
		$jmlisi 	= strlen($isi);
				
		if($jmlisi > 200 && $aksi!="post")
		{
			$isi = nl2br(substr($isi,0,200))."... <p><a class=\"btn btn-xs btn-success\" href=\"/member/post/$postid/$kanal\">Selengkapnya</a></p>";
		}
		$isii = explode(" ",$isi);
		for($uu=0;$uu<count($isii);$uu++)
		{
			$kata = $isii[$uu];
			if($kata[0]=="@")
			{
				$katax = str_replace("@","",$kata);
				
				$mnt = sql_get_var("select username from tbl_member where username='$katax' limit 1");
				
				if(!empty($mnt))
				{
					$isi = str_replace("$kata","<a href=\"$fulldomain/$mnt\">$kata</a>",$isi);
				}
				
			}
		}
		$isi		= preg_replace('/(\#)([^\s]+)/', "<a href=\"$fulldomain/member/cari/$2\">#$2</a>", $isi);
		$usernames	= $data['username'];
		$tousername	= $data['tousername'];
		$via		= $data['via'];
		$userid		= $data['userid'];
		$jmlkomen	= $data['jmlkomen'];
		$namalengkap= $data['userfullname'];
		$avatar		= "$fulldomain/uploads/avatar/$userid.jpg";		
		$profileurl		= "$fulldomain/$usernames";
		
		if($tousername!=$usernames)
		{
			$tonamalengkap = $data['touserfullname'];
			$toprofileurl		= "$fulldomain/$tousername";
		}
		else
		{
			$tonamalengkap  = "";
			$toprofileurl	= "";
		}
		
		if($userid=='0')
		{
			$namalengkap= "SalingSapa";
			$profileurl		= "$fulldomain/about";
		}
		
		
		$tanggal	= $data['tanggal'];
		$type		= $data['type'];
		$skr		= date("Y-m-d");
		$media	= $data['media'];

		
		if (($usernames == $_SESSION['username']) or ($tousername == $_SESSION['username'])) $hapusposting = 1;
		else $hapusposting = 0;
		
		
		//explode tanggal
		$tgl		= explode(" ",$tanggal);
		$tegeel		= $tgl[0];
		$tegeel1	= tanggalsingkat($tegeel);
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
	
		//check like stats name
		$query3	= "select username from tbl_post_like where postid='$postid' order by id desc";
		$hsl3	= sql($query3);
		$jum	= sql_num_rows($hsl3);
		$x		= 0;
		$likearray 	= array();
		$likearray1 = array();
		while ($dta3= sql_fetch_data($hsl3))
		{
			if($_SESSION['username']==$dta3['username'])
			{
				$userlikename	= "Anda";
				$userlikename1	= $userlikename;
			}
			else
			{
				$userxx			= getProfileName($dta3['username']);
				
				$userlikename1	= "<span class='none_icon'><a href='$fulldomain/".$userxx['username']."'>".$userxx['username']."</a></span>";
			}
		
			if ($jum == 2)	
			{
				$likearray[$x] 	= $userlikename;
				$likearray1[$x] = $userlikename1;
				if (($likearray[0] == "Anda") or ($likearray[0] == $userName))
					$userlikename2 = $likearray1[0] . " dan ".$likearray1[1];			
				else
					$userlikename2 = $likearray1[1] . " dan ".$likearray1[0];
				
			}
			else if ($jum > 2)
			{
				if ($userlikename == "Anda")
				{
					$userlikename2 = "Anda";
					break;
				}
				else 
				{
					if ($userlikename == $userName)
						$userlikename2 = $userName;
				}	
			}
			else
			{
					$userlikename2 = $userlikename1;
			}
			$x++;
		}
		
		//cek like atau tidak
		$query2		= "select count(*) as jmllikenya from tbl_post_like where postid='$postid' and username='$_SESSION[username]'";
		$hhsl		= sql($query2);
		$ddata1		= sql_fetch_data($hhsl);
		$jmllikenya	= $ddata1['jmllikenya'];
		if ($jmllikenya > 0)
			$unlike	= "benar";
		else
			$unlike	= "salah";
		sql_free_result($hhsl);	

		//buat ambil limit komennya
		$limit = 2;
		if ($jmlkomen > $limit)
			$mulai 	= $jmlkomen - $limit;
		else
			$mulai 	= 0; 
		
		if($jmlkomen>0)
		{
		
		if($s=="m" && $aksi!="post")
		{
		}
		else
		{
		
			if($aksi=="post")
				$sql	= "select id,username,userid,userfullname,isi,tanggal,via from tbl_post_komen where postid='$postid' order by tanggal desc";
			else
				$sql	= "select id,username,userid,userfullname,isi,tanggal,via from tbl_post_komen where postid='$postid' order by tanggal desc limit $mulai,2";
			
			$hsl	= sql($sql);
			while($row = sql_fetch_data($hsl))
			{
				$id			= $row['id'];
				$oleh		= $row['username'];
				$olehuserid		= $row['userid'];
				$gambar		= "$fulldomain/uploads/avatar/$olehuserid.jpg";
				$nama		= $row['userfullname'];
				$urlprofilecomment = "$fulldomain/$oleh";
				$cvia		= $row['via'];
				
				$komentar	= geturl(trim($row['isi']));
				$jmlkom = strlen($komentar);
				
				if($jmlkom > 300 && $aksi!="post")
				{
					$komentar = substr($komentar,0,300)." <a href=\"/member/post/$postid\">more</a>";
				}
				
				$linkgambar	= "$fulldomain/pic/$oleh";
				$ttggll		= $row['tanggal'];
				
				
				$skr		= date("Y-m-d");
				
				//explode tanggal
				$tgl1		= explode(" ",$ttggll);
				$tegeel1	= $tgl1[0];
				$tegeel2	= tanggalLahir($tegeel1);
				$jam1		= $tgl1[1];
				$jam2		= $jam1;
				//explode waktu
				$time1		= explode(":",$jam2);
				$tm11		= $time1[0];
				$tm22		= $time1[1];
				$tm32		= $time1[2];

				if($tm11>12)
					$ket1	= "pm";
				else
					$ket1	= "am";
				
				if($tegeel1==$skr)
					$tgltampil1	= $tm11.":".$tm22." ".$ket1;
				else
					$tgltampil1	= $tegeel2." at ".$tm11.":".$tm22." ".$ket1;
				
				//hapus komentar nya
				if( ($oleh==$_SESSION['username']) or ($hapusposting =="1") )
					$hapus	= 1;
				else
					$hapus	= 0;
				
				$datakomen[$postid][$id] = array("id"=>$id,"nama"=>$nama,"urlprofile"=>$urlprofilecomment,"komentar"=>$komentar,"gambar"=>$gambar,"oleh"=>$oleh,"hapus"=>$hapus,"tgltampil1"=>$tgltampil1,
											"linkgambar"=>$linkgambar,"via"=>$cvia);
			}
			sql_free_result($hsl);
			
			}
			
		
		}
		
			//Media
			if(!empty($media))
			{
				$media = unserialize($media);
				$mjenis = $media['jenis'];
				$mcontent = $media['gambar'];
				$mlokasi = $media['lokasi'];
				$mcontent = $media['media'];
				$myoutubeid = $media['youtubeid'];
				$mnama = $media['nama'];
				$mcontent = "$fulldomain/$mcontent";
				$murl = $media['url'];		
				
				
			}
		
			//hapus postingan 
			if($username==$_SESSION['username']) $hapus1 = 1;
			else $hapus1= 0;
		
			$datapost[$u] = array("u"=>$u,
									"postid"=>$postid,
									"isi"=>$isi,
									"avatar"=>$avatar,
									"hapus"=>$hapus1,
									"jmlkomen"=>$jmlkomen,
									"jmlkomenBaru"=>$jmlkomenBaru,
									"namalengkap"=>$namalengkap,
									"mjenis"=>$mjenis,
									"mnama"=>$mnama,
									"murl"=>$murl,
									"mcontent"=>$mcontent,
									"myoutubeid"=>$myoutubeid,
									"userDirName"=>$userDirName,
									"username"=>$usernames,
									"profileurl"=>$profileurl,
									"unlike"=>$unlike,
									"jmlLike"=>$jum,
									"jmlLike2"=>($jum-1),
									"userlikename"=>$userlikename,
									"userlikename2"=>$userlikename2,
									"tgltampil"=>$tgltampil,
									"hapusposting"=>$hapusposting,
									"via"=>$via,
									"tonamalengkap"=>$tonamalengkap,
									"toprofileurl"=>$toprofileurl);
			$u++;
			unset($mjenis,$murl,$mcontent);
			

	}
	sql_free_result($hasil);
	$tpl->assign("datapost",$datapost);
	$tpl->assign("datakomen",$datakomen);
	
	if (empty($aksi)) $aksi="listpost";
	if ($kanal=="profile") $kanal2 = $username;
	else $kanal2 = $kanal;
	//Paging 
	$batas_page =5;
	
	$stringpage = array();
	$pageid =0;
	
	if($aksi=="cari") $aksi = "cari/$var[3]";
	
	if ($hlm > 1){
		$prev = $hlm - 1;
		$stringpage[$pageid] = array("nama"=>"Awal","link"=>"$domainfull/$kanal2/$aksi/1/$subaksi");
		$pageid++;
		$stringpage[$pageid] = array("nama"=>"&laquo;","link"=>"$domainfull/$kanal2/$aksi/$prev/$subaksi");

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
			$stringpage[$pageid] = array("nama"=>"$ii","link"=>"","class"=>"active");
		}else{
			$stringpage[$pageid] = array("nama"=>"$ii","link"=>"$domainfull/$kanal2/$aksi/$ii/$subaksi");
		}
		$pageid++;
	}
	if ($hlm < $hlm_tot){
		$next = $hlm + 1;
		$stringpage[$pageid] = array("nama"=>"&raquo;","link"=>"$domainfull/$kanal2/$aksi/$next/$subaksi");
		$pageid++;
		$stringpage[$pageid] = array("nama"=>"Akhir","link"=>"$domainfull/$kanal2/$aksi/$hlm_tot/$subaksi");
	}
	else
	{
		$stringpage[$pageid] = array("nama"=>"&raquo;","link"=>"");
		$pageid++;
		$stringpage[$pageid] = array("nama"=>"Akhir","link"=>"");
	}
	$tpl->assign("stringpage",$stringpage);
	//Selesai Paging
	srand(time());
	$random = (rand()%4);
	
?>