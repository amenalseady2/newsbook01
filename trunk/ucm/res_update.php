<?php	session_start();
	include "connection.php";
	if($_SERVER['REQUEST_METHOD']=='POST')
	{	 
	    $resourceid = $_POST["resourceid"];
	
		$subject=str_replace("'","''",$_POST["subject"]);
		$subject=str_replace("\"","''",$subject);
		$subject=stripslashes($subject);
		
		$description=str_replace("'","''",$_POST["description"]);
		$description=str_replace("\"","''",$description);
		$description=stripslashes($description);

		$resourcetypeid=$_POST["resourcetypeid"];
		$diseaseid=$_POST["diseaseid"];
		$link=$_POST["link"];		
		$embedvideolink = ""; 		
		if(isset($_POST["evlink"]))
		  $embedvideolink = $_POST["evlink"];
		
		try
		{	
			$query="update tblresources set
				subject='".$subject."',
				description='".$description."',
				resourcetypeid=".$resourcetypeid.",
				diseaseid=".$diseaseid.",
				link='".$link."',
				embedvideolink = '".$embedvideolink."'
				where resourceid=".$resourceid;
				
				//echo $query;				
				
				if(mysql_query($query))
				{					
					echo "<script>location.href='resources.php?msg=Resource updated successfully.' </script>";
					header('Location: resources.php?msg=Resource updated successfully.');
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

