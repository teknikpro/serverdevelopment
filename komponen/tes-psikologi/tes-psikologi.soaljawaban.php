<?php

$idsoaljawaban = $_POST["idsoaljawaban"];
$soaljawaban = $_POST["soaljawaban"];

$query = "insert into tbl_tes_psikologi_soaljawaban (jawaban,soal,userid)
		 values ('$idsoaljawaban','$soaljawaban','$_SESSION[tes_userid]')";
$hasil = sql($query);

