<?php	session_start();
	include "connection.php";
	
	$userid=$_GET["ui"];
	$friendwith=$_GET["fw"];
	$notificationtime=date("Y-m-d H:i:s");
	$name=$_GET["n"];

	$friendshipstatus=$_GET["stat"];
	
	$notification="";
	$query1="";
	$query2="";
	
	$uname=$_GET["uname"];
	$usertypeid=$_GET["usertypeid"];
	$diseaseid=$_GET["diseaseid"];
	
	if($friendshipstatus=="2")
	{
		$notification="<a href=''viewprofile.php?userid=".$userid."''>".$name."</a> accepted your Friend Request.";
		
		// request for the person who has been added
		$query1="update tblfriends
		set friendshipstatus=".$friendshipstatus."
		where userid=".$userid." and friendwith=".$friendwith;
						
		// request of the person who added a friend
		$query2="update tblfriends
		set friendshipstatus=".$friendshipstatus."
		where friendwith=".$userid." and userid=".$friendwith;
	}
	elseif($friendshipstatus=="3")
	{
		$notification="<a href=''viewprofile.php?userid=".$userid."''>".$name."</a> rejected your Friend Request.";
		
		// request for the person who has been added
		$query1="delete from tblfriends
		where userid=".$userid." and friendwith=".$friendwith;
						
		// request of the person who added a friend
		$query2="delete from tblfriends
		where friendwith=".$userid." and userid=".$friendwith;
	}
	elseif($friendshipstatus=="un")
	{
		$notification="<a href=''viewprofile.php?userid=".$userid."''>".$name."</a> choose not to stay as your Friend.";	
		$friendshipstatus=3;
		
		// request for the person who has been added
		$query1="delete from tblfriends
		where userid=".$userid." and friendwith=".$friendwith;
						
		// request of the person who added a friend
		$query2="delete from tblfriends
		where friendwith=".$userid." and userid=".$friendwith;
	}
	
	try
	{	
		// add notifications for the person whos request has been accepted/rejected	
		$query3="insert into tblnotifications(
		userid,
		notification_type,
		notification,
		notificationtime) 
		values
		(".$friendwith.",1,'".$notification."','".$notificationtime."')";	
													
		if(mysql_query($query1) && mysql_query($query2) && mysql_query($query3))
		{
			echo "<script>location.href='members.php?uname=".$uname."&usertypeid=".$usertypeid."&diseaseid=".$diseaseid."' </script>";

				/* Send an email(if opted) to the concerned user */	 				
				$qry_email = "select email fname rcvemail4msgs from tbluser where userid = ".$userid;
				$sql = mysql_query($qry_email);				
				$row=mysql_fetch_array($sql);
				
				$rcv_mail = $row['rcvemail4msgs'];
				if($rcv_mail)
				{
					$EmailTo = $row['email'];	 
					$EmailFrom = "info@youcureme.com";
					$Emailfrom_name = "YouCureMe.com"; 
					$EmailSubject = "Your blog received a comment !"; 
					$fname = $row['fname'];  
					$EmailMsg = "Hi ".$fname.",<br>".		
								".$notification.";
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
