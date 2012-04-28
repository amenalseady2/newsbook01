<?php	
session_start();
	include "connection.php";
	//include "Mail.php";
	include "smtpmailer.php";
	
	$userid=$_GET["ui"];
	$friendwith=$_GET["fw"]; //the guy who sent req
	$notificationtime=date("Y-m-d H:i:s");
	$name=$_GET["n"];
	$notification=$name." added you as a <a href=''reqs.php''>friend</a>";	

/*
	$path = "images"; // Path to the directory where the emoticons are 
	// Query the database, and assign the result-set to $result 
	$query = "SELECT emote, image FROM tblemoticons"; 
	$result = mysql_query($query); 

	// Loop through the results, and place the results in two arrays 
	//$i=0;
	while($row = mysql_fetch_array($result, MYSQL_ASSOC)) 
	{ 
			$emotes[] = $row['emote']; 
			//$images[] = "<img src='" . $path . "/" . $row['image'] . "'>"; 
			//$images[] = "<img src='"."/" . $row['image'] . "'>";  ok
			$images[] = "<img src='"."/youcureme/".$row['image']."'>"; 
			//print_r($images[$i]);
			//$i++;
	} 
	$text = "<strong>Emoticons</strong><br> :-) <br><br> Neat? <3 <br><br>"; 

	// The line below replaces the emotes with the images 
	//echo str_replace($emotes, $images, $text); 	 

	$userid=$_GET["ui"];
	$friendwith=$_GET["fw"]; //the guy who sent req
	$notificationtime=date("Y-m-d H:i:s");
	$name=$_GET["n"];
	//$notification=$name." added you as a <a href=''reqs.php''> friend</a>";
	$notification=$name." added you as a =D friend</a>";
	//$notification=$name." added you as a <a img src=''/youcureme/images/hug.gif''> friend</a>";
	$noti=$name." added you as a "; 
	echo $noti;
	
	?>
	<img src="<? echo '/youcureme/images/hug.gif'; ?>" <br>">
	<?php
	
	$notification = str_replace($emotes, $images, $notification); 
	echo $notification;	
	
 	?>
    <div class="plusimg"><img src="/youcureme/images/angel.gif" /></div>	
	<?php 
	
	$message="<div class='album-pics'><a img src='/youcureme/images/sad.gif'/>";					
	$message=$message."</a><br/></div>";
	echo $message; 	
	*/
	
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
					
		$confirm_query = "select * from tblfriends where userid=".$userid." and friendwith=".$friendwith." and friendshipstatus=1";
		
		$rslt = mysql_query($confirm_query);
		$num=mysql_num_rows($rslt);
		if($num>0){
			echo "<script>location.href='viewprofile.php?userid=".$userid."&msg=Friend Request sent successfully.' </script>";
			exit;
		}
		$confirm_query = "select * from tblfriends where userid=".$friendwith." and friendwith=".$userid." and friendshipstatus=4";
		$rslt = mysql_query($confirm_query);
		$num=mysql_num_rows($rslt);
		if($num>0){
			echo "<script>location.href='viewprofile.php?userid=".$userid."&msg=Friend Request sent successfully.' </script>";
			exit;
		}
		if(mysql_query($query1) && mysql_query($query2) && mysql_query($query3))
		{
				echo "<script>location.href='viewprofile.php?userid=".$userid."&msg=Friend Request sent successfully.' </script>";

				/* Send an email(if opted) to the user(who has been added) informing him/her that he/she
				has been accepted as a friend */
				
				$qry_email = "SELECT email fname rcvemail4msgs from tbluser where userid = ".$userid."";
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
