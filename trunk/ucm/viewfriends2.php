<?php include "header_inner.php";
include "smtpmailer.php";
function encrypt_userid($text)  
{      
	return trim(base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, SALT, $text, 
	MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, 
	MCRYPT_MODE_ECB), MCRYPT_RAND))));  
}
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

$access_name=0;
$access_pic=0;
$access_dob=0;
$access_gender=0;
$access_disease=0;
$access_loc=0;
$access_email=0;
$access_web=0;
$access_iam=0;
$access_ilike=0;
$access_exp=0;
$access_photos=0;
$access_friends=0;
$access_blog=0;
$access_msg=0;


$frnds_count=0;
$usealias=0;
$alias="";
$cnfmsg='';
if(isset($_POST['reson_details'])){
// $txt = 'Hello Admin!\n User name: '.$_SESSION["fname"].' (id='.$_SESSION["userid"].') has reported the following url user as spam along with reason. You may take the required action for this user:\n'.$_SERVER['REQUEST_URI'].'\nReason specified:'.$_POST["reson_details"].'\nThank You!';
//$to = "sunny85indore@gmail.com";
// $subject = "User reported spam on your website youcareme.com";
//
//mail($to,$subject,$txt);

/* Send an mail to admin regarding post reported spam */		
					$EmailTo = "sunny85indore@gmail.com";	 
					$EmailFrom = "info@youcureme.com";
					$Emailfrom_name = "YouCureMe.com"; 
					$EmailSubject = "YouCureMe: User reported Spam!";
					$key = encrypt_userid($_GET['userid']);					
								
					$EmailMsg = 'Hello Admin,<br />User name: '.$_SESSION["fname"].' (id='.$_SESSION["userid"].') has reported the following url user as spam along with reason.<br />You may take the required action for this user using below url:<br /><a href="'.$_SERVER['HTTPS'].$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'].'">'.$_SERVER['HTTPS'].$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'].'</a><br />Reason specified:'.$_POST["reson_details"].'<br /><br />YouCureMe.com'; 
									
					send_smtpmail($EmailTo, $EmailFrom, $Emailfrom_name, $EmailSubject, $EmailMsg);	 

$cnfsmsg='You have reported this user as Spam! Admin is notified, and he will take the required action!';
}

if(!isset($_SESSION["userid"]) || $_SESSION["userid"] =="")
{	//header("location: login.php");   
		echo "<script>location.href='login.php' </script>";

}
else
{
	$viewerid=$_SESSION["userid"];
	$userid=$_GET["userid"];
	
	/******************************************* LOAD USER INFO & PRIVACY SETTINGS *****************************************************************/
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
				access_msg,
				alias,
				usealias
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
		
		
		$usealias=$row["usealias"];
		$alias=$row["alias"];
	}
	
	
	/******************************************* CHECK FRIENDSHIP STATUS *****************************************************************/
	$isfriend=false;
	$ispendingfreind=false;
	$queryfrnd="select 
			friendshipid,	friendshipstatus 			
			from tblfriends
			where userid=".$userid." and friendwith=".$viewerid." and friendshipstatus <> 3";//." and friendshipstatus = 2";
			
	$resultfrnd=mysql_query($queryfrnd);
	if($resultfrnd)
	{
		$num=mysql_num_rows($resultfrnd);
		if($num>0)
		{
			$row=mysql_fetch_array($resultfrnd);
			if($row["friendshipstatus"]=="2")
				$isfriend=true;
			elseif($row["friendshipstatus"]=="1")
				$ispendingfreind=true;
		}
	}
	
	
	$hasaddedme=false;
	$queryfrnd="select 
			friendshipid,	friendshipstatus 			
			from tblfriends
			where userid=".$userid." and friendwith=".$viewerid." and friendshipstatus = 4";//." and friendshipstatus = 2";
			
	$resultfrnd=mysql_query($queryfrnd);
	if($resultfrnd)
	{
		$num=mysql_num_rows($resultfrnd);
		if($num>0)
		{
			$row=mysql_fetch_array($resultfrnd);
			if($row["friendshipstatus"]=="4")
				$hasaddedme=true;
		}
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
	
	
	// $query_reqs=" 
	// SELECT friendshipid, tblfriends.userid, friendwith, CONCAT( fname, ' ', lname ) AS sendername, friendshipstatus, thumb_profile as profilepic, access_pic
// FROM tblfriends, tbluser
// WHERE tblfriends.userid = tbluser.userid
// AND tblfriends.userid =".$userid."
// AND tblfriends.friendshipstatus =2";
$query_reqs=
"
SELECT usealias, alias, fname, lname, friendshipid, tblfriends.userid, friendwith, CONCAT( fname, ' ', lname ) AS sendername, friendshipstatus, 
thumb_profile as profilepic,access_pic,strusertype,strdisease
FROM tblfriends, tbluser,tbldisease,tblusertype
WHERE 

tblfriends.friendwith = tbluser.userid
and tbldisease.diseaseid=tbluser.diseaseid
and tblfriends.userid =  ".$userid."	
AND tbluser.userid <>  ".$userid."	
AND friendshipstatus = 2
and tblusertype.usertypeid=tbluser.usertypeid 
";

// ." 
	// SELECT usealias, alias, fname, lname, friendshipid, tblfriends.userid, friendwith, CONCAT( fname, ' ', lname ) AS sendername, friendshipstatus, 
	// thumb_profile as profilepic,access_pic,strusertype,strdisease
	// FROM tblfriends, tbluser,tbldisease,tblusertype
	// WHERE tblfriends.userid = ".$userid."	
	// AND tbluser.userid <> ".$userid."
	// AND friendshipstatus = 2
	// AND tblfriends.friendwith = tbluser.userid
	// AND tbldisease.diseaseid=tbluser.diseaseid and tblusertype.usertypeid=tbluser.usertypeid ";
} ?>
        <div class="warpper">
        	<div class="left_side">
            	<div class="left_contant">
                	<div class="user_info">
                    	<div class="user_img">
                        	<!--<img src="images/user-img.jpg" />-->
                            <?php if($access_pic=="2" || ($access_pic=="3" && $isfriend==true))
					{
					?>
                            <img src="profilepics/<?php echo $profilepic; ?>" width="63" height="59" border="0" />
<?php
					}
					else
					{
					?>
                            <img src="profilepics/empty_profile.jpg" style="background-color:#FFFFFF" width="63" height="59" border="0">
                                <?php
					}
					?>
                            </div>
                        <div class="user_data">
                        	<div class="user_name bluetitle size20 bold" />
                            <?php //if($usealias=="1") 
										//	echo $alias;
										//  else 
											echo $fname." ".$lname; ?>
                        </div>
                         	<div class="ul_msg">
                            <ul>

                                <?php if($access_msg=="2" || ($access_msg=="3" && $isfriend==true))
													{
													?>
                            	<li><a href="msgmember.php?recieverid=<?php echo $userid; ?>&senderid=<?php echo $viewerid; ?>">Send a Message</a></li>


                                <?php 
                                }
                                if($access_blog=="2" || ($access_blog=="3" && $isfriend==true))
													{
													?>
                                <li><a href="blog.php?userid=<?php echo $userid; ?>">View Blog</a></li>
                                <?php
													}
													?>
                            </ul>
                            </div> 
                        </div>
                    </div>
                    <div class="profile_links">
                    	<div class="title_txt">
                        	<div class="plusimg"><img src="images/plus_icon.jpg" /></div>
                            <div class="txttitle whitetitle size12 bold">Profile</div>
                        </div>
                        <div class="txt_links">
                        	<ul>
                                <li>
                                    <a href="viewprofile.php?userid=<?php echo $userid; ?>">View Profile</a>
                                </li>
                                <li>
                                    <a href="userphotos.php?userid=<?php echo $userid; ?>">View Photos</a>
                                </li>
								<li>
                                    <a href="javascript:void(0);" onclick="open_report_spam();">Report Spam</a>
									<?php
									if(!empty($cnfsmsg)){
									echo "<br /><span style='color:#CA0002'>[ ".$cnfsmsg." ]</span>";
									}
									?>
                                </li>
								<li id="report_spam" style="display:none;">
                                   <form action="" method="post">
								   		Your Reason/Details<textarea cols="28" rows="5" name="reson_details" id="reson_details"></textarea>
										<br />
										<input type="submit" value="report" onclick="return verifyreason();">
										&nbsp;&nbsp;<input type="button" onclick="close_report_spam();" value="cancel">
										
								   </form>
                                </li>
                            </ul>
                        </div>
                    </div>
                <?php if($access_friends=="2" || ($access_friends=="3" && $isfriend==true))
				{
				?>
                    <div class="profile_links">
                    	<div class="title_txt">
                        	<div class="plusimg"><img src="images/plus_icon.jpg" /></div>
                            <div class="txttitle whitetitle size12 bold">Contacts</div>
                        </div>
                        <div class="txt_links">
                        	<ul>
                            	<li><a href="viewfriends.php?userid=<?php echo $userid; ?>">View Friends (<?php echo $frnds_count;?>)</a></li>
                            </ul>
                        </div>
                    </div>
                <?php 
                }
				?>
                </div>
            </div>
            <div class="body_main">
                <div class="body_menu whitetitle">
                    <a href="myprofile.php" class="whitetitle size12">Home</a>&nbsp;&nbsp;<span class="size9">>></span><a href="viewprofile.php?userid=<?php echo $userid; ?>">
                        <?php //if($usealias=="1") 
										//	echo $alias;
										//  else 
											echo $fname." ".$lname; ?>
                    </a>&nbsp;&nbsp;<span class="size9">>></span>&nbsp;&nbsp;<span class="bluetitle size11"><?php //if($usealias=="1") 
											//echo $alias;
										 // else 
											echo $fname." ".$lname; ?></span>
                </div>

                <div class="body">
                    <div class="main_link">
                        <div class="inbox_title">
                            <!--<div class="f_left">
                    <img align="absmiddle" src="images/inbox_icon.jpg" />
                </div>-->
                            <div class="f_left bluetitle size30 bold" style="padding-top:5px; width:auto;">
                                <?php //if($usealias=="1") 
											//echo $alias;
										//  else 
											echo $fname." ".$lname; ?>'s Friends
                                <?php $basicvis = false; ?>
                            </div>
                        </div>

                        <div class="email_table">
                            <table cellpadding="0" cellspacing="0" style="width:686px; border:0px;">
                                <tr>
                                    <tr>
                                        <td style="width:36px; height:12px; " colspan="5" align="left" valign="top"></td>
                                    </tr>

                           


                                <tr>
                                    <td style="width:686px; height:3px; " colspan="5" align="left" valign="top">




                                        <?php
													
							$sql=mysql_query($query_reqs);
							
							$count=mysql_num_rows($sql);		
							$message="";
							
							print '<h1>'.$count.'</h1>';
							if($count>0)
							{							
								while($row=mysql_fetch_array($sql))
								{	
								
								$search_id=$row['friendwith'];
									
									$message=$message."<div class='req-general'>";
									//	if((($_SESSION["userid"] != $row["userid"]) && ($row["profilepic"]!="" && $row["access_pic"]!="1")))
											$message=$message."<div class='searchlist'><a class='reqlisttitle' href='viewprofile.php?userid=".$row['friendwith']."'><img src='profilepics/".$row["profilepic"]."' style='background-color:#FFFFFF' width='50' height='50' border='0'></a></div>";
										//else
											//$message=$message."<div class='searchlist'><a class='reqlisttitle' href='viewprofile.php?userid=".$row['friendwith']."'><img src='profilepics/empty_profile.jpg' style='background-color:#FFFFFF' width='50' height='50' border='0'></a></div>";
									
											
									$message=$message."<div class='reqlist'>";
									$message=$message."<a class='frndlisttitle' href='viewprofile.php?userid=".$row['friendwith']."'>".$row['sendername']."</a><br><b>Interested in :</b> ".$row['strdisease']."<br/><b>Type :</b> ".$row['strusertype'];//<br/>";
									$message=$message."</div>"; 
									
									
											
									$message=$message."<div class='frnd-list-action'>";
									$message=$message."<div class='friend-list-button'>
													<a href='confrimfriend.php?ui=".$_SESSION["userid"]."&fw=".$row["friendwith"]."
												&n=".$_SESSION["fname"]."&stat=un'>Unfriend</a>
												</div><div style='width:5px;float:left;'>&nbsp;</div>";
									$message=$message."<div style='clear:both;'></div>";
									$message=$message."</div>"; 
									
									$message=$message."<div style='clear:both;'></div>";									
									$message=$message."</div>";//end of req-general
									

/*								
									$message=$message."<div class='req-general'>";	
										
									
									if($row["profilepic"]!="" && $row["access_pic"]!="1")
										$message=$message."<div class='searchlist'><a class='reqlisttitle' href='viewprofile.php?userid=".$row['friendwith']."'><img src='profilepics/".$row["profilepic"]."' style='background-color:#FFFFFF' width='50' height='50' border='0'></a></div>";
									else
										$message=$message."<div class='searchlist'><a class='reqlisttitle' href='viewprofile.php?userid=".$row['friendwith']."'><img src='profilepics/empty_profile.jpg' style='background-color:#FFFFFF' width='50' height='50' border='0'></a></div>";
											
											
									$message=$message."<div class='reqlist'>";
									$q = "select CONCAT( fname, ' ', lname ) AS sendername from tbluser where userid = ".$row['friendwith'];
									$my_row = mysql_query($q);
									$my_result = mysql_fetch_array($my_row);	
									
									
									$message=$message."<a class='frndlisttitle' href='viewprofile.php?userid=".$row['friendwith']."'>".$my_result['sendername']."</a>";//<br/>";

									
										
										
										
									$message=$message."</div>";
									
									$message=$message."<div class='frnd-list-action'>";
									//$message=$message."<div class='friend-req-confirm'>
//													<a href='confrimfriend.php?ui=".$_SESSION["userid"]."&fw=".$row["friendwith"]."
//												&n=".$_SESSION["fname"]."&stat=2'>Confirm</a>
//												</div>";
									
									if( ($_SESSION["userid"] != $row["friendwith"]) && ismyfriend($_SESSION["userid"],$row["friendwith"])  && !isfriendpendingapproval($_SESSION["userid"],$row["friendwith"]) )
									{
										$message=$message."<div class='friend-list-button'>";
										$message=$message."<a href='confrimfriend.php?ui=".$_SESSION["userid"].
										"&fw=".$row["friendwith"]."&n=".$_SESSION["fname"]."&stat=un'>Unfriend</a>";
										$message=$message."</div>";
									}
									elseif( ($_SESSION["userid"] != $row["friendwith"]) && !ismyfriend($_SESSION["userid"],$row["friendwith"])   && !isfriendpendingapproval($_SESSION["userid"],$row["friendwith"]))
									{
										$message=$message."<div class='friend-list-button'>";
										$message=$message."<a href='addfriend.php?ui=".$row["friendwith"].
										"&fw=".$_SESSION["userid"]."&n=".$_SESSION["fname"]."'>Add Friend</a>";
										$message=$message."</div>";
									}
									elseif( ($_SESSION["userid"] != $row["friendwith"]) && !ismyfriend($_SESSION["userid"],$row["friendwith"])   && isfriendpendingapproval($_SESSION["userid"],$row["friendwith"]))
									{
										$message=$message."<div class='friendreq-list-button'>";
										$message=$message."Friend Request Sent";
										$message=$message."</div>";
									}
									
									$message=$message."<div style='clear:both;'></div>";
									$message=$message."</div>";
									
									$message=$message."<div style='clear:both;'></div>";
									
									$message=$message."</div>";//end of req-general
									
									*/
								}
							}
							else 
							{
								$message="<div class='req-general'>No Friends Found.</div>";
							}
							
							echo $message; 
							
							?>
                                    
                                    
                                    
                                    
                                    
                                    
                                    
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
 <script type="text/javascript">
function open_report_spam(){
document.getElementById("report_spam").style.display="inline";
}
function close_report_spam(){
document.getElementById("report_spam").style.display="none";
}
function verifyreason(){
if(document.getElementById("reson_details").value==''){
alert('Please add your reason');
return false;
}else{
return true;
}
}
</script>        
<?php include "footer.php"; ?>