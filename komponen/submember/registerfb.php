<?php 
include($lokasiweb."/librari/facebook/facebook.php");

if(!isset($user_id) )
{
	header("location: /");
}
else
{

$userinfo = fbc_anon_api_client()->users_getInfo(array($user_id),fbc_userinfo_keys());
$userinfo = $userinfo[0];

$_SESSION['fbid'] = $user_id;
$tpl->assign("userinfo",$userinfo);

//dapatkan data tanggal
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

}
?>