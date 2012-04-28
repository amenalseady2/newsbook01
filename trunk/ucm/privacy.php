<?php include "header_inner.php";
	require_once('classes/tc_calendar.php');

$thumb_listing='';
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
//$usertypeid = $_SESSION["usertypeid"];
$resourcetypeid = "0";
$name='';

$fname='';
	$lname='';
	$email='';
	$password='';
	$profilepic='';
	$dob='0000-00-00';
	$genderid=1;
	$usertypeid=1;
	$diseaseid=1;
	$city='';
	$countryid=37;
	$website='';
	$iam='';
	$ilike='';
	$myexperience='';
	$isactive=0;
	$usealias=0;
	$alias='';
	$rcvemail4msgs=0;
	$rcvemail4notifications=0;
	$dateofbirth=explode("-",$dob);
$access_name=2;
$access_pic=2;
$access_dob=2;
$access_gender=2;
$access_disease=2;
$access_loc=2;
$access_email=2;
$access_web=2;
$access_iam=2;
$access_ilike=2;
$access_exp=2;
$access_photos=2;
$access_friends=2;
$access_blog=2;
$access_msg=2;


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
				isactive,
				access_name,
				access_pic,
				access_dob,
				access_gender,
				access_disease,
				access_loc,
				access_email,
				access_web,
				access_iam,
				access_ilike,
				access_exp,
				access_photos,
				access_friends,
				access_blog,
				access_msg
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
		
		$access_name=$row["access_name"];
		$access_pic=$row["access_pic"];
		$access_dob=$row["access_dob"];
		$access_gender=$row["access_gender"];
		$access_disease=$row["access_disease"];
		$access_loc=$row["access_loc"];
		$access_email=$row["access_email"];
		$access_web=$row["access_web"];
		$access_iam=$row["access_iam"];
		$access_ilike=$row["access_ilike"];
		$access_exp=$row["access_exp"];
		$access_photos=$row["access_photos"];
		$access_friends=$row["access_friends"];
		$access_blog=$row["access_blog"];
		$access_msg=$row["access_msg"];
		
	}

		/*******************************************************************************************************************/
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
	/* Get the Medical Professions and Survivors/councillors count with matching medical interests */
    $ps_match_count = 0;

	$query_ps_count="SELECT COUNT(*)
	FROM tbluser,tbldisease,tblusertype
	WHERE tbluser.userid <> ".$userid."
	AND tbluser.diseaseid = $disease_id	 
	AND tbldisease.diseaseid=tbluser.diseaseid and tblusertype.usertypeid=tbluser.usertypeid
	AND (tbluser.usertypeid=5 OR tbluser.usertypeid=4)" ;	
	$result_ps_count=mysql_query($query_ps_count);
	$num_ps_count=mysql_num_rows($result_ps_count);
	if($num_ps_count>0)
	{
		$row_ps_count=mysql_fetch_array($result_ps_count);				
		$ps_match_count = $row_ps_count[0];   
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
	
	$query1 = '';
	$where = '';
		
	if(!isset($_SESSION["userid"]) || $_SESSION["userid"] =="")
	{	
		header("location: index.php");
		echo "<script>location.href='index.php' </script>";   
	}
	else
	{
		
		
		
	}
	
	
} ?>
        <div class="warpper">
        	<div class="left_side">
            	<!--<div class="left_contant">
                	<div class="user_info">
                    	<div class="user_img">
                        	<!--<img src="images/user-img.jpg" />
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
								<li>
									<a href="myinterestmembers.php">
									Members With My Interest (<?php echo $disease_match_count-1;?>)
									</a>
                                </li> 
                            </ul>
                        </div>
                    </div>
                    <div class="profile_links">
                    	<div class="title_txt">
                        	<div class="plusimg"><img src="images/plus_icon.jpg" /></div>
                            <div class="txttitle whitetitle size12 bold">Social medical discovery</div>
                        </div>
                        <div class="txt_links">
                        	<ul>
                            	<li><a href="members.php">Search All Members</a></li>
                            </ul>
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
                            <div class="txttitle whitetitle size12 bold">Health Centre</div>
                        </div>
                        <div class="txt_links">
                        	<ul>
                            	<li><a href="resources.php">Health Resources</a></li>
                                <!-- <li><a href="medicalhistory.php">Medical History</a></li> 
								<li><a href="reachout.php">Reach Out(<?php echo $ps_match_count;?>)</a></li>
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
                    </div>-->
                     <?php require("left_usermenu.php");?>	
                </div>
            </div>
			
            <div class="body_main">
                <div class="body_menu whitetitle">
                    <a href="myprofile.php" class="whitetitle size12">Home</a>&nbsp;&nbsp;<span class="size12">>></span>&nbsp;&nbsp;<span class="whitetitle size12">Privacy Settings</span>
                </div>

                <div class="body">
                    <div class="main_link">
					
                        <div class="inbox_title">                            
                            <div class="f_left bluetitle size30 bold" style="padding-top:5px; width:auto;">Privacy Settings</div>
                            <div class="right_img">
                                <div class="inbox_img">                                    
                                </div>
                                <div class="outbox_img">                                   
                                </div>
                                <div class="notification_img">                                    
                                </div>
                            </div>
                        </div>

                        <div class="email_table">
                            <table cellpadding="0" cellspacing="0" style="width:686px; border:0px;">
                                <form action="updateprivacy.php" method="post" enctype="multipart/form-data" >
                                    <input type="hidden" id="userid" name="userid" value="<?php echo $userid; ?>" />
									
                                        <tr>
                                            <td style="width:36px; height:12px;" colspan="5" align="left" valign="top"></td>
                                        </tr>
                                        <tr>
                                            <td style="width:561px;" align="left" colspan="5" class="bluetitle size14 bold">
                                                Control Your Profile Privacy Here
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="width:36px; height:15px;" colspan="5" align="left" valign="top"></td>
                                        </tr>

										<tr>
											<td style="width:130px;" align="left" class="bold size12">&nbsp;</td>
											<td style="width:230px;" align="center" class="bold size12">
												<?php echo $item['privacy_pvt']; ?>
											</td>
											<td  style="width:230px;" align="center" class="bold size12">
												<?php echo $item['privacy_pub']; ?>
											</td>
											<td  style="width:230px;" align="center" class="bold size12">
												<?php echo $item['privacy_frnd']; ?>
											</td>
										</tr>
                                    
										<tr>
											<td style="width:36px; height:3px;" colspan="5" align="left" valign="top"></td>
										</tr>
										
										<input type="hidden"  id="access_name" name="access_name" value="2">
										
										<tr>
                                            <td style="width:36px; height:3px;" colspan="5" align="left" valign="top"></td>
										</tr>

										<tr>
                                            <td style="width:130px;" align="left" class="bold size12">
                                                <?php echo $item['privacy_item2']; ?>
                                            </td>
                                            <td   style="width:230px;" align="center" class="bold size12">
                                                <input type="radio"  id="access_pic" name="access_pic" value="1" <?php if($access_pic=="1") { echo " checked='checked' "; } ?>>
                                            </td>
                                            <td   style="width:230px;" align="center" class="bold size12">
                                                <input type="radio"  id="access_pic" name="access_pic" value="2" <?php if($access_pic=="2") { echo " checked='checked' "; } ?>>
                                            </td>
                                            <td   style="width:230px;" align="center" class="bold size12">
                                                <input type="radio"  id="access_pic" name="access_pic" value="3" <?php if($access_pic=="3") { echo " checked='checked' "; } ?>>
                                            </td>
                                            <td>&nbsp;</td>
										</tr>

                                        <tr>
                                            <td style="width:36px; height:3px;" colspan="5" align="left" valign="top"></td>
                                        </tr>

                                        <tr>
                                            <td style="width:130px;" align="left" class="bold size12">
                                                <?php echo $item['privacy_item3']; ?>
                                            </td>
                                            <td   style="width:230px;" align="center" class="bold size12">
                                                <input type="radio"  id="access_dob" name="access_dob" value="1" <?php if($access_dob=="1") { echo " checked='checked' "; } ?>>
                                            </td>
                                            <td   style="width:230px;" align="center" class="bold size12">
                                                <input type="radio"  id="access_dob" name="access_dob" value="2" <?php if($access_dob=="2") { echo " checked='checked' "; } ?>>
                                            </td>
                                            <td   style="width:230px;" align="center" class="bold size12">
                                                <input type="radio"  id="access_dob" name="access_dob" value="3" <?php if($access_dob=="3") { echo " checked='checked' "; } ?>>
                                            </td>
                                            <td>&nbsp;</td>
                                        </tr>

                                        <tr>
                                            <td style="width:36px; height:3px;" colspan="5" align="left" valign="top"></td>
                                        </tr>

                                        <tr>
                                            <td style="width:130px;" align="left" class="bold size12">
                                                <?php echo $item['privacy_item4']; ?>
                                            </td>
                                            <td   style="width:230px;" align="center" class="bold size12">
                                                <input type="radio"  id="access_gender" name="access_gender" value="1" <?php if($access_gender=="1") { echo " checked='checked' "; } ?>>
                                            </td>
                                            <td   style="width:230px;" align="center" class="bold size12">
                                                <input type="radio"  id="access_gender" name="access_gender" value="2" <?php if($access_gender=="2") { echo " checked='checked' "; } ?>>
                                            </td>
                                            <td   style="width:230px;" align="center" class="bold size12">
                                                <input type="radio"  id="access_gender" name="access_gender" value="3" <?php if($access_gender=="3") { echo " checked='checked' "; } ?>>
                                            </td>
                                            <td>&nbsp;</td>
                                        </tr>

                                        <tr>
                                            <td style="width:36px; height:3px;" colspan="5" align="left" valign="top"></td>
                                        </tr>

                                        <tr>
                                            <td style="width:130px;" align="left" class="bold size12">
                                                <?php echo $item['privacy_item5']; ?>
                                            </td>
                                            <td   style="width:230px;" align="center" class="bold size12">
                                                <input type="radio"  id="access_disease" name="access_disease" value="1" <?php if($access_disease=="1") { echo " checked='checked' "; } ?>>
                                            </td>
                                            <td   style="width:230px;" align="center" class="bold size12">
                                                <input type="radio"  id="access_disease" name="access_disease" value="2" <?php if($access_disease=="2") { echo " checked='checked' "; } ?>>
                                            </td>
                                            <td   style="width:230px;" align="center" class="bold size12">
                                                <input type="radio"  id="access_disease" name="access_disease" value="3" <?php if($access_disease=="3") { echo " checked='checked' "; } ?>>
                                            </td>
                                            <td>&nbsp;</td>
                                        </tr>

                                        <tr>
                                            <td style="width:36px; height:3px;" colspan="5" align="left" valign="top"></td>
                                        </tr>

                                        <tr>
                                            <td style="width:130px;" align="left" class="bold size12">
                                                <?php echo $item['privacy_item6']; ?>
                                            </td>
                                            <td   style="width:230px;" align="center" class="bold size12">
                                                <input type="radio"  id="access_loc" name="access_loc" value="1" <?php if($access_loc=="1") { echo " checked='checked' "; } ?>>
                                            </td>
                                            <td   style="width:230px;" align="center" class="bold size12">
                                                <input type="radio"  id="access_loc" name="access_loc" value="2" <?php if($access_loc=="2") { echo " checked='checked' "; } ?>>
                                            </td>
                                            <td   style="width:230px;" align="center" class="bold size12">
                                                <input type="radio"  id="access_loc" name="access_loc" value="3" <?php if($access_loc=="3") { echo " checked='checked' "; } ?>>
                                            </td>
                                            <td>&nbsp;</td>
                                        </tr>

                                        <tr>
                                            <td style="width:36px; height:3px;" colspan="5" align="left" valign="top"></td>
                                        </tr>

                                        <tr>
                                            <td style="width:130px;" align="left" class="bold size12">
                                                <?php echo $item['privacy_item7']; ?>
                                            </td>
                                            <td   style="width:230px;" align="center" class="bold size12">
                                                <input type="radio"  id="access_email" name="access_email" value="1" <?php if($access_email=="1") { echo " checked='checked' "; } ?>>
                                            </td>
                                            <td   style="width:230px;" align="center" class="bold size12">
                                                <input type="radio"  id="access_email" name="access_email" value="2" <?php if($access_email=="2") { echo " checked='checked' "; } ?>>
                                            </td>
                                            <td   style="width:230px;" align="center" class="bold size12">
                                                <input type="radio"  id="access_email" name="access_email" value="3" <?php if($access_email=="3") { echo " checked='checked' "; } ?>>
                                            </td>
                                            <td>&nbsp;</td>
                                        </tr>

                                        <tr>
                                            <td style="width:36px; height:3px;" colspan="5" align="left" valign="top"></td>
                                        </tr>

                                        <tr>
                                            <td style="width:130px;" align="left" class="bold size12">
                                                <?php echo $item['privacy_item8']; ?>
                                            </td>
                                            <td   style="width:230px;" align="center" class="bold size12">
                                                <input type="radio"  id="access_web" name="access_web" value="1" <?php if($access_web=="1") { echo " checked='checked' "; } ?>>
                                            </td>
                                            <td   style="width:230px;" align="center" class="bold size12">
                                                <input type="radio"  id="access_web" name="access_web" value="2" <?php if($access_web=="2") { echo " checked='checked' "; } ?>>
                                            </td>
                                            <td   style="width:230px;" align="center" class="bold size12">
                                                <input type="radio"  id="access_web" name="access_web" value="3" <?php if($access_web=="3") { echo " checked='checked' "; } ?>>
                                            </td>
                                            <td>&nbsp;</td>
                                        </tr>

                                        <tr>
                                            <td style="width:36px; height:3px;" colspan="5" align="left" valign="top"></td>
                                        </tr>

                                        <tr>
                                            <td style="width:130px;" align="left" class="bold size12">
                                                <?php echo $item['privacy_item9']; ?>
                                            </td>
                                            <td   style="width:230px;" align="center" class="bold size12">
                                                <input type="radio"  id="access_iam" name="access_iam" value="1" <?php if($access_iam=="1") { echo " checked='checked' "; } ?>>
                                            </td>
                                            <td   style="width:230px;" align="center" class="bold size12">
                                                <input type="radio"  id="access_iam" name="access_iam" value="2" <?php if($access_iam=="2") { echo " checked='checked' "; } ?>>
                                            </td>
                                            <td   style="width:230px;" align="center" class="bold size12">
                                                <input type="radio"  id="access_iam" name="access_iam" value="3" <?php if($access_iam=="3") { echo " checked='checked' "; } ?>>
                                            </td>
                                            <td>&nbsp;</td>
                                        </tr>

                                        <tr>
                                            <td style="width:36px; height:3px;" colspan="5" align="left" valign="top"></td>
                                        </tr>

                                        <tr>
                                            <td style="width:130px;" align="left" class="bold size12">
                                                <?php echo $item['privacy_item10']; ?>
                                            </td>
                                            <td   style="width:230px;" align="center" class="bold size12">
                                                <input type="radio"  id="access_ilike" name="access_ilike" value="1" <?php if($access_ilike=="1") { echo " checked='checked' "; } ?>>
                                            </td>
                                            <td   style="width:230px;" align="center" class="bold size12">
                                                <input type="radio"  id="access_ilike" name="access_ilike" value="2" <?php if($access_ilike=="2") { echo " checked='checked' "; } ?>>
                                            </td>
                                            <td   style="width:230px;" align="center" class="bold size12">
                                                <input type="radio"  id="access_ilike" name="access_ilike" value="3" <?php if($access_ilike=="3") { echo " checked='checked' "; } ?>>
                                            </td>
                                            <td>&nbsp;</td>
                                        </tr>

                                        <tr>
                                            <td style="width:36px; height:3px;" colspan="5" align="left" valign="top"></td>
                                        </tr>

                                        <tr>
                                            <td style="width:130px;" align="left" class="bold size12">
                                                <?php echo $item['privacy_item11']; ?>
                                            </td>
                                            <td   style="width:230px;" align="center" class="bold size12">
                                                <input type="radio"  id="access_exp" name="access_exp" value="1" <?php if($access_exp=="1") { echo " checked='checked' "; } ?>>
                                            </td>
                                            <td   style="width:230px;" align="center" class="bold size12">
                                                <input type="radio"  id="access_exp" name="access_exp" value="2" <?php if($access_exp=="2") { echo " checked='checked' "; } ?>>
                                            </td>
                                            <td   style="width:230px;" align="center" class="bold size12">
                                                <input type="radio"  id="access_exp" name="access_exp" value="3" <?php if($access_exp=="3") { echo " checked='checked' "; } ?>>
                                            </td>
                                            <td>&nbsp;</td>
                                        </tr>

                                        <tr>
                                            <td style="width:36px; height:3px;" colspan="5" align="left" valign="top"></td>
                                        </tr>

                                        <tr>
                                            <td style="width:130px;" align="left" class="bold size12">
                                                <?php echo $item['privacy_item15']; ?>
                                            </td>
                                            <td   style="width:230px;" align="center" class="bold size12">
                                                <input type="radio"  id="access_msg" name="access_msg" value="1" disabled="disabled">
                                            </td>
                                            <td   style="width:230px;" align="center" class="bold size12">
                                                <input type="radio"  id="access_msg" name="access_msg" value="2" <?php if($access_msg=="2") { echo " checked='checked' "; } ?>>
                                            </td>
                                            <td   style="width:230px;" align="center" class="bold size12">
                                                <input type="radio"  id="access_msg" name="access_msg" value="3" <?php if($access_msg=="3") { echo " checked='checked' "; } ?>>
                                            </td>
                                            <td>&nbsp;</td>
                                        </tr>

                                        <tr>
                                            <td style="width:36px; height:3px;" colspan="5" align="left" valign="top"></td>
                                        </tr>

                                        <tr style="display:none;">
                                            <td style="width:130px;" align="left" class="bold size12">
                                                <?php echo $item['privacy_item12']; ?>
                                            </td>
                                            <td   style="width:230px;" align="center" class="bold size12">
                                                <input type="radio"  id="access_photos" name="access_photos" value="1" <?php if($access_photos=="1") { echo " checked='checked' "; } ?>>
                                            </td>
                                            <td   style="width:230px;" align="center" class="bold size12">
                                                <input type="radio"  id="access_photos" name="access_photos" value="2" <?php if($access_photos=="2") { echo " checked='checked' "; } ?>>
                                            </td>
                                            <td   style="width:230px;" align="center" class="bold size12">
                                                <input type="radio"  id="access_photos" name="access_photos" value="3" <?php if($access_photos=="3") { echo " checked='checked' "; } ?>>
                                            </td>
                                            <td>&nbsp;</td>
                                        </tr>

                                        <tr>
                                            <td style="width:36px; height:3px;" colspan="5" align="left" valign="top"></td>
                                        </tr>

                                        <tr>
                                            <td style="width:130px;" align="left" class="bold size12">
                                                <?php echo $item['privacy_item13']; ?>
                                            </td>
                                            <td   style="width:230px;" align="center" class="bold size12">
                                                <input type="radio"  id="access_friends" name="access_friends" value="1" <?php if($access_friends=="1") { echo " checked='checked' "; } ?>>
                                            </td>
                                            <td   style="width:230px;" align="center" class="bold size12">
                                                <input type="radio"  id="access_friends" name="access_friends" value="2" <?php if($access_friends=="2") { echo " checked='checked' "; } ?>>
                                            </td>
                                            <td   style="width:230px;" align="center" class="bold size12">
                                                <input type="radio"  id="access_friends" name="access_friends" value="3" <?php if($access_friends=="3") { echo " checked='checked' "; } ?>>
                                            </td>
                                            <td>&nbsp;</td>
                                        </tr>

                                        <tr>
                                            <td style="width:36px; height:3px;" colspan="5" align="left" valign="top"></td>
                                        </tr>

                                        <tr>
                                            <td style="width:130px;" align="left" class="bold size12">
                                                <?php echo $item['privacy_item14']; ?>
                                            </td>
                                            <td   style="width:230px;" align="center" class="bold size12">
                                                <input type="radio"  id="access_blog" name="access_blog" value="1" disabled="disabled">
                                            </td>
                                            <td   style="width:230px;" align="center" class="bold size12">
                                                <input type="radio"  id="access_blog" name="access_blog" value="2" <?php if($access_blog=="2") { echo " checked='checked' "; } ?>>
                                            </td>
                                            <td   style="width:230px;" align="center" class="bold size12">
                                                <input type="radio"  id="access_blog" name="access_blog" value="3" <?php if($access_blog=="3") { echo " checked='checked' "; } ?>>
                                            </td>
                                            <td>&nbsp;</td>
                                        </tr>

                                        <tr>
                                            <td style="width:36px; height:10px;" colspan="5" align="left" valign="top"></td>
                                        </tr>

                                        <tr>
                                            <td >&nbsp;</td>
                                            <td >&nbsp;</td>
                                            <td align="center" >
                                                <input id="submit-subscirbe" type="submit" value="Save" />
                                            </td>
                                            <td >&nbsp;</td>
                                            <td>&nbsp;</td>
                                        </tr>  
									</form> 
									
                                    <tr>
                                        <td style="width:36px;" align="left" valign="top">&nbsp;</td>
                                        <td style="width:125px;" align="left" valign="top">&nbsp;</td>
                                        <td style="width:319px;" align="left" valign="top">&nbsp;</td>
                                        <td style="width:103px;" align="left" valign="top">&nbsp;</td>
                                        <td style="width:103px;" align="left" valign="top">&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td style="width:36px;" align="left" valign="top">&nbsp;</td>
                                        <td style="width:125px;" align="left" valign="top">&nbsp;</td>
                                        <td style="width:319px;" align="left" valign="top">&nbsp;</td>
                                        <td style="width:103px;" align="left" valign="top">&nbsp;</td>
                                        <td style="width:103px;" align="left" valign="top">&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td style="width:36px;" align="left" valign="top">&nbsp;</td>
                                        <td style="width:125px;" align="left" valign="top">&nbsp;</td>
                                        <td style="width:319px;" align="left" valign="top">&nbsp;</td>
                                        <td style="width:103px;" align="left" valign="top">&nbsp;</td>
                                        <td style="width:103px;" align="left" valign="top">&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td style="width:36px;" align="left" valign="top">&nbsp;</td>
                                        <td style="width:125px;" align="left" valign="top">&nbsp;</td>
                                        <td style="width:319px;" align="left" valign="top">&nbsp;</td>
                                        <td style="width:103px;" align="left" valign="top">&nbsp;</td>
                                        <td style="width:103px;" align="left" valign="top">&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td style="width:36px;" align="left" valign="top">&nbsp;</td>
                                        <td style="width:125px;" align="left" valign="top">&nbsp;</td>
                                        <td style="width:319px;" align="left" valign="top">&nbsp;</td>
                                        <td style="width:103px;" align="left" valign="top">&nbsp;</td>
                                        <td style="width:103px;" align="left" valign="top">&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td style="width:36px;" align="left" valign="top">&nbsp;</td>
                                        <td style="width:125px;" align="left" valign="top">&nbsp;</td>
                                        <td style="width:319px;" align="left" valign="top">&nbsp;</td>
                                        <td style="width:103px;" align="left" valign="top">&nbsp;</td>
                                        <td style="width:103px;" align="left" valign="top">&nbsp;</td>
                                    </tr>
                            </table> 
						</div> 
                    </div>
                </div> 
          </div>
        </div>	   
<?php include "footer.php"; ?>