<?php
$mysql = "select produkpostid,ringkas,title,create_date,alias from tbl_product_post where published='1' order by produkpostid desc";
$hasil = sql($mysql);

$dataprodukpilihan = array();
$a =1;
while ($data = sql_fetch_data($hasil)) {	
		$tanggal = $data['create_date'];
		$nama    = $data['title'];
		$ids     = $data['produkpostid'];
		$ringkas = $data['ringkas'];
		$alias   = $data['alias'];
		$tanggal = tanggal($tanggal);
		$secid   = $data['secid'];
		
		
		// Gambar
		$sql3		= "select albumid,nama,gambar_m,gambar_s,gambar_l,gambar_f from tbl_product_image where produkpostid='$produkpostid' order by albumid asc";
		$query3		= sql($sql3);
		$list_image	= array();
		$ii			= 1;
		$albumarr 	= array();
		while($row3		= sql_fetch_data($query3))
		{
			$albumid	= $row3['albumid'];
			$nama_image	= $row3['nama'];
			$gambar_s	= $row3['gambar_s'];
			$gambar_m	= $row3['gambar_m'];
			$gambar_l	= $row3['gambar_l'];
			$gambar_f	= $row3['gambar_f'];
			
			if(!empty($gambar_m))
				$image_m	= "$fulldomain/gambar/produk/$produkpostid/$gambar_m";
			else
				$image_m	= $fulldomain.$lokasiwebtemplate."images/no_photo.gif";
				
			if(!empty($gambar_s))
				$image_s	= "$fulldomain/gambar/produk/$produkpostid/$gambar_s";
			else
				$image_s	= $fulldomain.$lokasiwebtemplate."images/no_photo.gif";
			
			if(!empty($gambar_l))
				$image_l	= "$fulldomain/gambar/produk/$produkpostid/$gambar_f";
			else
				$image_l	= $fulldomain.$lokasiwebtemplate."images/no_photo.gif";
				
			if(!empty($gambar_f))
				$image_f	= "$fulldomain/gambar/produk/$produkpostid/$gambar_f";
			else
				$image_f	= $fulldomain.$lokasiwebtemplate."images/no_photo.gif";
				
			if(($s=="m" or $s=="f") and ($var[7]))
			{
				if($albumid==$var[7])
				{
					$nama_image		= $nama_image;
					$firstImageId	= $albumid;
					$tpl->assign("detailgambar",$image_m);
				}
			}
			else if($ii == 1)
			{
				$image_besar	= $image_f;
				$image_mm		= $image_m;
				$image_ss 		= $image_s;
				$image_ll		= $image_l;
				$nama_image		= $nama_image;
				
				$firstImageId	= $albumid;
			}
			
			$list_image[$albumid]	= array("index"=>$ii,"albumid"=>$albumid,"nama_image"=>$nama_image,"image_m"=>$image_m,"image_s"=>$image_s,"image_l"=>$image_l,"image_f"=>$image_f);
			$albumarr[$ii] 			= $albumid;
			$ii++;
		}
		sql_free_result($query3);
		$tpl->assign("list_image",$list_image);

		$link = "$fulldomain/produk/read/$ids/$alias";
			
		$dataprodukpilihan[$ids] = array("id"=>$id,"no"=>$a,"nama"=>$nama,"ringkas"=>$ringkas,"namasec"=>$namasec,"tanggal"=>$tanggal,"alias"=>$alias,"link"=>$link,"gambar"=>$gambar);
		$a++;	
}
$a = 0;
sql_free_result($hasil);
$tpl->assign("dataprodukpilihan",$dataprodukpilihan);

?>