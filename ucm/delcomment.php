<?php	session_start();
	include "connection.php";
	
	$blogcommentid=$_GET["blogcommentid"];
					
	try
	{	
		$query="delete from tblblogcomments where blogcommentid = ".$blogcommentid;
		if(mysql_query($query))
		{
			header("location: myblog.php?msg=Comment deleted successfully");
		}
		else
		{
			echo mysql_error();
		}
	}
	catch(exception $ex)
	{
		echo $ex;
	}

?>
