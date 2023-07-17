<?php
if (!$_SESSION['tes_hasil']) {
	if ($_SESSION['tes_userid']) {
		header("location: $fulldomain/tes-psikologi/tes");
		exit();
	}
	header("location: $fulldomain/tes-psikologi");
	exit();
}

$jmlsoalsikap = sql_get_var("select count(*) as jml from tbl_sekolah_soal");

$nilaitipea = sql_get_var("select sum(nilai) from tbl_sekolah_tes where userid='$_SESSION[tes_hasil]' and tipe='A' and nilai='1' ");
if(!$nilaitipea){
	$nilaitipea = 0;
}
$nilaitipeb = sql_get_var("select sum(nilai) from tbl_sekolah_tes where userid='$_SESSION[tes_hasil]' and tipe='B' and nilai='1' ");
if(!$nilaitipeb){
	$nilaitipeb = 0;
}
$nilaitipec = sql_get_var("select sum(nilai) from tbl_sekolah_tes where userid='$_SESSION[tes_hasil]' and tipe='C' and nilai='1' ");
if(!$nilaitipec){
	$nilaitipec = 0;
}

$identitas = sql_get_var_row("select nama_lengkap,jenis_kelamin,tempat_lahir,tanggal_lahir,lama_stk,sekolah,alamat,nama_guru,tanggal_pengisian from tbl_sekolah_user where id_tes_sekolah='$_SESSION[tes_hasil]' ");

$mencolok = sql_get_var("SELECT mencolok FROM tbl_sekolah_mencolok WHERE userid='$_SESSION[tes_hasil]' ");
$pertanyaan = sql_get_var("SELECT pertanyaan FROM tbl_sekolah_pertanyaan WHERE userid='$_SESSION[tes_hasil]' ");

$tpl->assign("nilaia", $nilaitipea);
$tpl->assign("nilaib", $nilaitipeb);
$tpl->assign("nilaic", $nilaitipec);
$tpl->assign("identitas", $identitas);
$tpl->assign("mencolok", $mencolok);
$tpl->assign("pertanyaan", $pertanyaan);

