<?php include "header_inner.php"; ?>

<link rel="stylesheet" href="colorbox/colorbox.css" />
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
		<script src="colorbox/jquery.colorbox.js"></script>
		<script>
			$(document).ready(function(){
				//Examples of how to assign the ColorBox event to elements
				$(".group1").colorbox();
			});
		</script>
		
<?php

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
	
		if($_SERVER['REQUEST_METHOD']=='POST')
		{
				// Search function in this page
				$uname = str_replace("'","''",$_POST["uname"]);
				$uname=str_replace("\"","''",$uname);
				$uname=stripslashes($uname);
				$usertypeid = $_POST["usertypeid"];
				$diseaseid = $_POST["diseaseid"];
				
				$where = '';
				
				if($uname!='' && trim($uname)!='')
					//$where = $where. " and alias like '%".$uname."%'"; //" and (fname like '%".$uname."%' or lname like '%".$uname."%' or concat( fname, ' ', lname ) like '%".$uname."%')"; 
				
				$where = $where. " and CASE WHEN usealias=1 THEN alias like '%".$uname."%' ELSE (fname like '%".$uname."%' or lname like '%".$uname."%' or concat( fname, ' ', lname ) like '%".$uname."%') END";
				
				if($usertypeid!='0')
				{
					//if($where=='')
					//	$where = $where. " tbluser.usertypeid = ".$usertypeid;
					//else 
						$where = $where. " and tbluser.usertypeid = ".$usertypeid; 
				}
				
				if($diseaseid!='0')
				{
					//if($where=='')
					//	$where = $where. " tbluser.diseaseid = ".$diseaseid;
					//else 
						$where = $where. " and tbluser.diseaseid = ".$diseaseid; 
				}
				
				$query = "
				select 
					userid,
					fname,
					lname,
					thumb_profile as profilepic,
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
 				
				if($where!='')
					$query=$query.$where;//$query=$query." where ".$where;
					
				$query=$query." limit 10";				
		}
		else
		{
			if(isset($_GET["usertypeid"]) && isset($_GET["diseaseid"]) && isset($_GET["uname"]))
			{
				//Call to this module members.php from addfriend2.php or confrimfriend2.php
				
				$uname = str_replace("'","''",$_GET["uname"]);
				$uname=str_replace("\"","''",$uname);
				$uname=stripslashes($uname);
				$usertypeid = $_GET["usertypeid"];
				$diseaseid = $_GET["diseaseid"];
				
				$where = '';
				
				if($uname!='' && trim($uname)!='')
				//$where = $where. " and alias like '%".$uname."%'"; //" and (fname like '%".$uname."%' or lname like '%".$uname."%' or concat( fname, ' ', lname ) like '%".$uname."%')"; 				
				$where = $where. " and CASE WHEN usealias=1 THEN alias like '%".$uname."%' ELSE (fname like '%".$uname."%' or lname like '%".$uname."%' or concat( fname, ' ', lname ) like '%".$uname."%') END";
				
				if($usertypeid!='0')
				{
					//if($where=='')
					//	$where = $where. " tbluser.usertypeid = ".$usertypeid;
					//else 
						$where = $where. " and tbluser.usertypeid = ".$usertypeid; 
				}
				
				if($diseaseid!='0')
				{
					//if($where=='')
					//	$where = $where. " tbluser.diseaseid = ".$diseaseid;
					//else 
						$where = $where. " and tbluser.diseaseid = ".$diseaseid; 
				}
				
				$query = "
				select 
					userid,
					fname,
					lname,
					thumb_profile as profilepic,
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
				
				if($where!='')
					$query=$query.$where;//$query=$query." where ".$where;
					
				$query=$query." limit 10";
			}
			else
			{
				//Usual members display
				$query = "
					select 
					userid,
					fname,
					lname,
					thumb_profile as profilepic,
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
					from tbluser,tbldisease,tblusertype where tbluser.isactive=1 and tbldisease.diseaseid=tbluser.diseaseid and 
					tblusertype.usertypeid=tbluser.usertypeid and tbluser.userid <> ".$userid." limit 10";
				}
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
        <a href="myprofile.php" class="whitetitle size12">Home</a>&nbsp;&nbsp;<span class="size12">>></span>&nbsp;&nbsp;<span class="whitetitle size12">Search All Members</span>
    </div>
    <div class="body">
        <div class="main_link">
            <div class="inbox_title">
                
                <div class="f_left bluetitle size30 bold" style="padding-top:5px; width:auto;">
                    Members
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

            <div class="menulinks">
                <div class="top-i"></div>
                <div class="centertxt">
                    <table cellpadding="0" cellspacing="0" style="width:660px; border:0px;">
                     
                        <tr>
                            <td style="width:660px;" colspan="3" align="left" valign="top">
                                <table cellpadding="0" cellspacing="0" style="width:660px; border:0px;">
                                    <form action="members.php" method="post" enctype="multipart/form-data" >
                                        <input type="hidden" id="uname_h" name="uname_h" value="<?php echo $uname; ?>" />
                                        <input type="hidden" id="usertypeid_h" name="usertypeid_h" value="<?php echo $usertypeid; ?>" />
                                        <input type="hidden" id="diseaseid_h" name="diseaseid_h" value="<?php echo $diseaseid; ?>" />

                                        <tr>
                                            <td style="width:58px; height:10px;" align="left" valign="top"></td>
                                            <td style="width:110px;" align="left" valign="top"></td>
                                            <td style="width:35px;" align="left" valign="top"></td>
                                            <td style="width:50px;" align="left" valign="top"></td>
                                            <td style="width:110px;" align="left" valign="top"></td>
                                            <td style="width:35px;" align="left" valign="top"></td>
                                            <td style="width:50px;" align="left" valign="top"></td>
                                            <td style="width:132px;" align="left" valign="top"></td>
                                            <td rowspan="2" align="left" valign="bottom" style="width:80px;">
                                                <!--<a href="#">
                                                <img style="border:0px;" src="images/search-btn.jpg" />
                                            </a>-->
                                                <input id="submit-membersearch" type="submit" value="Search" />
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="width:58px;" align="left" class="size12">
                                                Name
                                            </td>
                                            <td style="width:110px;" align="left" valign="top">
                                                <input value="<?php echo $uname; ?>" style="width:115px; border:1px #bcbcbc solid; height:18px; padding-left:3px;" name="uname" id="uname" type="text" />
                                            </td>
                                            <td style="width:35px;" align="left">&nbsp;
                                                
                                            </td>
                                            <td style="width:50px;" align="left" class="size12">Type </td>
                                            <td style="width:110px;" align="left" valign="top">
                                                <select name="usertypeid" id="usertypeid" style="width:115px; border:1px #bcbcbc solid; height:22px; padding-left:3px;">
                                                    <option value="0" >Any</option>
                                                    <?php    
											$q="select usertypeid,strusertype from tblusertype";	
											$r=mysql_query($q);
											if($r)
											{	
												$n=mysql_num_rows($r);
												if($n>0)
												{			
													$count=0;	
													while($rw=mysql_fetch_array($r))
													{
														echo "<option value='".$rw["usertypeid"]."'";
														if ($rw["usertypeid"]==$usertypeid) echo "selected='selected'";
														echo " >".$rw["strusertype"]."</option>";
														$count++;			
													}
												}
											} 
										?>
                                                </select>
                                            </td>
                                            <td style="width:35px;" align="left">
                                                <!--<img style="border:0px;" src="images/calender.jpg" />--> &nbsp;
                                            </td>
                                            <td style="width:50px;" align="left" class="size12">Interest</td>
                                            <td style="width:132px;" align="left" valign="top">
                                                <select name="diseaseid" id="diseaseid" style="width:115px; border:1px #bcbcbc solid; height:22px; padding-left:3px;">
                                                    <option value="0" >Any</option>
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
                                                    <option value="15">Other</option>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="width:660px; height:10px;" colspan="9" align="left"></td>
                                        </tr>
                                    </form>
                                </table>
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="bottom-i"></div>
            </div>
            
            
            <div class="email_table">              
                <table cellpadding="0" cellspacing="0" style="width:686px; border:0px;">
                    <tr>
                        <td style="width:686px;"align="left" valign="top">
                        </td>
                    </tr>
                            <tr>
                        <td style="width:686px;" align="left" valign="top">
                            <?php 
					if($query!='')
					{
					?>
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
											$message=$message."<div class='searchlist'><a class='group1' href='profilepics/".$row["profilepic"]."'><img src='profilepics/".$row["profilepic"]."' style='background-color:#FFFFFF' width='50' border='0'></a></div>";
										else
											$message=$message."<div class='searchlist'><a class='group1' href='profilepics/tp-youcureme_35_201203231332476162.jpg'><img src='profilepics/empty_profile.jpg' style='background-color:#FFFFFF' width='50'  border='0'></a></div>";
										
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

                                    <div id="moresearch<?php echo $search_id; ?>" class="morebox">
                                        <a href="#" class="moresearch" id="<?php echo $search_id; ?>"> <img src="images/more.png" alt="more" width="40" height="40" border="0"/>
                                        </a>
                                    </div>
                                </div>
                                <!-- End of msg-list -->
                            </div>
                            <!-- end of inbox -->

                            <?php 
					}
					?>
        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>    
        </div>	  
        
<?php include "footer.php"; ?>