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
	AND (tbluser.usertypeid=3 OR tbluser.usertypeid=4)";	
	$result_ps_count=mysql_query($query_ps_count);
	$num_ps_count=mysql_num_rows($result_ps_count);
	if($num_ps_count>0)
	{
		$row_ps_count=mysql_fetch_array($result_ps_count);				
		$ps_match_count = $row_ps_count[0];   
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
		
	
	$msgid=$_GET["msgid"];
	$inbox_items = $_GET["inbox_items"];
	$outbox_items = $_GET["outbox_items"]; 
	
	//Note this msg as read
	$query="update tblmsgs set isread=1 where msgid = ".$msgid;		//set the msg as read 
	mysql_query($query); 
	
	$query_msgs="";
	$recieverid=0;
	if($_GET["mode"]=="inbox")
	{
		$query_msgs=" 
			select 
			msgid ,	msg , isread ,
			(select CONCAT(fname,' ',lname) from tbluser where userid=m.senderid) as username,
			senderid as userid,
			(select CONCAT(fname,' ',lname) from tbluser where userid=m.recieverid) as reciever,
			msgtime,senderid,recieverid
			
			from tblmsgs m 
			where msgid = ".$msgid;	
		    $msgheadingtitle="Inbox";
		    $titletime="Recieved Time";
		    $titletype="Sender";		
	}	
	else
	{
		$query_msgs=" 
			select 
			msgid ,	msg , isread ,
			(select CONCAT(fname,' ',lname) from tbluser where userid=m.senderid) as sender,
			(select CONCAT(fname,' ',lname) from tbluser where userid=m.recieverid) as username,
			recieverid as userid,
			msgtime,senderid,recieverid
			
			from tblmsgs m 
			where msgid = ".$msgid;
		    $msgheadingtitle="Outbox";
		    $titletime="Sent Time";
		    $titletype="Recipient";
	}
		

	
	
} ?>
        <div class="warpper">
        	<div class="left_side">
            	<div class="left_contant">
                	<div class="user_info">
                    	<div class="user_img">
                        	<!--<img src="images/user-img.jpg" />-->
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

                </div>
            </div>
            <div class="body_main">
                <div class="body_menu whitetitle">
                    <a href="myprofile.php" class="whitetitle size12">Home</a>&nbsp;&nbsp;<span class="size12">>></span>&nbsp;&nbsp;<span class="whitetitle size12">My Messages</span>
                </div>

                <div class="body">
                    <div class="main_link">
                        <div class="inbox_title">
                            <div class="f_left">
                                <img align="absmiddle" src="images/inbox_icon.jpg" />
                            </div>
                            <div class="f_left bluetitle size30 bold" style="padding-top:5px; width:auto; padding-left:10px;"><?php echo $msgheadingtitle; ?></div>
                                 <div class="outbox_img">
                                    <a href="messages.php?mode=sent&page=1"><img style="border:0px;" src="images/inbox-img.jpg"></a>
                                    <br />                                     
                                    <a href="messages.php?mode=sent&page=1" >Outbox</a>
                                </div> 
								<div class="inbox_img">
                                    <a href="messages.php?mode=inbox&page=1"><img style="border:0px;" src="images/inbox-img.jpg"></a>
                                    <br />                                     
                                    <a href="messages.php?mode=inbox&page=1" >Inbox(<?php echo $inbox_items;?>)</a> 
                                </div>
								<!--<div class="inbox_img">
                                    <a href="messages.php?mode=inbox&page=1"><img style="border:0px;" src="images/dmsg1.gif"></a>
                                    <br />                                     
                                    <a href="messages.php?mode=inbox&page=1" >Delete</a> 
                                </div> -->
	
							    <?php
								if($_GET["mode"]=="inbox")
								{ ?>
								<div class="inbox_img">  
                                    <a href="viewmsg.php?mode=inbox&msgid=<?php echo $msgid;?>&mode=inbox&act=compose&inbox_items=<?php echo $inbox_items;?>&outbox_items=<?php echo $outbox_items;?>"><img style="border:0px;" src="images/inbox-img.jpg"></a> 
             						<br />  
									<a href="viewmsg.php?mode=inbox&msgid=<?php echo $msgid;?>&mode=inbox&act=compose&inbox_items=<?php echo $inbox_items;?>&outbox_items=<?php echo $outbox_items;?>" align="right" style="border:0px;">Reply</a>
                                </div> 
								<?php }
								?> 
                            </div>							
                        </div> 
						
                    	<div class="msg_bar"></div>  
						
 						<!-- 	 
                        <div class="menulinks">
                            <div class="top-i"></div>
                            <div class="centertxt">
                                <table cellpadding="0" cellspacing="0" style="width:660px; border:0px;">
                                    <tr>
                                        <td style="width:127px;" align="left" valign="top">
                                            <table cellpadding="0" cellspacing="0" style="width:127px; border:0px;">
                                                <tr>
                                                    <td style="width:36px;" align="left" valign="top">
                                                        <img style="border:0px;" src="images/msg_icon.jpg" />
                                                    </td>
                                                    <td style="width:80px; padding-left:11px;" align="center" valign="top">                                                         
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
										
                                        <td style="width:2px;" align="left">
                                            <img src="images/gray_line.jpg" />
                                        </td> 										
                                        <td style="width:525px; padding-left:6px;" align="left" valign="top">
                                            <table cellpadding="0" cellspacing="0" style="width:525px; border:0px;">
                                                <tr>
                                                    <td style="width:38px; height:10px;" align="left" valign="top">
         												<a href="messages.php?mode=inbox&page=1"><img align="absmiddle" style="border:0px;" src="images/mesg_icon.jpg"></a>&nbsp;<a class="bluelink" href="messages.php?mode=inbox&page=1">Received</a>&nbsp;&nbsp;<img align="absmiddle" src="images/light_gray.jpg" />&nbsp;&nbsp;<a href="messages.php?mode=sent&page=1"><img align="absmiddle" style="border:0px;" src="images/mesg_icon.jpg"></a>&nbsp;<a class="bluelink" href="messages.php?mode=sent&page=1">Sent</a>
                                            		</td>
                                                    <td style="width:58px; height:10px;" align="left" valign="top">
                                                     </td> 
                                                </tr>
                                            </table>
  
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <div class="bottom-i"></div>
                        </div> 
						-->
                        <div class="newmsg_btn"></div>
                    <div class="email_table">
                            <?php													
							$sql=mysql_query($query_msgs);							
							$row=mysql_fetch_array($sql);								
							$msg_id=$row['msgid'];
						    ?>
														 
                        <div class="menulinks">
                            <div class="top-i"></div>
                            <div class="centertxt"> 
                            <table cellpadding="0" cellspacing="0" style="width:686px; border:0px;"> 
                                <tr>
                                    <td style="width:125px;" align="left" class="bold size12">
                                        <?php echo $titletype; ?> :
                                    </td>
                                    <td colspan="4" style="width:561px;" align="left" class="size12">
                                        <?php echo $row['username']; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width:36px; height:5px;" colspan="5" align="left" valign="top"></td>
                                </tr>
                                <tr>
                                    <td style="width:125px;" align="left" class="bold size12">
                                        <?php echo $titletime; ?> :
                                    </td>
                                    <td colspan="4" style="width:561px;" align="left" class="size12">
                                        <?php echo $row['msgtime']; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width:36px; height:5px;" colspan="5" align="left" valign="top"></td>
                                </tr>
                                <tr>
                                    <td style="width:125px;" align="left" class="bold size12">
                                        Message :
                                    </td>
                                    <td colspan="4" style="width:561px;" align="left" class="size12">
                                        <?php echo $row['msg'];?>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width:36px; height:5px;" colspan="5" align="left" valign="top"></td>
                                </tr>
                                <?php 
	                            if($_GET["mode"]=="inbox" and $_GET["act"]=="compose")
	                            {?>
                                <form action="replymsg.php" method="post" >
                                    <input type="hidden" id="msgid" name="msgid" value="<?php echo $_GET["msgid"]; ?>" />
                                    <input type="hidden" id="mode" name="mode" value="<?php echo $_GET["mode"]; ?>" />
                                    <input type="hidden" id="recieverid" name="recieverid" value="<?php echo $recieverid; ?>" />
                                    <input type="hidden" id="senderid" name="senderid" value="<?php echo $_SESSION["userid"]; ?>" />

                                    <tr>
                                        <td style="width:125px;" align="left" class="bold size12">
                                            <?php echo "Reply : "; ?>
                                        </td>
                                        <td colspan="4" style="width:561px;" align="left" class="size12">
                                            <textarea style="height: 120px; width: 248px; border:1px #bcbcbc solid;  padding-left:3px;" id="msg" name="msg" rows="3" cols="50"></textarea>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="width:36px; height:5px;" colspan="5" align="left" valign="top"></td>
                                    </tr>

                                    <tr>
                                        <td style="width:125px;" align="left" class="bold size12">
                                            &nbsp;
                                        </td>
                                        <td colspan="4" style="width:561px;" align="left" >
                                            <!--<input id="submit-subscirbe" type="button" onclick="history.go(-1)" value="Back" />&nbsp;<input id="submit-subscirbe" type="submit" value="Send" />-->
											<input id="submit-subscirbe" type="button" onclick="javascript:history.back()" value="Back" />&nbsp;<input id="submit-subscirbe" type="submit" value="Send" />
											
											
                                        </td>
                                    </tr>
                                </form>   
                                <?php } ?>
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
                            <div class="bottom-i"></div>
                        <!-- </div>  -->
                        </div>
                    </div>
                </div>
                
                
          </div>
        </div>	  
        
<?php include "footer.php"; ?>