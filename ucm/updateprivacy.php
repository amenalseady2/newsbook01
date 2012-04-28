<?php	session_start();
	include "connection.php";
	
	if($_SERVER['REQUEST_METHOD']=='POST')
	{
		$userid=$_POST["userid"];
		
		$access_name=$_POST["access_name"];
		$access_pic=$_POST["access_pic"];
		$access_dob=$_POST["access_dob"];
		$access_gender=$_POST["access_gender"];			
		$access_disease=$_POST["access_disease"];
		$access_loc=$_POST["access_loc"];
		$access_email=$_POST["access_email"];
		$access_web=$_POST["access_web"];
		$access_iam=$_POST["access_iam"];			
		$access_ilike=$_POST["access_ilike"];		
		$access_exp=$_POST["access_exp"];		
		$access_photos=$_POST["access_photos"];		
		$access_friends=$_POST["access_friends"];		
		$access_blog=$_POST["access_blog"];
		$access_msg=$_POST["access_msg"];
		
		try
		{	
			$query="update tbluser
					set
						access_name=".$access_name.",
						access_pic=".$access_pic.",
						access_dob=".$access_dob.",
						access_gender=".$access_gender.",
						access_disease=".$access_disease.",
						access_loc=".$access_loc.",
						access_email=".$access_email.",
						access_web=".$access_web.",
						access_iam=".$access_iam.",
						access_ilike=".$access_ilike.",
						access_exp=".$access_exp.",
						access_photos=".$access_photos.",
						access_friends=".$access_friends.",
						access_blog=".$access_blog.",
						access_msg=".$access_msg."
					where 
						userid=".$userid;
			
			if(mysql_query($query))
			{
				header("location: editprofile.php?userid=".$userid."&msg=Privacy settings updated successfully");
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
