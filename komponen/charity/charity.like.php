<?php

$postid = $_POST["postid"];
$userid = $_POST["userid"];
$tanggal = date('d-m-y');

mysql_query("insert into tbl_like values('','$post_id','$userid','$tanggal')");
