<?php include "header_inner.php";

	function thumbnail($image_path, $size = '200x150') {
  list($width, $height) = getimagesize($image_path);
  $image_aspect = $width / $height;
 
  list($thumb_width, $thumb_height) = explode('x', $size);
  $thumb_aspect = $thumb_width / $thumb_height;
 
  if ($image_aspect > $thumb_aspect) {
    $crop_height = $height;
    $crop_width = round($crop_height * $thumb_aspect);
  } else {
    $crop_width = $width;
    $crop_height = round($crop_width / $thumb_aspect);
  }
 
  $crop_x_offset = round(($width - $crop_width) / 2);
  $crop_y_offset = round(($height - $crop_height) / 2);
 
  // crop parameter
  $crop_size = $crop_width.'x'.$crop_height.'+'.$crop_x_offset.'+'.$crop_y_offset;
 
  // thumbnail is created next to original image with th- prefix.
  $thumb = dirname($image_path).'/th-'.basename($image_path);
  exec('convert '. escapeshellarg($image_path).' -crop ' . $crop_size .' -thumbnail '.$size.' '. escapeshellarg($thumb));
 
  return $thumb;
}



$fname='';
$lname='';
$email='';
$password='';
$profilepic='';
$gender="";
$usertype="";
$disease="";
$disease_id = 1;
$city='';
$country="";
$website='';
$iam='';
$ilike='';
$myexperience='';
$age=0;

$req_count=0;
$frnds_count=0;

$uname = '';
$usertypeid = $_SESSION["usertypeid"];
$resourcetypeid = "0";
$diseaseid = "0";
$albumid=0;
$albumname='';

if(!isset($_SESSION["userid"]) || $_SESSION["userid"] =="")
{	//header("location: login.php");   
		echo "<script>location.href='index.php' </script>";

}
else
{
	$userid=$_SESSION["userid"];
	
	/******************************************* LOAD USER INFO **********************************************************/
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
			
	$result=mysql_query($query);
	$num=mysql_num_rows($result);
	if($num>0)
	{
		$row=mysql_fetch_array($result);	
				
		$fname=$row["fname"];
		$lname=$row["lname"];
		$email=$row["email"];
		$password=$row["password"];
		if($row["profilepic"]!="")
			$profilepic=$row["profilepic"];
		else
			$profilepic="empty_profile.jpg";
		$dob=$row["dob"];
		if($row["genderid"]=="1")
			$gender="Male";
		else
			$gender="Female";
		$usertype=$row["usertype"];
		$disease=$row["disease"];
		$city=$row["city"];
		$country=$row["country"];
		$website=$row["website"];
		$iam=$row["iam"];
		$ilike=$row["ilike"];
		$myexperience=$row["myexperience"];
		$isactive=$row["isactive"];	
		$dateofbirth=explode("-",$dob);
		$age=date("Y")-$dateofbirth[0];
	}
	
	$query_nots=" 
	SELECT notificationid,userid,notification_type,notification,notificationtime
	FROM tblnotifications
	WHERE userid = ".$userid." order by notificationtime desc limit 10";
		
/* Now that the new client has been inserted into the database, we will extract all the
	   existing users with matching medical interest and update the notification in their
	   profiles */

	   			$read_msgs=" 
			select 
			msgid ,	msg ,
			(select CONCAT(fname,' ',lname) from tbluser where userid=m.senderid) as username,
			m.senderid as userid,
			(select CONCAT(fname,' ',lname) from tbluser where userid=m.recieverid) as reciever,
			msgtime,senderid,recieverid	
			from tblmsgs m
			where isread = 1 and recieverid = ".$userid;	 
		$sql=mysql_query($read_msgs);							
		$read_msg_count = mysql_num_rows($sql); 
		
		$query_msgs=" 
			select 
			msgid ,	msg , isread,
			(select CONCAT(fname,' ',lname) from tbluser where userid=m.senderid) as username,
			m.senderid as userid,
			(select CONCAT(fname,' ',lname) from tbluser where userid=m.recieverid) as reciever,
			msgtime,senderid,recieverid	
			from tblmsgs m
			where recieverid = ".$userid;	 
			
			$sql=mysql_query($query_msgs);							
			$total_items=mysql_num_rows($sql); 			
			$inbox_items = $total_items - $read_msg_count;	
	                            	
	/* First get the disease id of this user */
	
	$disease_id = 1;

    $qry_disease ="SELECT diseaseid 
    FROM tbluser 
	WHERE userid = ".$userid."";
	$result_disease = mysql_query($qry_disease );
 	if($result_disease)
	{
		$num = mysql_num_rows($result_disease);
		if($num>0)
		{
			$row=mysql_fetch_array($result_disease);
			$disease_id = $row["diseaseid"];				 
		}
	}
	
	/* Get the members count with matching medical interests */
      $disease_match_count = 0;

	$query_disease_count="SELECT COUNT(*)
	FROM tbluser
	WHERE diseaseid = $disease_id";	 
	$result_disease_count=mysql_query($query_disease_count);
	$num_disease_count=mysql_num_rows($result_disease_count);
	if($num_disease_count>0)
	{
		$row_disease_count=mysql_fetch_array($result_disease_count);				
		$disease_match_count = $row_disease_count[0];   
	}
	
	/* Get the Medical Professions and Survivors/councillors count with matching medical interests */
    $ps_match_count = 0;

	$query_ps_count="SELECT COUNT(*)
	FROM tbluser,tbldisease,tblusertype
	WHERE tbluser.userid <> ".$userid."
	AND tbluser.diseaseid = $disease_id	 
	AND tbldisease.diseaseid=tbluser.diseaseid and tblusertype.usertypeid=tbluser.usertypeid
	AND (tbluser.usertypeid=5 OR tbluser.usertypeid=4)";	
	$result_ps_count=mysql_query($query_ps_count);
	$num_ps_count=mysql_num_rows($result_ps_count);
	if($num_ps_count>0)
	{
		$row_ps_count=mysql_fetch_array($result_ps_count);				
		$ps_match_count = $row_ps_count[0];   
	}

	$query_req_count="SELECT COUNT(*)	 
	FROM tblfriends, tbluser,tbldisease,tblusertype
	WHERE tblfriends.userid = ".$userid."
	AND friendshipstatus =1
	AND tblfriends.friendwith = tbluser.userid
	AND tbldisease.diseaseid=tbluser.diseaseid and tblusertype.usertypeid=tbluser.usertypeid";

	$result_req_count=mysql_query($query_req_count);
	$num_req_count=mysql_num_rows($result_req_count);
	if($num_req_count>0)
	{
		$row_req_count=mysql_fetch_array($result_req_count);	
				
		$req_count=$row_req_count[0];//." ".$userid;
	}
		

		$query_frnds_count="SELECT COUNT(*)	
	FROM tblfriends, tbluser,tbldisease,tblusertype
	WHERE tblfriends.userid = ".$userid."	
	AND tbluser.userid <> ".$userid."
	AND friendshipstatus = 2
	AND tblfriends.friendwith = tbluser.userid
	AND tbldisease.diseaseid=tbluser.diseaseid and tblusertype.usertypeid=tbluser.usertypeid ";
	$result_frnds_count=mysql_query($query_frnds_count);
	$num_frnds_count=mysql_num_rows($result_frnds_count);
	
	if($num_frnds_count>0)
	{
		$row_frnds_count=mysql_fetch_array($result_frnds_count);	
	
		$frnds_count=$row_frnds_count[0];//." ".$userid;
	}
	
	
	
} ?>
        <div class="warpper">
        	<div class="left_side">
<!--
				<div class="left_contant">
                	<div class="user_info">
                    	<div class="user_img">
                            <img src="profilepics/<?php echo $profilepic; ?>" width="63" height="59" border="0" />
                        </div>
                        <div class="user_data">
                        	<div class="user_name bluetitle size20 bold" /><?php echo $username; ?></div>
                         	<div class="ul_msg">
                            <ul>
							    <li><a href="messages.php?mode=inbox&page=1">My Messages(<?php echo $inbox_items;?>)</a></li>
                                <li><a href="myblog.php">My Blog</a></li>
                            </ul>
                            </div>   
                        </div>
                    </div>
                    <div class="profile_links">
                    	<div class="title_txt">
                        	<div class="plusimg"><img src="images/plus_icon.jpg" /></div>
                            <div class="txttitle whitetitle size12 bold">My Profile</div>
                        </div>
                        <div class="txt_links">
                        	<ul>
                            	<li><a href="editprofile.php?userid=<?php echo $_SESSION["userid"]; ?>">Account Settings</a></li>
                                <li>
                                    <a href="privacy.php">Privacy Settings</a>
                                </li>
                                <li>
                                    <a href="myprofile.php">My Profile</a>
                                </li>
                                <li>
                                    <a href="photos.php">My Photos</a>
                                </li>
                                <li>
                                    <a href="myblog.php">My Blog</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="profile_links">
                    	<div class="title_txt">
                        	<div class="plusimg"><img src="images/plus_icon.jpg" /></div>
                            <div class="txttitle whitetitle size12 bold">My Messages</div>
                        </div>
                        <div class="txt_links">
                        	<ul>
								<li><a href="messages.php?mode=inbox&page=1">My Messages(<?php echo $inbox_items;?>)</a></li>
                               <li>
                                    <a href="notifications.php">My Notifications</a>
                                </li>
                                
                            </ul>
                        </div>
                    </div>
                    <div class="profile_links">
                    	<div class="title_txt">
                        	<div class="plusimg"><img src="images/plus_icon.jpg" /></div>
                            <div class="txttitle whitetitle size12 bold">Make a Difference</div>
                        </div>
                        <div class="txt_links">
                        	<ul><li><a href="resources.php">Health Resources</a></li>  </ul>
                            <ul>
                                <li>
                                    <a href="survey.php">Surveys</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="profile_links">
                    	<div class="title_txt">
                        	<div class="plusimg"><img src="images/plus_icon.jpg" /></div>
                            <div class="txttitle whitetitle size12 bold">Connect with Others</div>
                        </div>
                        <div class="txt_links">
                            <ul>
                            	<li><a href="members.php">Search All Members</a></li>
								<li>
                                    <a href="myinterestmembers.php">Members With My Interest(<?php echo $disease_match_count-1;?>)</a>
                                </li> 
								<li><a href="reachout.php">Reach Out(<?php echo $ps_match_count;?>)</a></li>
                            </ul>
                        </div>
                    </div>
                     <div class="profile_links">
                    	<div class="title_txt">
                        	<div class="plusimg"><img src="images/plus_icon.jpg" /></div>
                            <div class="txttitle whitetitle size12 bold">My Interest Wall</div>
                        </div>
                        <div class="txt_links">
                            <ul>
                            	<li><a href="interest_wall.php">My Interest Wall</a></li><li><a href="friends_activity.php">My Friends Activity</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="profile_links">
                    	<div class="title_txt">
                        	<div class="plusimg"><img src="images/plus_icon.jpg" /></div>
                            <div class="txttitle whitetitle size12 bold">My Contacts</div>
                        </div>
                        <div class="txt_links">
                        	<ul>
                            	<li><a href="myfriends.php">
                                    My Friends (<?php echo $frnds_count;?>)
                                </a></li>
                                <li>
                                    <a href="importcontacts.php">Import Contacts</a>
                                </li>
                                <li>
                                    <a href="reqs.php">
                                        Friend Requests (<?php echo $req_count;?>)
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
 -->
	<?php require("left_usermenu.php");?>	
                </div>
            </div>

<div class="body_main">
    <div class="body_menu whitetitle">
        <a href="myprofile.php" class="whitetitle size12">Home</a>&nbsp;&nbsp;<span class="size12">>></span>&nbsp;&nbsp;<span class="whitetitle size12">Notifications</span>
    </div>
    <div class="body">
        <div class="main_link">
            <div class="inbox_title">
                <!--<div class="f_left">
                    <img align="absmiddle" src="images/inbox_icon.jpg" />
                </div>-->
                <div class="f_left bluetitle size30 bold" style="padding-top:5px; width:auto;">
                    Notifications
                </div>
                <div class="right_img">
                    <div class="inbox_img">
                        <!--<img src="images/inbox-img.jpg" />
                        <br />
                        <a href="#">Inbox</a>-->
                    </div>
                    <div class="outbox_img">
                        <!--<img src="images/inbox-img.jpg" />
                        <br />
                        <a href="#">Outbox</a>-->
                    </div>
                    <div class="notification_img">
                        <!--<img src="images/inbox-img.jpg" />
                        <br />
                        <a href="#">Notification</a>-->
                        
                    </div>
                </div>
            </div>
            
            <div class="email_table">              
                <table cellpadding="0" cellspacing="0" style="width:686px; border:0px;">
                    <tr>
                        <td style="width:686px;"align="left" valign="top">


                            <ol class="timeline" id="updates">
                                <?php
													
							$sql=mysql_query($query_nots);
							
							$count=mysql_num_rows($sql);	
							$message="";
							if($count>0)
							{							
								while($row=mysql_fetch_array($sql))
								{									
									$notificationid=$row['notificationid'];
									
									$message=$message."<div class='not-general'>";	
										
									$message=$message."<div class='notlist'>";
									$message=$message.$row['notification'];//<br/>";
									$message=$message."</div>";
									
									$message=$message."<div >";/*class='frnd-list-action'*/

									$message=$message."<div class='notifications-date'>
													".$row["notificationtime"]."
												</div>";
									$message=$message."<div style='clear:both;'></div>";
									$message=$message."</div>";  
									
									$message=$message."<div style='clear:both;'></div>";
									
									$message=$message."</div>";//end of req-general
								
							?>
                                <!--<li> -->
                                <?php //echo $message; ?>
                                <!--</li> -->
                                <?php 
								}
							}
							else 
							{
								//echo "<div class='req-general'>No Notifications Found.</div>";
							}
							?>
                            
                                <li>
                                    <?php
									
							echo $message; 
							?>

                                </li>
                            </ol>

                            <div id="morenots<?php echo $notificationid; ?>" class="morebox">
                                <a href="#" class="morenots" id="<?php echo $notificationid; ?>"><img src="images/more.png" alt="more" width="40" height="40" border="0"/>
                                </a>
                            </div>






                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>
            
        </div>	  
        
<?php include "footer.php"; ?>