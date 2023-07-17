<?php
$tpl->assign("userfullname",$_SESSION['userfullname']);

$jawaban = $var[4];
$jawab = base64_decode($jawaban);
$jawab = explode("++",$jawab);

$id = $jawab[0];
$jawabanid = $jawab[1];
$step = $jawab[2];
$secid = $jawab[3];

$kunci = sql_get_var("select kunci from tbl_quiz_soal_jawaban where id='$id' and jawabanid='$jawabanid'");

if($kunci=="0")
{
	$_SESSION['end'] = 1;
	$_SESSION['step'] = $step;
	$_SESSION['jawabanid'] = $jawabanid;
	
	$sql = "update tbl_quiz_peserta set jawaban='$jawabanid' where pesertaid='$_SESSION[pesertaid]' and level='$step' and soalid='$id'";
	sql($sql);
	
	header("location: $fulldomain/quiz/start/$secid/$step/end");
	exit();
	
}
else
{
	$_SESSION['nextstep'] = $step+1;
	$_SESSION['jawabanid'] = $jawabanid;
	
	$sql = "update tbl_quiz_peserta set jawaban='$jawabanid',benar='1' where pesertaid='$_SESSION[pesertaid]' and level='$step' and soalid='$id'";
	sql($sql);
	
	header("location: $fulldomain/quiz/start/$secid/$step/success");
	exit();
}
?>