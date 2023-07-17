<?php
if(isset($_POST['userfullname']))
{
	$userfullname = cleaninsert($_SESSION['userfullname']);
	if(!empty($userfullname))
	{
		$_SESSION['userfullname'] = $_SESSION['userfullname'];
		$_SESSION['pesertaid'] = $_SESSION['userid'];
		
		header("location: $fulldomain/quiz/pilihtopik");
		exit();
	}
	else
	{
		header("location: $fulldomain/quiz/inputnama");
		exit();
	}
}
	
?>