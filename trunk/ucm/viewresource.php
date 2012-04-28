<?php include "header_inner.php";

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
		$query="
		select 
		resourceid,postedby,dateposted,link,description,subject,tblresources.resourcetypeid,embedvideolink,strresourcetype,fname,lname
        from tblresourcetype,tblresources,tbluser 
        where tblresources.resourcetypeid=tblresourcetype.resourcetypeid and tblresources.postedby=tbluser.userid
        and resourceid=".$resourceid;
        //echo $query;
		$result=mysql_query($query);
		$num=mysql_num_rows($result);
		if($num>0)
		{
			$row=mysql_fetch_array($result);	
				
			$postedby=$row["postedby"];
			$fname=$row["fname"];
			$subject=$row["subject"];
			$description=$row["description"];
			$lname=$row["lname"];
			$link=$row["link"];
			$embedvideolink = $row["embedvideolink"];
			$dateposted=$row["dateposted"];
			$strresourcetype=$row["strresourcetype"];
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
                                <!--<li><a href="medicalhistory.php">Medical History</a></li>-->
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
        <a href="myprofile.php" class="whitetitle size12">Home</a>&nbsp;&nbsp;<span class="size12">>></span>&nbsp;&nbsp;<a href="resources.php">Resources</a>&nbsp;&nbsp;<span class="size9">>></span>&nbsp;&nbsp;<span class="whitetitle size12">View Resource</span>
    </div>
    <div class="body">
        <div class="main_link">
            <div class="inbox_title">
                <!--<div class="f_left">
                    <img align="absmiddle" src="images/inbox_icon.jpg" />
                </div>-->
                <div class="f_left bluetitle size30 bold" style="padding-top:5px; width:auto;">Resources</div>
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
                        <tr>
                            <td style="width:36px; height:12px; " colspan="5" align="left" valign="top"></td>
                        </tr>
                        
                        <td style="width:125px;" align="left" class="bold size12">
                            <?php echo "Subject :"; ?>
                        </td>
                        <td colspan="4" style="width:561px;" align="left" class="size12">
                                <?php echo $subject; ?>
                        </td>
                    </tr>
                    <tr>
                        <td style="width:36px; height:3px; " colspan="5" align="left" valign="top"></td>
                    </tr>
                    <tr>

                        <td style="width:125px;" align="left" class="bold size12">
                            <?php echo "Posted By :"; ?>
                        </td>
                        <td colspan="4" style="width:561px;" align="left" class="size12">
                            <a class="bluelink" href="viewprofile.php?userid=<?php echo $postedby; ?>"><?php echo $fname." ".$lname; ?></a>
                        </td>
                    </tr>
                    <tr>
                        <td style="width:36px; height:3px;" colspan="5" align="left" valign="top"></td>
                    </tr>
                    <tr>

                        <td style="width:125px;" align="left" class="bold size12">
                            <?php echo "Date :"; ?>
                        </td>
                        <td colspan="4" style="width:561px;" align="left" class="size12">
                            <?php echo $dateposted; ?>
                        </td>
                    </tr>
                    <tr>
                        <td style="width:36px; height:3px;;" colspan="5" align="left" valign="top"></td>
                    </tr>
                    <tr>

                        <td style="width:125px;" align="left" class="bold size12">
                            <?php echo "Resource Type :"; ?>
                        </td>
                        <td colspan="4" style="width:561px;" align="left" class="size12">
                            <?php echo $strresourcetype; ?>
                        </td>
                    </tr>
                    <tr>
                        <td style="width:36px; height:3px;" colspan="5" align="left" valign="top"></td>
                    </tr>
                    <tr>
                        <td style="width:125px;" align="left" class="bold size12">
                            <?php echo "Link :"; ?>
                        </td>
                        <td colspan="4" style="width:561px;" align="left" class="size12">
                            <a class="bluelink" href="<?php echo $link; ?>">
                                <?php echo $link; ?> <br /><br />
                            </a>
                        </td>
                    </tr>
					
					 
                    <tr>
                        <td style="width:125px;" align="left" class="bold size12">
                            <?php echo "Embeded Video:"; ?><br />
                        </td>
                        <td colspan="4" style="width:561px;" align="left" class="size12">
                            <a class="bluelink" href="<?php echo $embedvideolink; ?>">
                                <?php echo $embedvideolink; ?>                        </a>    
                        </td> 
                    </tr> 
					 
					
                    <tr>
                        <td style="width:36px; height:3px; " colspan="5" align="left" valign="top"></td>
                    </tr>
                    <tr>

                        <td style="width:125px;" align="left" class="bold size12">
                            <br /><br /><?php echo "Details :"; ?>
                        </td>
                        <td colspan="4" style="width:561px;" align="left" class="size12">
                             <br /><br />   <?php echo $description; ?>
                        </td>
                    </tr>
                    <tr>
                        <td style="width:36px; height:3px;" colspan="5" align="left" valign="top"></td>
                    </tr> 
                    <td style="width:125px;" align="left" class="bold size12">&nbsp;
                           
                    </td>
                    <td colspan="2" style="width:256px;" align="left" class="bold size14">
                        <?php if($_SESSION["userid"]==$postedby) {?> <br />
                        <a href="editresource.php?resourceid=<?php echo $resourceid; ?>">Edit <br /><br />
                        </a>
                        <?php } ?>
                    </td>  
                    <td colspan="2" style="width:256px;" align="left" class="bold size14">
                        <?php if($_SESSION["userid"]==$postedby) {?> <br />
                        <a href="deleteresource.php?resourceid=<?php echo $resourceid; ?>">Delete <br /><br />
                        </a>
                        <?php } ?>
                    </td>                       
					</tr>
					
                    <tr>
                        <td style="width:36px; height:3px;" colspan="5" align="left" valign="top"></td>
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