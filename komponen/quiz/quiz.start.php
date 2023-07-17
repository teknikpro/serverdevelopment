<?php
$step = $var[5];
$secid = $var[4];
$bantuan = $var[6];

$tpl->assign("userfullname",$_SESSION['userfullname']);
$tpl->assign("secid",$secid);

$cek = sql_get_var_row("select benar from tbl_quiz_peserta where pesertaid='$_SESSION[userid]' and secid='$secid' and level='10'");
$benar = $cek['benar'];

if($benar)
{
	header("location: $fulldomain/quiz/pilihtopik/done");
	exit();
}


$sql = "select level,nominal from tbl_quiz_level order by nominal desc";
$hsl = sql($sql);
while($data = sql_fetch_data($hsl))
{
	$level = $data['level'];
	$nominal=  $data['nominal'];
	
	$levels[] = array("level"=>$level,"nominal"=>$nominal);
}
$tpl->assign("levels",$levels);

$jmllevel = count($levels);

$tpl->assign("step",$step);

$uri = "$fulldomain/quiz/start/$secid/$step";
$tpl->assign("uri",$uri);


if(preg_match("/bantuan/i",$bantuan) && (!empty($_SESSION['bantuan1']) || !empty($_SESSION['bantuan2']) || !empty($_SESSION['bantuan3'])) )
{
	//$tpl->assign("reload","1");
}

$cek = sql_get_var_row("select soalid,benar,jawabanid,jawaban from tbl_quiz_peserta where pesertaid='$_SESSION[pesertaid]' and level='$step'");
$soalid = $cek['soalid'];
$dijawab = $cek['jawaban'];
$benar = $cek['benar'];

if(empty($soalid))
{
	//Tampilkan Soal
	$sql = "select id,pertanyaan,materi from tbl_quiz_soal where level='$step' and secid='$secid' order by rand() limit 1";
	$hsl = sql($sql);
	$data = sql_fetch_data($hsl);
	$pertanyaan = $data['pertanyaan'];
	$id = $data['id'];
	$materi = $data['materi'];
	
	
	$tpl->assign("pertanyaan",$pertanyaan);
	
	$alfa = array("A","B","C","D");
	$sql = "select jawabanid,jawaban from tbl_quiz_soal_jawaban where id='$id' order by rand() limit 4";
	$hsl = sql($sql);
	$a = 0;
	while($data = sql_fetch_data($hsl))
	{
		$jawabanid = $data['jawabanid'];
		$jawaban =  $data['jawaban'];
		
		$hash = base64_encode("$id++$jawabanid++$step++$secid");
		
		$jawabans[] = array("alfabet"=>$alfa[$a],"hash"=>$hash,"jawabanid"=>$jawabanid,"jawaban"=>$jawaban);
		
		$jawab[] = array("alfabet"=>$alfa[$a],"jawabanid"=>$jawabanid);
		$jawabans1 = serialize($jawab);
		$a++;
	}
	$tpl->assign("jawabans",$jawabans);
	
	$sql = "insert into tbl_quiz_peserta(pesertaid,userfullname,soalid,level,jawabanid,jawaban,create_date,secid) values('$_SESSION[pesertaid]','$_SESSION[userfullname]','$id','$step','$jawabans1','',now(),'$secid')";
	$sql = sql($sql);


}
elseif(!empty($soalid) && empty($dijawab))
{
	
	//Tampilkan Soal
	$sql = "select id,pertanyaan,materi from tbl_quiz_soal where level='$step' and secid='$secid' and id='$soalid'";
	$hsl = sql($sql);
	$data = sql_fetch_data($hsl);
	$pertanyaan = $data['pertanyaan'];
	$id = $data['id'];
	$materi = $data['materi'];
	
	$tpl->assign("pertanyaan",$pertanyaan);
	
	$jw = unserialize($cek['jawabanid']);
	
	$alfa = array("A","B","C","D");
	$a = 0;
	$k = 1;
	for($i=0;$i<count($jw);$i++)
	{
		$row = $jw[$i];
		$jawabanid = $row['jawabanid'];
		$jawabanss = sql_get_var_row("select jawaban,kunci from tbl_quiz_soal_jawaban where id='$soalid' and jawabanid='$jawabanid'");
		$jawaban = $jawabanss['jawaban'];
		$kunci = $jawabanss['kunci'];
		
		if($bantuan=="bantuan1" && empty($_SESSION['bantuan1']))
		{
			if(empty($kunci))
			{
				$hide = 1;
				$k++;
				if($k>3 && empty($kunci) ) $hide = "";
			}
			else
			{
				$hide = "";
			}
		}
		
		
		
		$hash = base64_encode("$id++$jawabanid++$step++$secid");
		
		$jawabans[] = array("alfabet"=>$alfa[$a],"hash"=>$hash,"jawabanid"=>$jawabanid,"jawaban"=>$jawaban,"hide"=>$hide);
		$a++;
		
	}
	
	$tpl->assign("jawabans",$jawabans);
}
else
{
	//Tampilkan Soal
	$sql = "select id,pertanyaan,materi from tbl_quiz_soal where level='$step' and secid='$secid' and id='$soalid'";
	$hsl = sql($sql);
	$data = sql_fetch_data($hsl);
	$pertanyaan = $data['pertanyaan'];
	$id = $data['id'];
	$materi = $data['materi'];
	
	$tpl->assign("pertanyaan",$pertanyaan);
	
	$jw = unserialize($cek['jawabanid']);
	
	$alfa = array("A","B","C","D");
	for($i=0;$i<count($jw);$i++)
	{
		$row = $jw[$i];
		$jawabanid = $row['jawabanid'];
		$jawaban = sql_get_var("select jawaban from tbl_quiz_soal_jawaban where id='$soalid' and jawabanid='$jawabanid'");
		
		$hash = base64_encode("$id++$jawabanid++$step++$secid");
		
		$jawabans[] = array("alfabet"=>$row['alfabet'],"hash"=>$hash,"jawabanid"=>$jawabanid,"jawaban"=>$jawaban);
		
	}
	
	$tpl->assign("jawabans",$jawabans);
	
	$status = $var[6];
	if($status=="success" || $benar=="1")
	{
		$tpl->assign("benar","1");
		$tpl->assign("jawabanids",$dijawab);
		$jawabankunci = sql_get_var("select jawabanid from tbl_quiz_soal_jawaban where id='$id' and kunci='1'");
		$tpl->assign("jawabankunci",$jawabankunci);
		$tpl->assign("materi",$materi);
		$next = $step+1;
		$status="success";
		
		if($jmllevel==$step)
		{
			$pesan = "Selamat anda menjadi <strong>JUARA</strong> dalam permainan ini dan anda berhak mendapatkan poin member baru. Kumpulkan
			poin member terus dan dapatkan benefitnya";
			$nominal = sql_get_var("select nominal from tbl_quiz_level where level='$step'");
			
			$nominal = rupiah($nominal);
			
			earnpoin("quiz",$_SESSION['userid']);
			
			$tpl->assign("pesan",$pesan);
			$tpl->assign("nominal",$nominal);
			$tpl->assign("nextstep","$fulldomain/quiz/reset/$secid");
			$tpl->assign("end","1");
		}
		else
		{
			
			$pesan = "Selamat anda telah menjawab dengan benar pertanyaan pada level $step dan anda berhak mendapatkan";
			$nominal = sql_get_var("select nominal from tbl_quiz_level where level='$step'");
				
			$nominal = rupiah($nominal);
			
			$tpl->assign("pesan",$pesan);
			$tpl->assign("nominal",$nominal);
			$tpl->assign("nextstep","$fulldomain/quiz/start/$secid/".$next);

		}
		
	}
	else
	{
		$tpl->assign("salah","1");
		$tpl->assign("jawabanids",$dijawab);
		$jawabankunci = sql_get_var("select jawabanid from tbl_quiz_soal_jawaban where id='$id' and kunci='1'");
		$tpl->assign("jawabankunci",$jawabankunci);
		$tpl->assign("materi",$materi);
		$status="end";
	}
	
	$tpl->assign("status",$status);
	
}

if($bantuan=="bantuan1")
{
	$_SESSION['bantuan1'] = "1";
	$tpl->assign("bantuan1","1");
}
if($bantuan=="bantuan2")
{
	$_SESSION['bantuan2'] = "1";
	$tpl->assign("bantuan2","1");
}
if($bantuan=="bantuan3")
{
	$_SESSION['bantuan3'] = "1";
	$tpl->assign("bantuan3","1");
}

//Bantuan
if(empty($_SESSION['bantuan1']))
{
	$urlbantuan1 = "$fulldomain/quiz/start/$secid/$step/bantuan1";
	$tpl->assign("urlbantuan1",$urlbantuan1);
}
if(empty($_SESSION['bantuan2']))
{
	$urlbantuan2 = "$fulldomain/quiz/start/$secid/$step/bantuan2";
	$tpl->assign("urlbantuan2",$urlbantuan2);
}
if(empty($_SESSION['bantuan3']))
{
	$urlbantuan3 = "$fulldomain/quiz/start/$secid/$step/bantuan3";
	$tpl->assign("urlbantuan3",$urlbantuan3);
}

$tpl->assign("step",$step);
?>