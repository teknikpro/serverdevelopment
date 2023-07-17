<?php
$katid = str_replace(".html","",$katid);
$tanggal = $katid;

if(empty($tanggal))
{
	$date = getdate();
}
else
{
	$date = strtotime("$tanggal-01 12:00:00");
	$date = getdate($date);
}

 $day = $date["mday"];
 $month = $date["mon"];
 $month_name = $date["month"];
 $year = $date["year"];

 $this_month = getdate(mktime(0, 0, 0, $month, 1, $year));
 $next_month = getdate(mktime(0, 0, 0, $month + 1, 1, $year));
 
 $bln_now = mktime(0,0,0,$month,1,$year);
 $bln_next = strtotime('+1 month', $bln_now);
 $bln_next = date("Y-m",$bln_next);
 
 $bln_last = strtotime('-1 month', $bln_now);
 $bln_last = date("Y-m",$bln_last);
 


 //Find out when this month starts and ends.
 $first_week_day = $this_month["wday"];
 $days_in_this_month = round(($next_month[0] - $this_month[0]) / (60 * 60 * 24));

 $calendar_html = "<table class=\"tblagenda\" width=99%>";

/* $calendar_html .= "<tr><td colspan=\"7\" align=\"center\" style=\"color:000000;\"><b> <a href=\"$fulldomain/agenda/arsip/$bln_last\" class=\"judul\">&lt;</a> &nbsp;" .
				   $month_name . " " . $year . "&nbsp;<a href=\"$fulldomain/agenda/arsip/$bln_next\" class=\"judul\">&gt;</a><b><br><br></td></tr>";
*/
  $calendar_html .= "<tr><td colspan=\"7\" align=\"center\" style=\"color:000000;\"><b>" .
				   $month_name . " " . $year . "&nbsp;<br><br></td></tr>";

$calendar_html .= "<tr>";

 //Fill the first week of the month with the appropriate number of blanks.
 for($week_day = 0; $week_day < $first_week_day; $week_day++)
	{
	$calendar_html .= "<td style=\"color:#666666;\"> </td>";
	}

 $week_day = $first_week_day;
 for($day_counter = 1; $day_counter <= $days_in_this_month; $day_counter++)
	{
	$week_day %= 7;

	if($week_day == 0)
	   $calendar_html .= "</tr><tr>";
	   
	//cari data jadwal
	if($day_counter < 10) $tgl = "0".$day_counter;
	 else $tgl = $day_counter;
	
	if($month < 10) $bln = "0".$month;
	 else $bln = $month;
	 
	$dt = "$year-$bln-$tgl";
	
	$perintah = "select count(*) as jumlah from tbl_agenda where substring(tanggal,1,10)='$dt'";
	$hasil = sql($perintah);
	$data = sql_fetch_data($hasil);
	$jml = $data['jumlah'];
	
	if($jml>0) $warna = "#acd456";
	 else $warna = "#fafbfc";
	
	if($jml>0)
	{
	//Do something different for the current day.
	$calendar_html .= "<td align=\"center\" style=\"background-color:$warna; color:#333333; border:2px solid #666666; padding:10px; margin=2px;\">&nbsp;
		<a href=\"$fulldomain/agenda/date/$dt\" class=judul>" .
						 $day_counter . "</a> </td>";
	}
	else
	{
	//Do something different for the current day.
	$calendar_html .= "<td align=\"center\" style=\"background-color:$warna; color:#333333; border:2px solid #666666; padding:10px; margin=2px;\">&nbsp;
		<a href=\"#\" class=judul>" .
						 $day_counter . "</a> </td>";
	}

	$week_day++;
	}

 $calendar_html .= "</tr>";
 $calendar_html .= "</table>";
 
 $tpl->assign("agendacalendar", $calendar_html);
		 
?>
