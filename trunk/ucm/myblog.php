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

$req_count=0;
$frnds_count=0;

$uname = '';
$usertypeid = $_SESSION["usertypeid"];
$resourcetypeid = "0";
$name='';

if(!isset($_SESSION["userid"]) || $_SESSION["userid"] =="")
{	//header("location: login.php");   
		echo "<script>location.href='index.php' </script>";
}
else
{
	$userid=$_SESSION["userid"];
	
	if(isset($_GET['msg']))
	{ ?>
		<div class="txttitle whitetitle size12 bold"><?php echo $_GET['msg']; ?></div>
	<?php 
	}
	
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
		if(isset($_GET["resourcetypeid"]) && isset($_GET["name"]))
		{
			$name = str_replace("'","''",$_GET["name"]);
			$name=str_replace("\"","''",$name);
			$name=stripslashes($name);
			$resourcetypeid = $_GET["resourcetypeid"];
			
			$where = '';
			
			if($resourcetypeid!='0')
			{ 
					$where = $where. " and tblresources.resourcetypeid = ".$resourcetypeid; 
			}
			
			$query1 = "
			select resourceid,postedby,dateposted,link,description,subject,tblresources.resourcetypeid,strresourcetype,fname,lname
from tblresourcetype,tblresources,tbluser where tblresources.resourcetypeid=tblresourcetype.resourcetypeid and tblresources.postedby=tbluser.userid  ";
				
			if($where!='')
			{
				$query1=$query1.$where;
			}
				
			//$query1=$query1." limit 10";
		}
	}
	
	
} ?>
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

                            	<!--<li><a href="messages.php?mode=inbox">My Messages</a></li>
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
                            	<!--<li><a href="messages.php?mode=inbox">My Messages</a></li>
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
                    <a href="myprofile.php" class="whitetitle size12">Home</a>&nbsp;&nbsp;<span class="size12">>></span>&nbsp;&nbsp;<span class="whitetitle size12">My Blog</span>
                </div>

                <div class="body">
                    <div class="main_link">
                        <div class="inbox_title">
                            <!--<div class="f_left">
                    <img align="absmiddle" src="images/inbox_icon.jpg" />
                </div>-->
                            <div class="f_left bluetitle size30 bold" style="padding-top:5px; width:auto;">My Blog</div>
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
                                    <tr>
                                        <td style="width:561px;" align="left" colspan="5" class="bluetitle size14 bold">Post on your Blog</td>
                                    </tr>                                    
                                    <tr>
                                        <td style="width:36px; height:12px; " colspan="5" align="left" valign="top"></td>
                                    </tr>

                                    <form action="postblog.php" method="post" enctype="multipart/form-data" >
                                        <input type="hidden" id="postedbyuserid" name="postedbyuserid" value="<?php echo $_SESSION["userid"]; ?>" />
                                        <input type="hidden" id="postedonuserid" name="postedonuserid" value="<?php echo $_SESSION["userid"]; ?>" />
										<!-- <input type="hidden" name="MAX_FILE_SIZE" value="100000" /> -->
									<tr>
                                    <td colspan="5" style="width:686px;" align="left" class="size12">
                                        <textarea style="height: 120px; width: 666px; border:1px #bcbcbc solid;  padding-left:3px;" id="posttext" name="posttext" rows="3" cols="50"></textarea>

                                    </td>
									</tr>	 
								
								<tr style='display:none;float:left;' id="divpic"> 															 
                                    <td style="width:136px; height:10px; " colspan="5" align="left" valign="top">
                                        <input class="pic-upload" type="file" id="postimage" name="postimage"/>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width:36px; height:10px; " colspan="5" align="left" valign="top"></td>
                                </tr>
                                <tr>
                                    <td style="width:236px;" align="left" valign="top">
                                        <a class="bluelink" href="#" onclick="showdivpic();">Attach Picture(size:1 MB Max)</a>
                                    </td>	 
                                    <td style="width:125px;" align="left" valign="top"></td>
                                    <td style="width:319px;" align="left" valign="top">&nbsp;</td>
                                    <td style="width:203px;" align="right" colspan="2" valign="top">
                                        <div align="right" style="margin : 0 0 0 0;margin-right:12px;float:right;font-family: Arial, Helvetica, tahoma;font-size: 12px;color: #0d4c94;text-decoration: none;text-align: left;">
                                            Share with&nbsp;<select name="privacylevel" id="privacylevel">
                                                <option value="2" >Public</option>
                                                <option value="3" >Friends</option>
                                            </select>
                                        </div>
                                    </td>
                                </tr>
                                    
								<tr style='display:none;float:left;' id="divvid">
                                    <td style="width:136px; height:10px; " colspan="5" align="left" valign="top">
                                        <input class="pic-upload" type="file" id="postvideo" name="postvideo"/>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width:36px; height:2px; " colspan="5" align="left" valign="top"></td>
                                </tr>
                                <tr>
                                    <td style="width:236px;" align="left" valign="top">
                                        <a class="bluelink" href="#" onclick="showdivvid();">Attach Video Link</a>
                                    </td>
                                    <td style="width:125px;" align="left" valign="top"></td>
                                    <td style="width:319px;" align="left" valign="top">&nbsp;</td>									
                                    <td style="width:203px;" align="right" colspan="2" valign="top"> 
								</tr>
								
		
								<tr style='display:none;float:left;' id="embedvid">
                                    <td style="width:136px; height:10px; " colspan="5" align="left" valign="top">                                         
										<textarea style="height: 40px; width: 300px; border:1px #bcbcbc solid;  padding-left:3px;" id="embedlink" name="embedlink" rows="3" cols="10"></textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width:36px; height:2px; " colspan="5" align="left" valign="top"></td>
                                </tr>
                                <tr>
                                    <td style="width:236px;" align="left" valign="top">
                                        <a class="bluelink" href="#" onclick="showembedvid();">Embed Video Link:</a> 
                                    </td>
                                    <td style="width:125px;" align="left" valign="top"></td>
                                    <td style="width:319px;" align="left" valign="top">&nbsp;</td>									
                                    <td style="width:203px;" align="right" colspan="2" valign="top">  
								</tr>	

								
								<tr>
                                    <td style="width:36px; height:2px; " colspan="5" align="left" valign="top">
                                    <input id="submit-subscirbe" type="submit" value="Post" />
                                    </td>
                                </tr>								
                                </form> 
                                <tr>
                                    <td style="width:36px; height:15px; " colspan="5" align="left" valign="top"></td>
                                </tr>
                                    <tr>
                                        <td style="width:686px; height:3px; " colspan="5" align="left" valign="top">

                                            <div name="newboxes" id="posts">
                                                <div> 
                                                    <?php 
					$query_post=" 										
					select 					
					blogpostid,posttext,postimage,postvideo,postembedvideolink,postedbyuserid,postedonuserid,datetimeposted,privacylevel,
					(select CONCAT(fname,' ',lname) from tbluser where userid=p.postedbyuserid) as poster,
					(select thumb_profile  from tbluser where userid=p.postedbyuserid) as posterpic
					
					from tblblogposts p 
					where postedonuserid = ".$userid."
					order by datetimeposted desc ";//limit 10";	
					
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
                                                                    <?php echo $row["datetimeposted"]; ?> | <a class="bluelink" href="javascript:void(0);" onclick="javascript:if(confirm('Are you sure you want to delete the post?')){location.href='delpost.php?blogpostid=<?php echo $row["blogpostid"]; ?>';}">Delete
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
								$query_comments=" 
											
								select 
								
								blogcommentid,blogpostid,commenttext,postedbyuserid,datetimeposted,
								(select CONCAT(fname,' ',lname) from tbluser where userid=c.postedbyuserid) as poster,
								(select thumb_profile from tbluser where userid=c.postedbyuserid) as posterpic
								
								from tblblogcomments c 
								where blogpostid = ".$row["blogpostid"]."
								order by datetimeposted asc ";	
								
								$sqlcomment=mysql_query($query_comments);
								
								$countcooment=mysql_num_rows($sqlcomment);
								if($countcooment>0)
								{							
									while($rowc=mysql_fetch_array($sqlcomment))
									{
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
                                                                        <?php echo $rowc["datetimeposted"]; ?> | <a class="bluelink" href="javascript:void(0);" onclick="javascript:if(confirm('Are you sure you want to delete this reply to your post?')){location.href='delcomment.php?blogcommentid=<?php echo $rowc["blogcommentid"]; ?>';}">Delete
                                                                        </a>
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
                                                                <img src="profilepics/<?php echo $profilepic; ?>" style="background-color:#FFFFFF" width="50" height="50" border="0">
                                                            </div>
                                                            <div style="float:left;display:inline;padding-left:10px;min-height:50px;width:530px;background-color:#e7e7e7;">
                                                                <div style="float:left;">
                                                                    <a class="bluelink" href="viewprofile.php?userid=<?php echo $_SESSION["userid"]; ?>"><?php echo $_SESSION["fname"]; ?>
                                                                    </a>
                                                                </div>
                                                                <div style="clear:both;"></div>
                                                                <div style="float:left;">
                                                                    <form action="postcomment.php" method="post" enctype="multipart/form-data" >
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