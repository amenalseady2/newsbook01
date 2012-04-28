<?php	session_start();
	include "connection.php";
	//include "Mail.php";
	include "smtpmailer.php";
		
	if($_SERVER['REQUEST_METHOD']=='POST')
	{
		$userid=$_POST["userid"];
		$blogpostid=$_POST["blogpostid"];
		$postedbyuserid=$_POST["postedbyuserid"];
		$datetimeposted=date("Y-m-d H:i:s");
		
		$commenttext=str_replace("'","''",$_POST["commenttext"]);
		$commenttext=str_replace("\"","''",$commenttext);
		$commenttext=stripslashes($commenttext);
						
		try
		{	
			$query="insert into tblblogcomments 
					(blogpostid,
					commenttext,
					postedbyuserid,
					datetimeposted)
					values
					(".$blogpostid.",
					'".$commenttext."',
					".$postedbyuserid.",
					'".$datetimeposted."')";
			//echo $query;
			if(mysql_query($query))
			{
				/* Insert the notification and send email to the blog poster */
				$qry = "select fname,lname, email, rcvemail4msgs
						from tbluser 
						where userid = ".$userid."";
				$sql = mysql_query($qry);				
				$row = mysql_fetch_array($sql);		
					
				$fname = $row['fname'];  
				$name = $row['fname']." ".$row['lname'];				     
				$rcv_mail = $row['rcvemail4msgs'];				
				
				$notification="<a href=''viewprofile.php?userid=".$postedbyuserid."''>".$name."</a> commented on your blog.";
				$query1 = "insert into tblnotifications(
							userid, 
							notification_type,
							notification,
							notificationtime) 
							values
							(".$postedbyuserid.",1,'".$notification."','".$datetimeposted."')";						
				if(! mysql_query($query1)) 
				{
						echo mysql_error();
				}
				
				if($rcv_mail)
				{
					$EmailTo = $row['email'];	 
					$EmailFrom = "info@youcureme.com";
					$Emailfrom_name = "YouCureMe.com"; 
					$EmailSubject = "Your blog received a comment !"; 
					$fname = $row['fname'];  
					$EmailMsg = "Hi ".$fname.",<br>									
									Your blog received a comment from youcureme.com
									member on ".$datetimeposted."<br>
									Please click on the following link to read the comment.<br/>".
									"http://www.youcureme.com/blog.php?userid=".$userid."<br/>".
									"YouCureMe.com";		
				
					send_smtpmail($EmailTo, $EmailFrom, $Emailfrom_name, $EmailSubject, $EmailMsg);		
				}
				
				header("location: blog.php?userid=".$userid."&msg=Your comment posted successfully");
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
