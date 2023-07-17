<?php
$subaksi=$var[4];
$tpl->assign("subaksi",$subaksi);
switch ($subaksi)
{
	case 'read':

		//produk
		$perintah = "select id,nama,oleh,ringkas,lengkap,gambar1,gambar,create_date,alias,views from tbl_untukmember where published='1' and id='$id'";
		$hasil = sql($perintah);
		$data =  sql_fetch_data($hasil);
		
		$idcontent = $data['id'];
		$nama=$data['nama'];
		$oleh = $data['oleh'];
		$lengkap= $data['lengkap'];
		$tanggal = tanggal($data['create_date']);
		$gambar = $data['gambar1'];
		$gambarshare = $data['gambar'];
		$ringkas = $data['ringkas'];
		$alias = $data['alias'];
		$views = $data['views'];
		$tag = $data['tags'];
		$penulisid = $data['userid'];


		$tpl->assign("detailid",$idcontent);
		$tpl->assign("detailnama",$nama);
		$tpl->assign("detaillengkap",$lengkap);
		$tpl->assign("detailringkas",$ringkas);
		$tpl->assign("detailcreator",$oleh);
		$tpl->assign("detailtanggal",$tanggal);
		$tpl->assign("detaildate",$data['create_date']);
		
		if(!empty($gambar)) $tpl->assign("detailgambar","$fulldomain/gambar/untukmember/$gambar");
		if(!empty($gambarshare)) $tpl->assign("detailgambarshare","$fulldomain/gambar/untukmember/$gambarshare");

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