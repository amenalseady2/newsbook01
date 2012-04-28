<?php	session_start();
	include "connection.php";
	
	$userid=$_GET["userid"];
	$blogcommentid=$_GET["blogcommentid"];
					
	try
	{	
		$query="delete from tblblogcomments where blogcommentid = ".$blogcommentid;
		if(mysql_query($query))
		{
			header("location: blog.php?userid=".$userid."&msg=Your comment deleted successfully");
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
