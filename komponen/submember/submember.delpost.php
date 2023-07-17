<?php 
$postid = $var[4];

$perintah	= "delete from tbl_post_komen where username='$_SESSION[username]' and postid='$postid'";
$hasil	=  sql($perintah);

if($hasil)
{
	$perintah2	= "delete from tbl_post where username='$_SESSION[username]' and postid='$postid'";
	$hasil2		=  sql($perintah2);
	
	if($hasil2)
	{
		//Hapus Point
		$poin = sql_get_var("select poin from tbl_poin_config where alias='delete-status'");
		$last = sql_get_var("select point from tbl_member where userid='$_SESSION[userid]'");
		$totpoint = $last-$poin;
		
		$sql = "insert into tbl_member_point_history(create_date,userid,transid,ordernumber,redeemnumber,point,tipe,balancetotal,activity)
								values('$date','$_SESSION[userid]','$newredeeimd','$earnordernumber','$ordernumber','$poin','DB','$totpoint','delete-status')";
		$hsl = sql($sql);
			
			
		$update = sql("update tbl_member set point=$totpoint where userid='$_SESSION[userid]'");
			
		
		$sqlp	= "update tbl_member set posting=posting-1 where username='$_SESSION[username]'";
		$qry = sql($sqlp);
		
		$perintah	= "delete from tbl_post_komen where postid='$postid'";
		header("location: $fulldomain/user");
		
	}
}
?>