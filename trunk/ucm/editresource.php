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

		/*******************************************************************************************************************/
	/* Now that the new client has been inserted into the database, we will extract all the
	   existing users with matching medical interest and update the notification in their
	   profiles */

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
	AND (tbluser.usertypeid=3 OR tbluser.usertypeid=4)";	
	$result_ps_count=mysql_query($query_ps_count);
	$num_ps_count=mysql_num_rows($result_ps_count);
	if($num_ps_count>0)
	{
		$row_ps_count=mysql_fetch_array($result_ps_count);				
		$ps_match_count = $row_ps_count[0];   
	}

	$query_req_count="SELECT COUNT(*)
	FROM tblfriends
	WHERE tblfriends.userid = ".$userid."
	AND friendshipstatus =1";
	$result_req_count=mysql_query($query_req_count);
	$num_req_count=mysql_num_rows($result_req_count);
	if($num_req_count>0)
	{
		$row_req_count=mysql_fetch_array($result_req_count);	
				
		$req_count=$row_req_count[0];//." ".$userid;
	}
		

	$query_frnds_count="SELECT COUNT(*)
	FROM tblfriends
	WHERE tblfriends.userid = ".$userid."
	AND friendshipstatus = 2";
	$result_frnds_count=mysql_query($query_frnds_count);
	$num_frnds_count=mysql_num_rows($result_frnds_count);
	if($num_frnds_count>0)
	{
		$row_frnds_count=mysql_fetch_array($result_frnds_count);	
				
		$frnds_count=$row_frnds_count[0];//." ".$userid;
	}
	
	
	$query1 = '';
	$query2 = '';
	$query3 = '';
	$query4 = '';
	$query5 = '';
	$query6 = '';
	$where = '';
		
	if(!isset($_SESSION["userid"]) || $_SESSION["userid"] =="")
	{	
		header("location: index.php");
		echo "<script>location.href='index.php' </script>";   
	}
	else
	{
		$fname='';
	$lname='';
	$strresourcetype='';
	$strdisease='';	
	$subject='';
	$description='';
	$link='';
	$dateposted='';
	$postedby=0;
		
	$heading_title=$item['Profile_title'];
	$heading_msg=$item['Profile_msg'];

	if(isset($_GET["resourceid"]))
	{
		$resourceid=$_GET["resourceid"];
		$query="select 
		        resourceid,
				postedby,
				subject,
				description,
				resourcetypeid,
				diseaseid,
				link,
				embedvideolink,
				dateposted
				from tblresources where resourceid=".$resourceid;
		$result=mysql_query($query);
		$num=mysql_num_rows($result);
		if($num>0)
		{
			$row=mysql_fetch_array($result);	
					
			$postedby=$row["postedby"];
			$subject=$row["subject"];
			$description=$row["description"];
			$resourcetypeid=$row["resourcetypeid"];
			$diseaseid=$row["diseaseid"];
			$link=$row["link"];
			$embedvideolink = $row["embedvideolink"];
			$dateposted=$row["dateposted"];
			$dateposted=explode("-",$dateposted);
		}
	}
	}
	
	
} ?>
        <div class="warpper">
        	<div class="left_side">
            	<div class="left_contant">
                	<div class="user_info">
                    	<div class="user_img">
                        	<img src="profilepics/<?php echo $profilepic; ?>" width="63" height="59" border="0" />
                        </div>
                        <div class="user_data">
                        	<div class="user_name bluetitle size20 bold" /><?php echo $username; ?></div>
                         	<div class="ul_msg">
                            <ul>
                            	<li><a href="messages.php?mode=inbox">My Messages</a></li>
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
                            	<li><a href="messages.php?mode=inbox">My Messages</a></li>
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
                                <li><a href="medicalhistory.php">Medical History</a></li>
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
                    </div>
                </div>
            </div>

<div class="body_main">
    <div class="body_menu whitetitle">
        <a href="myprofile.php" class="whitetitle size12">Home</a>&nbsp;&nbsp;<span class="size12">>></span>&nbsp;&nbsp;<a href="resources.php">Resources</a>&nbsp;&nbsp;<span class="size9">>></span>&nbsp;&nbsp;<span class="whitetitle size12">Edit Resource</span>
    </div>
    <div class="body">
        <div class="main_link">
            <div class="inbox_title">
                <!--<div class="f_left">
                    <img align="absmiddle" src="images/inbox_icon.jpg" />
                </div>-->
                <div class="f_left bluetitle size30 bold" style="padding-top:5px; width:auto;">Resource</div>
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
                <form action="res_update.php" method="post" enctype="multipart/form-data" id="customForm" >
                    <input type="hidden" id="resourceid" name="resourceid" value="<?php echo $resourceid; ?>" />
                    <tr>
                        <tr>
                            <td style="width:36px; height:12px; " colspan="5" align="left" valign="top"></td>
                        </tr>
                        
                        <td style="width:125px;" align="left" class="bold size12">
                            <?php echo "Subject :"; ?>
                        </td>
                        <td colspan="4" style="width:561px;" align="left" class="size12">
                            <input type="text" name="subject" id="subject" style="width:120px; border:1px #bcbcbc solid; height:16px; padding-left:3px;" value="<?php echo $subject; ?>" />&nbsp;&nbsp;<span id="msgsubject"></span>
                        </td>
                    </tr>
                    <tr>
                        <td style="width:36px; height:3px; " colspan="5" align="left" valign="top"></td>
                    </tr>
                    
                    <tr>

                        <td style="width:125px;" align="left" class="bold size12">
                            <?php echo "Resource Type :"; ?>
                        </td>
                        <td colspan="4" style="width:561px;" align="left" class="size12">
                            <select name="resourcetypeid" id="resourcetypeid" style="width:120px; border:1px #bcbcbc solid; height:22px; padding-left:3px;">
                                <?php    
										$q="select resourcetypeid,strresourcetype from tblresourcetype order by strresourcetype";	
										$r=mysql_query($q);
										if($r)
										{	
											$n=mysql_num_rows($r);
											if($n>0)
											{			
												$count=0;	
												while($rw=mysql_fetch_array($r))
												{
													echo "<option value='".$rw["resourcetypeid"]."'";
													if ($rw["resourcetypeid"]==$resourcetypeid) echo "selected='selected'";
													echo " >".$rw["strresourcetype"]."</option>";
													$count++;			
												}
											}
										} 
									?>
                            </select>
                        </td>
                    </tr>
                   <tr>
                        <td style="width:36px; height:3px; " colspan="5" align="left" valign="top"></td>
                    </tr>                    
                    <tr>

                        <td style="width:125px;" align="left" class="bold size12">
                            <?php echo "Interest :"; ?>
                        </td>
                        <td colspan="4" style="width:561px;" align="left" class="size12">
                            <select name="diseaseid" id="diseaseid" style="width:120px; border:1px #bcbcbc solid; height:22px; padding-left:3px;">
                                <?php    										
										$q="select diseaseid,strdisease from tbldisease where diseaseid<>15 order by strdisease";										
										$r=mysql_query($q);
										if($r)
										{	
											$n=mysql_num_rows($r);
											if($n>0)
											{			
												$count=0;	
												while($rw=mysql_fetch_array($r))
												{
													echo "<option value='".$rw["diseaseid"]."'";
													if ($rw["diseaseid"]==$diseaseid) echo "selected='selected'";
													echo " >".$rw["strdisease"]."</option>";
													$count++;			
												}
											}
										} 
								?>	
									<option value="15"
										<?php if ($diseaseid=="15") echo "selected='selected'"; ?>>Other
                                    </option>									
                            </select>
                        </td>
                    </tr>
					
					
                    <tr>
                        <td style="width:36px; height:3px;" colspan="5" align="left" valign="top"></td>
                    </tr>
                    <tr>
                        <td style="width:125px;" align="left" class="bold size12">
                            <?php echo "Link (URL) :"; ?>
                        </td>
                        <td colspan="4" style="width:561px;" align="left" class="size12">
                            <input type="text" name="link" id="link" style="width:248px; border:1px #bcbcbc solid; height:16px; padding-left:3px;" value="<?php echo $link; ?>" />&nbsp;&nbsp;<span id="msgsubject"></span>
                        </td>
                    </tr>
					
 	                <tr>
                        <td style="width:36px; height:3px;" colspan="5" align="left" valign="top"></td>
                    </tr>
					
					<?php if ($resourcetypeid == 1) {  ?>
                    <tr>
                        <td style="width:125px;" align="left" class="bold size12">
                            <?php echo "Embeded Video:";?>
                        </td>
						<td colspan="4" style="width:561px;" align="left" class="size12">
                            <textarea name="evlink" id="evlink" style="width:248px; border:1px #bcbcbc solid; height:60px; padding-left:3px;"><?php echo $embedvideolink; ?></textarea>
							<!--	<input type="text" name="evlink" id="evlink" style="width:248px; border:1px #bcbcbc solid; height:40px; padding-left:3px;" value="<?php echo $embedvideolink; ?>" /> -->
                        </td>  
 					</tr>
					<?php }  ?> 
                    <tr>
                        <td style="width:36px; height:3px; " colspan="5" align="left" valign="top"></td>
                    </tr>
                    <tr>

                        <td style="width:125px;" align="left" class="bold size12">
                            <?php echo "Description/Details :"; ?>
                        </td>
                        <td colspan="4" style="width:561px;" align="left" class="size12">
                            <textarea style="height: 120px; width: 248px; border:1px #bcbcbc solid;  padding-left:3px;" id="description" name="description" rows="3" cols="50"><?php echo $description; ?></textarea>&nbsp;&nbsp;<span id="msgdescription"></span>

                        </td>
                    </tr>
                    <tr>
                        <td style="width:36px; height:3px;" colspan="5" align="left" valign="top"></td>
                    </tr>

                    <td style="width:125px;" align="left" class="bold size12">
                        &nbsp;
                    </td>
                    <td colspan="4" style="width:561px;" align="left" >
                        <input id="submit-subscirbe" type="submit" value="Save" />
                    </td>
                    </tr>
                    <tr>
                        <td style="width:36px; height:3px;" colspan="5" align="left" valign="top"></td>
                    </tr>

                </form>
                </table>
            </div>
        </div>
    </div>
</div>
            
        </div>	  
        
<?php include "footer.php"; ?>