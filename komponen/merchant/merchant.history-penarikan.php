<?php


$tpl->assign("namarubrik","Daftar Tiket");

	$id_merchant = sql_get_var("SELECT merchant FROM tbl_member WHERE userid='$_SESSION[userid]' ");
		
	$sql1 	= "select * from tbl_transaksi_tiket_penarikan where userid='$id_merchant' order by tanggal desc";
	$hsl1 	= sql($sql1);
	$history	= array();
	$no	= 1;
	while ($row1 = sql_fetch_data($hsl1))
	{
		$id_penarikan             	= $row1['id_penarikan'];
		$tanggal             	= tanggal($row1['tanggal']);
		$jumlah             		= number_format($row1['jumlah']);
		$status             		= $row1['status'];
		$alasanditolak			= $row1['alasanditolak'];

		if($status == 0){
			$status = "Diproses";
			$btn = "primary";
			$donadate = "Dalam Pengecekan";
		}else if($status == 1){
			$status = "Berhasil";
			$btn = "success";
			$donadate				= tanggal($row1['donedate']);
		}else {
			$status = "Gagal";
			$btn = "danger";
			$donadate				= tanggal($row1['donedate']);
		}
		
		
		$history[$id_penarikan] = array(
			"id_penarikan"=>$id_penarikan,"tanggal"=>$tanggal,"jumlah"=>$jumlah,"status"=>$status,"no"=>$no,"btn"=>$btn,"donedate"=>$donadate,"alasanditolak"=>$alasanditolak
		);
		$no++;
	}

	sql_free_result($hsl1);

	$pesan = $var[4];

	if($pesan == "berhasil-proses"){
		$alert = "Penarikan sedang diproses, silahkan menunggu maksimal 2 hari kerja";
	}



	$tpl->assign("history",$history);
	$tpl->assign("pesan",$alert);

?>