<?php	session_start();
	include "connection.php";
	if($_SERVER['REQUEST_METHOD']=='POST')
	{	 
	    $postedby = $_SESSION["userid"];
	
		$subject=str_replace("'","''",$_POST["subject"]);
		$subject=str_replace("\"","''",$subject);
		$subject=stripslashes($subject);
		
		$description=str_replace("'","''",$_POST["description"]);
		$description=str_replace("\"","''",$description);
		$description=stripslashes($description);

		//$resourcetypeid=$_POST["resourcetypeid"];
		$resourcetypeid=$_SESSION["resourcetypeid"];
		$diseaseid=$_POST["diseaseid"];
		$link=$_POST["link"];	
		$embedvideolink = "";
		if(isset($_POST["evlink"]))
		  $embedvideolink = $_POST["evlink"];		
		$dateposted=date("Y-m-d");
		
		try
		{	
			$query="insert into tblresources(
				postedby,
				subject,
				description,
				resourcetypeid,
				diseaseid,
				link,
				embedvideolink,
				dateposted) 
				values
				(".$postedby.",'".$subject."','".$description."',$resourcetypeid,$diseaseid,'".$link."','".$embedvideolink."','".$dateposted."')";
											
											//echo $query;				
				if(mysql_query($query))
				{
					$albumid=mysql_insert_id();
					
					echo "<script>location.href='resources.php?msg=Resource added successfully.' </script>";
					header('Location: resources.php?msg=Resource added successfully.');
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

