<?php 
$komentar = array();

$sql = "select nama,email,komid,id from tbl_berita_komentar order by komid desc limit 2";
$hsl = sql( $sql);
while ($row =  sql_fetch_data($hsl))
{
	$nama = $row['nama;
	$id2 = $row['id;
	$komid = $row['komid;
			
	$perintah = "select nama,id from tbl_berita where id='$id2' limit 1";
	$hasil = sql($perintah);
	$data =  sql_fetch_data($hasil);
	$namatulisan = $data['nama;
	$alias = getAlias($namatulisan);
	$linktulisan = "$fulldomain/berita/read/global/$id2/$alias#komentar";
	sql_free_result($hasil);
	
	$namatulisan = substr($namatulisan,0,52)."...";
			
	$lowercase = strtolower($row['email);
	$md5 = md5( $lowercase );
			
	$avatar = "http://www.gravatar.com/avatar/";
	$avatar .= $md5;
	$avatar .= "?s=28&amp;d=identicon&amp;r=X";
	
	$komentar[$komid] = array("komid"=>$komid,"nama"=>$nama,"namatulisan"=>$namatulisan,"url"=>$linktulisan,"avatar"=>$avatar);	
			
}
$sql = "select nama,email,komid,id from tbl_informasi_komentar order by komid desc limit 2";
$hsl = sql( $sql);
while ($row =  sql_fetch_data($hsl))
{
	$nama = $row['nama;
	$id2 = $row['id;
	$komid = $row['komid;
			
	$perintah = "select nama,id from tbl_informasi where id='$id2' limit 1";
	$hasil = sql($perintah);
	$data =  sql_fetch_data($hasil);
	$namatulisan = $data['nama;
	$alias = getAlias($namatulisan);
	$linktulisan = "$fulldomain/aneka/read/global/$id2/$alias#komentar";
	sql_free_result($hasil);
	
	$namatulisan = substr($namatulisan,0,52)."...";
			
	$lowercase = strtolower($row['email);
	$md5 = md5( $lowercase );
			
	$avatar = "http://www.gravatar.com/avatar/";
	$avatar .= $md5;
	$avatar .= "?s=28&amp;d=identicon&amp;r=X";
	
	$komentar[$komid+10000] = array("komid"=>$komid,"nama"=>$nama,"namatulisan"=>$namatulisan,"url"=>$linktulisan,"avatar"=>$avatar);	
			
}

$sql = "select nama,email,komid,id from tbl_aneka_komentar order by komid desc limit 2";
$hsl = sql( $sql);
while ($row =  sql_fetch_data($hsl))
{
	$nama = $row['nama;
	$id2 = $row['id;
	$komid = $row['komid;
			
	$perintah = "select nama,id from tbl_aneka where id='$id2' limit 1";
	$hasil = sql($perintah);
	$data =  sql_fetch_data($hasil);
	$namatulisan = $data['nama;
	$alias = getAlias($namatulisan);
	$linktulisan = "$fulldomain/aneka/read/$id2/$alias#komentar";
	sql_free_result($hasil);
	
	$namatulisan = substr($namatulisan,0,52)."...";
			
	$lowercase = strtolower($row['email);
	$md5 = md5( $lowercase );
			
	$avatar = "http://www.gravatar.com/avatar/";
	$avatar .= $md5;
	$avatar .= "?s=28&amp;d=identicon&amp;r=X";
	
	$komentar[$komid+100000] = array("komid"=>$komid,"nama"=>$nama,"namatulisan"=>$namatulisan,"url"=>$linktulisan,"avatar"=>$avatar);	
			
}
$sql = "select nama,email,komid,id from tbl_wisata_komentar order by komid desc limit 2";
$hsl = sql( $sql);
while ($row =  sql_fetch_data($hsl))
{
	$nama = $row['nama;
	$id2 = $row['id;
	$komid = $row['komid;
			
	$perintah = "select nama,id from tbl_wisata where id='$id2' limit 1";
	$hasil = sql($perintah);
	$data =  sql_fetch_data($hasil);
	$namatulisan = $data['nama;
	$alias = getAlias($namatulisan);
	$linktulisan = "$fulldomain/wisata/read/$id2/$alias#komentar";
	sql_free_result($hasil);
	
	$namatulisan = substr($namatulisan,0,52)."...";
			
	$lowercase = strtolower($row['email);
	$md5 = md5( $lowercase );
			
	$avatar = "http://www.gravatar.com/avatar/";
	$avatar .= $md5;
	$avatar .= "?s=28&amp;d=identicon&amp;r=X";
	
	$komentar[$komid+1000000] = array("komid"=>$komid,"nama"=>$nama,"namatulisan"=>$namatulisan,"url"=>$linktulisan,"avatar"=>$avatar);	
			
}
$tpl->assign("komentar",$komentar);
sql_free_result($hsl);

?>
