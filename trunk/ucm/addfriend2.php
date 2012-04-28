<?php	session_start();
	include "connection.php";
	
	$userid=$_GET["ui"];
	$friendwith=$_GET["fw"]; //the guy who sent req
	$notificationtime=date("Y-m-d H:i:s");
	$name=$_GET["n"];
	$notification=$name." added you as a <a href=''reqs.php''>friend</a>";
	
	$uname=$_GET["uname"];
	$usertypeid=$_GET["usertypeid"];
	$diseaseid=$_GET["diseaseid"];
	
	try
	{	
		// request for the person who has been added
		$query1="insert into tblfriends(
		userid,
		friendwith,
		friendshipstatus) 
		values
		(".$userid.",".$friendwith.",1)";
						
		// request of the person who added a friend
		$query2="insert into tblfriends(
		userid,
		friendwith,
		friendshipstatus) 
		values
		(".$friendwith.",".$userid.",4)";	
		
		// add notifications for the person who has been added	
		$query3="insert into tblnotifications(
		userid,
		notification_type,
		notification,
		notificationtime) 
		values
		(".$userid.",1,'".$notification."','".$notificationtime."')";	
					
													
		if(mysql_query($query1) && mysql_query($query2) && mysql_query($query3))
		{
			//echo "<script>location.href='members.php?msg=Friend request sent successfully' </script>";
			//echo "<script>location.href='members.php?uname=".$uname."&usertypeid=".$usertypeid."&diseaseid=".$diseaseid."&msg=Friend request sent successfully' </script>";
			echo "<script>location.href='viewprofile.php?userid=".$userid."&msg=Friend Request sent successfully.' </script>";

				/* Send an email(if opted) to the user(who has been added) informing him/her that he/she
				has been accepted as a friend */
				
				$qry_email = "select email fname rcvemail4msgs from tbluser where userid = ".$userid;
				$sql = mysql_query($qry_email);				
				$row=mysql_fetch_array($sql);
				
				$rcv_mail = $row['rcvemail4msgs'];
				if($rcv_mail)
				{
					$EmailTo = $row['email'];	 
					$EmailFrom = "info@youcureme.com";
					$Emailfrom_name = "YouCureMe.com"; 
					$EmailSubject = $name." added you as a friend";  
					$fname = $row['fname'];  
					$EmailMsg = "Hi ".$fname.",<br>"		
								.$name." added you as a friend.";  
					send_smtpmail($EmailTo, $EmailFrom, $Emailfrom_name, $EmailSubject, $EmailMsg);		
				} 
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
