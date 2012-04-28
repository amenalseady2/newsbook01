<?php	session_start();
	include "connection.php";

	$albumid=$_GET["albumid"];
							
	try
	{	
		$query="delete from tblalbums
				where 
					albumid=".$albumid;
		
		if(mysql_query($query))
		{
			header("location: photos.php?msg=Album deleted successfully");
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
