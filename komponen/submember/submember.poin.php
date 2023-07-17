<?php
$tpl->assign("namarubrik","Poin Member");
		
$sql = "select id, ordernumber,redeemnumber,point, status, create_date,balancetotal,tipe,activity from tbl_member_point_history where userid='$_SESSION[userid]' order by id desc limit 50";
$qry = sql($sql);
$listpoin = array();
$balance = 0;
$no = 1;
while($row = sql_fetch_data($qry))
{
	$id				= $row['id'];
	$ordernumber 	= $row['ordernumber'];
	$point		 	= $row['point'];
	$tanggal	 	= tanggaltok($row['create_date']);
	$status		 	= $row['status'];
	$tipe		 	= $row['tipe'];
	$balance		 	= $row['balancetotal'];
	$activity = $row['activity'];
	
	$aktifitas = sql_get_var("select nama from tbl_poin_config where alias='$activity'");

	$pointt	 = number_format($point,0,".",".");
	$balancet	 = number_format($balance,0,".",".");

	if($status == '1')
		$ketstatus = "Aktif";
	elseif($status == '0')
		$ketstatus = "Expire";

	if($tipe == 'CR')
	{
		$tipe = "<span class=\"label label-success\">Earn</span>";
		$ketstatus = "Earning point from Activity $aktifitas";
		
	}
	elseif($tipe == 'DB')
	{
		if($activity!="delete-status")
		{
			$redeemnumber 	= $row['redeemnumber'];
			$tipe = "<span class=\"label label-danger\">Redeem</span>";
			$ketstatus = "Redeem point for Transaction";
		}
		else
		{
			$redeemnumber 	= $row['redeemnumber'];
			$tipe = "<span class=\"label label-warning\">Minus</span>";
			$ketstatus = "Minus poin because from activity $aktifitas";
		}
	}

	$listpoin[$id]	= array("id"=>$id,"ordernumber"=>$ordernumber,"no"=>$no,"point"=>$pointt,"aktifitas"=>$aktifitas,"tipe"=>$tipe,"balance"=>$balancet,"tanggal"=>$tanggal,"ordernumber"=>$ordernumber,"ketstatus"=>$ketstatus);
	$no++;
}

$tpl->assign("listpoin",$listpoin);

	?>