<?php
$sql = "select count(*) as jml from tbl_chat_message where to_userid='$_SESSION[userid]' and isread='0'";
$hsl = sql($sql);
$tot = sql_result($hsl, 0, 'jml');

echo $tot;
exit();
?>