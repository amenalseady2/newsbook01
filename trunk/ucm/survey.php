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
$access_name=1;
$access_pic=1;
$access_dob=1;
$access_gender=1;
$access_disease=1;
$access_loc=1;
$access_email=1;
$access_web=1;
$access_iam=1;
$access_ilike=1;
$access_exp=1;
$access_photos=1;
$access_friends=1;
$access_blog=1;
$access_msg=1;


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
				isactive,
				access_name,
				access_pic,
				access_dob,
				access_gender,
				access_disease,
				access_loc,
				access_email,
				access_web,
				access_iam,
				access_ilike,
				access_exp,
				access_photos,
				access_friends,
				access_blog,
				access_msg
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
		
		$access_name=$row["access_name"];
		$access_pic=$row["access_pic"];
		$access_dob=$row["access_dob"];
		$access_gender=$row["access_gender"];
		$access_disease=$row["access_disease"];
		$access_loc=$row["access_loc"];
		$access_email=$row["access_email"];
		$access_web=$row["access_web"];
		$access_iam=$row["access_iam"];
		$access_ilike=$row["access_ilike"];
		$access_exp=$row["access_exp"];
		$access_photos=$row["access_photos"];
		$access_friends=$row["access_friends"];
		$access_blog=$row["access_blog"];
		$access_msg=$row["access_msg"];
		
	}
	
    $query_issurveydone="SELECT *
	FROM tblusersurveyans where userid = ".$userid."";	
	$result_surveydone=mysql_query($query_issurveydone);
	$num_sd=mysql_num_rows($result_surveydone);
	if($num_sd>0)
	{
		echo "<script>location.href='surveyresults2.php' </script>";
	}
	//echo $query_issurveydone;

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
	
	$query1 = '';
	$where = '';
		
	if(!isset($_SESSION["userid"]) || $_SESSION["userid"] =="")
	{	
		header("location: index.php");
		echo "<script>location.href='index.php' </script>";   
	}
	else
	{
		
		
		
	}
	
	
} ?>
<div class="warpper">
    <div class="left_side">
 <!--       <div class="left_contant">
            <div class="user_info">
                <div class="user_img">
                    <img src="profilepics/<?php echo $profilepic; ?>" width="63" height="59" border="0" />

                </div>
                <div class="user_data">
                    <div class="user_name bluetitle size20 bold" />
                    <?php echo $username; ?>
                </div>
                <div class="ul_msg">
                    <ul>
                        <li>
 							<a href="messages.php?mode=inbox&page=1">My Messages(<?php echo $inbox_items;?>)</a> 
                        </li>
                        <li>
                            <a href="myblog.php">My Blog</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="profile_links">
            <div class="title_txt">
                <div class="plusimg">
                    <img src="images/plus_icon.jpg" />
                </div>
                <div class="txttitle whitetitle size12 bold">My Profile</div>
            </div>
            <div class="txt_links">
                <ul>
                    <li>
                        <a href="editprofile.php?userid=<?php echo $_SESSION["userid"]; ?>">Account Settings
                        </a>
                    </li>
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
                <div class="plusimg">
                    <img src="images/plus_icon.jpg" />
                </div>
                <div class="txttitle whitetitle size12 bold">My Messages</div>
            </div>
            <div class="txt_links">
                <ul>
                    <li> 
						<a href="messages.php?mode=inbox&page=1">My Messages(<?php echo $inbox_items;?>)</a> 
                    </li>
                    <li>
                        <a href="notifications.php">My Notifications</a>
                    </li>
                   
                </ul>
            </div>
        </div>
        <div class="profile_links">
            <div class="title_txt">
                <div class="plusimg">
                    <img src="images/plus_icon.jpg" />
                </div>
                <div class="txttitle whitetitle size12 bold">Make a Difference</div>
            </div>
            <div class="txt_links">
                <ul>
                    
					<li>
                        <a href="resources.php">Health Resources</a>
                    </li>
                    <li>
                        <a href="survey.php">Surveys</a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="profile_links">
            <div class="title_txt">
                <div class="plusimg">
                    <img src="images/plus_icon.jpg" />
                </div>
                <div class="txttitle whitetitle size12 bold">Contact with Others</div>
            </div>
            <div class="txt_links">
                <ul>
                    
                    <li>
                    </li>
					<li>
                        <a href="members.php">Search All Members</a>
                    </li>
                
				 <li>
                        <a href="myinterestmembers.php">
                            Members With My Interest (<?php echo $disease_match_count-1;?>)
                        </a>
                    </li>
                    <li>
                        <a href="reachout.php">
                            Reach Out(<?php echo $ps_match_count;?>)
                        </a>
                    </li>
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
                            	<li><a href="interest_wall.php">My Interest Wall</a></li>
                            </ul>
                        </div>
                    </div>
        <div class="profile_links">
            <div class="title_txt">
                <div class="plusimg">
                    <img src="images/plus_icon.jpg" />
                </div>
                <div class="txttitle whitetitle size12 bold">My Contacts</div>
            </div>
            <div class="txt_links">
                <ul>
                    <li>
                        <a href="myfriends.php">
                            My Friends (<?php echo $frnds_count;?>)
                        </a>
                    </li>
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
                    <a href="myprofile.php" class="whitetitle size12">Home</a>&nbsp;&nbsp;<span class="size12">>></span>&nbsp;&nbsp;<span class="whitetitle size12">My Survey</span>
                </div>

                <div class="body">
                <?php  if(isset($_GET['msg']))
                { ?>
                    <div class="txttitle red size12 bold"><?php echo $_GET['msg']; ?></div>
					<br />
                <?php 
                }
                ?>
                    <div class="main_link">
                        <div class="inbox_title">
                            
                            <div class="f_left bluetitle size30 bold" style="padding-top:5px; width:auto;">Survey</div>
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
                                <form action="submitsurvey.php" method="post" enctype="multipart/form-data" >
                                    <input type="hidden" id="userid" name="userid" value="<?php echo $userid; ?>" />


                                        <tr>
                                            <td style="width:36px; height:12px;" colspan="5" align="left" valign="top"></td>
                                        </tr>
                                        <tr>
                                            <td style="width:561px;" align="left" colspan="5" class="bluetitle size14 bold">
											
											<p>This ongoing survey is intended to be completed by those facing a challenge.<br>
											Please check back often to see results and new questions.<br> If you are just 
 browsing or are a friend / family member, please participate by completing it with that person in mind. <br>Our researchers and community sincerely thank you for your contribution.<p>
																						
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="width:36px; height:15px;" colspan="5" align="left" valign="top"></td>
                                        </tr>


                                        <?php
                            $qnum=1;
							$query_questions="select questionid, strquestion from tblsurveyquestions order by questionid";
							//////////////////////////////////////////////////////////////////////////////////////////////
							$query_ds="select * from tbladmin_q_disease where interest_id ='".$disease_ds."'";	
							$res_in=mysql_query($query_ds);
							while($row_in=mysql_fetch_array($res_in))
							{							
							    $query_questions="select questionid, strquestion from tblsurveyquestions where questionid='".$row_in["q_id"]."' order by questionid";					
							}
							//////////////////////////////////////////////////////////////////////////////////////////////					
							$sqlq=mysql_query($query_questions);
							$countq=mysql_num_rows($sqlq);
							if($countq>0)
							{							
								while($rowq=mysql_fetch_array($sqlq))
								{
							?>

                                        <tr>
                                            <td style="width:36px; height:15px;" colspan="5" align="left" valign="top" class="bold size12">
                                                <?php echo $rowq["strquestion"];?>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td style="width:36px; height:3px;" colspan="5" align="left" valign="top"></td>
                                        </tr>
                            <?php
							$query_answers="select qansid,strqans,questionid from tblsurveyquesanswers where questionid=".$rowq["questionid"];					
							//echo $query_answers;
							$sqla=mysql_query($query_answers);
							$counta=mysql_num_rows($sqla);
							if($counta>0)
							{							
								while($rowa=mysql_fetch_array($sqla))
								{
							?>
                                        <tr>
                                            <td style="width:36px; height:15px;" colspan="5" align="left" valign="top" class="size12">

                                                <input type="radio"  id="q<?php echo $rowq["questionid"];?>" name="q<?php echo $rowq["questionid"];?>" value="<?php echo $rowa["qansid"];?>" >&nbsp;<?php echo $rowa["strqans"];?>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td style="width:36px; height:3px;" colspan="5" align="left" valign="top"></td>
                                        </tr>
                                        
                            <?php $qnum++; }
                            } ?>            





                                        <tr>
                                            <td style="width:36px; height:3px;" colspan="5" align="left" valign="top"></td>
                                        </tr>
                         <?php }
                            }?>


                                   
                                        <tr>
                                            <td style="width:36px; height:10px;" colspan="5" align="left" valign="top"></td>
                                        </tr>

                                        <tr>
                                            <td >&nbsp;</td>
                                            <td >&nbsp;</td>
                                            <td align="center" >
                                                <input id="submit-subscirbe" type="submit" value="Submit" />
                                            </td>
                                            <td >&nbsp;</td>
                                            <td>&nbsp;</td>
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