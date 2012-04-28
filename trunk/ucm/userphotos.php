<?php include "header_inner.php";


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


if(!isset($_SESSION["userid"]) || $_SESSION["userid"] =="")
{	//header("location: login.php");   
		echo "<script>location.href='index.php' </script>";

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
	
	
	/******************************************* LOAD PHOTO ALBUMS **********************************************************/
	
	$query_albums="
		SELECT albumid, albumname, location, description, privacylevel, coverphotoid, datecreated, userid, COALESCE( (
		
		SELECT picname
		FROM tblphotos
		WHERE photoid = a.coverphotoid
		), '' ) AS picname
		FROM tblalbums a
		WHERE userid = ".$userid;
	
	
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
                    <a href="myprofile.php" class="whitetitle size12">Home</a>&nbsp;&nbsp;<span class="size12">>></span><a href="viewprofile.php?userid=<?php echo $userid; ?>">
                        <?php //if($usealias=="1") 
										//	echo $alias;
										//  else 
											echo $fname." ".$lname; ?>
                    </a>&nbsp;&nbsp;<span class="size9">>></span>&nbsp;&nbsp;<span class="bluetitle size11">Photos</span>
                </div>

                <div class="body">
                    <div class="main_link">
                        <div class="inbox_title">
                            <div class="f_left bluetitle size30 bold" style="padding-top:5px; width:auto;">
                                <?php //if($usealias=="1") 
											//echo $alias."'s Photos";
										 // else 
											echo $fname." ".$lname."'s Photos"; ?>
                                <?php $basicvis = false; ?>
                            </div>
                        </div>

                        <div class="email_table">
                            <table cellpadding="0" cellspacing="0" style="width:686px; border:0px;">
                                
                                    <tr>
                                        <td style="width:36px; height:12px; " colspan="5" align="left" valign="top"></td>
                                    </tr>

                            


                                <tr>
                                    <td colspan="5" align="left" valign="top">






                                        <?php
													
							$sql=mysql_query($query_albums);
							$message="";
							$rowcount=0;
							$count=mysql_num_rows($sql);
							if($count>0)
							{							
								while($row=mysql_fetch_array($sql))
								{
								
									$rowcount++;
									if($rowcount%4==0)
									{
										$message=$message."<div style='display:inline;float:left;'>";
									}	
									
									$message=$message."<div class='album-pics'><a href='viewuseralbum.php?albumid=".$row['albumid']."&albumname=".$row['albumname']."&userid=".$userid."'>";
									
									if($row['picname']!='')
										$message=$message."<img src='albumphotos/".$row['picname']."' border='0' width='156px' height= '115px' />";
									else
										$message=$message."<img src='albumphotos/nophoto.jpg' border='0' width='156px' height= '115px' />";
									
									$message=$message."</a><br/><a class='bluelink' href='viewuseralbum.php?albumid=".$row['albumid']."&albumname=".$row['albumname']."&userid=".$userid."'>".$row['albumname']."</a></div>";
									
									if($rowcount%4==0)
									{
										$message=$message."</div><div style='clear:both;'></div>";
									}	
																		
									//$msg_id=$row['albumid'];
//									
//									$message="<div class='msg-general'>";	
//										
//									$message=$message."<div class='msglist'>";
//										$message=$message."<a class='msglisttitle' href='viewuseralbum.php?albumid=".$row['albumid']."&userid=".$userid."'><img src='albumphotos/".$row['picname']."' border='0' width='156px' height= '115px' /></a><br/>";
//									
//										$message=$message."<div class='msglistmsgbody'>";
//									
//											$message=$message."<div>";
//											$message=$message.$row['albumname'];
//											$message=$message."</div>";
//									
//										$message=$message."</div>";
//									
//									$message=$message."</div>";//end of message
//									
//									$message=$message."<div class='msg-statut'>";
//									$message=$message."</div>";
//									
//									$message=$message."<div id=msg-actions>";
//									
//								
//									
//									$message=$message."</div>";//end of msg-actions
//									
//									$message=$message."</div>";//end of msg-general
								
							?>

                                        <?php 
								}
							}
							else
								 $message=$message."No albums found.";
								 
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
        
<?php include "footer.php"; ?>