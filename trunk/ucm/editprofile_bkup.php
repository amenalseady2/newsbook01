<?php 
include "header_inner.php";
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
if(isset($_SESSION["usertypeid"]))
   $usertypeid = $_SESSION["usertypeid"];
else 
   $usertypeid=1;
   
$resourcetypeid = "0";
$name='';

$fname='';
	$lname='';
	$email='';
	$password='';
	$profilepic='';
	$dob='0000-00-00';
	$genderid=1;	
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
		echo "<script>location.href='login.php' </script>";

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
		$year=date("Y")- $dateofbirth[0];
		$month=abs(date("m")- $dateofbirth[1]);
		$day=abs(date("d")- $dateofbirth[2]);
		$age =$year." years , ".$month." months & ".$day." days " ;
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
	
	$query1 = '';
	$where = '';
		
	if(!isset($_SESSION["userid"]) || $_SESSION["userid"] =="")
	{	
		header("location: index.php");
		echo "<script>location.href='login.php' </script>";   
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
					    alias,
						rcvemail4msgs,
						rcvemail4notifications
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
				$rcvemail4msgs = $row["rcvemail4msgs"];
				$rcvemail4notifications = $row["rcvemail4notifications"];
			    $alias=$row["alias"];	
			    $dateofbirth=explode("-",$dob);
		    }
		}
		
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
                    <a href="myprofile.php" class="whitetitle size12">Home</a>&nbsp;&nbsp;<span class="size12">>></span>&nbsp;&nbsp;<span class="whitetitle size12">Account Settings</span>
                </div>

                <div class="body">
                    <div class="main_link">
                        <div class="inbox_title">
                            
                            <div class="f_left bluetitle size30 bold" style="padding-top:5px; width:auto;">Account Settings</div>
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
                                <form action="updateprofile.php" method="post" enctype="multipart/form-data" id="customForm" >
                                    <input type="hidden" id="isactive" name="isactive" value="<?php echo $isactive; ?>" />
                                    <input type="hidden" id="userid" name="userid" value="<?php echo $userid; ?>" />

                                    <tr>
                                        <td style="width:36px; height:12px;" colspan="5" align="left" valign="top"></td>
                                    </tr>

                                    <tr>
                                        <td style="width:561px;" align="left" colspan="5" class="bluetitle size14 bold">
                                            Please fill-in your details
                                        </td>
                                    </tr>



                                    <tr>
                                        <td style="width:36px; height:3px;" colspan="5" align="left" valign="top"></td>
                                    </tr>
                                    <tr>

                                        <td style="width:130px;" align="left" class="bold size12">
                                            <?php echo $item['signup_item1']; ?>
                                        </td>
                                        <td colspan="4" style="width:556px;" align="left" class="size12">
                                            <input type="text" name="fname" id="fname" style="width:160px; border:1px #bcbcbc solid; height:16px; padding-left:3px;" value="<?php echo $fname; ?>" />&nbsp;&nbsp;<span id="msgfname"></span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="width:36px; height:3px;" colspan="5" align="left" valign="top"></td>
                                    </tr>
                                    <tr>

                                        <td style="width:130px;" align="left" class="bold size12">
                                            <?php echo $item['signup_item2']; ?>
                                        </td>
                                        <td colspan="4" style="width:556px;" align="left" class="size12">
                                            <input type="text" name="lname" id="lname" style="width:160px; border:1px #bcbcbc solid; height:16px; padding-left:3px;" value="<?php echo $lname; ?>" />&nbsp;&nbsp;<span id="msglname"></span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="width:36px; height:3px;" colspan="5" align="left" valign="top"></td>
                                    </tr>
                                    <tr>

                                        <td style="width:130px;" align="left" class="bold size12">
                                            <?php echo $item['signup_alias']; ?>
                                        </td>
                                        <td colspan="4" style="width:556px;" align="left" class="size12">
                                            <input type="text" name="alias" id="alias" style="width:160px; border:1px #bcbcbc solid; height:16px; padding-left:3px;"  value="<?php echo $alias; ?>" />&nbsp;<span id="msgalias"></span>&nbsp;<input type="checkbox" id="usealias" name="usealias" <?php if($usealias=="1") { echo " checked='checked' "; } ?> onclick="showhidealias()" />&nbsp;<?php echo $item['signup_usealias']; ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="width:36px; height:3px;" colspan="5" align="left" valign="top"></td>
                                    </tr>
                                    <tr>

                                        <td style="width:125px;" align="left" class="bold size12">
                                            <?php echo $item['signup_item7']; ?>
                                        </td>
                                        <td colspan="4" style="width:561px;" align="left" class="size12">
                                            <input style="width:160px; border:1px #bcbcbc solid; height:16px; padding-left:3px;" type="file" id="profilepic" name="profilepic"/><?php if($profilepic!='') { ?>&nbsp;&nbsp;<a href="#" style="margin-left:35px;" class="bluelink" onClick="window.open('"
                                                <?php echo("profilepics/".$profilepic); ?>','View Profile Picture','width=400,height=200,left=0,top=100,screenX=0,screenY=100')">&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbspView
                                            </a> <?php } ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="width:36px; height:6px;" colspan="5" align="left" valign="top"></td>
                                    </tr>
                                    <tr>

                                        <td style="width:130px;" align="left" class="bold size12">
                                            <?php echo $item['signup_item8']; ?>
                                        </td>
                                        <td colspan="4" style="width:556px;" align="left" class="size12">

                                            <?php
									  $myCalendar = new tc_calendar("dob", true, false);
									  $myCalendar->setIcon("images/calender.jpg");
									  
									  if($dateofbirth[0]!="0000")
									  	$myCalendar->setDate($dateofbirth[2], $dateofbirth[1], $dateofbirth[0]);//date('d'), date('m'), date('Y'));
									  
									  $myCalendar->setPath("./");
									  $myCalendar->setYearInterval(1900, 2000);
									  $myCalendar->dateAllow('1910-01-01', '2011-12-01');
									  $myCalendar->setDateFormat('j F Y');
									  //$myCalendar->setHeight(350);	  
									  //$myCalendar->autoSubmit(true, "form1");
									  $myCalendar->writeScript();
									?>

                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="width:36px; height:3px;" colspan="5" align="left" valign="top"></td>
                                    </tr>
                                    <tr>

                                        <td style="width:125px;" align="left" class="bold size12">
                                            <?php echo $item['signup_item9']; ?>
                                        </td>
                                        <td colspan="4" style="width:561px;" align="left" class="size12">
                                            <select name="genderid" id="genderid" style="width:160px; border:1px #bcbcbc solid; height:16px; padding-left:3px;">
                                                <option value="1"
                                                    <?php if($genderid=='1') { echo " selected='selected' "; } ?> >Male
                                                </option>
                                                <option value="2"
                                                    <?php if($genderid=='2') { echo " selected='selected' "; } ?> >Female
                                                </option>
                                            </select>

                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="width:36px; height:3px;" colspan="5" align="left" valign="top"></td>
                                    </tr>
                                    <tr>
                                        <td style="width:125px;" align="left" class="bold size12">
                                            <?php echo $item['signup_item5']; ?>
                                        </td>
                                        <td colspan="4" style="width:561px;" align="left" class="size12">
                                            <select name="usertypeid" id="usertypeid" style="width:160px; border:1px #bcbcbc solid; height:16px; padding-left:3px;">
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
                                    </tr>
                                    <tr>
                                        <td style="width:36px; height:3px;" colspan="5" align="left" valign="top"></td>
                                    </tr>
                                    <tr>
                                        <td style="width:125px;" align="left" class="bold size12">
                                            <?php echo $item['signup_item6']; ?>
                                        </td>
                                        <td colspan="4" style="width:561px;" align="left" class="size12">
                                            <select name="diseaseid" id="diseaseid" style="width:160px; border:1px #bcbcbc solid; height:16px; padding-left:3px;">
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

                                        <td style="width:130px;" align="left" class="bold size12">
                                            <?php echo $item['signup_item10']; ?>
                                        </td>
                                        <td colspan="4" style="width:556px;" align="left" class="size12">
                                            <input type="text" name="city" id="city" style="width:160px; border:1px #bcbcbc solid; height:16px; padding-left:3px;" value="<?php echo $city; ?>" />&nbsp;&nbsp;<span id="msgcity"></span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="width:36px; height:3px;" colspan="5" align="left" valign="top"></td>
                                    </tr>
                                    <tr>
                                        <td style="width:125px;" align="left" class="bold size12">
                                            <?php echo $item['signup_item11']; ?>
                                        </td>
                                        <td colspan="4" style="width:561px;" align="left" class="size12">
                                            <select name="countryid" id="countryid" style="width:160px; border:1px #bcbcbc solid; height:16px; padding-left:3px;">
                                                <?php    
								$q="select CountryID,CountryName,CountryCode from tblcountry";	
											$r=mysql_query($q);
											if($r)
											{	
												$n=mysql_num_rows($r);
												if($n>0)
												{			
													$count=0;	
													while($rw=mysql_fetch_array($r))
													{
														echo "<option value='".$rw["CountryID"]."'";
														if ($rw["CountryID"]==$countryid) echo "selected='selected'";
														echo " >".$rw["CountryName"]."</option>";
														$count++;			
													}
												}
											} 
							?>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="width:36px; height:3px;" colspan="5" align="left" valign="top"></td>
                                    </tr>
                                    <tr>

                                        <td style="width:130px;" align="left" class="bold size12">
                                            <?php echo $item['signup_item3']; ?>
                                        </td>
                                        <td colspan="4" style="width:556px;" align="left" class="size12">
                                            <input type="text" name="email" id="email" style="width:160px; border:1px #bcbcbc solid; height:16px; padding-left:3px;" value="<?php echo $email; ?>"  readonly="readonly"/>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="width:36px; height:3px;" colspan="5" align="left" valign="top"></td>
                                    </tr>
                                    <tr>

                                        <td style="width:130px;" align="left" class="bold size12">
                                            <?php echo $item['signup_item4']; ?>
                                        </td>
                                        <td colspan="4" style="width:556px;" align="left" class="size12">
                                            <input type="password" name="pwd" id="pwd" style="width:160px; border:1px #bcbcbc solid; height:16px; padding-left:3px;"  />&nbsp;&nbsp;<em>Leave blank if you wish not to change your password.</em>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="width:36px; height:3px;" colspan="5" align="left" valign="top"></td>
                                    </tr>
                                    <tr>

                                        <td style="width:130px;" align="left" class="bold size12">
                                            <?php echo $item['signup_item12']; ?>
                                        </td>
                                        <td colspan="4" style="width:556px;" align="left" class="size12">
                                            <input type="text" name="website" id="website" style="width:160px; border:1px #bcbcbc solid; height:16px; padding-left:3px;" value="<?php echo $website; ?>" />&nbsp;&nbsp;<span id="msgwebsite"></span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="width:36px; height:3px;" colspan="5" align="left" valign="top"></td>
                                    </tr>
                                    <tr>

                                        <td style="width:130px;" align="left" class="bold size12">
                                            <?php echo $item['signup_item13']; ?>
                                        </td>
                                        <td colspan="4" style="width:556px;" align="left" class="size12">
                                            <textarea style="height: 120px; width: 248px; border:1px #bcbcbc solid;  padding-left:3px;" id="iam" name="iam" rows="3" cols="50"><?php echo $iam; ?></textarea>&nbsp;&nbsp;<span id="msgiam"></span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="width:36px; height:3px;" colspan="5" align="left" valign="top"></td>
                                    </tr>
                                    <tr>

                                        <td style="width:130px;" align="left" class="bold size12">
                                            <?php echo $item['signup_item14']; ?>
                                        </td>
                                        <td colspan="4" style="width:556px;" align="left" class="size12">
                                            <textarea style="height: 120px; width: 248px; border:1px #bcbcbc solid;  padding-left:3px;" id="ilike" name="ilike" rows="3" cols="50"><?php echo $ilike; ?></textarea>&nbsp;&nbsp;<span id="msgilike"></span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="width:36px; height:3px;" colspan="5" align="left" valign="top"></td>
                                    </tr>
                                    <tr>

                                        <td style="width:130px;" align="left" class="bold size12">
                                            <?php echo $item['signup_item15']; ?>
                                        </td>
                                        <td colspan="4" style="width:556px;" align="left" class="size12">
                                            <textarea style="height: 120px; width: 248px; border:1px #bcbcbc solid;  padding-left:3px;" id="myexperience" name="myexperience" rows="3" cols="50"><?php echo $myexperience; ?></textarea>&nbsp;&nbsp;<span id="msgmyexp"></span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="width:36px; height:3px;" colspan="5" align="left" valign="top"></td>
                                    </tr>
                                    <tr>
                                        <td style="width:125px;" align="left" class="bold size12">Receive Emails for :</td>
                                        <td colspan="4" style="width:561px;" align="left" class="size12">
                                            <input type="checkbox" id="rcvemail4msgs" name="rcvemail4msgs" <?php if($rcvemail4msgs=="1") { echo " checked='checked' "; } ?> onclick="showhidealias()" />&nbsp;Messages&nbsp;&nbsp;<input type="checkbox" id="rcvemail4notifications" name="rcvemail4notifications" <?php if($rcvemail4notifications=="1") { echo " checked='checked' "; } ?> onclick="showhidealias()"/>&nbsp;Notifications
  										</td>
                                    </tr>
                                    <tr>
                                        <td style="width:36px; height:3px;" colspan="5" align="left" valign="top"></td>
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td colspan="4" align="left">
                                            <input id="submit-subscirbe" type="submit" value="Submit" />
                                        </td>
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