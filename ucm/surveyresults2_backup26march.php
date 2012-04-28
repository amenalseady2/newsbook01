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
		echo "<script>location.href='login.php' </script>";

}
else
{
	$userid=$_SESSION["userid"];
	
	/******************************************* LOAD USER INFO **********************************************************/
	$query="select 
				fname,
				lname,
				thumb_listing as profilepic,
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

				
	/*******************************************************************************************************************/
	/* Now that the new client has been inserted into the database, we will extract all the
	   existing users with matching medical interest and update the notification in their
	   profiles */

	/* First get the disease id of this user */
	$disease_id = 1;
  $disease_ds=0;
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
			$disease_ds=$row["diseaseid"];					 
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
	FROM tblfriends
	WHERE tblfriends.userid = ".$userid."
	AND friendshipstatus =1";
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
		header("location: login.php");
		echo "<script>location.href='login.php' </script>";   
	}
	else
	{
		
		
		
	}
	
	
} ?>
<div class="warpper">
    <div class="left_side">
        <!-- <div class="left_contant">
            <div class="user_info">
                <div class="user_img">
                    <!--<img src="images/user-img.jpg" />-
                    <img src="profilepics/<?php echo $profilepic; ?>" width="63" height="59" border="0" />

                </div>
                <div class="user_data">
                    <div class="user_name bluetitle size20 bold" />
                    <?php echo $username; ?>
                </div>
                <div class="ul_msg">
                    <ul>
                        <li>
                            <a href="messages.php?mode=inbox">My Messages</a>
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
                        <a href="messages.php?mode=inbox">My Messages</a>
                    </li>
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
                <div class="plusimg">
                    <img src="images/plus_icon.jpg" />
                </div>
                <div class="txttitle whitetitle size12 bold">Make a Difference</div>
            </div>
            <div class="txt_links">
                 <ul> <li>
                        <a href="resources.php">Health Resources</a>
                    </li>
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
                <div class="plusimg">
                    <img src="images/plus_icon.jpg" />
                </div>
                <div class="txttitle whitetitle size12 bold">Connect with Others</div>
            </div>
            <div class="txt_links">
                <ul>
                  
                    <li>
                        <a href="members.php">Explore YouCureMe network</a>
                    </li>
             
                    <li>
                        <a href="medicalhistory.php">Medical History</a>
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
        </div>-->
        <?php 
		require("left_usermenu.php");?>
    </div>
</div>
            <div class="body_main">
                <div class="body_menu whitetitle">
                    <a href="myprofile.php" class="whitetitle size12">Home</a>&nbsp;&nbsp;<span class="size12">>></span>&nbsp;&nbsp;<span class="whitetitle size12">Survey Results</span>
                </div>

                <div class="body">
                    <div class="main_link">
                        <div class="inbox_title">
                            
                            <div class="f_left bluetitle size30 bold" style="padding-top:5px; width:auto;">Survey Results</div>
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
                                                Below are the responses from the other members of YouCureMe network

                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="width:36px; height:15px;" colspan="5" align="left" valign="top"></td>
                                        </tr>


                                        <?php
										
                            $qnum=1;
							$query_ds="select * from tbladmin_q_disease where interest_id ='".$disease_ds."'";	
							$res_in=mysql_query($query_ds);
							while($row_in=mysql_fetch_array($res_in))
							{	
							$query_questions="select questionid, strquestion from tblsurveyquestions where questionid='".$row_in["q_id"]."' order by questionid";				
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
							$query_answers="SELECT COUNT( * ) , questionid, qansid
                            FROM tblusersurveyans
                            WHERE questionid = ".$rowq["questionid"]."
                            GROUP BY questionid, qansid";					
							//echo $query_answers;
							$sqla=mysql_query($query_answers);
							$counta=mysql_num_rows($sqla);
							if($counta>0)
							{	
							    $arrans="";		
							    $isfirst=true;				
								while($rowa=mysql_fetch_array($sqla))
								{
								    if($isfirst)
								        $arrans=$arrans.$rowa[0].",".$rowa[2];
								    else
								        $arrans=$arrans.",".$rowa[0].",".$rowa[2];
							        $isfirst=false;
							        $qnum++; 
                                 }
                             ?>
                                        <tr>
                                            <td style="width:36px; height:15px;" colspan="5" align="left" valign="top" class="size12">
                                                <img src="surveyresults1.php?arrans=<?php echo $arrans; ?>"/>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td style="width:36px; height:3px;" colspan="5" align="left" valign="top"></td>
                                        </tr>


                              <?php 
                              } ?>





                            <tr>
                                            <td style="width:36px; height:3px;" colspan="5" align="left" valign="top"></td>
                                        </tr>
                         <?php }
							}}?>


                                   
                                        <tr>
                                            <td style="width:36px; height:10px;" colspan="5" align="left" valign="top"></td>
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