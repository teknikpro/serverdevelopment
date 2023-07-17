<?php
$step = $var[5];
$secid = $var[4];
$bantuan = $var[6];

$sql = sql("delete from tbl_quiz_peserta where secid='$secid' and pesertaid='$_SESSION[userid]'");

header("location: $fulldomain/quiz");
exit();
?>