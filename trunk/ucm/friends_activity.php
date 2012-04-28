<?php 
include "header_inner.php";ini_set('display_errors',1);
function age($date){
    list($year,$month,$day) = explode("-",$date);
    $year_diff  = date("Y") - $year;
    $month_diff = date("m") - $month;
    $day_diff   = date("d") - $day;
    if ($day_diff < 0 || $month_diff < 0) $year_diff--;
    return $year_diff;
}
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
//$usertypeid = $_SESSION["usertypeid"];
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
		$row1=mysql_fetch_array($result);	
				
		$fname=$row1["fname"];
		$lname=$row1["lname"];
		$email=$row1["email"];
		$password=$row1["password"];
		
		if($row1["profilepic"] == '' || $row1["profilepic"] == null)
			
			$profilepic="empty_profile.jpg";
		else
			$profilepic=$row1["profilepic"];
		$dob=$row1["dob"];
		if($row1["genderid"]=="1")
			$gender="Male";
		else
			$gender="Female";
		$usertype=$row1["usertype"];
		$disease=$row1["disease"];
		$city=$row1["city"];
		$country=$row1["country"];
		$website=$row1["website"];
		$iam=$row1["iam"];
		$ilike=$row1["ilike"];
		$myexperience=$row1["myexperience"];
		$isactive=$row1["isactive"];
		$age = age($dob). " years";				
		//if(age($dob)==0){
		$birthDate = explode("-", $dob);
				if(date('Y') != $birthDate[0]){
				$mons = 12 - $birthDate[1] + date('m');
				if($mons > 11){$mons = $mons - 12;}
				$age .= ", ".$mons. " months";
				} else {
				$age .= ", ".(date('m') - $birthDate[1]). " months";
				}
				
				$dys = 30 - $birthDate[2] + date('d');
				if($dys > 29){$dys = $dys - 30;}
				$age .= ", ".$dys. " days";
		//}
		if(age($dob)==date('Y')){		$age = "Not Specified";		}
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
		$row_frnds_count = mysql_fetch_array($result_frnds_count);	
	
		$frnds_count = $row_frnds_count[0];//." ".$userid;
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
	
	
}

if(!isset( $row1 ["profilepic"] ))
	$profilepic = "empty_profile.jpg";
else {
	
	if ($row1 ["profilepic"] == '' || $row1 ["profilepic"] == null)
		
		$profilepic = "empty_profile.jpg";
	else
		$profilepic = $row1 ["profilepic"];
}
?>
<!-- FRIENDS ACTIVITY PAGE FEED LIST MORE BUTTON -->
<script type="text/javascript">
$(function() {
//More Button 
$('.morefeeds').live("click",function() 
{
var ID = $(this).attr("id");
var notcounter = document.getElementById("notcounter").value; 
if(ID)
{
$("#morefeeds"+ID).html('<img src="images/more.png" alt="more" width="40" height="40" border="0"/>');

$.ajax({
type: "POST",
url: "feeds_more.php",
data: "notcounter="+notcounter, 
cache: false,
success: function(html){
$("ol#updates").append(html);
$("#morefeeds"+ID).remove();
}
});
}
else
{
$(".morebox").html('The End');
}

document.getElementById("notcounter").value = notcounter*1+10;
return false;

});
});
</script>
<!-- END OF FRIENDS ACTIVITY PAGE FEED LIST MORE BUTTON -->

<div class="warpper">
<div class="left_side">
<!--
<div class="left_contant">
<div class="user_info">
<div class="user_img"> <img
	src="profilepics/<?php echo $profilepic; ?>" width="63" height="59"
	border="0" /></div>
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
	<li><a href="editprofile.php?userid=<?php echo $_SESSION["userid"]; ?>">Account
	Settings</a></li>
	<li><a href="privacy.php">Privacy Settings</a></li>
	<li><a href="myprofile.php">My Profile</a></li>
	<li><a href="photos.php">My Photos</a></li>
	<li><a href="myblog.php">My Blog</a></li>
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
	<li><a href="notifications.php">My Notifications</a></li>
	
</ul>
</div>
</div>
<div class="profile_links">
<div class="title_txt">
<div class="plusimg"><img src="images/plus_icon.jpg" /></div>
<div class="txttitle whitetitle size12 bold">Make a Difference</div>
</div>
<div class="txt_links">
<ul>
	<li><a href="resources.php">Health Resources</a></li>
</ul>
<ul>
	<li><a href="survey.php">Surveys</a></li>
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
	<li><a href="myinterestmembers.php">Members With My Interest(<?php echo $disease_match_count-1;?>)</a>
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
	<li><a href="importcontacts.php">Import Contacts</a></li>
	<li><a href="reqs.php">
                                        Friend Requests (<?php echo $req_count;?>)
                                    </a></li>
</ul>
</div>
</div>

-->
<?php require("left_usermenu.php");?>	
</div>
</div>

<div class="body_menu whitetitle"><a href="myprofile.php"
	class="whitetitle size12">Home</a>&nbsp;&nbsp;<span
	class="whitetitle size12">>></span>&nbsp;&nbsp;<span
	class="whitetitle size12" class="bluetitle size14 bold">My Profile</span>
</div>

<div class="body">
<div class="main_link">
<div class="inbox_title"><!--<div class="f_left">
                    <img align="absmiddle" src="images/inbox_icon.jpg" />
                </div>-->
<div class="f_left bluetitle size30 bold"
	style="padding-top: 5px; width: auto;">My Friends Activity</div>
<div class="right_img">
<div class="inbox_img"></div>
<div class="outbox_img"></div>
<div class="notification_img"></div>
</div>
</div>
<?php 

if(!isset($_SESSION["userid"]) || $_SESSION["userid"] =="")
{
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
	
	if(isset($_POST['feed'])){
		$feed_message = $_POST['feed_msg'];
		$query = "INSERT INTO `tblfeeds`  (`userid`,`message`)VALUES ($userid, '$feed_message')";
	  	mysql_query($query);
		
		
		$notification_msg = "<a href='viewprofile.php?userid=".$userid."'>".$fname." ".$lname."</a> has  posted on <a href='friends_activity.php'>news feed</a>";
			
		$query_get_friends_ids=sprintf("select friendwith from tblfriends where userid='%s' and friendshipstatus=2",$userid);
			
/*		$query_get_friends_ids=sprintf(
			"SELECT * FROM `tblfeeds`
			where userid='%s' or userid in (select friendwith from tblfriends where userid='%s' and friendshipstatus=2)
			order by created desc limit 10",
		$userid,$userid);*/


		$result_get_friends_ids =mysql_query($query_get_friends_ids);
		while($_row=mysql_fetch_array($result_get_friends_ids))
		{
			$friendid = $_row['friendwith'];
			
			$notification_query = sprintf("insert into `tblnotifications`	( `userid`, `notification_type`, `notification`, `notificationtime` )
				values	(		'%s',		1,		'%s',		now()	)",
				mysql_real_escape_string($friendid),
				mysql_real_escape_string($notification_msg));
				
			mysql_query($notification_query);		
		}
	}
}

?>
<!--START OF BODY-->
<form action="#" method="post">
<div class="menulinks">
<div class="top-i"></div>
<div class="centertxt">
<table style="width: 80%">
	<tr>
		<td style="width: 10%">Feed</td>
		<td style="width: 85%"><input style="width: 90%" type="text"
			name="feed_msg" /></td>
		<td style="width: 5%">
<!--		<input type="submit" name="feed" value="" />-->
		<input id="submit-membersearch" name="feed" type="submit" value="Post">
		</td>
	</tr>
</table>

</div>
<div class="bottom-i"></div>
</div>
</form>

<!--SHOWING THE FEEDS LIST -->

<div class="body">
<div class="main_link">
<div class="inbox_title"><!--                <div class="f_left bluetitle size30 bold" style="padding-top:5px; width:auto;">-->
<!--                    Notifications--> <!--                </div>-->
<div class="right_img">
<div class="inbox_img"></div>
<div class="outbox_img"></div>
<div class="notification_img"></div>
</div>
</div>

<div class="email_table">
<table cellpadding="0" cellspacing="0"
	style="width: 686px; border: 0px;">
	<tr>
		<td style="width: 686px;" align="left" valign="top">
		<ol class="timeline" id="updates">
                           <?php

						$query_get_feeds=sprintf(
							"SELECT * FROM `tblfeeds`
							where userid='%s' or userid in (select friendwith from tblfriends where userid='%s' and friendshipstatus=2)
							order by created desc limit 10",
							$userid,$userid);
     
                           
                           
							$sql=mysql_query($query_get_feeds);
							
							$count=mysql_num_rows($sql);	
							$message="";
							if($count>0)
							{							
								while($row=mysql_fetch_array($sql))
								{									
									$notificationid=$row['id'];
									
									$message=$message."<div class='not-general'>";	
										
									$message=$message."<div class='notlist'>";
									$message=$message.get_name_link($row['userid'])." ".$row['message'];//<br/>";
									$message=$message."</div>";
									
									$message=$message."<div >";

									$message=$message."<div class='notifications-date'>
													".$row["created"]."
												</div>";
									$message=$message."<div style='clear:both;'></div>";
									$message=$message."</div>";  
									
									$message=$message."<div style='clear:both;'></div>";
									
									$message=$message."</div>";
								
								}
							}
							else 
							{
								echo "<div class='req-general'>No Activities Found.</div>";
							}
							?>
                            
                                <li>
                                    <?php
									
							echo $message; 
							?>

                                </li>
		</ol>

		<div id="morefeeds<?php echo $notificationid; ?>" class="morebox"><a
			href="#" class="morefeeds" id="<?php echo $notificationid; ?>"><img
			src="images/more.png" alt="more" width="40" height="40" border="0" />
		</a></div>
		</td>
	</tr>
</table>
</div>
</div>
</div>
<!--END OF BODY--> <!-- </div> --></div>
</div>
</div>
<?php include "footer.php"; ?>
<?php 
function get_name_link($userid){
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
	$result =	mysql_query($query);
	if(mysql_num_rows($result)>0){
		$row=mysql_fetch_array($result);
		
		//$image = '<a href="viewprofile.php?userid='.$userid.'"><img src="profilepics/'.$row['profilepic'].'" style="background-color:#FFFFFF" width="50" height="50" border="0"/></a>';
		
		$rslt = "<a href='viewprofile.php?userid=".$userid."'>".  $row['fname']." ".$row['lname']."</a>";
		return $rslt;
	}
	else{
		return '';
	}
}
?>