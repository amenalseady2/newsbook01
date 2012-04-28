<?php include "header_inner.php";
include "smtpmailer.php";
function encrypt_userid($text)  
{      
	return trim(base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, SALT, $text, 
	MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, 
	MCRYPT_MODE_ECB), MCRYPT_RAND))));  
}   
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
//$to = "info@youcureme.com";
// $subject = "User reported spam on your website youcareme.com";
//
//mail($to,$subject,$txt);

/* Send an mail to admin regarding post reported spam */		
					$EmailTo = "info@youcureme.com";	 
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
		echo "<script>location.href='index.php' </script>";

}
else
{
	$viewerid=$_SESSION["userid"];
	$userid=$_GET["userid"];
	
	if ($_GET["userid"] == $_SESSION["userid"])
		$visible=false;
	else
		$visible=true;

	
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
		$isActive=$row["isactive"];
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
									//		echo $alias;
									//	  else 
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
														if($isActive == 1){
													?>
                                <li><a href="blog.php?userid=<?php echo $userid; ?>">View Blog</a></li>
                                <?php
													}}
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
                    <a href="myprofile.php" class="whitetitle size12">Home</a>&nbsp;&nbsp;<span class="size12">>></span>&nbsp;&nbsp;<span class="whitetitle size12">
					<?php //if($usealias=="1") 
										//	echo $alias;
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
                                <?php if($usealias=="1" and $alias != "") 
											echo $alias;
									  else 
											echo $fname." ".$lname; ?>
                                <?php $basicvis = false; ?>
                            </div>
                            <div class="addsection">
                                <?php 
											if($hasaddedme)
											{
											?>
                                <div class="friend-req-sent">
                                    <a href='confrimfriend.php?ui=<?php echo $_SESSION["userid"]; ?>&fw=<?php echo $userid; ?>							&n=<?php echo $_SESSION["fname"]; ?>&stat=2'>Confirm Friend
                                    </a>
                                </div>
                                <?php
											}
											elseif($isfriend==false && $ispendingfreind==false)
											{
											
											
											
											?>
                                <div class="friend-add" style="display: <?php echo $visible==true ? '' : 'none' ?>">
                                    <a href="addfriend.php?ui=<?php echo $_GET["userid"]; ?>&fw=<?php echo $_SESSION["userid"]; ?>&n=<?php echo $_SESSION["fname"]; ?>">Add Friend
                                    </a>
                                </div>
                                <?php
											}
											elseif($isfriend==false && $ispendingfreind==true)
											{
											?>
                                <div class="friend-req-sent">
                                <a href="addfriend2.php?ui=<?php echo $_SESSION["userid"]?>&fw=<?php echo  $_GET["userid"]?>&n=<?php echo $_SESSION["fname"]?>">Friend&nbsp;Request&nbsp;Sent</a>
                                </div>
                                <?php
											}
											else
											{
											?>
                                <div class="friend-add" style="display: <?php echo $visible==true ? '' : 'none' ?>>
                                    <a href="confrimfriend.php?ui=<?php echo $_SESSION["userid"]; ?>&fw=<?php echo $_GET["userid"]; ?>&n=<?php echo $_SESSION["fname"]; ?>&stat=un">Unfriend
                                    </a>
                                </div>
                                <?php
											}
											?>
                            </div>
                        </div>

                        <div class="email_table">
                            <table cellpadding="0" cellspacing="0" style="width:686px; border:0px;">
                                <tr>
                                    <tr>
                                        <td style="width:36px; height:12px; " colspan="5" align="left" valign="top"></td>
                                    </tr>

                                    <tr>
                                        <td style="width:561px;" align="left" colspan="5" class="bluetitle size14 bold">Basic Information</td>
                                    </tr>
                                    
                                    <tr>
                                        <td style="width:36px; height:12px; " colspan="5" align="left" valign="top"></td>
                                    </tr>

                                    <?php if($access_name=="2" || ($access_name=="3" && $isfriend==true))
									{
										$basicvis = true;
									?>
                                <tr>
                                    <td style="width:125px;" align="left" class="bold size12">
                                        <?php echo $item['privacy_item1']; ?>
                                    </td>
                                    <td colspan="4" style="width:561px;" align="left" class="size12">
                                        <?php echo $fname." ".$lname; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width:36px; height:3px; " colspan="5" align="left" valign="top"></td>
                                </tr>

                                <?php
								}
								
								if($access_gender =="2" || ($access_gender =="3" && $isfriend==true))
								{
									$basicvis = true;
								?>
                                    <tr>

                                    <td style="width:125px;" align="left" class="bold size12">
                                        <?php echo $item['privacy_item4']; ?>
                                    </td>
                                    <td colspan="4" style="width:561px;" align="left" class="size12">
                                        <?php echo $gender; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width:36px; height:3px;" colspan="5" align="left" valign="top"></td>
                                </tr>

                                    <?php
									}
									
									if($access_dob=="2" || ($access_dob=="3" && $isfriend==true))
									{
										$basicvis = true;
									?>
                                <tr>

                                    <td style="width:125px;" align="left" class="bold size12">
                                        <?php echo $item['privacy_item3']; ?>
                                    </td>
                                    <td colspan="4" style="width:561px;" align="left" class="size12">
                                        <?php echo $age; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width:36px; height:3px;;" colspan="5" align="left" valign="top"></td>
                                </tr>

                                    <?php
									}
									
									if($access_loc=="2" || ($access_loc=="3" && $isfriend==true))
									{
										$basicvis = true;
									?>
                                
                                <tr>

                                    <td style="width:125px;" align="left" class="bold size12">
                                        <?php echo $item['privacy_item6']; ?>
                                    </td>
                                    <td colspan="4" style="width:561px;" align="left" class="size12">
                                        <?php if(!empty($city)){echo $city.", ";}?>
										<?php echo $country; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width:36px; height:3px;" colspan="5" align="left" valign="top"></td>
                                </tr>

                                    <?php
									}
									
									if($access_disease =="2" || ($access_disease =="3" && $isfriend==true))
									{
										$basicvis = true;
									?>
                                
                                <tr>

                                    <td style="width:125px;" align="left" class="bold size12">
                                        <?php echo $item['privacy_item5']; ?>
                                    </td>
                                    <td colspan="4" style="width:561px;" align="left" class="size12">
                                        <?php echo $disease; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width:36px; height:3px; " colspan="5" align="left" valign="top"></td>
                                </tr>
                                    <?php
									}
									?>


                                    <?php
									
									if(!$basicvis)
									{
									?>

                                    <tr>
                                        <td colspan="5" style="width:561px;" align="left" class="size12">
                                            This member does not share this information with everyone.
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="width:36px; height:3px; " colspan="5" align="left" valign="top"></td>
                                    </tr>

                                    <?php
									}
									?>

                                <tr>
                                    <td style="width:36px; height:12px; " colspan="5" align="left" valign="top"></td>
                                </tr>
                                    <?php $contactvis = false; ?>
                                <tr>
                                    <td style="width:561px;" align="left" colspan="5" class="bluetitle size14 bold">Contact Information</td>
                                </tr>
                                <tr>
                                    <td style="width:36px; height:12px; " colspan="5" align="left" valign="top"></td>
                                </tr>
                                    <?php 
									if($access_email =="2" || ($access_email =="3" && $isfriend==true))
									{
										$contactvis = true; 
									?>
                                <tr>
                                <td style="width:125px;" align="left" class="bold size12">
                                    <?php echo $item['privacy_item7']; ?>
                                </td>
                                <td colspan="4" style="width:561px;" align="left" class="size12">
                                    <?php echo $email; ?>
                                </td>
                                </tr>
                                <tr>
                                    <td style="width:36px; height:3px; " colspan="5" align="left" valign="top"></td>
                                </tr>
                                    <?php
									}
									
									if($access_web =="2" || ($access_web =="3" && $isfriend==true))
									{
										$contactvis = true; 
									?>


                                <tr>
                                    <td style="width:125px;" align="left" class="bold size12">
                                        <?php echo $item['privacy_item8']; ?>
                                    </td>
                                    <td colspan="4" style="width:561px;" align="left" class="size12">
                                        <?php echo $website; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width:36px; height:3px;" colspan="5" align="left" valign="top"></td>
                                </tr>
                                <?php
									}
									?>


                                    <?php
									
									if(!$contactvis)
									{
									?>
                                    <tr>
                                        <td colspan="5" style="width:561px;" align="left" class="size12">
                                            This member does not share this information with everyone.
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="width:36px; height:3px;" colspan="5" align="left" valign="top"></td>
                                    </tr>

                                    <?php
									}
									?>
									
                                <tr>
                                    <td style="width:36px; height:12px; " colspan="5" align="left" valign="top"></td>
                                </tr>


                                   
                                <tr>
                                    <td style="width:561px;" align="left" colspan="5" class="bluetitle size14 bold">Who I am</td>
                                </tr>
                                <tr>
                                    <td style="width:36px; height:12px; " colspan="5" align="left" valign="top"></td>
                                </tr>
                                
                                 <?php 
							if($access_iam =="2" || ($access_email =="3" && $isfriend==true))
							{
							?>
                                <tr>
                                <td colspan="5" style="width:686px;" align="left" class="size12">
                                    <?php echo $iam; ?>
                                </td>
                                </tr>
                                    <?php
							}
							else
							{
							?>
                                    <tr>
                                        <td colspan="5" style="width:561px;" align="left" class="size12">
                                            This member does not share this information with everyone.
                                        </td>
                                    </tr>

                                    <?php
							}
							?>
                                    


                                <tr>
                                    <td style="width:36px; height:12px; " colspan="5" align="left" valign="top"></td>
                                </tr>
                                <tr>
                                    <td style="width:561px;" align="left" colspan="5" class="bluetitle size14 bold">Things I like</td>
                                </tr>
                                <tr>
                                    <td style="width:36px; height:12px; " colspan="5" align="left" valign="top"></td>
                                </tr>

                                    <?php if($access_ilike  =="2" || ($access_ilike  =="3" && $isfriend==true))
							{
							?>
                                <tr>
                                    <td colspan="5" style="width:686px;" align="left" class="size12">
                                        <?php echo $ilike; ?>
                                    </td>
                                </tr>

                                    <?php
							}
							else
							{
							?>
                                    <tr>
                                        <td colspan="5" style="width:561px;" align="left" class="size12">
                                            This member does not share this information with everyone.
                                        </td>
                                    </tr>
                                    <?php
							}
							?>   
                                    

                                <tr>
                                    <td style="width:36px; height:12px; " colspan="5" align="left" valign="top"></td>
                                </tr>
                                <tr>
                                    <td style="width:561px;" align="left" colspan="5" class="bluetitle size14 bold">My Experience</td>
                                </tr>
                                <tr>
                                    <td style="width:36px; height:12px; " colspan="5" align="left" valign="top"></td>
                                </tr>

                                    <?php
							
							if($access_exp  =="2" || ($access_exp  =="3" && $isfriend==true))
							{
							?>
                                <tr>
                                    <td colspan="5" style="width:686px;" align="left" class="size12">
                                        <?php echo $myexperience; ?>
                                    </td>
                                </tr>
                                    <?php
							}
							else
							{
							?>
                                    <tr>
                                        <td colspan="5" style="width:561px;" align="left" class="size12">
                                            This member does not share this information with everyone.
                                        </td>
                                    </tr>
                            <?php
							}
							?>


                                    <tr>
                                    <td style="width:36px; height:3px; " colspan="5" align="left" valign="top"></td>
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