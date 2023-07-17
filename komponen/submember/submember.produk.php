<?php
$subaksi=$var[4];
$tpl->assign("subaksi",$subaksi);


switch ($subaksi)
{
	case 'add':
		if($_POST['save']=="save")
		{
			$title    = cleaninsert($_POST['title']);
			$ringkas = desc($_POST['ringkas']);
			$content = desc($_POST['content']);
			$tag    = cleaninsert($_POST['tag']);
			$secid   = $_POST['secid'];
			$subid   = $_POST['subid'];
			$harga   = $_POST['harga'];
			$status  = $_POST['status'];
			$body_dimension   = $_POST['body_dimension'];
			$body_weight   = $_POST['body_weight'];
			$stock   = $_POST['stock'];
			$alias   = getalias($nama);
			
			$produkpostid = newid("produkpostid","tbl_product_post");
			$date     = date("Y-m-d H:i:s");
			
			$dirname = "$pathfile/produk/$produkpostid";
			if(!file_exists("$dirname")) mkdir("$dirname");
			
			$no = 10000+$produkpostid;
			$kodeproduk = "FS".$no;
			
			$xx = count($_FILES['gambar']['tmp_name']);
			$files = $_FILES['gambar']['tmp_name'];
			$type = $_FILES['gambar']['type'];
			$size = $_FILES['gambar']['size'];
			
	
			for($x=0;$x<$xx;$x++)
			{
				$xname = "$files[$x]";
			
				if($size[$x]> 0)
				{
				
					$jenis = $type[$x];
					if(preg_match("/jp/i",$jenis)) $ext = "jpg";
					if(preg_match("/gif/i",$jenis)) $ext = "gif";
					if(preg_match("/png/i",$jenis)) $ext = "png";
					
					$albumid = newid("albumid","tbl_product_image");

					if ($ext == 'jpg' || $ext == 'png' || $ext == 'jpeg' )
					{	
						$namagambars = "produk-$_SESSION[userid]-$produkpostid-$albumid-s.$ext";
						$gambar     = thumbnail($xname,"$dirname/$namagambars",200,200);
						
						$namagambarm = "produk-$_SESSION[userid]-$produkpostid-$albumid-m.$ext";
						$gambar     = thumbnail($xname,"$dirname/$namagambarm",400,400);
						
						$namagambarl = "produk-$_SESSION[userid]-$produkpostid-$albumid-l.$ext";
						$gambarl     = resizeimg($xname,"$dirname/$namagambarl",600,800);
						
						$namagambarf = "produk-$_SESSION[userid]-$produkpostid-$albumid-f.$ext";
						$gambarf     = resizeimg($xname,"$dirname/$namagambarf",800,1200);
						
						if($gambarl)
						{ 
							$query	= "insert into tbl_product_image(albumid,create_date,create_userid,produkpostid,gambar_f,gambar_l,gambar_m,gambar_s,userid)
										values ('$albumid','$date','$_SESSION[userid]','$produkpostid','$namagambarf','$namagambarl','$namagambarm','$namagambars','$_SESSION[userid]')";
							$hasil 	= sql($query); 
						}
					}
				}
			}
			
			$body_dimension   = $_POST['body_dimension'];
			$body_weight   = $_POST['body_weight'];
			$stock   = $_POST['stock'];
			
			$query	= "insert into tbl_product_post(produkpostid,kodeproduk,create_date,create_userid,userid,title,ringkas,content,alias,tag,harga,secid,subid,status,kecid,kotaid,published,body_dimension,body_weight,stock)
					values ('$produkpostid','$kodeproduk','$date','$_SESSION[userid]','$_SESSION[userid]','$title','$ringkas','$content','$alias','$tag','$harga','$secid','$subid','$status','$kecid','$kotaid','1','$body_dimension','$body_weight','$stock')";
			$hasil 	= sql($query); 
			
					
			if($hasil)
			{
				$error 		= "Penyimpanan produk baru telah berhasil dilakukan, silahkan tambahkan produk lain yang akan anda jual kepada calon pelanggan anda. Jangan lupa untuk merubah status iklan anda menjadi <strong>Tayangkan</strong>";
				$style		= "alert-success";
				$backlink	= "$fullmanage/user/produk/detail/$produkpostid/";
			}
			else
			{
				$error 		= "Penyimpanan produk baru gagal dilakukan ada beberapa kesalahan yang harus diperbaiki, silahkan periksa kembali.";
				$style		= "alert-danger";
				$backlink	= "$fullmanage/user/produk/detail/$produkpostid/";
			}
			
			$tpl->assign("error",$error);
			$tpl->assign("style",$style);
			$tpl->assign("backlink",$backlink);
		}
		else
		{
			$kecid = sql_get_var("SELECT kecid FROM tbl_member WHERE userid='$_SESSION[userid]'");
			
			//kategori
			$datakategori = array();
			$pkategori = "select secid,namasec from tbl_product_sec order by namasec asc";
			$hkategori = sql($pkategori);
			while($dkategori= sql_fetch_data($hkategori))
			{
				$datakategori[$dkategori['secid']] = array("secid"=>$dkategori['secid'],"nama"=>$dkategori['namasec']);
			}
			sql_free_result($hkategori);
			$tpl->assign("kategori",$datakategori);
			$tpl->assign("kecid",$kecid);
			$tpl->assign("status",($kecid != 0)? 1:0);
		}
		
		break;

	case 'edit':
		if($_POST['save']=='save')
		{
			$title    = cleaninsert($_POST['title']);
			$ringkas = desc($_POST['ringkas']);
			$content = desc($_POST['content']);
			$tag     = cleaninsert($_POST['tag']);
			$secid    = $_POST['secid'];
			$subid    = $_POST['subid'];
			$harga    = $_POST['harga'];
			$status   = $_POST['status'];
			$niaga    = $_POST['niaga'];
			$body_dimension   = $_POST['body_dimension'];
			$body_weight   = $_POST['body_weight'];
			$stock   = $_POST['stock'];
			$alias    = getalias($nama);
			$date     = date("Y-m-d H:i:s");
			$produkpostid = $_POST['produkpostid'];

			$dirname = "$pathfile/produk/$produkpostid";
			if(!file_exists("$dirname")) mkdir("$dirname");
			

			
			$xx = count($_FILES['gambar']['tmp_name']);
			$files = $_FILES['gambar']['tmp_name'];
			$type = $_FILES['gambar']['type'];
			$size = $_FILES['gambar']['size'];
			
	
			for($x=0;$x<$xx;$x++)
			{
	
				$xname = "$files[$x]";
			
				if($size[$x]> 0)
				{
				
					$jenis = $type[$x];
					if(preg_match("/jp/i",$jenis)) $ext = "jpg";
					if(preg_match("/gif/i",$jenis)) $ext = "gif";
					if(preg_match("/png/i",$jenis)) $ext = "png";
					
					$albumid = newid("albumid","tbl_product_image");

					if ($ext == 'jpg' || $ext == 'png' || $ext == 'jpeg' )
					{
						$namagambars = "produk-$_SESSION[userid]-$produkpostid-$albumid-s.$ext";
						$gambar     = thumbnail($xname,"$dirname/$namagambars",200,200);
						
						$namagambarm = "produk-$_SESSION[userid]-$produkpostid-$albumid-m.$ext";
						$gambar     = thumbnail($xname,"$dirname/$namagambarm",400,400);
						
						$namagambarl = "produk-$_SESSION[userid]-$produkpostid-$albumid-l.$ext";
						$gambarl     = resizeimg($xname,"$dirname/$namagambarl",600,800);
						
						$namagambarf = "produk-$_SESSION[userid]-$produkpostid-$albumid-f.$ext";
						$gambarf     = resizeimg($xname,"$dirname/$namagambarf",800,1200);
						
						if($gambarl)
						{ 
							$query	= "insert into tbl_product_image(albumid,create_date,create_userid,userid,produkpostid,gambar_f,gambar_l,gambar_m,gambar_s)
										values ('$albumid','$date','$_SESSION[userid]','$_SESSION[userid]','$produkpostid','$namagambarf','$namagambarl','$namagambarm','$namagambars')";
							$hasil 	= sql($query); 
						}
					}
				}
			}
			
			$query	= "UPDATE tbl_product_post SET update_date='$date',update_userid='$_SESSION[userid]',userid='$_SESSION[userid]',title='$title',alias='$alias',tag='$tag',
				ringkas='$ringkas',content='$content',harga='$harga',secid='$secid',subid='$subid',status='$status',kecid='$kecid',kotaid='$kotaid',body_dimension='$body_dimension',body_weight='$body_weight',stock='$stock' WHERE produkpostid='$produkpostid'";
			$hasil 	= sql($query); 
					
			if($hasil)
			{
				$error 		= "Perubahan produk telah berhasil dilakukan, silahkan tambahkan produk lain yang akan anda jual kepada calon pelanggan anda";
				$style		= "alert-success";
				$backlink	= "$fullmanage/user/produk/detail/$produkpostid";
			}
			else
			{
				$error 		= "Perubahan produk baru gagal dilakukan ada beberapa kesalahan yang harus diperbaiki, silahkan periksa kembali.";
				$style		= "alert-danger";
				$backlink	= "$fullmanage/user/produk/detail/$produkpostid";
			}
			
			$tpl->assign("error",$error);
			$tpl->assign("style",$style);
			$tpl->assign("backlink",$backlink);

		}
		else
		{
			$dataedit = array();

			//produk
			$sql = "SELECT * FROM tbl_product_post WHERE produkpostid='$var[5]'";
			$sql = sql($sql);
			$produk = sql_fetch_data($sql);
			
			$produkpostid = $produk['produkpostid']; 

			//kategori
			$datakategori = array();
			$pkategori = "select secid,namasec from tbl_product_sec order by namasec asc";
			$hkategori = sql($pkategori);
			while($dkategori= sql_fetch_data($hkategori))
				$datakategori[] = $dkategori;
			sql_free_result($hkategori);

			//sub kategori
			$subopt = array();
			$sql = "SELECT subid,namasub FROM tbl_product_sub WHERE secid='$produk[secid]'";
			$sql = sql($sql);
			while ($row = sql_fetch_data($sql))
				$subopt[$row['subid']] = $row;
			sql_free_result($sql);

			// gambar
			$sql = "SELECT albumid,gambar_s FROM tbl_product_image WHERE produkpostid='$produk[produkpostid]'";
			$sql = sql($sql);
			while ($row = sql_fetch_data($sql))
				$gambar[$row['albumid']] = array('albumid'=>$row['albumid'], 'gambar'=>"$fulldomain/gambar/produk/$produkpostid/$row[gambar_s]");
			sql_free_result($sql);

			
			$dataedit = array(
					'produk'   => $produk,
					'kategori' => $datakategori,
					'gambar'   => $gambar,
					'subopt'   => $subopt
				);

			$tpl->assign("dataedit",$dataedit);
		}

		break;

	case 'ajax-subcat':
		$query = "SELECT subid,nama FROM tbl_product_post_sub WHERE secid='$_POST[secid]'";
		$query = sql($query);

		$datasub = array();
		while ($row = sql_fetch_data($query))
			$datasub[$row['subid']] = $row['nama'];

		header('Content-Type: application/json');
		$datasub = json_encode($datasub);
		echo $datasub;
		break;

	case 'hapusgambar':
		
		$albumid = $var[5];
		$query   = ("SELECT produkpostid,gambar_s,gambar_m,gambar_l,gambar_f FROM tbl_product_image WHERE albumid='$albumid' and userid='$_SESSION[userid]'");
		$query = sql($query);
		$gambar  = sql_fetch_data($query);
		$gambar_s = $gambar['gambar_s'];
		$gambar_m = $gambar['gambar_m'];
		$gambar_l = $gambar['gambar_l'];
		$gambar_f = $gambar['gambar_f'];
		$produkpostid = $gambar['produkpostid'];
		
		sql_free_result($query);

		unlink("$pathfile/produk/$produkpostid/$gambar_s");
		unlink("$pathfile/produk/$produkpostid/$gambar_m");
		unlink("$pathfile/produk/$produkpostid/$gambar_l");
		unlink("$pathfile/produk/$produkpostid/$gambar_f");

		$query = sql("DELETE FROM tbl_product_image WHERE albumid='$albumid'  and userid='$_SESSION[userid]' ");

		if ($query)
		{
			header("location: $fulldomain/user/produk/detail/$produkpostid/");
			exit();
		}

		break;

	case 'publish':
		$produkpostid = $var[5];
		$published = sql_get_var("SELECT published FROM tbl_product_post WHERE produkpostid='$produkpostid'  and userid='$_SESSION[userid]'");

		$published = ($published == 1) ? 0 : 1;
		$query = sql("UPDATE tbl_product_post SET published='$published' WHERE produkpostid='$produkpostid'  and userid='$_SESSION[userid]'");

		if ($query)
			header("Location: $fulldomain/user/produk/");

		break;

	case 'detail':

		//produk
		$sql = "SELECT a.*,b.namasec,c.namasub FROM tbl_product_post a LEFT JOIN tbl_product_sec b ON a.secid=b.secid LEFT JOIN tbl_product_sub c ON a.subid=c.subid WHERE a.produkpostid='$var[5]'";
		$sql = sql($sql);
		$produk = sql_fetch_data($sql);

		$produk['status'] = ($produk['status']==1) ? 'Bekas' : 'Baru';
		$produk['niaga'] = ($produk['niaga']==1) ? 'Ya' : 'Tidak';

		// gambar
		$sql = "SELECT albumid,gambar_s FROM tbl_product_image WHERE produkpostid='$produk[produkpostid]'";
		$sql = sql($sql);
		while ($row = sql_fetch_data($sql))
			$gambar[$row['albumid']] = array('albumid'=>$row['albumid'], 'gambar'=>"$fulldomain/gambar/produk/$produk[produkpostid]/$row[gambar_s]");
		sql_free_result($sql);
		$produk['gambar'] = $gambar;

		$tpl->assign("detail",$produk);
		break;

	case 'hapus':
		$produkpostid = $var[5];

		$query = sql("SELECT gambar,gambar1 FROM tbl_product_image WHERE produkpostid='$produkpostid' and userid='$_SESSION[userid]'");
		while ($gambar = sql_fetch_data($query))
		{
			foreach ($gambar as $key => $val)
				if ($key != 'produkpostid')unlink("$lokasimember/$_SESSION[userdirname]/$val");
			
		}
		sql_free_result($query);

		$query = sql("DELETE FROM tbl_product_imageWHERE produkpostid='$produkpostid'");

		$query = sql("DELETE FROM tbl_product_post WHERE produkpostid='$produkpostid'");

		if ($query)
			header("Location: $fulldomain/user/produk/");

		break;

	case 'terjual':
		$produkpostid = $var[5];

		$query = sql("UPDATE tbl_product_post SET published='2' WHERE produkpostid='$produkpostid'  and userid='$_SESSION[userid]'");

		if ($query)
			header("Location: $fulldomain/user/produk/");

		break;

	default:
		$judul_per_hlm = 20;
		$batas_paging = 5;
		$hlm = $var[4];
			
		$sql = "select count(*) as jml from tbl_product_post where userid='$_SESSION[userid]'";
		$hsl = sql($sql);
		$tot = sql_result($hsl, 0, jml);
		
		$tpl->assign("jml_post",$tot);
		
		$hlm_tot = ceil($tot / $judul_per_hlm);		
		if (empty($hlm)){
			$hlm = 1;
		}
		if ($hlm > $hlm_tot){
		$hlm = $hlm_tot;
		}
		$ord = ($hlm - 1) * $judul_per_hlm;
		if ($ord < 0 ) $ord=0;
		
		$perintah	= "select produkpostid,secid,title,alias,create_date,content,ringkas,harga,status,published from tbl_product_post where userid='$_SESSION[userid]' order by produkpostid desc limit $ord, $judul_per_hlm";
		$hasil		= sql($perintah);
		$datadetail	= array();
		$no = 0;
		while($data = sql_fetch_data($hasil))
		{
			$tanggal = $data['create_date'];
			$nama    = $data['nama'];
			$harga   = rupiah($data['harga']);
			$status  = $data['status'];
			$published  = ($data['published'] == 1) ? (($data['status'] == 1) ? '<span class="label label-success">Ditayangkan</span>':'<span class="label label-info">Menunggu Persetujuan</span>') : (($data['published'] == 2) ? '<span class="label label-warning">Terjual</span>':'<span class="label label-default">Draft</span>');
			$publish  = ($data['published'] == 1) ? 'Draft': (($data['published']==2)? 'Tayangkan Kembali':'Tayangkan');
			$id      = $data['produkpostid'];
			$ringkas = substr($data['ringkas'], 0,190);
			$alias   = $data['alias'];
			$tanggal = tanggalonly($tanggal);
			$gambar  = $data['gambar'];
			$gambar1 = $data['gambar1'];
			$secid   = $data['secid'];
			
			$secalias1 = sql_get_var("select alias from tbl_product_sec where secid='$secid'");
			$gambar = sql_get_var("SELECT gambar_s FROM tbl_product_image WHERE produkpostid='$id' LIMIT 1");
			
			if(!empty($gambar)) $gambar = "$domain/gambar/produk/$id/$gambar";
			 else $gambar = "$fulldomain/images/noimages.jpg";
			 
			$link = "$fulldomain/$kanal/produk/detail/$id";
			 
			$no++;
			$datadetail[$id] = array("id"=>$id,"no"=>$i,"nama"=>$nama,"ringkas"=>$ringkas,"harga"=>$harga,"status"=>$status,"published"=>$published,"published1"=>$data['published'],"status_tayang"=>$data['status_tayang'],"publish"=>$publish,"tanggal"=>$tanggal,"alias"=>$alias,"link"=>$link,"gambar"=>$gambar);
		}

		sql_free_result($hasil);
		$tpl->assign("datadetail",$datadetail);
		
		//Paging 
		$batas_page =5;
		
		$stringpage = array();
		$pageid =0;
		
		$Selanjutnya = "&rsaquo;";
		$Sebelumnya  = "&lsaquo;";
		$Akhir       = "&raquo;";
		$Awal        = "&laquo;";
		
		if ($hlm > 1){
			$prev = $hlm - 1;
			$stringpage[$pageid] = array("nama"=>"$Awal","link"=>"$domain/$kanal/$aksi/1");
			$pageid++;
			$stringpage[$pageid] = array("nama"=>"$Sebelumnya","link"=>"$domain/$kanal/$aksi/$prev");
		
		}
		else {
			$stringpage[$pageid] = array("nama"=>"$Awal","link"=>"");
			$pageid++;
			$stringpage[$pageid] = array("nama"=>"$Sebelumnya","link"=>"");
		}
		
		$hlm2 = $hlm - (ceil($batas_page/2));
		$hlm4= $hlm+(ceil($batas_page/2));
		
		if($hlm2 <= 0 ) $hlm3=1;
		   else $hlm3 = $hlm2;
		$pageid++;
		for ($ii=$hlm3; $ii <= $hlm_tot and $ii<=$hlm4; $ii++){
			if ($ii==$hlm){
				$stringpage[$pageid] = array("nama"=>"$ii","link"=>"");
			}else{
				$stringpage[$pageid] = array("nama"=>"$ii","link"=>"$domain/$kanal/$aksi/$ii");
			}
			$pageid++;
		}
		if ($hlm < $hlm_tot){
			$next = $hlm + 1;
			$stringpage[$pageid] = array("nama"=>"$Selanjutnya","link"=>"$domain/$kanal/$aksi/$next");
			$pageid++;
			$stringpage[$pageid] = array("nama"=>"$Akhir","link"=>"$domain/$kanal/$aksi/$hlm_tot");
		}
		else
		{
			$stringpage[$pageid] = array("nama"=>"$Selanjutnya","link"=>"");
			$pageid++;
			$stringpage[$pageid] = array("nama"=>"$Akhir","link"=>"");
		}
		$tpl->assign("stringpage",$stringpage);
		//Selesai Paging
		break;
}

?>