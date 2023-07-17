<?php 
$tanggal = date("Y-m-d");
$tanggal1 = tanggalonly($tanggal);

$tpl->assign("tanggal",$tanggal1);
if(isset($_POST['jawabanid']))
{
	if(!$_SESSION['userid'])
	{
		header("location: $fulldomain/user");
		exit();
	}
	
	//
	$jawabanid = $_POST['jawabanid'];
	$cek = sql_get_var("select count(*) as jml from tbl_kuis_peserta where userid='$_SESSION[userid]' and tanggal='$tanggal'");
	

	if(!empty($cek))
	{
		$msg = "Mohon maaf, anda tidak bisa mengikuti kuis secara berulang-ulang dihari yang sama, silahkan coba kembali untuk
		mengikuti kuis esok hari";
		$error = 1;
	}
	else
	{
		$jawabanid = $_POST['jawabanid'];
		$jawaban = base64_decode($jawabanid);
		
		$jawab = explode("++",$jawaban);
		$jawabs = $jawab[1];

		$soalid = sql_get_var("select id from tbl_kuis_soal where tanggal='$tanggal'");
		$kunci = sql_get_var("select kunci from tbl_kuis_soal_jawaban where id='$soalid' and jawabanid='$jawabs'");
		
		if($kunci==1)
		{
			$benar = 1;
			
			earnpoin("kuis-harian",$_SESSION['userid']);
				
			$msg = "Selamat, anda menjawab pertanyaan kuis hari ini dengan benar dan anda berhak mendapatkan tambahan poin. Kumpulkan poin sebanyak-banyaknya
			dan tukarkan dengan banyak hadiah menarik dari dfun Station";
			$error = 0;
		}
		else
		{
			$benar = 0;
				
			$msg = "Sayang sekali, jawaban anda salah untuk pertanyaan kuis hari ini, silahkan coba kuis dan pertanyaan esok hari. Kumpulkan poin sebanyak-banyaknya
			dan tukarkan dengan banyak hadiah menarik dari dfun Station";
			$error = 1;
		}
		
		$sql = "insert into tbl_kuis_peserta(userid,tanggal,jawabanid,benar) values('$_SESSION[userid]','$tanggal','$jawabs','$benar')";
		$sql = sql($sql);

	
	}
	$tpl->assign("msg",$msg);
	$tpl->assign("error",$error);

}
else
{
	//Tampilkan Soal
	$sql = "select id,pertanyaan from tbl_kuis_soal where tanggal='$tanggal'";
	$hsl = sql($sql);
	$data = sql_fetch_data($hsl);
	$pertanyaan = $data['pertanyaan'];
	$soalid = $data['id'];
	$materi = $data['materi'];
	
	$tpl->assign("pertanyaan",$pertanyaan);
	
	$alfa = array("A","B","C","D");
	$a = 0;
	$k = 1;
	$jawabanss = sql("select jawaban,kunci,jawabanid from tbl_kuis_soal_jawaban where id='$soalid'  order by rand()");
	while($dt=sql_fetch_data($jawabanss))
	{
		$jawabanid = $dt['jawabanid'];
	
		$jawaban = $dt['jawaban'];
		$kunci = $dt['kunci'];
		
		
		$hash = base64_encode("$soalid++$jawabanid++$tanggal");
		
		$jawabans[] = array("alfabet"=>$alfa[$a],"hash"=>$hash,"jawabanid"=>$jawabanid,"jawaban"=>$jawaban,"hide"=>$hide);
		$a++;
		
	}
	
	$tpl->assign("jawabans",$jawabans);
}


$tpl->display("$kanal.html");
?>