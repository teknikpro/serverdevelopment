<?php 
$sql = "select id,gambar,secid,nama,ringkas from tbl_galeri where published='1' and gambar!='' group by secid order by secid desc limit 3";
$hsl = sql($sql);
$a=1;
while ($row = sql_fetch_data($hsl))
{
	$nama = $row['nama'];
	$alias = getalias($nama);
	$id2 = $row['id'];
	$gambar = $row['gambar'];
	$secid = $row['secid'];
	$ringkas = $row['ringkas'];
	
	$url = "$fulldomain/galeri/read/$secid/$id2/$alias";
	
	$gambar =  "$domain/gambar/galeri/$gambar";
	
	$datagaleridepan[$id2] = array("a"=>$a,"nama"=>$nama,"ringkas"=>$ringkas,"gambar"=>$gambar,"url"=>$url);
	$a++;
}
sql_free_result($hsl);
		
$tpl->assign("datadepangaleri",$datagaleridepan);
?>