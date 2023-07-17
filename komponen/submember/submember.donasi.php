<?php
$tpl->assign("namarubrik","Donasi Mainan Saya");
		
	$sql1 	= "select nama,id,ringkas,file from tbl_donasi where userid='$_SESSION[userid]' order by id desc";
	$hsl1 	= sql($sql1);
	$jmldonasi = sql_num_rows($hsl1);
	$listdonasi	= array();
	$no	= 1;
	while ($row1 = sql_fetch_data($hsl1))
	{
		$nama             = $row1['nama'];
		$ids               = $row1['id'];
		$file                 = $row1['file'];
		$ringkas                 = $row1['ringkas'];
	
		if(!empty($file))
		{
			$linkfile = "$fulldomain/gambar/donasimain/$file";
		}
		else
		{
			$linkfile = "";
		}
		
		
		$listdonasi[$ids] = array("no"=>$no,"id"=>$ids,"nama"=>$nama,"ringkas"=>$ringkas,"file"=>$file,"linkfile"=>$linkfile);
		$no++;
	}
	sql_free_result($hsl1);
	$tpl->assign("listdonasi",$listdonasi);
	$tpl->assign("jmldonasi",$jmldonasi);
?>