<?php	session_start();
	include "connection.php";
	if($_SERVER['REQUEST_METHOD']=='POST')
	{	 
	
		$albumname=str_replace("'","''",$_POST["albumname"]);
		$albumname=str_replace("\"","''",$albumname);
		$albumname=stripslashes($albumname);
		
		$description=str_replace("'","''",$_POST["description"]);
		$description=str_replace("\"","''",$description);
		$description=stripslashes($description);

		$privacylevel=$_POST["privacylevel"];
		
		$userid=$_SESSION["userid"];			
		
		$datecreated=date("Y-m-d H:i:s");
		
		try
		{	
			$query="insert into tblalbums(
				albumname,
				description,
				privacylevel,
				coverphotoid,
				datecreated,
				userid) 
				values
				('".$albumname."','".$description."',".$privacylevel.",0,'".$datecreated."',".$userid.")";
											
											//echo $query;				
				if(mysql_query($query))
				{
					$albumid=mysql_insert_id();
					
					echo "<script>location.href='uploadphotos.php?albumid=".$albumid."&albumname=".$albumname."&isnew=1' </script>";
					header('Location: uploadphotos.php?albumid='.$albumid.'&albumname='.$albumname."&isnew=1");
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

