<?php
$date = date("Y-m-d H:i:s");
$date = tanggal($date);

$code = $var['3'];

$username = sql_get_var("SELECT username FROM tbl_member_konfirmasi WHERE code='$code' ");
$userid = sql_get_var("SELECT userid FROM tbl_member_konfirmasi WHERE code='$code' ");

$sql = sql("select id,nama from tbl_attraction");
$array = array();
while ($dt = sql_fetch_data($sql)) {
	$id = $dt['id'];
	$nama = $dt['nama'];

	$array[] = array("id" => $id, "nama" => $nama);
}


$tpl->assign("tanggal", $date);
$tpl->assign("atraksi", $array);
$tpl->assign("code", $code);
$tpl->assign("username", $username);
$tpl->assign("userid", $userid);


$tpl->display("aktivasi.html");


if (isset($_POST['submit'])) {
	$username 	= $_POST['username'];
	$userid 	= $_POST['userid'];

	if (empty($userid)) {
		$error = "Akun anda salah, silahkan daftar kembali di aplikai dfunstation <br />$salah $error_back ";
	} else {
		$query = ("UPDATE tbl_member SET useractivestatus='1' WHERE userid='$userid' ");
		$hasil = sql($query);
		header("Location: https://www.dfunstation.com/aktivasi_berhasil");
		// header("Location: https://www.dfunstation.com/aktivasi_berhasil");
	}
}
