<?
	$hlm 	= $var[5];
	$judul_per_hlm 	= 20;
	$batas_paging 	= 5;

	$tpl->assign("uid",$uid);
	
	$sql 	= "select count(*) as jml from tbl_viewer where puserid='$uid'";
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

	//daftar teman
	$perintah	= "select userid,username,userfullname from tbl_viewer where puserid='$uid' group by userid order by id desc limit $ord, $judul_per_hlm";
	$hasil		= sql($perintah);
	$jumviewer	= sql_num_rows($hasil);
	$datateman	= array();
	$id	= 1;
	while($data	= sql_fetch_data($hasil))
	{
		$approveId	= $data['userid'];
		
		$approve	= $data['username'];
		$namateman	= $data['userfullname'];
		$gambar		= "$fulldomain/uploads/avatar/$approveId.jpg";		
			
		if(preg_match("/\</i",$namateman) and !preg_match("/>/i",$namateman)) $namateman = $namateman.">";
			$namateman = bersih($namateman);
			
		$link		= "$fulldomain/$approve";

		$dataviewer[$id]=array("id"=>$id,"namateman"=>$namateman,"link"=>$link,"avatar"=>$gambar,"approve"=>$approve);
		$id++;
	}
	sql_free_result($hasil);
	$tpl->assign("dataviewer",$dataviewer);
	$tpl->assign("jumviewer",$tot);
	
	//Paging 
	$batas_page =5;
	
	$stringpage = array();
	$pageid =0;
	
	if ($kanal == "profile")
		$kanal1	= "$username/viewer";
	
	if ($hlm > 1){
		$prev = $hlm - 1;
		$stringpage[$pageid] = array("nama"=>"&laquo;","link"=>"$domainfull/$kanal1/1");
		$pageid++;
		$stringpage[$pageid] = array("nama"=>"&lsaquo;","link"=>"$domainfull/$kanal1/$prev");

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
			$stringpage[$pageid] = array("nama"=>"$ii","link"=>"$domainfull/$kanal1/$ii");
		}
		$pageid++;
	}
	if ($hlm < $hlm_tot){
		$next = $hlm + 1;
		$stringpage[$pageid] = array("nama"=>"&rsaquo;","link"=>"$domainfull/$kanal1/$next");
		$pageid++;
		$stringpage[$pageid] = array("nama"=>"&raquo;","link"=>"$domainfull/$kanal1/$hlm_tot");
	}
	else
	{
		$stringpage[$pageid] = array("nama"=>"&rsaquo;","link"=>"");
		$pageid++;
		$stringpage[$pageid] = array("nama"=>"&raquo;","link"=>"");
	}
	$tpl->assign("stringpage",$stringpage);
	//Selesai Paging
?>