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
$usertypeid = $_SESSION["usertypeid"];
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
	
	$query1 = '';
	$where = '';
		
	if(!isset($_SESSION["userid"]) || $_SESSION["userid"] =="")
	{	
		header("location: index.php");
		echo "<script>location.href='index.php' </script>";   
	}
	else
	{
		if(isset($_GET["userid"]))
	    {
		    $userid=$_GET["userid"];
		    $query="select 
					    fname,
					    lname,
					    thumb_listing,
					    thumb_profile as profilepic,
					    dob,
					    genderid,
					    usertypeid,
					    diseaseid,
					    city,
					    countryid,
					    email,
					    password,
					    website,
					    iam,
					    ilike,
					    myexperience,
					    isactive,
					    usealias,
					    alias
				    from tbluser where userid=".$userid;
		    $result=mysql_query($query);
		    $num=mysql_num_rows($result);
		    if($num>0)
		    {
			    $row=mysql_fetch_array($result);	
    					
			    $fname=$row["fname"];
			    $lname=$row["lname"];
			    $email=$row["email"];
			    $password=$row["password"];
			    $profilepic=$row["profilepic"];
			    $thumb_listing=$row["thumb_listing"];
			    $dob=$row["dob"];
			    $genderid=$row["genderid"];
			    $usertypeid=$row["usertypeid"];
			    $diseaseid=$row["diseaseid"];
			    $city=$row["city"];
			    $countryid=$row["countryid"];
			    $website=$row["website"];
			    $iam=$row["iam"];
			    $ilike=$row["ilike"];
			    $myexperience=$row["myexperience"];
			    $isactive=$row["isactive"];	
			    $usealias=$row["usealias"];	
			    $alias=$row["alias"];	
			    $dateofbirth=explode("-",$dob);
		    }
		}
		
	}
	
	
	/******************************************* LOAD PHOTO ALBUMS **********************************************************/
	
	$query_albums="
		SELECT albumid, albumname, location, description, privacylevel, coverphotoid, datecreated, userid, COALESCE( (
		
		SELECT picname
		FROM tblphotos
		WHERE photoid = a.coverphotoid
		), '' ) AS picname
		FROM tblalbums a
		WHERE userid = ".$userid;
	
} 

?>



<!--<link rel="stylesheet" href="css/basic.css" type="text/css" />-->
<link rel="stylesheet" href="css/galleriffic-2.css" type="text/css" />
<script type="text/javascript" src="js_viewer/jquery-1.3.2.js"></script>
<script type="text/javascript" src="js_viewer/jquery.galleriffic.js"></script>
<script type="text/javascript" src="js_viewer/jquery.opacityrollover.js"></script>
<!-- We only want the thunbnails to display when javascript is disabled -->
<script type="text/javascript">
    document.write('<style>.noscript { display: none; }</style>');
</script>
        <div class="warpper">
        	<div class="left_side">
            	<!-- <div class="left_contant">
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
                    <a href="myprofile.php" class="whitetitle size12">Home</a>&nbsp;&nbsp;<span class="size12">>></span>&nbsp;&nbsp;<span class="whitetitle size12">Photos</span>
                </div>

                <div class="body">
                    <div class="main_link">
                        <div class="inbox_title">
                            
                            <div class="f_left bluetitle size28 bold" style="padding-top:5px; width:auto;">My Albums</div>
                            <div class="right_img">
                                <div class="inbox_img">
                                    
                                </div>
                                <div class="outbox_img">
                                   
                                </div>
                                <div style="float:right; vertical-align:bottom;">
                                    <br/>
                                <a class="bluelink" href="createalbum.php">Create Album</a>
                                </div>
                            </div>
                        </div>

                        <div class="email_table">
                            <table cellpadding="0" cellspacing="0" style="width:686px; border:0px;">
                                <form action="updateprofile.php" method="post" enctype="multipart/form-data" id="customForm" >
                                    <input type="hidden" id="isactive" name="isactive" value="<?php echo $isactive; ?>" />
                                    <input type="hidden" id="userid" name="userid" value="<?php echo $userid; ?>" />

                                    <tr>
                                        <td style="width:36px; height:12px;" colspan="5" align="left" valign="top"></td>
                                    </tr>

                                    <tr>
                                        <td style="width:561px;" align="left" colspan="5" class="size12">
                                        <!--<a class="bluelink" href="createalbum.php">Create Album</a>-->
                                        </td>
                                    </tr>



                                    <tr>
                                        <td style="width:36px; height:3px;" colspan="5" align="left" valign="top"></td>
                                    </tr>
                                 
                                 
                                 
                                 




                                    <tr>
                                        <td align="left" valign="top" colspan="5">
                                        	<?php
													
							$sql=mysql_query($query_albums);
							$message="";
							$rowcount=0;
							$count=mysql_num_rows($sql);
							if($count>0)
							{							
								while($row=mysql_fetch_array($sql))
								{
								
									$rowcount++;
									if($rowcount%4==0)
									{
										$message=$message."<div style='display:inline;float:left;'>";
									}	
									
									$message=$message."<div class='album-pics'><a href='viewalbum.php?albumid=".$row['albumid']."&albumname=".$row['albumname']."'>";
									
									if($row['picname']!='')
										$message=$message."<img src='albumphotos/".$row['picname']."' border='0' width='156px' height= '115px' />";
									else
										$message=$message."<img src='albumphotos/nophoto.jpg' border='0' width='156px' height= '115px' />";
									
									$message=$message."</a><br/><a class='bluelink' href='viewalbum.php?albumid=".$row['albumid']."&albumname=".$row['albumname']."'>".$row['albumname']."</a></div>";
									
									if($rowcount%4==0)
									{
										$message=$message."</div><div style='clear:both;'></div>";
									}	
																		
									//$msg_id=$row['albumid'];
//									
//									$message="<div class='msg-general'>";	
//										
//									$message=$message."<div class='msglist'>";
//										$message=$message."<a class='msglisttitle' href='viewalbum.php?albumid=".$row['albumid']."'><img src='albumphotos/".$row['picname']."' border='0' width='156px' height= '115px' /></a><br/>";
//									
//										$message=$message."<div class='msglistmsgbody'>";
//									
//											$message=$message."<div>";
//											$message=$message.$row['albumname'];
//											$message=$message."</div>";
//									
//										$message=$message."</div>";
//									
//									$message=$message."</div>";//end of message
//									
//									$message=$message."<div class='msg-statut'>";
//									$message=$message."</div>";
//									
//									$message=$message."<div id=msg-actions>";
//									
//								
//									
//									$message=$message."</div>";//end of msg-actions
//									
//									$message=$message."</div>";//end of msg-general
								
							?>
							
							<?php 
								}
							}
							else
								 $message=$message."No albums found.";
								 
							echo $message; 
							?>
								
                                        
                                        </td>
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