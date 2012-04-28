<?php include "header_inner.php";


function ismyfriend($userid,$friendwith)
	{
		$query_frnds="SELECT friendshipid, tblfriends.userid, friendwith, friendshipstatus
		FROM tblfriends
		WHERE tblfriends.userid = ".$userid." and friendwith = ".$friendwith."
		AND friendshipstatus = 2";
		$result=mysql_query($query_frnds);
		$num=mysql_num_rows($result);
		if($num>0)
			return true;
		else
			return false;
	}
	
	function isfriendpendingapproval($userid,$friendwith)
	{
		$query_frnds="SELECT friendshipid, tblfriends.userid, friendwith, friendshipstatus
		FROM tblfriends
		WHERE tblfriends.userid = ".$userid." and friendwith = ".$friendwith."
		AND friendshipstatus = 4";
		$result=mysql_query($query_frnds);
		$num=mysql_num_rows($result);
		if($num>0)
			return true;
		else
			return false;
	}
$search_id=0;
$uname = '';
$usertypeid = "0";
$diseaseid = "0";
$disease_id = 1;	
$query = '';
$where = '';

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

$userid=0;

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
	$user_query = "
				select 
					userid,
					fname,
					lname,
					profilepic,
					dob,
					genderid,
					tbluser.usertypeid,
					tbluser.diseaseid,
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
					alias,
					strusertype,
					strdisease,
					access_msg,
					access_pic,
					tbluser.diseaseid
				from tbluser,tbldisease,tblusertype where tbluser.isactive=1 and tbldisease.diseaseid=tbluser.diseaseid and tblusertype.usertypeid=tbluser.usertypeid and tbluser.userid <> ".$userid."";	
	$result=mysql_query($user_query);
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
		//$usertype=$row["usertype"];
		$disease=$row["diseaseid"];
		$city=$row["city"];
		//$country=$row["country"];
		$website=$row["website"];
		$iam=$row["iam"];
		$ilike=$row["ilike"];
		$myexperience=$row["myexperience"];
		$isactive=$row["isactive"];	
		$dateofbirth=explode("-",$dob);
		$age=date("Y")-$dateofbirth[0];
	}

	/*******************************************************************************************************************/

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
	
		if($_SERVER['REQUEST_METHOD']=='POST')
		{
				// Search function in this page
				$searchstr = str_replace("'","''",$_POST["searchstr"]);
				$where = $where. " and (alias like '%".$searchstr."%' or fname like '%".$searchstr."%' or lname like '%".$searchstr."%' or concat( fname, ' ', lname ) like '%".$searchstr."%')";
				$where_resources = " and (subject like '%".$searchstr."%' or alias like '%".$searchstr."%' or fname like '%".$searchstr."%' or lname like '%".$searchstr."%' or concat( fname, ' ', lname ) like '%".$searchstr."%')"; 
				$query = "
				select 
					userid,
					fname,
					lname,
					profilepic,
					dob,
					genderid,
					tbluser.usertypeid,
					tbluser.diseaseid,
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
					alias,
					strusertype,
					strdisease,
					access_msg,
					access_pic
				from tbluser,tbldisease,tblusertype where tbluser.isactive=1 and tbldisease.diseaseid=tbluser.diseaseid and tblusertype.usertypeid=tbluser.usertypeid and tbluser.userid <> ".$userid."";
 				
				$where_members = $where;
    			$query=$query.$where_members;
					
				$query=$query." limit 10";		

				
				$query1 = "
				select resourceid,postedby,dateposted,link,description,subject,strresourcetype,fname,lname
				from tblresourcetype,tblresources,tbluser where tblresources.resourcetypeid=tblresourcetype.resourcetypeid and tblresources.postedby=tbluser.userid  and tblresources.resourcetypeid = 4 ";
				
				$query2 = "
				select resourceid,postedby,dateposted,link,description,subject,strresourcetype,fname,lname
				from tblresourcetype,tblresources,tbluser where tblresources.resourcetypeid=tblresourcetype.resourcetypeid and tblresources.postedby=tbluser.userid  and tblresources.resourcetypeid = 2 ";
	
				
				$query3 = "
				select resourceid,postedby,dateposted,link,description,subject,strresourcetype,fname,lname
				from tblresourcetype,tblresources,tbluser where tblresources.resourcetypeid=tblresourcetype.resourcetypeid and tblresources.postedby=tbluser.userid  and tblresources.resourcetypeid = 1 ";
				
				
				$query4 = "
				select resourceid,postedby,dateposted,link,description,subject,strresourcetype,fname,lname
				from tblresourcetype,tblresources,tbluser where tblresources.resourcetypeid=tblresourcetype.resourcetypeid and tblresources.postedby=tbluser.userid  and tblresources.resourcetypeid = 5 ";
				
				
				$query5 = "
				select resourceid,postedby,dateposted,link,description,subject,strresourcetype,fname,lname
				from tblresourcetype,tblresources,tbluser where tblresources.resourcetypeid=tblresourcetype.resourcetypeid and tblresources.postedby=tbluser.userid  and tblresources.resourcetypeid = 3 ";
				
				
				$query6 = "
				select resourceid,postedby,dateposted,link,description,subject,strresourcetype,fname,lname
				from tblresourcetype,tblresources,tbluser where tblresources.resourcetypeid=tblresourcetype.resourcetypeid and tblresources.postedby=tbluser.userid  and tblresources.resourcetypeid = 6 ";
				
				$where = ' ';
				
				$query1=$query1.$where_resources;
				$query2=$query2.$where_resources;
				$query3=$query3.$where_resources;
				$query4=$query4.$where_resources;
				$query5=$query5.$where_resources;
				$query6=$query6.$where_resources;
				
				$blog_query = "
			select resourceid,postedby,dateposted,link,description,subject,tblresources.resourcetypeid,strresourcetype,fname,lname
from tblresourcetype,tblresources,tbluser where tblresources.resourcetypeid=tblresourcetype.resourcetypeid and tblresources.postedby=tbluser.userid  ";
			
		}
} ?>

<script type="text/javascript">

    function openmsg(sid,rid)
    { 
		//mywindow = window.open("msgmember.php?senderid="+sid+"&recieverid="+rid, "Send Message", "location=0,status=1,scrollbars=0,  width=500,height=350");
		//mywindow.moveTo(200, 200);
 
		url = "msgmember.php?senderid="+sid+"&recieverid="+rid;
		mywindow = window.open(url);
		mywindow.focus();
		self.close();
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
                                    <a href="myinterestmembers.php">Members With My Interest(<?php echo $disease_match_count-1;?>)</a>
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
        <a href="myprofile.php" class="whitetitle size12">Home</a>&nbsp;&nbsp;<span class="size12">>></span>&nbsp;&nbsp;<span class="whitetitle size12">Search All Members</span>
    </div>
    <div class="body">
        <div class="main_link">
            <div class="inbox_title">
                
                <div class="f_left bluetitle size30 bold" style="padding-top:5px; width:auto;">
                    Search Results
                </div>
                <div class="right_img">
                    <div class="inbox_img">
                    </div>
                    <div class="outbox_img">
                    </div>
                    <div class="notification_img">                        
                    </div>
                </div>
            </div>

<?php if(isset($_POST['searchstr'])){  // add not empty ?>
           
<!--           MEMBERS AREA BEGIN           -->
            <h1>Members</h1>
            <hr>
            <div class="email_table">              
                <table cellpadding="0" cellspacing="0" style="width:686px; border:0px;">
                    <tr>
                        <td style="width:686px;"align="left" valign="top">
                        </td>
                    </tr>
                            <tr>
                        <td style="width:686px;" align="left" valign="top">
                            <div name="newboxes" id="inbox" >
                                <div id="msg-list">
                                    <ol class="timeline" id="updates">
                                        <li>
                                            <?php
							$sql=mysql_query($query);
							if($sql)
							{
								$count=mysql_num_rows($sql);		
								$message="";
								if($count>0)
								{							
									while($row=mysql_fetch_array($sql))
									{	
										$search_id=$row['userid'];
									
										$message=$message."<div class='req-general'>";	
																				
										if((($_SESSION["userid"] != $row["userid"]) && ismyfriend($_SESSION["userid"],$row["userid"])  && !isfriendpendingapproval($_SESSION["userid"],$row["userid"]) && ($row["profilepic"]!="" && $row["access_pic"]!="1"))	|| ($row["profilepic"]!="" && $row["access_pic"]=="2") )
											$message=$message."<div class='searchlist'><a class='reqlisttitle' href='viewprofile.php?userid=".$row["userid"]."'><img src='profilepics/".$row["profilepic"]."' style='background-color:#FFFFFF' width='50'  border='0'></a></div>";
										else
											$message=$message."<div class='searchlist'><a class='reqlisttitle' href='viewprofile.php?userid=".$row["userid"]."'><img src='profilepics/empty_profile.jpg' style='background-color:#FFFFFF' width='50' border='0'></a></div>";
										
										$message=$message."<div class='searchlist'>";
										$message=$message."<a class='reqlisttitle' href='viewprofile.php?userid=".$row["userid"]."'>";
										
										if($row['usealias'] && $row['alias']!="")
											$message=$message.$row['alias'];
										else
											$message=$message.$row['fname']." ".$row['lname'];
										
										$message=$message."</a><br><b>Interested in :</b> ".$row['strdisease']."<br/><b>Type :</b> ".$row['strusertype'];
										$message=$message."</div>";
										
										$message=$message."<div class='search-list-action'>";
										
										if( ($_SESSION["userid"] != $row["userid"]) && ismyfriend($_SESSION["userid"],$row["userid"])  && !isfriendpendingapproval($_SESSION["userid"],$row["userid"]) )
										{
											if($row['access_msg']!='1')
											{
												$message=$message."<div class='friend-list-button'>";
												$message=$message."<a href='#' class='memsearchbutton' onclick='openmsg(".$_SESSION["userid"].",".$row["userid"].");'>Message</a>";				 																				
				 								$message=$message."</div><div style='width:5px;float:left;'>&nbsp;</div>";
											}
											else
											{
												$message=$message."<div class='no-button'>&nbsp;";
												$message=$message."</div><div style='width:5px;float:left;'>&nbsp;</div>";
											}
											
											$message=$message."<div class='friend-list-button'>";
											$message=$message."<a href='confrimfriend2.php?ui=".$_SESSION["userid"].
											"&fw=".$row["userid"]."&n=".$_SESSION["fname"]."&stat=un&uname=".$uname."&usertypeid=".$usertypeid."&diseaseid=".$diseaseid."'>Unfriend</a>";
											$message=$message."</div>";
										}
										elseif( ($_SESSION["userid"] != $row["userid"]) && !ismyfriend($_SESSION["userid"],$row["userid"])   && !isfriendpendingapproval($_SESSION["userid"],$row["userid"]))
										{
											if($row['access_msg']=='2')
											{
												$message=$message."<div class='friend-list-button'>";
												$message=$message."<a href='#' class='memsearchbutton' onclick='openmsg(".$_SESSION["userid"].",".$row["userid"].");'>Message</a>";
												$message=$message."</div><div style='width:5px;float:left;'>&nbsp;</div>";
 											}
											else
											{
												$message=$message."<div class='no-button'>&nbsp;";
												$message=$message."</div><div style='width:5px;float:left;'>&nbsp;</div>";
											}
											
											$message=$message."<div class='friend-list-button'>";
											$message=$message."<a href='addfriend2.php?ui=".$row["userid"].
											"&fw=".$_SESSION["userid"]."&n=".$_SESSION["fname"]."&uname=".$uname."&usertypeid=".$usertypeid."&diseaseid=".$diseaseid."'>Add Friend</a>";
											$message=$message."</div>";
										}
										elseif( ($_SESSION["userid"] != $row["userid"]) && !ismyfriend($_SESSION["userid"],$row["userid"])   && isfriendpendingapproval($_SESSION["userid"],$row["userid"]))
										{
											if($row['access_msg']=='2')
											{
												$message=$message."<div class='friend-list-button'>";
												$message=$message."<a href='#' onclick='openmsg(".$_SESSION["userid"].",".$row["userid"].");'>Message</a>";
												$message=$message."</div><div style='width:5px;float:left;'>&nbsp;</div>";
						 						}
											else
											{
												$message=$message."<div class='no-button'>&nbsp;";
												$message=$message."</div><div style='width:5px;float:left;'>&nbsp;</div>";
											}
											
											$message=$message."<div class='friendreq-list-button'>";
											$message=$message."<a href='addfriend2.php?ui=".$row["userid"].
											"&fw=".$_SESSION["userid"]."&n=".$_SESSION["fname"]."&uname=".$uname."&usertypeid=".$usertypeid."&diseaseid=".$diseaseid."'>Friend&nbsp;Request&nbsp;Sent</a>";
											$message=$message."</div>";
										}
										
										$message=$message."<div style='clear:both;'></div>";
										$message=$message."</div>";
										
										$message=$message."<div style='clear:both;'></div>";
										
										$message=$message."</div>";
									}
								}
								else 
								{
									$message="<div class='req-general'>No Members Found.</div>";
								}
							?>
                                            <?php	
								echo $message;  
							}
							else
								echo "<div class='req-general'>No Members Found.</div>";
							?>
                                        </li>
                                    </ol>

                                    <div style='text-align: center'>
                                       <a href="members.php">Search More</a>
                                    </div>
                                </div>
                                <!-- End of msg-list -->
                            </div>
                            <!-- end of inbox -->
    
            				</td>
                        </tr>
                    </table>
                </div>
                
                
<!--                MEMBERS AREA END -->
                
<!--                RESOURCES AREA BEGIN    -->
<?php
 
//	$query1 = $query1 . " order by dateposted desc limit 5";
//	$query2 = $query2 . " order by dateposted desc limit 5";
//	$query3 = $query3 . " order by dateposted desc limit 5";
//	$query4 = $query4 . " order by dateposted desc limit 5";
//	$query5 = $query5 . " order by dateposted desc limit 5";
//	$query6 = $query6 . " order by dateposted desc limit 5";
    $_type = 0;

?>			
<div style='clear:both; padding-top: 5px; padding-bottom: 5px'></div>
<!--			<div style='padding-top: 5px; padding-bottom: 5px'>-->
<!--           <hr  />-->
<!--            </div>-->
			<h1>Resources</h1>
			<hr>
			<div class="divw">
                        	<div class="w_div" style="display:<?php	echo ($_type == 4 || $_type == 0) ? '' : 'none;' ?>">
                            	<div class="w_titlediv">
                                	<div class="w_titlediv_inner">
                                    	<div class="titlewebsite"  >Articles</div>

                                        <?php
							$search_id ='';					
							$sql=mysql_query($query1);
							if($sql)
							{
								$count=mysql_num_rows($sql);		
								$message="";
								if($count>0)
								{							
									while($row=mysql_fetch_array($sql))
									{	
										$search_id=$row['resourceid'];
									
										$message=$message."<div class='txtW_area1'>
                           	          <div class='smalltxt'>".substr($row['dateposted'],5)."</div>
                                            <div class='titlemain'><span class='titlered'><a href='viewresource.php?resourceid=".$row["resourceid"]."'>".$row['subject']."</a></span><br />
                                        <span class='txtred'>".$row['fname']." ".$row['lname']."</span></div>
                                      </div>";	
										
									}
								}
								else 
								{
									$message="<div class='txtred'>No Resources Found.</div>";
								}
								
								echo $message;  
							}
							else
								echo "<div class='txtred'>No Resources Found.</div>";
							?>    
                                      <div class="wbottomtxt">
                                      [ <a href="viewallresources.php?resourcetypeid=4&name=Articles">View all</a> ]<?php /*if($_SESSION["usertypeid"]=="3" || $_SESSION["usertypeid"]=="4") {*/?>&nbsp;&nbsp;[ <a href="addresource.php?resourcetypeid=4">Create</a> ]<?php //} ?> </div>
                                    </div>
                                </div>
                            </div>
                            <div class="w_div1" style="display:<?php	echo ($_type == 2 || $_type == 0) ? '' : 'none;' ?>">
                            	<div class="w_titlediv">
                                	<div class="w_titlediv_inner">
                                    	<div class="titlewebsite">Websites</div>
                                        <?php
													
//                            print_r($query2);
//                            exit;
							$sql=mysql_query($query2);
							if($sql)
							{
								$count=mysql_num_rows($sql);		
								$message="";
								if($count>0)
								{							
									while($row=mysql_fetch_array($sql))
									{	
										$search_id=$row['resourceid'];
									
										$message=$message."<div class='txtW_area1'>
                           	          <div class='smalltxt'>".substr($row['dateposted'],5)."</div>
                                            <div class='titlemain'><span class='titlered'><a href='viewresource.php?resourceid=".$row["resourceid"]."'>".$row['subject']."</a></span><br />
                                        <span class='txtred'>".$row['fname']." ".$row['lname']."</span></div>
                                      </div>";	
										
									}
								}
								else 
								{
									$message="<div class='txtred'>No Resources Found.</div>";
								}
								
								echo $message;  
							}
							else
								echo "<div class='txtred'>No Resources Found.</div>";
							?>
                                        <div class="wbottomtxt">
                                      [ <a href="viewallresources.php?resourcetypeid=2&name=Websites">View all</a> ]<?php //if($_SESSION["usertypeid"]=="3" || $_SESSION["usertypeid"]=="4") {?>&nbsp;&nbsp;[ <a href="addresource.php?resourcetypeid=2">Create</a> ]<?php //} ?> </div>
                                    </div>
                                </div>
                            </div>
                            <div class="w_div2"  style="display:<?php	echo ($_type == 1 || $_type == 0) ? '' : 'none;' ?>">
                            	<div class="w_titlediv">
                                	<div class="w_titlediv_inner">
                                    	<div class="titlewebsite">Videos</div>
                                        <?php
													
							$sql=mysql_query($query3);
							if($sql)
							{
								$count=mysql_num_rows($sql);		
								$message="";
								if($count>0)
								{							
									while($row=mysql_fetch_array($sql))
									{	
										$search_id=$row['resourceid'];
									
										$message=$message."<div class='txtW_area1'>
                           	          <div class='smalltxt'>".substr($row['dateposted'],5)."</div>
                                            <div class='titlemain'><span class='titlered'><a href='viewresource.php?resourceid=".$row["resourceid"]."'>".$row['subject']."</a></span><br />
                                        <span class='txtred'>".$row['fname']." ".$row['lname']."</span></div>
                                      </div>";	
										
									}
								}
								else 
								{
									$message="<div class='txtred'>No Resources Found.</div>";
								}
								
								echo $message;  
							}
							else
								echo "<div class='txtred'>No Resources Found.</div>";
							?>
                                      <div class="wbottomtxt">
                                      [ <a href="viewallresources.php?resourcetypeid=1&name=Videos">View all</a> ]<?php //if($_SESSION["usertypeid"]=="3" || $_SESSION["usertypeid"]=="4") {?>&nbsp;&nbsp;[ <a href="addresource.php?resourcetypeid=1">Create</a> ]<?php //} ?> </div>
                                    </div>
                                </div>
                            </div>
                            <div class="w_div3" style="display:<?php	echo ($_type == 5 || $_type == 0) ? '' : 'none;' ?>">
                            	<div class="w_titlediv">
                                	<div class="w_titlediv_inner">
                                    	<div class="titlewebsite">Teleconferences</div>
                                        <?php
													
							$sql=mysql_query($query4);
							if($sql)
							{
								$count=mysql_num_rows($sql);		
								$message="";
								if($count>0)
								{							
									while($row=mysql_fetch_array($sql))
									{	
										$search_id=$row['resourceid'];
									
										$message=$message."<div class='txtW_area1'>
                           	          <div class='smalltxt'>".substr($row['dateposted'],5)."</div>
                                            <div class='titlemain'><span class='titlered'><a href='viewresource.php?resourceid=".$row["resourceid"]."'>".$row['subject']."</a></span><br />
                                        <span class='txtred'>".$row['fname']." ".$row['lname']."</span></div>
                                      </div>";	
										
									}
								}
								else 
								{
									$message="<div class='txtred'>No Resources Found.</div>";
								}
								
								echo $message;  
							}
							else
								echo "<div class='txtred'>No Resources Found.</div>";
							?>
                                      <div class="wbottomtxt">
                                      [ <a href="viewallresources.php?resourcetypeid=5&name=Teleconferences">View all</a> ]<?php //if($_SESSION["usertypeid"]=="3" || $_SESSION["usertypeid"]=="4") {?>&nbsp;&nbsp;[ <a href="addresource.php?resourcetypeid=5">Create</a> ]<?php //} ?> </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                      <div class="w_div2" style="display:<?php	echo ($_type == 3 || $_type == 0) ? '' : 'none;' ?>">
                          <div class="w_titlediv">
                              <div class="w_titlediv_inner">
                                  <div class="titlewebsite">Organizations</div>
                                  <?php
													
							$sql=mysql_query($query5);
							if($sql)
							{
								$count=mysql_num_rows($sql);		
								$message="";
								if($count>0)
								{							
									while($row=mysql_fetch_array($sql))
									{	
										$search_id=$row['resourceid'];
									
										$message=$message."<div class='txtW_area1'>
                           	          <div class='smalltxt'>".substr($row['dateposted'],5)."</div>
                                            <div class='titlemain'><span class='titlered'><a href='viewresource.php?resourceid=".$row["resourceid"]."'>".$row['subject']."</a></span><br />
                                        <span class='txtred'>".$row['fname']." ".$row['lname']."</span></div>
                                      </div>";	
										
									}
								}
								else 
								{
									$message="<div class='txtred'>No Resources Found.</div>";
								}
								
								echo $message;  
							}
							else
								echo "<div class='txtred'>No Resources Found.</div>";
							?>
                                  <div class="wbottomtxt">
                                      [ <a href="viewallresources.php?resourcetypeid=3&name=Organizations">View all</a> ]<?php //if($_SESSION["usertypeid"]=="3" || $_SESSION["usertypeid"]=="4") {?>&nbsp;&nbsp;[ <a href="addresource.php?resourcetypeid=3">Create</a> ]<?php// } ?>
                                  </div>
                              </div>
                          </div>
                      </div>
                      <div class="w_div3"  style="display:<?php	echo ($_type == 6 || $_type == 0) ? '' : 'none;' ?>">
                          <div class="w_titlediv">
                              <div class="w_titlediv_inner">
                                  <div class="titlewebsite">Other Resources</div>
                                  <?php
													
							$sql=mysql_query($query6);
							if($sql)
							{
								$count=mysql_num_rows($sql);		
								$message="";
								if($count>0)
								{							
									while($row=mysql_fetch_array($sql))
									{	
										$search_id=$row['resourceid'];
									
										$message=$message."<div class='txtW_area1'>
                           	          <div class='smalltxt'>".substr($row['dateposted'],5)."</div>
                                            <div class='titlemain'><span class='titlered'><a href='viewresource.php?resourceid=".$row["resourceid"]."'>".$row['subject']."</a></span><br />
                                        <span class='txtred'>".$row['fname']." ".$row['lname']."</span></div>
                                      </div>";	
										
									}
								}
								else 
								{
									$message="<div class='txtred'>No Resources Found.</div>";
								}
								
								echo $message;  
							}
							else
								echo "<div class='txtred'>No Resources Found.</div>";
							?>
                                  <div class="wbottomtxt">
                                      [ <a href="viewallresources.php?resourcetypeid=6&name=Other Resources">View all</a> ]<?php //if($_SESSION["usertypeid"]=="3" || $_SESSION["usertypeid"]=="4") {?>&nbsp;&nbsp;[ <a href="addresource.php?resourcetypeid=6">Create</a> ]<?php //} ?>
                                  </div>
                              </div>
                          </div>
                      </div>
<!--			    RESOURCES AREA END    -->
                
<!--                BLOG AREA START   -->
<div style='clear:both; padding-top: 5px; padding-bottom: 5px'></div>
<h1>Blog</h1>
<hr>
<div name="newboxes" id="posts">
                                                <div> 
                                                    <?php 
					$query_post=" 										
					select *, 					
					blogpostid,posttext,postimage,postvideo,postembedvideolink,postedbyuserid,postedonuserid,datetimeposted,privacylevel,
					(select CONCAT(fname,' ',lname) from tbluser where userid=p.postedbyuserid) as poster,
					(select thumb_profile  from tbluser where userid=p.postedbyuserid) as posterpic
					
					from tblblogposts p 
					right outer join tbluser u on u.userid = p.postedonuserid
					where posttext like '%".mysql_real_escape_string($_POST['searchstr'])."%'
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
                                                                <img src="profilepics/<?php echo $row["profilepic"]; ?>" style="background-color:#FFFFFF" width="50" height="50" border="0">
                                                            </div>
                                                            <div style="float:left;display:inline;padding-left:10px;min-height:50px;width:530px;background-color:#e7e7e7;">
                                                                <div style="float:left;">
                                                                    <a class="bluelink" href="viewprofile.php?userid=<?php echo $row['userid']; ?>"><?php echo $row["fname"]; ?>
                                                                    </a>
                                                                </div>
                                                                <div style="clear:both;"></div>
                                                                <div style="float:left;">
                                                                    <form action="postcomment.php" method="post" enctype="multipart/form-data" >
                                                                        <input type="hidden" id="blogpostid" name="blogpostid" value="<?php echo $row["blogpostid"]; ?>" />
                                                                        <input type="hidden" id="postedbyuserid" name="postedbyuserid" value="<?php echo $row["userid"]; ?>" />
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
<!--				 BLOG AREA END    -->
                
                
               <?php } else echo '<br><p>No Results Available!</p>' ;//end of if(!empty($query))  ?>
        </div>
    </div>
</div>
</div>	  
        
<?php include "footer.php"; ?>