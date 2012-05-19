<?php include "header_inner.php";ini_set('display_errors',1);
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
				isactive,
				alias
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


<div class="warpper">
<div class="left_side">
<div class="left_contant">
<div class="user_info">
<div class="user_img"><!--<img src="images/user-img.jpg" />--> <img
	src="profilepics/<?php echo $profilepic; ?>" width="63" height="59"
	border="0" /></div>
	
<?php if(empty($_name))
		$_name=$username;
?>
	
<div class="user_data">
<div class="user_name bluetitle size20 bold" /><?php echo $_name; ?></div>
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

</div>
</div>

<!--    <div class="body_menu whitetitle">
                    <a href="myprofile.php" class="whitetitle size12">Home</a>&nbsp;&nbsp;<span class="size12">>></span>&nbsp;&nbsp;<span class="whitetitle size12">Account Settings</span>
                </div>

                <div class="body">
                    <div class="main_link">
                        <div class="inbox_title">
                            
                            <div class="f_left bluetitle size30 bold" style="padding-top:5px; width:auto;">Account Settings</div>
                            <div class="right_img">
 <div class="body_main"> 
			-->






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
	style="padding-top: 5px; width: auto;">My Profile</div>
<div class="right_img">
<div class="inbox_img"><!--<img src="images/inbox-img.jpg" />
                        <br />
                        <a href="#">Inbox</a>--></div>
<div class="outbox_img"><!--<img src="images/inbox-img.jpg" />
                        <br />
                        <a href="#">Outbox</a>--></div>
<div class="notification_img"><!--<img src="images/inbox-img.jpg" />
                        <br />
                        <a href="#">Notification</a>--></div>
</div>
</div>

<div class="email_table">
<table cellpadding="0" cellspacing="0"
	style="width: 686px; border: 0px;">
	<tr>
	
	
	<tr>
		<td style="width: 36px; height: 12px;" colspan="5" align="left"
			valign="top"></td>
	</tr>

	<tr>
		<td style="width: 561px;" align="left" colspan="5"
			class="bluetitle size14 bold"><a href="editprofile.php">Basic
		Information</a></td>
	</tr>

	<tr>
		<td style="width: 36px; height: 12px;" colspan="5" align="left"
			valign="top"></td>
	</tr>


	<td style="width: 125px;" align="left" class="bold size12">
                                        <?php echo $item['privacy_item1']; ?>
                                    </td>
	<td colspan="4" style="width: 561px;" align="left" class="size12">
                                        <?php echo $fname." ".$lname; ?>
                                    </td>
	</tr>
	<tr>
		<td style="width: 36px; height: 3px;" colspan="5" align="left"
			valign="top"></td>
	</tr>
	<tr>

		<td style="width: 125px;" align="left" class="bold size12">
                                        <?php echo $item['privacy_item4']; ?>
                                    </td>
		<td colspan="4" style="width: 561px;" align="left" class="size12">
                                        <?php echo $gender; ?>
                                    </td>
	</tr>
	<tr>
		<td style="width: 36px; height: 3px;" colspan="5" align="left"
			valign="top"></td>
	</tr>
	<tr>

		<td style="width: 125px;" align="left" class="bold size12">
                                        <?php echo $item['privacy_item3']; ?>
                                    </td>
		<td colspan="4" style="width: 561px;" align="left" class="size12">
                                        <?php if($age!=""){ echo $age;} ?>
                                    </td>
	</tr>
	<tr>
		<td style="width: 36px; height: 3px;" colspan="5" align="left"
			valign="top"></td>
	</tr>
	<tr>

		<td style="width: 125px;" align="left" class="bold size12">
                                        <?php echo $item['privacy_item6']; ?>
                                    </td>
		<td colspan="4" style="width: 561px;" align="left" class="size12">
                                         <?php if(!empty($city)){echo $city.", ";}?>
										<?php echo $country; ?>
                                    </td>
	</tr>
	<tr>
		<td style="width: 36px; height: 3px;" colspan="5" align="left"
			valign="top"></td>
	</tr>
	<tr>

		<td style="width: 125px;" align="left" class="bold size12">
                                        <?php echo $item['privacy_item5']; ?>
                                    </td>
		<td colspan="4" style="width: 561px;" align="left" class="size12">
                                        <?php echo $disease; 

////////////////////////// SHOWING SECONDARY INTERESTS HERE //////////////////////////////////////                                        

	$m_query = sprintf ( "select * from tblsecondary_interests where userid='%s'", $userid );
	$m_rslt = mysql_query ( $m_query );
	
	while ( $m_rw = mysql_fetch_array ( $m_rslt ) ) {
		
		$query_for_disease_desc = sprintf("select * from tbldisease where diseaseid='%s'",$m_rw['diseaseid']);
		$rs = mysql_query($query_for_disease_desc);
		if(mysql_num_rows($rs)>0){
			$disease_row = mysql_fetch_array ( $rs );
			echo ", " . $disease_row['strdisease']  ;
		}
	}
//////////////////////////////////////////////////////////////////////////////////////////////////                                        
                                        
                                        
                                        
?>                                      
                                        
                                    </td>
	</tr>
	<tr>
		<td style="width: 36px; height: 3px;" colspan="5" align="left"
			valign="top"></td>
	</tr>


	<tr>
		<td style="width: 36px; height: 12px;" colspan="5" align="left"
			valign="top"></td>
	</tr>
	<tr>
		<td style="width: 561px;" align="left" colspan="5"
			class="bluetitle size14 bold"><a href="editprofile.php">Contact
		Information</a></td>
	</tr>
	<tr>
		<td style="width: 36px; height: 12px;" colspan="5" align="left"
			valign="top"></td>
	</tr>
	<td style="width: 125px;" align="left" class="bold size12">
                                    <?php echo $item['privacy_item7']; ?>
                                </td>
	<td colspan="4" style="width: 561px;" align="left" class="size12">
                                    <?php echo $email; ?>
                                </td>
	</tr>
	<tr>
		<td style="width: 36px; height: 3px;" colspan="5" align="left"
			valign="top"></td>
	</tr>
	<tr>
		<td style="width: 125px;" align="left" class="bold size12">
                                        <?php echo $item['privacy_item8']; ?>
                                    </td>
		<td colspan="4" style="width: 561px;" align="left" class="size12">
                                        <?php echo $website; ?>
                                    </td>
	</tr>
	<tr>
		<td style="width: 36px; height: 3px;" colspan="5" align="left"
			valign="top"></td>
	</tr>
	<tr>
		<td style="width: 36px; height: 12px;" colspan="5" align="left"
			valign="top"></td>
	</tr>
	<tr>
		<td style="width: 561px;" align="left" colspan="5"
			class="bluetitle size14 bold"><a href="editprofile.php">Who I am</a></td>
	</tr>
	<tr>
		<td style="width: 36px; height: 12px;" colspan="5" align="left"
			valign="top"></td>
	</tr>
	<tr>
		<td colspan="5" style="width: 686px;" align="left" class="size12">
                                    <?php echo $iam; ?>
                                </td>
	</tr>

	<tr>
		<td style="width: 36px; height: 12px;" colspan="5" align="left"
			valign="top"></td>
	</tr>
	<tr>
		<td style="width: 561px;" align="left" colspan="5"
			class="bluetitle size14 bold"><a href="editprofile.php">Things I like</a></td>
	</tr>
	<tr>
		<td style="width: 36px; height: 12px;" colspan="5" align="left"
			valign="top"></td>
	</tr>
	<tr>
		<td colspan="5" style="width: 686px;" align="left" class="size12">
                                        <?php echo $ilike; ?>
                                    </td>
	</tr>

	<tr>
		<td style="width: 36px; height: 12px;" colspan="5" align="left"
			valign="top"></td>
	</tr>
	<tr>
		<td style="width: 561px;" align="left" colspan="5"
			class="bluetitle size14 bold"><a href="editprofile.php">My Experience</a></td>
	</tr>
	<tr>
		<td style="width: 36px; height: 12px;" colspan="5" align="left"
			valign="top"></td>
	</tr>
	<tr>
		<td colspan="5" style="width: 686px;" align="left" class="size12">
                                        <?php echo $myexperience; ?>
                                    </td>
	</tr>
	<tr>
		<td style="width: 36px; height: 3px;" colspan="5" align="left"
			valign="top"></td>
	</tr>
	<tr>
		<td style="width: 36px;" align="left" valign="top">&nbsp;</td>
		<td style="width: 125px;" align="left" valign="top">&nbsp;</td>
		<td style="width: 319px;" align="left" valign="top">&nbsp;</td>
		<td style="width: 103px;" align="left" valign="top">&nbsp;</td>
		<td style="width: 103px;" align="left" valign="top">&nbsp;</td>
	</tr>
	<tr>
		<td style="width: 36px;" align="left" valign="top">&nbsp;</td>
		<td style="width: 125px;" align="left" valign="top">&nbsp;</td>
		<td style="width: 319px;" align="left" valign="top">&nbsp;</td>
		<td style="width: 103px;" align="left" valign="top">&nbsp;</td>
		<td style="width: 103px;" align="left" valign="top">&nbsp;</td>
	</tr>
	<tr>
		<td style="width: 36px;" align="left" valign="top">&nbsp;</td>
		<td style="width: 125px;" align="left" valign="top">&nbsp;</td>
		<td style="width: 319px;" align="left" valign="top">&nbsp;</td>
		<td style="width: 103px;" align="left" valign="top">&nbsp;</td>
		<td style="width: 103px;" align="left" valign="top">&nbsp;</td>
	</tr>
	<tr>
		<td style="width: 36px;" align="left" valign="top">&nbsp;</td>
		<td style="width: 125px;" align="left" valign="top">&nbsp;</td>
		<td style="width: 319px;" align="left" valign="top">&nbsp;</td>
		<td style="width: 103px;" align="left" valign="top">&nbsp;</td>
		<td style="width: 103px;" align="left" valign="top">&nbsp;</td>
	</tr>
	<tr>
		<td style="width: 36px;" align="left" valign="top">&nbsp;</td>
		<td style="width: 125px;" align="left" valign="top">&nbsp;</td>
		<td style="width: 319px;" align="left" valign="top">&nbsp;</td>
		<td style="width: 103px;" align="left" valign="top">&nbsp;</td>
		<td style="width: 103px;" align="left" valign="top">&nbsp;</td>
	</tr>
	<tr>
		<td style="width: 36px;" align="left" valign="top">&nbsp;</td>
		<td style="width: 125px;" align="left" valign="top">&nbsp;</td>
		<td style="width: 319px;" align="left" valign="top">&nbsp;</td>
		<td style="width: 103px;" align="left" valign="top">&nbsp;</td>
		<td style="width: 103px;" align="left" valign="top">&nbsp;</td>
	</tr>
</table>
</div>
<!-- </div> --></div>
</div>
</div>
<?php include "footer.php"; ?>
<!-- TEMPERARY CODE HERE, TO REMOVE DUPLICATE FRIENDS -->
<!-- AFTER WE HAVE DIAGNOSED THE PROBLEM ABOUT WHAT IS INCURRING THE DUPLICATE FRIENDS WE WILL REMOVE THIS CODE -->
<?php
$result = mysql_query("select * FROM tblfriends WHERE friendshipid NOT IN (SELECT friendshipid FROM (SELECT * FROM tblfriends ORDER BY friendshipid DESC) as temp_table GROUP BY userid, friendwith)");
$num=mysql_num_rows($result);
// if duplicate friends exist, delete them.
if($num>0)
{
mysql_query("CREATE TABLE temp_table AS (SELECT * FROM tblfriends)");
mysql_query("DELETE FROM tblfriends WHERE friendshipid NOT IN (SELECT friendshipid FROM (SELECT * FROM temp_table ORDER BY friendshipid DESC) as temp_table GROUP BY userid, friendwith)");
mysql_query("DROP TABLE temp_table");
}
?>