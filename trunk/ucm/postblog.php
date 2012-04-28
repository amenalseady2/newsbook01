<?php	session_start();
	include "connection.php";
	define('BASE_DIR_DLOAD',$_SERVER['DOCUMENT_ROOT'].'/wallphotos');
	$allowed_ext = array (							
		// images
		'gif' => 'image/gif',
		'png' => 'image/png',
		'jpg' => 'image/jpeg',
		'jpeg' => 'image/jpeg'
		
	);
	
	if($_SERVER['REQUEST_METHOD']=='POST')
	{
		$postedbyuserid=$_POST["postedbyuserid"];
		$postedonuserid=$_POST["postedonuserid"];
		$privacylevel=$_POST["privacylevel"];
		$datetimeposted=date("Y-m-d H:i:s");
		
		$posttext=str_replace("'","''",$_POST["posttext"]);
		$posttext=str_replace("\"","''",$posttext);		
		$posttext=stripslashes($posttext);
		
		$postembedlink=str_replace("'","''",$_POST["embedlink"]);
		$postembedlink=str_replace("\"","''",$postembedlink);		
		$postembedlink=stripslashes($postembedlink);		
		 
		$postimage="";
		if( $_FILES["postimage"]["error"]==0 )
		{
			$file=basename($_FILES["postimage"]["name"]);	
			$e = explode(".", $_FILES['postimage']['name']);
			$extension_img = $e[count($e)-1]; 
			$fileName=$e[count($e)-2]."_".$_SESSION["userid"]."_".date("Y").date("m").date("d").time().".".$extension_img;					
			$postimage=$fileName;
			//echo $postimage;
		}
		
		$postvideo="";
		if( $_FILES["postvideo"]["error"]==0 )
		{
			$file=basename($_FILES["postvideo"]["name"]);	
			$e = explode(".", $_FILES['postvideo']['name']);
			$extension_video = $e[count($e)-1]; 
			$fileName=$e[count($e)-2]."_".$_SESSION["userid"]."_".date("Y").date("m").date("d").time().".".$extension_video;					
			$postvideo=$fileName;
			//echo $postvideo;
		}
		
		/*****************************************/
	
		if ($postimage!="")
		{
			if (array_key_exists($extension_img, $allowed_ext)) 
			{
				if(!move_uploaded_file($_FILES["postimage"]["tmp_name"],BASE_DIR_DLOAD."/".$postimage))
					echo "<script>location.href='myblog.php?msg=Picture uploading failed.' </script>";
			}
			else
				echo "<script>location.href='myblog.php?msg=Profile Picture type is not allowed.' </script>";
		}
		
		if ($postvideo!="")
		{
			if (array_key_exists($extension_video, $allowed_ext)) 
			{
				if(!move_uploaded_file($_FILES["postimage"]["tmp_name"],BASE_DIR_DLOAD."/".$postimage))
					echo "<script>location.href='myblog.php?msg=Video uploading failed.' </script>";
			}
			else
				echo "<script>location.href='myblog.php?msg=Profile Video type is not allowed.' </script>";
		}
						
		try
		{	
			$query="insert into tblblogposts 
					(posttext,
					postimage,
					postvideo,
					postembedvideolink,
					postedbyuserid,
					postedonuserid,
					datetimeposted,
					privacylevel)
					values
					('".$posttext."',
					'".$postimage."',
					'".$postvideo."',
					'".$postembedlink."',
					".$postedbyuserid.",
					".$postedonuserid.",
					'".$datetimeposted."',
					".$privacylevel.")
					";
			
			if(mysql_query($query))
			{
				//make a feed message
				$feed_message = " has posted on <a href='blog.php?userid=$postedbyuserid'>blog</a>";
				
				//insert into my friends activity page
				$query = sprintf("INSERT INTO `tblfeeds`  (`userid`,`message`)VALUES ('%s', '%s')",
							mysql_real_escape_string($postedbyuserid),
							mysql_real_escape_string($feed_message));

											
			  	mysql_query($query);
			  	
				$notification_msg = get_name_link($postedbyuserid) . " has posted on <a href='blog.php?userid=$postedbyuserid'>blog</a>";

				$query_get_friends_ids=sprintf("select friendwith from tblfriends where userid='%s' and friendshipstatus=2",$postedbyuserid);
					
				$result_get_friends_ids =mysql_query($query_get_friends_ids);
				while($_row=mysql_fetch_array($result_get_friends_ids))
				{
					$friendid = $_row['friendwith'];
					
					$notification_query = sprintf("insert into `tblnotifications`	( `userid`, `notification_type`, `notification`, `notificationtime` )
						values	(		'%s',		1,		'%s',		now()	)",
						mysql_real_escape_string($friendid),
						mysql_real_escape_string($notification_msg));
						
					mysql_query($notification_query);		
				}
					
				
				header("location: myblog.php?msg=Your blog post posted successfully");
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

function get_name_link($userid){
	$query="select 
				fname,
				lname,
				thumb_profile as profilepic,
				dob,
				genderid,
				strusertype as usertype,
				strdisease as disease,				
				city,
				CountryName as country,
				email,
				password,
				website,
				iam,
				ilike,
				myexperience,
				isactive
			from tbluser,tblusertype,tbldisease,tblcountry where userid=".$userid." and 
			tblusertype.usertypeid=tbluser.usertypeid and tbldisease.diseaseid=tbluser.diseaseid and tblcountry.countryid=tbluser.countryid";
	$result =	mysql_query($query);
	if(mysql_num_rows($result)>0){
		$row=mysql_fetch_array($result);
		
		$rslt = "<a href='viewprofile.php?userid=".$userid."'>".  $row['fname']." ".$row['lname']."</a>";
		return $rslt;
	}
	else{
		return '';
	}
}
?>
