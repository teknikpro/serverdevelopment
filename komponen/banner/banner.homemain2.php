<?php  
$space="homemain2";
$limitbanner = 1;
$perintah ="SELECT id,gambar,space,jenis,tglakhir,ukuran,harga,tipe,impresi,budget,code FROM tbl_banner where space='$space' and published='1' order by rand() desc limit $limitbanner";
$hasil = sql($perintah);

$no=1;
		
if(sql_num_rows($hasil)>0)
{
	while($row =sql_fetch_data($hasil))
	{
	$jenis = $row['jenis'];
	$gambar=$row['gambar'];
	$adsid =$row['id'];
	$code =$row['code'];
	$akhir = $row['tglakhir'];
	$akhir = explode("-",$akhir);
	$tgl_akhir = $akhir[2];
	$bln_akhir = $akhir[1];
	$thn_akhir = $akhir[0];
	
	$ukuran = $row['ukuran'];
	$ukuran = explode("x",$ukuran);
	$width = $ukuran[0];
	$height = $ukuran[1];
	$target = base64_encode($adsid);
	
	$tanggal = date("d");
	$bulan = date("m");
	$tahun = date("Y");
	$batas ="$tanggal-$bulan-$tahun";
	$batas = mktime(0,0,0,$bulan,$tanggal,$tahun);
	$akhir = mktime(0,0,0,$bln_akhir,$tgl_akhir,$thn_akhir);
	

	$harga = $row['harga'];
	$tipe = $row['tipe'];
	$impresi = $row['impresi'];
	$budget = $row['budget'];
	
	
		
	if($tipe=="bybudget")
	{
		//itung yah
		$impresi1000 = $impresi/1000;
		$terpakai = $impresi1000*$harga;
		if($terpakai > $budget)
		{ 
			$adi = "update tbl_banner set published='0' where id='$adsid'";
			$hasils = sql($adi);	
		}
		
	}
	else 
	{
		if($akhir <= $batas)
		{ 
			$adi = "update tbl_banner set published='0' where id='$adsid'";
			$hasils = sql($adi);	
		}
	}
	$sql = "SELECT count(*) as jml FROM tbl_banner_stats WHERE id='$adsid' and tanggal='$tanggal' and bulan='$bulan' and tahun='$tahun'";
	$hsl = sql($sql);
	$tot = sql_result($hsl, 0, jml);
	if ($tot == "0")
	{
		$lihat="1";
		$perintahb = " insert into tbl_banner_stats(id,tanggal,bulan,tahun,view) values ('$adsid','$tanggal','$bulan','$tahun','$lihat')";
		$hasilb = sql($perintahb);
	}
	else
	{
		//update statistik harian
		$perintahc = "update tbl_banner_stats set view=view+1 WHERE id='$adsid' and tanggal='$tanggal' and bulan='$bulan' and tahun='$tahun'";
		$hasilc = sql($perintahc);
		
		//update statistik banner
		$perintahc = "update tbl_banner set impresi=impresi+1 WHERE id='$adsid'";
		$hasilc = sql($perintahc);
	
	}
	
	$databanner .= "<span class=\"padding5\">";			
	if($jenis == "swf")
	{	
	$databanner .= "
	<a href=\"$fulldomain/banner/lihat/$target\" target=\"_blank\">
	<object type=\"application/x-shockwave-flash\"  data=\"$domain/gambar/banner/$gambar\" width=\"$width\" height=\"$height\" >
	<param name=\"movie\" value=\"$domain/gambar/banner/$gambar\" />
	<param name=\"quality\" value=\"high\" />
	<param name=\"wmode\" value=\"transparent\" />
	<param name=\"menu\" value=\"false\" />
	<param name=\"AllowScriptAccess\" value=\"always\" />
	</object>
	<i style=\"display:block; height: $height"."px; width: $width"."px;  position: relative; z-index: 9; margin-top: -$height"."px;\"></i>
	</a>";									 
	}
	elseif($jenis=="code")
	{
		$databanner .= $code;	
	}
	else{
		$databanner .= "<a href=\"$fulldomain/banner/lihat/$target\" target=\"_blank\"  title=\"Banner\"><img src=\"/gambar/banner/$gambar\" border=\"0\" width=\"$width\" alt=\"Banner\" title=\"Banner\"  height=\"$height\" class=\"imgbanner\"/></a>";	
	}	
	$databanner .= "</span>";
	$no++;
}	
}	
sql_free_result($hasil);
$tpl->assign("banner_$space",$databanner);
unset($databanner);
?>