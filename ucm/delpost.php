<?php	session_start();
	include "connection.php";
	
	$blogpostid=$_GET["blogpostid"];
					
	try
	{	
		$query="delete from tblblogposts where blogpostid = ".$blogpostid;
		$query2="delete from tblblogcomments where blogpostid = ".$blogpostid;
		if(mysql_query($query) && mysql_query($query2))
		{
			header("location: myblog.php?msg=Post deleted successfully");
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
