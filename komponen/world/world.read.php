<?php 
$alias = $var[3];
$subkanal = $var[4];
$tpl->assign("subkanal",$subkanal);


$perintah = "select id,nama,ringkas,lengkap,gambar1,gambar,cover,cover1,cover2,background,create_date,alias,views,latitude,longitude,alamat,contact,telp,email,homepage from tbl_$kanal where id='$id'";
$hasil = sql($perintah);
$data =  sql_fetch_data($hasil);

$idcontent = $data['id'];
$nama=$data['nama'];
$oleh = $data['oleh'];
$lengkap= $data['lengkap'];
$tanggal = tanggal($data['create_date']);
$gambar = $data['gambar1'];
$gambarshare = $data['gambar'];
$gambarcover = $data['cover'];
$gambarcover1 = $data['cover1'];
$gambarcover2 = $data['cover2'];
$background = $data['background'];
$ringkas = $data['ringkas'];
$alias = $data['alias'];
$views = $data['views'];
$latitude = $data['latitude'];
$longitude = $data['longitude'];
$alamat = $data['alamat'];
$contact = $data['contact'];
$telp = $data['telp'];
$email = $data['email'];
$homepage = $data['homepage'];

if(empty($katid)) $katid="0";

//Sesuaikan dengan path
$lengkap = str_replace("jscripts/tiny_mce/plugins/emotions","$domain/plugin/emotion",$lengkap);
$lengkap = str_replace("../../","/",$lengkap);


$tpl->assign("detailid",$idcontent);
$tpl->assign("detailnama",$nama);
$tpl->assign("detailalias",$alias);
$tpl->assign("detaillengkap",$lengkap);
$tpl->assign("detailringkas",$ringkas);
$tpl->assign("detailcreator",$oleh);
$tpl->assign("detailtanggal",$tanggal);
$tpl->assign("detaildate",$data['create_date']);
$tpl->assign("detaillatitude",$latitude);
$tpl->assign("detaillongitude",$longitude);
$tpl->assign("detailalamat",$alamat);
$tpl->assign("detailcontact",$contact);
$tpl->assign("detailtelp",$telp);
$tpl->assign("detailemail",$email);
$tpl->assign("detailhomepage",$homepage);

if(!empty($gambar)) $tpl->assign("detailgambar","$domain/gambar/$kanal/$gambar");
if(!empty($gambarshare)) $tpl->assign("detailgambarshare","$domain/gambar/$kanal/$gambarshare");
if(!empty($background)) $tpl->assign("detailbackground","$domain/gambar/$kanal/$background");

//Slide Cover
$slide = array();
$x = 1;
if(!empty($gambarcover))
{
	$slide[] = array("no"=>$x,"nama"=>"$nama Cover 1","gambar"=>"$domain/gambar/$kanal/$gambarcover");
	$x++;
}
if(!empty($gambarcover1))
{
	$slide[] = array("no"=>$x,"nama"=>"$nama Cover 1","gambar"=>"$domain/gambar/$kanal/$gambarcover1");
	$x++;
}

if(!empty($gambarcover1))
{
	$slide[] = array("no"=>$x,"nama"=>"$nama Cover 1","gambar"=>"$domain/gambar/$kanal/$gambarcover2");
	$x++;
}
$tpl->assign("slide",$slide);


sql_free_result($hasil);

// Masukan data kedalam statistik
$stats = number_format($views,0,0,".");
$tpl->assign("detailstats",$stats);
$tpl->assign("detailviews",$stats);
$view = 1;

$views = "update tbl_$kanal set views=views+1 where id='$id'";
$hsl = sql($views);


//Halaman
$mysql = "select id,ringkas,nama,create_date,alias from tbl_world_page where pageid='$idcontent'";
$hasil = sql($mysql);

$datapage = array();
$a =1;
while ($data = sql_fetch_data($hasil)) {	
		$tanggal = $data['create_date'];
		$namas = $data['nama'];
		$id = $data['id'];
		$ringkas = $data['ringkas'];
		$pagealias = $data['alias'];
		
		if($pagealias==$subkanal) $active = 1;		
		else $active = 0;

		$link = "$fulldomain/world/$alias/$pagealias";
			
		$datapage[] = array("id"=>$id,"a"=>$a,"nama"=>$namas,"ringkas"=>$ringkas,"namasec"=>$namasec,"tanggal"=>$tanggal,"alias"=>$pagealias,"link"=>$link,"active"=>$active);
		$a++;	
}
$a = 0;
sql_free_result($hasil);
$tpl->assign("datapage",$datapage);

if(!empty($subkanal))
{
	
	if($subkanal=="galeri")
	{
		$galeriid = $var[5];
		if(empty($galeriid))
		{
			$perintah = "select id,galeriid,nama,oleh,ringkas,gambar1,create_date,alias,views from tbl_world_galeri where id='$idcontent' limit 1";
		}
		else
		{
			$perintah = "select id,galeriid,nama,oleh,ringkas,gambar1,create_date,alias,views from tbl_world_galeri where id='$idcontent' and galeriid='$galeriid' limit 1";
		}
		
		
		$hasil = sql($perintah);
		$data =  sql_fetch_data($hasil);
		
		$galeriid = $data['galeriid'];
		$nama=$data['nama'];
		$oleh = $data['oleh'];
		$lengkap= $data['lengkap'];
		$tanggal = tanggal($data['create_date']);
		$gambar = $data['gambar1'];
		$ringkas = $data['ringkas'];
		$views = $data['views'];
		
		if(empty($katid)) $katid="0";
		
		//Sesuaikan dengan path
		$lengkap = str_replace("jscripts/tiny_mce/plugins/emotions","$domain/plugin/emotion",$lengkap);
		$lengkap = str_replace("../../","/",$lengkap);
		
		
		$tpl->assign("galeridetailid",$idcontent);
		$tpl->assign("galeridetailnama",$nama);
		$tpl->assign("galeridetaillengkap",$lengkap);
		$tpl->assign("galeridetailringkas",$ringkas);
		$tpl->assign("galeridetailoleh",$oleh);
		$tpl->assign("galeridetailtanggal",$tanggal);
		
		if(!empty($gambar)) $tpl->assign("galeridetailgambar","$domain/gambar/world/$gambar");
		sql_free_result($hasil);
		
		// Masukan data kedalam statistik
		$stats = number_format($views,0,0,".");
		$tpl->assign("detailviews",$stats);
		$view = 1;
		
		$views = "update tbl_world_galeri set views=views+1 where galeriid='$galeriid'";
		$hsl = sql($views);
		
		//Berita Terkait
		$mysql = "select galeriid,nama,alias,gambar,ringkas,create_date from tbl_world_galeri where published='1' and galeriid != '$galeriid' and id='$idcontent' $where order by create_date desc";
		$hasil = sql( $mysql);
		
		$terkait = array();
		$a =1;		
		while ($data =  sql_fetch_data($hasil)) {	
				$tanggal = $data['create_date'];
				$nama = $data['nama'];
				$galeriid = $data['galeriid'];
				$ringkas = $data['ringkas'];
				$aliasgaleri = getalias($data['nama']);
				$tanggal = tanggal($tanggal);
				$gambar = $data['gambar'];
				
				if(!empty($gambar)) $gambar = "$domain/gambar/world/$gambar";
					 else $gambar = "";
				
				$link = "$fulldomain/world/$alias/galeri/$galeriid/$aliasgaleri";
					
				$terkait[$galeriid] = array("id"=>$id,"a"=>$a,"nama"=>$nama,"ringkas"=>$ringkas,"namasec"=>$namasec,"tanggal"=>$tanggal,"alias"=>$alias,"link"=>$link,"gambar"=>$gambar);
				$a++;	
		}
		sql_free_result($hasil);
		$tpl->assign("terkait",$terkait);
	}
	if($subkanal=="news")
	{
		$newsid = $var[5];
		if(!empty($newsid))
		{
			$perintah = "select id,nama,ringkas,lengkap,gambar from tbl_world_news where pageid='$idcontent' and id='$newsid'";
			$hasil = sql($perintah);
			$data =  sql_fetch_data($hasil);
			
			$namapage =$data['nama'];
			$lengkappage = $data['lengkap'];
			$gambarnews = $data['gambar'];
			
			if(!empty($gambarnews)) $tpl->assign("detailgambarnews","$fulldomain/gambar/$kanal/$gambarnews");
		
			$tpl->assign("detailnamanews",$namapage);
			$tpl->assign("detaillengkapnews",$lengkappage);			
			$tpl->assign("detailnamanews","$namapage");
		}
		else
		{
			//Berita 
			$mysql = "select id,nama,alias,gambar,ringkas,create_date from tbl_world_news where published='1' and pageid='$idcontent' order by create_date desc";
			$hasil = sql( $mysql);
			
			$datanews = array();
			$a =1;		
			while ($data =  sql_fetch_data($hasil)) {	
					$tanggal = $data['create_date'];
					$nama = $data['nama'];
					$ids = $data['id'];
					$ringkas = $data['ringkas'];
					$aliasgaleri = getalias($data['nama']);
					$tanggal = tanggal($tanggal);
					$gambar = $data['gambar'];
					
					if(!empty($gambar)) $gambar = "$domain/gambar/world/$gambar";
						 else $gambar = "";
					
					$link = "$fulldomain/world/$alias/news/$ids/$aliasgaleri";
						
					$datanews[] = array("id"=>$id,"a"=>$a,"nama"=>$nama,"ringkas"=>$ringkas,"namasec"=>$namasec,"tanggal"=>$tanggal,"alias"=>$alias,"link"=>$link,"gambar"=>$gambar);
					$a++;	
			}
			sql_free_result($hasil);
			$tpl->assign("datanews",$datanews);
			
		}
	}
	else
	{
		$perintah = "select id,nama,ringkas,lengkap from tbl_world_page where pageid='$idcontent' and alias='$subkanal'";
		$hasil = sql($perintah);
		$data =  sql_fetch_data($hasil);
		
		$namapage =$data['nama'];
		$lengkappage = $data['lengkap'];
	
		$tpl->assign("detailid",$idcontent);
		$tpl->assign("detailnamapage",$namapage);
		$tpl->assign("detaillengkappage",$lengkappage);
		
		$tpl->assign("detailnama","$nama");
	}

}


?>
