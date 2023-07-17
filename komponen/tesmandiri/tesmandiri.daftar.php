<?php 

if($_SESSION['fbid'])
{
	$fbid = $_SESSION['fbid'];
	$daftaremail = $_SESSION['fbemail'];
	$daftarname = $_SESSION['fbname'];
	$daftaravatar = $_SESSION['fbavatar'];
	
	$tpl->assign("fbid",$fbid);
	$tpl->assign("daftaremail",$daftaremail);
	$tpl->assign("daftarname",$daftarname);
	$tpl->assign("daftaravatar",$daftaravatar);
};

if($_SESSION['twid'])
{
	$twid = $_SESSION['twid'];
	$daftaremail = $_SESSION['fbemail'];
	$daftarname = $_SESSION['twname'];
	$daftaravatar = $_SESSION['twpicture'];
	$daftarusername = strtolower($_SESSION['twuname']);
	
	$tpl->assign("twid",$twid);
	$tpl->assign("daftaremail",$daftaremail);
	$tpl->assign("daftarname",$daftarname);
	$tpl->assign("daftaravatar",$daftaravatar);
	$tpl->assign("daftarusername",$daftarusername);
};

if($_SESSION['me'])
{
	$gdata = $_SESSION['me'];
	
	$gpid = $gdata['id'];
	$daftaremail = $gdata['emails'][0]['value'];
	$daftarname = $gdata['displayName'];
	$daftaravatar = $gdata['image']['url'];
	$daftarusername = $gdata['twuname'];
	
	$tpl->assign("gpid",$gpid);
	$tpl->assign("daftaremail",$daftaremail);
	$tpl->assign("daftarname",$daftarname);
	$tpl->assign("daftaravatar",$daftaravatar);
	$tpl->assign("daftarusername",$daftarusername);
};

$mysql = "select id,lengkap,nama,create_date,alias,gambar,gambar1 from tbl_static where alias='termofuse'  limit 1";
$hasil = sql( $mysql);

$data =  sql_fetch_data($hasil);	
$tanggal = $data['create_date'];
$nama = $data['nama'];
$id = $data['id'];
$lengkap = $data['lengkap'];
$alias = $data['alias'];
$tanggal = tanggal($tanggal);
$gambar = $data['gambar'];
$gambar1 = $data['gambar1'];
$urlradio = $data['urlradio'];

if(!empty($gambar1)) $gambar1 = "$fulldomain/gambar/$kanal/$gambar1";
 else $gambar1 = "";
 
sql_free_result($hasil);
$tpl->assign("detailid",$id);
$tpl->assign("detailnama",$nama);
$tpl->assign("detailringkas",$ringkas);
$tpl->assign("detaillengkap",$lengkap);
$tpl->assign("detailgambar",$gambar1);
$tpl->assign("detailtanggal",$tanggal);
$tpl->assign("rubrik","$rubrik");

//propinsi
$datapropinsi = array();
$ppropinsi = "select propid,namapropinsi from tbl_propinsi order by namapropinsi asc";
$hpropinsi = sql($ppropinsi);
while($dpropinsi=sql_fetch_data($hpropinsi))
{
	$datapropinsi[$dpropinsi['propid']] = array("id"=>$dpropinsi['propid'],"namapropinsi"=>$dpropinsi['namapropinsi']);
}
sql_free_result($hpropinsi);
$tpl->assign("datapropinsi",$datapropinsi);

//kota
$datakota = array();
$pkota = "select kotaid,namakota,tipe from tbl_kota where propid='$propid' order by namakota asc";
$hkota = sql($pkota);
while($dkota=sql_fetch_data($hkota))
{
	$kota = $dkota['namakota'];
	$tipe = $dkota['tipe'];
	$kotaids = $dkota['kotaid'];
	
	if($tipe=="Kota") $kota = "$kota (Kota)";
	else $kota = $kota;
	
	$datakota[] = array("kotaid"=>$kotaids,"nama"=>$kota);
}
sql_free_result($hkota);
$tpl->assign("datakota",$datakota);

//propinsi
$datakec = array();
$pkec = "select kecid,namakecamatan from tbl_kecamatan where kotaid='$kotaid' and propid='$propid' order by kecid asc";
$hkec = sql($pkec);
while($dkec=sql_fetch_data($hkec))
{
	$kecid2 = $dkec['kecid'];
	$namakec = $dkec['namakecamatan'];
	$datakec[] = array("kecid"=>$kecid2,"nama"=>$namakec);
}
sql_free_result($hkec);
$tpl->assign("datakecamatan",$datakec);



/*//dapatkan data tanggal
$dateLoop = array();
$tempI = 1;
while ($tempI < 32) {
if ($tempI < 10){
array_push($dateLoop,"0".$tempI);
		 $temp2 = "0".$tempI;
	 }else{
		 array_push($dateLoop,$tempI);
		 $temp2 = $tempI;
	}
	if($temp2 == $DOB[2]) $dateSelected = $tempI;
	$tempI++;
}

$monthLoop = array();
$tempI = 1;
while ($tempI < 13) {
	 if ($tempI < 10){
		 array_push($monthLoop,"0".$tempI);
		  $temp2 = "0".$tempI;
	 }else{
		 array_push($monthLoop,$tempI);
		 $temp2 = $tempI;
	}
	if($temp2 == $DOB[1]) $monthSelected = $tempI;
	$tempI++;

}

$yearLoop = array();
$tempI = date("Y")-80;

while ($tempI < date("Y") - 10) {
	 array_push($yearLoop,$tempI);
	if($tempI == $DOB[0]) $yearSelected = $tempI;
	$tempI++;

}	
$tpl -> assign( 'yearLoop', $yearLoop );
$tpl -> assign( 'yearSelected' , $yearSelected);
$tpl -> assign( 'monthLoop', $monthLoop );
$tpl -> assign( 'monthSelected' , $monthSelected);
$tpl -> assign( 'dateLoop', $dateLoop );
$tpl -> assign( 'dateSelected' , $dateSelected);

//Negara
$datanegara = array();
$pnegara = "select id,negara from tbl_negara order by negara asc";
$hnegara = mysql_query($pnegara);
while($dnegara=mysql_fetch_object($hnegara))
{
	$datanegara[$dnegara->id] = array("id"=>$dnegara->id,"namanegara"=>$dnegara->negara);
}
mysql_free_result($hnegara);
$tpl->assign("datanegara",$datanegara);

//propinsi
$datapropinsi = array();
$ppropinsi = "select id,namapropinsi from tbl_propinsi order by namapropinsi asc";
$hpropinsi = mysql_query($ppropinsi);
while($dpropinsi=mysql_fetch_object($hpropinsi))
{
	$datapropinsi[$dpropinsi->id] = array("id"=>$dpropinsi->id,"namapropinsi"=>$dpropinsi->namapropinsi);
}
mysql_free_result($hpropinsi);
$tpl->assign("datapropinsi",$datapropinsi);

//Negara
$dataangkatan = array();
$pangkatan = "select tahun from tbl_angkatan order by tahun asc";
$hangkatan = mysql_query($pangkatan);
while($dangkatan=mysql_fetch_object($hangkatan))
{
	$dataangkatan[$dangkatan->tahun] = array("tahun"=>$dangkatan->tahun);
}
mysql_free_result($hangkatan);
$tpl->assign("dataangkatan",$dataangkatan);
*/
?>