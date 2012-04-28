<?php	session_start();
	include "connection.php";
	
	$photoid=$_GET["photoid"];
					
	try
	{	
		$query="delete from tblphotos where photoid = ".$photoid;
		if(mysql_query($query))
		{
			header("location: photos.php?msg=Comment deleted successfully");
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
