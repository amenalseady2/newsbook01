<?php	
//session_start();
    include "header_new.php"; 
	//include "connection.php";
	//include "encrypt_decrypt.php";
	
	$userid = 0;
	$key = 0;
	
	if(isset($_GET["u"]))
	{
		$userid=$_GET["u"];
		$rcvd_key = $_GET["k"];
		$rcvd_uid = decrypt_userid($rcvd_key); 	
	}	
	
	if($userid != $rcvd_uid)
	{
		echo "User Activation Failed - Mismatch key";
		echo $userid;
		echo $rcvd_key;
		echo $rcvd_uid; 
		session_destroy();
		//unset($_SESSION['userid']); 
		//session_unset(); 
	}	 
	
	try
	{	
		$query="update tbluser set isactive=1 where userid='".$userid."'";	//set the user entry to active status
		
		if(mysql_query($query))
		{
			$qry = "SELECT tbluser.userid,CONCAT( fname, ' ', lname ) AS name, tbluser.usertypeid,
					tbluser.email
					FROM tbluser,tblusertype 
					WHERE tbluser.userid = ".$userid." 
					AND tblusertype.usertypeid=tbluser.usertypeid";		
			
			$query_alias = "update tbluser set alias='Youcureme User' where userid='".$userid."'";
			$result1 = mysql_query($query_alias);
			//$num1 = mysql_num_rows($result1);
			
			$result = mysql_query($qry);
			if($result)
			{
				$num = mysql_num_rows($result);
				if($num>0)
				{
					$row=mysql_fetch_array($result);
					
					$_SESSION["userid"]= $row["userid"];
					$_SESSION["fname"] = $row["name"];
					$_SESSION["email"] = $row["email"];
					$_SESSION["usertypeid"]= $row["usertypeid"]; 
					$_SESSION["profilepic"]="empty_profile.jpg";
				}
			} 
			echo "<script>location.href='editprofile.php?userid=".$userid."' </script>";			
			//header("location: editprofile.php?userid=".$userid);
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
