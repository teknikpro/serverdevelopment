<?php 
$jmlsikap = sql_get_var("select count(*) as jml from tbl_tesmandiri_user where userid='$_SESSION[userid]'");

if($jmlsikap>0)
{
	header("location: $fulldomain/tesmandiri/hasil");
	exit();
	
	$notif = "Mohon maaf anda sebelumnya telah mengisi kuisioner tentang tingkat kecamasan anda. Untuk saat ini anda tidak dapat melakukan tes secara berulang-ulang.<br><br>
	<a href=\"$fulldomain/tesmandiri/hasil\" class=\"btn btn-default btn-lg\">Lihat Hasil</a>";
	$tpl->assign("pesan",$pesan);
	$tpl->assign("pesanhasil",$notif);
	$tpl->assign("berhasil",0);
}
else
{
	
	if(isset($_POST['sikap']))
	{
		$dt = $_POST['soal'];
		for($i=0;$i<count($dt);$i++)
		{
			$soalid = $dt[$i];
			$jawaban = $_POST["j-$soalid"];
			
			if(!empty($jawaban))
			{
				$sql = "insert into tbl_tesmandiri_user(userid,nilai,soalid) values('$_SESSION[userid]','$jawaban','$soalid')";
				$hsl = sql($sql);
			}
			unset($jawaban);
			
		}
		
	   if($hsl)
	   {
			$pesanhasil = "Selamat kuisioner anda untuk <strong>$paket[paket]</strong> di $title telah berhasil, silahkan lanjutkan
			proses belajar anda ataupun ujian anda";
			$berhasil = "1";
			
			header("location: $fulldomain/tesmandiri/hasil");
			exit();
	   }
		else
		{
			$pesanhasil = "Penyimpanan data pendaftaran gagal dilakukan ada beberapa kesalahan yang mesti diperbaiki, silahkan periksa kembali kemungkinan ada kesalahan yang harus anda perbaiki";
			$berhasil = "0";
		}
		
	$tpl->assign("pesan",$pesan);
	$tpl->assign("pesanhasil",$pesanhasil);
	$tpl->assign("berhasil",$berhasil);
	
	}
	
	$perintah2 = sql("select soalid,nomor,pertanyaan,tidaksamasekali,kadangkadang,jarang,sering from tbl_tesmandiri_soal order by nomor asc");
	while($data2 = sql_fetch_data($perintah2))
	{
		$soalid = $data2['soalid'];
		$nomor = $data2['nomor'];
		$pertanyaan = $data2['pertanyaan'];
		$tidaksamasekali = $data2['tidaksamasekali'];
		$kadangkadang = $data2['kadangkadang'];
		$jarang = $data2['jarang'];
		$sering = $data2['sering'];
		
		$soal[] = array("soalid"=>$soalid,"nomor"=>$nomor,"pertanyaan"=>$pertanyaan,"tidaksamasekali"=>$tidaksamasekali,"jarang"=>$jarang,"kadangkadang"=>$kadangkadang,"sering"=>$sering);
	}
	
	$tpl->assign("soalsikap",$soal);
	
} 

?>