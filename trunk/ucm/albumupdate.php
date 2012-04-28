<?php	session_start();
	include "connection.php";
	
	if($_SERVER['REQUEST_METHOD']=='POST')
	{
		$albumid=$_POST["albumid"];
	
		$albumname=str_replace("'","''",$_POST["albumname"]);
		$albumname=str_replace("\"","''",$albumname);
		$albumname=stripslashes($albumname);
		
		$description=str_replace("'","''",$_POST["description"]);
		$description=str_replace("\"","''",$description);
		$description=stripslashes($description);

		$privacylevel=$_POST["privacylevel"];
		
						
		try
		{	
			$query="update tblalbums
					set
						albumname='".$albumname."',
						description='".$description."',
						privacylevel=".$privacylevel."
					where 
						albumid=".$albumid;
			
			if(mysql_query($query))
			{
				header("location: viewalbum.php?msg=Album info updated successfully&albumid=".$albumid."&albumname=".$albumname);
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
	}

?>
