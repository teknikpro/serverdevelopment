<?php

$idtiket = $var['4'];
$cektiket = sql_get_var("SELECT voucherid FROM tbl_world_voucher WHERE voucherid='$idtiket' ");
if($cektiket){
	$cekpublish = sql_get_var("SELECT published FROM tbl_world_voucher WHERE voucherid='$idtiket' ");
	
	if($cekpublish == 1){
		sql("UPDATE tbl_world_voucher SET published='0' WHERE voucherid='$cektiket' ");
		$pesanpublish = "data barhasil di unpublish";
	}else {
		sql("UPDATE tbl_world_voucher SET published='1' WHERE voucherid='$cektiket' ");
		$pesanpublish = "data barhasil dipublish";
	}
}else {
	if($idtiket == "tambahberhasil"){
		$pesan = "Data berhasil ditambah";
	}elseif($idtiket == "editberhasil"){
		$pesan = "Data berhasil diedit";
	}
}


$tpl->assign("namarubrik","Daftar Tiket");

	$id_merchant = sql_get_var("SELECT merchant FROM tbl_member WHERE userid='$_SESSION[userid]' ");
		
	$sql1 	= "select * from tbl_world_voucher where id='$id_merchant' order by create_date desc";
	$hsl1 	= sql($sql1);
	$daftartiket	= array();
	$no	= 1;
	while ($row1 = sql_fetch_data($hsl1))
	{
		$voucherid             	= $row1['voucherid'];
		$nama             		= $row1['nama'];
		$startdate             	= tanggalsingkat($row1['startdate']);
		$enddate             	= tanggalsingkat($row1['enddate']);
		$startusedate           = tanggalsingkat($row1['startusedate']);
		$endusedate            	= tanggalsingkat($row1['endusedate']);
		$published             	= $row1['published'];
		$harga             		= number_format($row1['harga']);
		$qty             		= $row1['qty'];

		if($published == 1){
			$published = "publish";
			$btn = "success";
		}else {
			$published = "draf";
			$btn = "info";
		}
		
		
		$daftartiket[$voucherid] = array(
			"voucherid"=>$voucherid,"nama"=>$nama,"startdate"=>$startdate,"enddate"=>$enddate,"startusedate"=>$startusedate,"endusedate"=>$endusedate,"published"=>$published,"harga"=>$harga,"qty"=>$qty,"no"=>$no,"btn"=>$btn
		);
		$no++;
	}

	sql_free_result($hsl1);
	$tpl->assign("daftartiket",$daftartiket);
	$tpl->assign("pesanpublish",$pesanpublish);
	$tpl->assign("id_merchant",$id_merchant);
	$tpl->assign("pesan",$pesan);

?>