<?php include "header_inner.php";


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
$DID=0;
$access_name=0;
$access_pic=0;
$access_dob=0;
$access_gender=0;
$access_disease=0;
$access_loc=0;
$access_email=0;
$access_web=0;
$access_iam=0;
$access_ilike=0;
$access_exp=0;
$access_photos=0;
$access_friends=0;
$access_blog=0;
$access_msg=0;


$frnds_count=0;
$usealias=0;
$alias="";


if(!isset($_SESSION["userid"]) || $_SESSION["userid"] =="")
{	//header("location: login.php");   
		echo "<script>location.href='index.php' </script>";

}
else
{
	$viewerid=$_SESSION["userid"];
	$userid=$_SESSION["userid"];

	if(isset($_GET['msg']))
	{ ?>
	  <div class="txttitle whitetitle size12 bold"><?php echo $_GET['msg']; ?></div>
	<?php 
	}
	
	/******************************************* LOAD USER INFO & PRIVACY SETTINGS *****************************************************************/
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
	
	
} 

$u_query=mysql_query("select * from tbluser where userid='".$_SESSION["userid"]."'");
if(mysql_num_rows($u_query))
{
$row_user_d=mysql_fetch_array($u_query);	
$DID=$row_user_d["diseaseid"];
}
?>
        <div class="warpper">
        	<div class="left_side">
            <!-- <div class="left_contant">
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
                    <a href="myprofile.php">Home</a>&nbsp;&nbsp;<span class="size9">>> </span> <a href="viewprofile.php?userid=<?php echo $userid; ?>">
                            <?php //echo $alias; if($usealias=="1") 
										//	echo $alias;
										//  else 
											echo $fname." ".$lname; ?>
                        </a>&nbsp;&nbsp;<span class="size9">>></span>&nbsp;&nbsp;<span class="bluetitle size11">Interest Wall</span>
                </div>

                <div class="body">
                    <div class="main_link">
                        <div class="inbox_title">
                            <!--<div class="f_left">
                    <img align="absmiddle" src="images/inbox_icon.jpg" />
                </div>-->
                            <div class="f_left bluetitle size30 bold" style="padding-top:5px; width:auto;">
                                <?php //if($usealias=="1") 
										//	echo $alias;
										 // else 
											//echo $fname." ".$lname; 
											echo "My Interest blog"?>
                                <?php $basicvis = false; ?><!--'s Blog-->
                            </div>
                            
                        </div>

                        <div class="email_table">
                            <table cellpadding="0" cellspacing="0" style="width:686px; border:0px;">
                                <tr>
                                    <tr>
                                        <td style="width:36px; height:12px; " colspan="5" align="left" valign="top"></td>
                                    </tr>





                                    <tr>
                                        <td style="width:686px; height:3px; " colspan="5" align="left" valign="top">

                                            <div name="newboxes" id="posts">
                                                <div>

                                                    <?php
				
				  
					$u_query="select * from tbluser where diseaseid='".$DID."'";
					$res=mysql_query($u_query);	
					if(mysql_num_rows($res))
					{
				    while($row_userid=mysql_fetch_array($res))
					{
					$query_post=" 										
					select 					
					blogpostid,posttext,postimage,postvideo,postembedvideolink,postedbyuserid,postedonuserid,datetimeposted,privacylevel,
					(select CONCAT(fname,' ',lname) from tbluser where userid=p.postedbyuserid) as poster,
					(select thumb_profile from tbluser where userid=p.postedbyuserid) as posterpic					
					from tblblogposts p 
					where postedonuserid = ".$row_userid["userid"]; 				
					$query_post=$query_post." and privacylevel = 2 ";					
					$query_post=$query_post." order by datetimeposted desc ";//limit 10";	
					$sql=mysql_query($query_post);
					$count=mysql_num_rows($sql);
					if($count>0)
					{							
						while($row=mysql_fetch_array($sql))
						{
					?>
                                                    <div style="display:inline;">
                                                        <div style="float:left;min-height:50px;width:50px;margin-top:20px">
                                                            <?php if($row["posterpic"]!="")
								{
								?>
                                                            <img src="profilepics/<?php echo $row["posterpic"]; ?>" style="background-color:#FFFFFF" width="50" height="50" border="0">
                                                            <?php 
								}
								else
								{
								?>
                                                            <img src="profilepics/empty_profile.jpg" style="background-color:#FFFFFF" width="50" height="50" border="0">
                                                                <?php
								}
								?>
                                                                <!--<img src="profilepics/<?php echo $row["posterpic"]; ?>" style="background-color:#FFFFFF" width="50" height="50" border="0"> -->
                                                            </div>
                                                        <div style="float:left;display:inline;margin-left:10px;width:600px;margin-top:20px">
                                                            <div style="display:inline;">
                                                                <div style="float:left;">
                                                                    <a class="bluelink" href="viewprofile.php?userid=<?php echo $row["postedbyuserid"]; ?>"><?php echo $row["poster"]; ?>
                                                                    </a>
                                                                </div>
                                                                <div style="text-align: right;float:right;font-family: Arial, Helvetica, tahoma;font-size: 12px;color: #0d4c94;text-decoration: none;">
                                                                    <?php echo $row["datetimeposted"]; ?>
                                                                    </a>
                                                                </div>
                                                            </div>

                                                            <div style="clear:both;"></div>
                                                            <div style="float:left;" class="size12">
                                                                <?php echo $row["posttext"]; ?>
                                                            </div>
                                                            <?php if($row["postimage"]!="")
															{ ?>
                                                            <div style="clear:both;"></div>
                                                            <div style="float:left;max-height:600px;max-width:600px;">
                                                                <img src="wallphotos/<?php echo $row["postimage"]; ?>" style="background-color:#FFFFFF;max-height:600px;max-width:600px;" border="0">
                                                            </div>
                                                            <?php	}
                                                            if($row["postvideo"]!="")
															{ ?>
                                                            <div style="clear:both;"></div>
                                                            <div style="float:left;max-height:600px;max-width:600px;">
                                                                <img src="wallphotos/<?php echo $row["postvideo"]; ?>" style="background-color:#FFFFFF;max-height:600px;max-width:600px;" border="0">
                                                            </div> 
															<?php	}	 
															if($row["postembedvideolink"]!="")
															{ 
															$embed = $row["postembedvideolink"]; 
															//$embed = '<iframe title="YouTube video player" class="youtube-player" type="text/html" width="640" height="390" src="http://www.youtube.com/embed/W-Q7RMpINVo"frameborder="0" allowFullScreen></iframe>';
							
		 													//embed = preg_replace('/(width)=("[^"]*")/i', 'width="200"', $embed);   
															//$embed = preg_replace('/(height)=("[^"]*")/i', 'height="200"', $embed);  
															echo $embed;    
															?>
                                                            <div style="clear:both;"></div>
                                                            <div style="float:left;max-height:600px;max-width:600px;">															
                                                                <img src="<?php echo $row["postembedvideolink"]; ?>" style="background-color:#FFFFFF;max-height:600px;max-width:600px;" border="0">
                                                            </div> 
															<?php	}
															?>
                                                        </div>            
                                                        <div style="clear:both;"></div>
                                                    </div>

                                                    <?php
																
								$query_comments="select blogcommentid,blogpostid,commenttext,postedbyuserid,datetimeposted,
								(select CONCAT(fname,' ',lname) from tbluser where userid=c.postedbyuserid) as poster,
								(select thumb_profile from tbluser where userid=c.postedbyuserid) as posterpic from tblblogcomments c 
								where blogpostid = ".$row["blogpostid"]."
								order by datetimeposted asc ";	
								
								$sqlcomment=mysql_query($query_comments);
								
								$countcooment=mysql_num_rows($sqlcomment);
								if($countcooment>0)
								{							
									while($rowc=mysql_fetch_array($sqlcomment))
									{									
										if($rowc["posterpic"]=="")
											$rowc["posterpic"]="empty_profile.jpg";
						?>
                                                    <div style="background-color:#e7e7e7;margin-left:60px;margin-top:2px;margin-right:30px;">
                                                        <div style="display:inline;background-color:#e7e7e7;">
                                                            <div style="float:left;min-height:50px;width:50px;background-color:#e7e7e7;">
                                                                <?php if($rowc["posterpic"]!="")
													{
													?>
                                                                <img src="profilepics/<?php echo $rowc["posterpic"]; ?>" style="background-color:#FFFFFF" width="50" height="50" border="0">
                                                                <?php 
													}
													else
													{
													?>
                                                                <img src="profilepics/empty_profile.jpg" style="background-color:#FFFFFF" width="50" height="50" border="0">
                                                                    <?php
													}
													?>
                                                                </div>
                                                            <div style="float:left;display:inline;padding-left:10px;min-height:50px;width:530px;background-color:#e7e7e7;">
                                                                <div style="display:inline;">
                                                                    <div style="float:left;">
                                                                        <a class="bluelink" href="viewprofile.php?userid=<?php echo $rowc["postedbyuserid"]; ?>"><?php echo $rowc["poster"]; ?>
                                                                        </a>
                                                                    </div>
                                                                    <div style="text-align: right;float:right;font-family: Arial, Helvetica, tahoma;font-size: 12px;color: #0d4c94;">
                                                                        <?php echo $rowc["datetimeposted"]; ?>
                                                                        <?php 
													                    if($rowc["postedbyuserid"]==$_SESSION["userid"])
													                    {
													                    ?> | <a class="bluelink" href="delmycomment.php?blogcommentid=<?php echo $rowc["blogcommentid"]; ?>&userid=<?php echo $userid; ?>">Delete</a>
                                                                        <?php 
														                }
														                ?>
                                                                    </div>
                                                                </div>
                                                                <div style="clear:both;"></div>
                                                                <div style="float:left;" class="size12">
                                                                    <?php echo $rowc["commenttext"]; ?>
                                                                </div>
                                                            </div>
                                                            <div style="clear:both;"></div>
                                                        </div>
                                                    </div>

                                                    <?php	
									}
								}
						?>
                                                    <div style="background-color:#e7e7e7;margin-left:60px;margin-top:2px;margin-right:30px;">
                                                        <div style="display:inline;background-color:#e7e7e7;">
                                                            <div style="float:left;min-height:50px;width:50px;background-color:#e7e7e7;">
                                                                <img src="profilepics/<?php echo $_SESSION["profilepic"]; ?>" style="background-color:#FFFFFF" width="50" height="50" border="0">
                                                            </div>
                                                            <div style="float:left;display:inline;padding-left:10px;min-height:50px;width:530px;background-color:#e7e7e7;">
                                                                <div style="float:left;">
                                                                    <a class="bluelink" href="viewprofile.php?userid=<?php echo $_SESSION["userid"]; ?>"><?php echo $_SESSION["fname"]; ?>
                                                                    </a>
                                                                </div>
                                                                <div style="clear:both;"></div>
                                                                <div style="float:left;">

                                                                    <form action="postblogcomment.php" method="post" enctype="multipart/form-data" >
                                                                        <input type="hidden" id="userid" name="userid" value="<?php echo $userid; ?>" />
                                                                        <input type="hidden" id="blogpostid" name="blogpostid" value="<?php echo $row["blogpostid"]; ?>" />
                                                                        <input type="hidden" id="postedbyuserid" name="postedbyuserid" value="<?php echo $_SESSION["userid"]; ?>" />
                                                                        <textarea class="form-text-area"  id="commenttext" name="commenttext" rows="2" cols="300" style="width:470px;height:25px;"></textarea>&nbsp;<input id="submit-comment" type="submit" value="Reply" />
                                                                    </form>
                                                                </div>
                                                            </div>
                                                            <div style="clear:both;"></div>
                                                        </div>
                                                    </div>
                                                    <?php		
													 	}
													    }
												        }
								                       }
					                               else
													{
													?>
													No posts.
													<?php
													}
													?>

                                                </div>
                                                <!-- End of bio -->
                                            </div>
                                            <!-- end of profile -->



                                        </td>
                                    </tr>
                                    <tr>
                                    <td style="width:36px; height:3px; " colspan="5" align="left" valign="top"></td>
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