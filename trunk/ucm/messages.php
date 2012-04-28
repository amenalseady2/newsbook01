<?php 
include "header_inner.php";
include "pagination.php";

$fname='';
$lname='';
$email='';
$password='';
$profilepic='';
$gender="";
$usertype="";
$disease="";
$disease_id=1;
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
$inbox_items = 0;
$outbox_items = 0;
$msg_id = 0;
$prev_sort_optn = "";
$page = 1;

if(!isset($_SESSION["userid"]) || $_SESSION["userid"] =="")
{	//header("location: login.php");   
		echo "<script>location.href='index.php' </script>";
}
else
{
	$userid=$_SESSION["userid"];
	
	/* If we got here to delete a msg after confirmation, just do it */
	if(isset($_GET["msgid"]))
	{
		$msgid = $_GET["msgid"];
		$action = $_GET["action"];		
		$mode = $_GET["mode"];
		$psoptn = $_GET["psort"];
		$page = $_GET["page"];  
		 
		if($action == "delete")
		{
			$query="delete from tblmsgs where msgid=".$msgid;
			$result=mysql_query($query);
			echo "<script>location.href='messages.php?mode=".$mode."&sort_option=".$psoptn."&page=".$page."&msg=Message has been deleted.!!!'</script>";
		}
	}
	
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
 
	/*$query_req_count="SELECT COUNT(*)
	FROM tblfriends
	WHERE tblfriends.userid = ".$userid."
	AND friendshipstatus =1";*/
	
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
	
	$pagination = "";
	$start = 0;
	$limit = 10;
	$adjacents = 1;	
	$pagestring = "&page=";

	$msgheadingtitle="Inbox";
	$query_msgs="";
	if($_GET["mode"]=="inbox")
	{ 
		//Get outbox items count just for display
		$query_msgs=" 
			select 
			msgid ,	msg ,
			(select CONCAT(fname,' ',lname) from tbluser where userid=m.senderid) as sender,
			(select CONCAT(fname,' ',lname) from tbluser where userid=m.recieverid) as username,
			m.recieverid as userid,
			msgtime,senderid,recieverid			
			from tblmsgs m
			where senderid = ".$userid;
			
		$sql=mysql_query($query_msgs);	 
		$outbox_items = mysql_num_rows($sql); 	
		
		//continue to work with inbox	 
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
			
			if($total_items > 0)
			{
				$page = (!isset($_GET['page']))? 1 : $_GET['page']; 
				$targetpage = "messages.php?mode=inbox";
				$pagination = getPaginationString($page,$total_items,$limit,$adjacents,$targetpage,$pagestring);
		    			
				if($page)
					$start = ($page -1) * $limit; 
			}					
			 
			$qry_extra = "";			
			if(isset($_GET["sort_option"]))	
			{
				$saction = $_GET["sort_option"];
				$prev_sort_optn = $saction;
				
				if($saction == "msgtime")			
				  $qry_extra = " order by msgtime desc limit $start, $limit";	

				if($saction == "msgsort")			
				   $qry_extra = " order by msg asc limit $start, $limit";	
				 
				if($saction == "msgtitle")			
				   $qry_extra = " order by username asc limit $start, $limit";	
				
				$query_msgs=$query_msgs.$qry_extra;  
			} 
			else
				$query_msgs=$query_msgs. " order by msgtime desc limit $start, $limit";					
 
		$msgheadingtitle="Inbox";
		$titletime="Recieved Time";
		$titletype="Sender";
	}	
	else
	{
		//Get inbox items count just for display
		$query_msgs=" 
			select 
			msgid ,	msg ,
			(select CONCAT(fname,' ',lname) from tbluser where userid=m.senderid) as username,
			m.senderid as userid,
			(select CONCAT(fname,' ',lname) from tbluser where userid=m.recieverid) as reciever,
			msgtime,senderid,recieverid	
			from tblmsgs m
			where recieverid = ".$userid;	 
			
		$sql=mysql_query($query_msgs);	 
		$inbox_items = mysql_num_rows($sql); 
		
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
	
		$inbox_items -= $read_msg_count;
		
		//continue to work with outbox	 
		$query_msgs=" 
			select 
			msgid ,	msg , isread,
			(select CONCAT(fname,' ',lname) from tbluser where userid=m.senderid) as sender,
			(select CONCAT(fname,' ',lname) from tbluser where userid=m.recieverid) as username,
			m.recieverid as userid,
			msgtime,senderid,recieverid			
			from tblmsgs m
			where senderid = ".$userid;
			
			$sql=mysql_query($query_msgs);							
			$total_items=mysql_num_rows($sql); 
			$outbox_items = $total_items;
			
			if($total_items > 0)
			{
				$page = (!isset($_GET['page']))? 1 : $_GET['page']; 
				$targetpage = "messages.php?mode=outbox";
				$pagination = getPaginationString($page,$total_items,$limit,$adjacents,$targetpage,$pagestring);
		    			
				if($page)
					$start = ($page -1) * $limit;
			}					
			
			$qry_extra = "";			
			if(isset($_GET["sort_option"]))	
			{
				$saction = $_GET["sort_option"];
				$prev_sort_optn = $saction;
				
				if($saction == "msgtime")			
				  $qry_extra = " order by msgtime desc limit $start, $limit";	

				if($saction == "msgsort")			
				   $qry_extra = " order by msg asc limit $start, $limit";	
				 
				if($saction == "msgtitle")			
				   $qry_extra = " order by username asc limit $start, $limit";					
 
				$query_msgs=$query_msgs.$qry_extra;  			
			} 
			else
				$query_msgs=$query_msgs. " order by msgtime desc limit $start, $limit";

		$msgheadingtitle="Outbox";
		$titletime="Sent Time";
		$titletype="Recipient";
	}
	
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
	
} ?>

<script type="text/javascript">
function deleteAlert(mid,action,msuid,mode,psoptn,pageno)
{
	var conBox = confirm("Are you sure you want to delete this message?");
	if(conBox) 	
       location.href="<?=$_SERVER['PHP_SELF'];?>?msgid="+mid +"&action="+action +"&msguserid="+msuid +"&mode="+mode+"&psort="+psoptn+"&page="+pageno; 
	else 
	   return; 
}
</script>

        <div class="warpper">
        	<div class="left_side">
            	<div class="left_contant">
                	<div class="user_info">
                    	<div class="user_img">
                        	<!--<img src="images/user-img.jpg" />-->
                            <img src="profilepics/<?php echo $profilepic; ?>" width="63" height="59" border="0" />
                        </div>
						<?php
							$_name='';
							if(isset($_SESSION["userid"])){
							
							/******************************************* LOAD USER INFO **********************************************************/
							$query="select 
										access_name,
										alias
									from tbluser,tblusertype,tbldisease,tblcountry where userid=".$_SESSION["userid"]." and 
									tblusertype.usertypeid=tbluser.usertypeid and tbldisease.diseaseid=tbluser.diseaseid and tblcountry.countryid=tbluser.countryid";
									
							$result=mysql_query($query);
							$num=mysql_num_rows($result);
							if($num>0)
							{
								$row=mysql_fetch_array($result);	
								
								if($row["access_name"]==1 && !empty($row["alias"]))		
									$_name=$row["alias"];
							}	
						}
						
						?>
                        <div class="user_data">
                        	<div class="user_name bluetitle size20 bold" /><?php echo empty($_name) ? $username : $_name; ?></div>
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
                            <div class="f_left bluetitle size30 bold" style="padding-top:3px; width:auto; padding-left:0px;"><?php echo $msgheadingtitle; ?></div> 
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
                           				
                        </div>  
                    	<div class="msg_bar"></div>   
                        <div class="newmsg_btn">
                        </div>
                        <div class="email_table">
                            <table cellpadding="0" cellspacing="0" style="width:686px; border:0px;">
                                <tr style="background-color:#3d84d6;height:25px;">
                                    <td style="width:140px;" align="left" class="whitetitle size12 bold">									 
									<a class="whitetitle size12 bold" href='messages.php?msguserid=0&mode=<?php echo $_GET["mode"]; ?>&page=<?php echo $_GET["page"];?>&sort_option=msgtime'><?php echo $titletime;?></a></td>   									
                                    <td style="width:450px;" colspan="2" align="left" class="whitetitle size12 bold">					                 
									<a class="whitetitle size12 bold" href='messages.php?msguserid=0&mode=<?php echo $_GET["mode"]; ?>&page=<?php echo $_GET["page"];?>&sort_option=msgsort'>Message</a></td> 
									<td style="width:60px;" align="left" class="whitetitle size12 bold">
									<a class="whitetitle size12 bold" href='messages.php?msguserid=0&mode=<?php echo $_GET["mode"]; ?>&page=<?php echo $_GET["page"];?>&sort_option=msgtitle'><?php echo $titletype?></a></td>                                       
									<td style="width:35px;" align="left" class="whitetitle size12 bold">Actions</td>
                                </tr>
                                <tr>
                                    <td style="width:36px; height:10px;" colspan="4" align="left" valign="top"></td>
                                </tr>
                            <?php
													
							$sql=mysql_query($query_msgs);							
							$count=mysql_num_rows($sql);
							 
							if($count>0)
							{								
							    $sql=mysql_query($query_msgs);								
								while($row=mysql_fetch_array($sql))
								{									
									$msg_id=$row['msgid'];	
									
									if($prev_sort_optn == "")
										$prev_sort_optn = "msgtime"; // default sort based on msgtime	 
								
							?>
                                <tr style="height:25px;">
                                    <td style="width:140px;" align="left" class="size11"><?php echo $row['msgtime'];?></td>
									<?php if(!$row['isread']) { ?>
                                         <td style="width:450px;" colspan="2"  align="left" class="size12"><a class='bluelink' href='viewmsg.php?msgid=<?php echo $row['msgid']; ?>&mode=<?php echo $_GET["mode"]; ?>&act=none&inbox_items=<?php echo $inbox_items;?>&outbox_items=<?php echo $outbox_items;?>'> <?php echo substr($row['msg'], 0, 50);?></a></td>
									<?php } else { ?>
                                         <td style="width:450px;" colspan="2"  align="left" class="size12"><a class='bluelinkv' href='viewmsg.php?msgid=<?php echo $row['msgid']; ?>&mode=<?php echo $_GET["mode"]; ?>&act=none&inbox_items=<?php echo $inbox_items;?>&outbox_items=<?php echo $outbox_items;?>'> <?php echo substr($row['msg'], 0, 50);?></a></td>
  									<?php } ?>
									<td style="width:60px;" align="left" class="size12"><?php echo $row['username']; ?></td>
 									<td style="width:35px;" align="left" class="size12"><a class='bluelink' href="javascript: deleteAlert('<?php echo $row['msgid'];?>','<?php echo 'delete';?>','<?php echo $row['userid'];?>','<?php echo $_GET["mode"]; ?>','<?php echo $prev_sort_optn; ?>','<?php echo $page; ?>');">Delete</a>&nbsp;|&nbsp;<a class='bluelink' href='messages.php?msguserid=<?php echo $row['userid'];?>&mode=<?php echo $_GET["mode"]; ?>&page=<?php echo $_GET["page"]; ?>'>Filter</a></td>								
                                  </tr> 								
								<?php	 
								} 								
								?>
                               <tr style="height:25px;">                                    
									<td style="width:350px;" align="left" class="size12 bold"><?php echo "\n".$pagination;?></td>
								</tr> 
							<?php								
							} 
							?>
								<tr>
                                    <td style="width:36px; height:10px;" colspan="4" align="left" valign="top"></td>
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
                    <!--</div>-->
                </div>
          </div>
        </div>	  
<?php include "footer.php"; ?>