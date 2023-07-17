 <?php 
		$hlm =$katid;
		$judul_per_hlm = 10;
		$batas_paging = 5;
		$sql = "select count(*) as jml from tbl_$kanal"."_cat";
		$hsl = mysql_query($sql);
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
		$i = 1;
		$mysql = "select a.gambar,b.* from tbl_$kanal a, tbl_$kanal"."_cat b where a.published='1' and a.catId=b.catId group by a.catId order by b.kat_id desc limit $ord,$judul_per_hlm";
		$hasil = mysql_query($mysql);
		
		$datagaleri = array();
		while ($data = mysql_fetch_object($hasil)) {	
		$tanggal = $data->tanggal;
		$id = $data->isi_id;
		$nama = $data->nama;
		$catId = $data->catId;
		$ringkas = $data->keterangan;
		$gambar = $data->gambar;
		$tanggal = $data->tanggal;
		$alias = $data->alias;
		
		$datagaleri[$catId] = array("id"=>$id,"nama"=>$nama,"ringkas"=>$ringkas,"gambar"=>"$domain/gambar/$gambar","url"=>"$domain$cleanurl/$kanal/read/$catId/1/$alias");
		
		}
		$tpl->assign("datagaleri",$datagaleri);
		//Paging 
		$batas_page =5;
		
		$stringpage = array();
		$pageid =0;
		
		if ($hlm > 1){
			$prev = $hlm - 1;
			$stringpage[$pageid] = array("nama"=>"First","link"=>"$domainfull/$kanal/$aksi/1");
			$pageid++;
			$stringpage[$pageid] = array("nama"=>"Previous","link"=>"$domainfull/$kanal/$aksi/$prev");

		}
		else {
			$stringpage[$pageid] = array("nama"=>"First","link"=>"");
			$pageid++;
			$stringpage[$pageid] = array("nama"=>"Previous","link"=>"");
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
				$stringpage[$pageid] = array("nama"=>"$ii","link"=>"$domainfull/$kanal/$aksi/$ii");
			}
			$pageid++;
		}
		if ($hlm < $hlm_tot){
			$next = $hlm + 1;
			$stringpage[$pageid] = array("nama"=>"Next","link"=>"$domainfull/$kanal/$aksi/$next");
			$pageid++;
			$stringpage[$pageid] = array("nama"=>"Last","link"=>"$domainfull/$kanal/$aksi/$hlm_tot");
		}
		else
		{
			$stringpage[$pageid] = array("nama"=>"Next","link"=>"");
			$pageid++;
			$stringpage[$pageid] = array("nama"=>"Last","link"=>"");
		}
		$tpl->assign("stringpage",$stringpage);
		//Selesai Paging
		
		?>
