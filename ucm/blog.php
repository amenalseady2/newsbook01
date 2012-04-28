<?php include "header_inner.php";
include "smtpmailer.php";
function encrypt_userid($text)  
{      
	return trim(base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, SALT, $text, 
	MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, 
	MCRYPT_MODE_ECB), MCRYPT_RAND))));  
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

$cnfmsg='';$post_id_spammed='';
if(isset($_POST['spantitle'])){
$str="reson_details".$_POST["spamid"];
//$txt = 'Hello Admin!\n User name: '.$_SESSION["fname"].' (id='.$_SESSION["userid"].') has reported the following post of user as spam. You may take the required action for this user:\n'.$_SERVER['REQUEST_URI'].'\nReason specified:'.$_POST[$str].'\n\nPost Title: '.$_POST["spantitle"].'\n\nThank You!';
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
								
					$EmailMsg =  
					'Hello Admin,<br />User name: '.$_SESSION["fname"].' (id='.$_SESSION["userid"].') has reported the following post of user as spam.<br />You may take the required action for this post using below url and post title:\n<a href="'.$_SERVER['HTTPS'].$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'].'">'.$_SERVER['HTTPS'].$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'].'</a><br />Reason specified:'.$_POST[$str].'<br />Post Title: '.$_POST["spantitle"].'<br />YouCureMe.com'; 
									
					send_smtpmail($EmailTo, $EmailFrom, $Emailfrom_name, $EmailSubject, $EmailMsg);	 	

$post_id_spammed=$_POST["spamid"];
$cnfsmsg='You have reported this post as Spam! Admin is notified, and he will take the required action!';
}


if(!isset($_SESSION["userid"]) || $_SESSION["userid"] =="")
{	//header("location: login.php");   
		echo "<script>location.href='index.php' </script>";

}
else
{
	$viewerid=$_SESSION["userid"];
	$userid=$_GET["userid"];

	if(isset($_GET['msg']))
	{ ?>
	  <div class="txttitle whitetitle size12 bold"><?php echo $_GET['msg']; ?></div>
	<?php 
	}
	
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
							<?php
							$_name='';
							if(isset($_GET["userid"])){
							
							/******************************************* LOAD USER INFO **********************************************************/
							$query="select 
										access_name,
										alias
									from tbluser,tblusertype,tbldisease,tblcountry where userid=".$_GET["userid"]." and 
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
							?>
                        <div class="user_data">
                        	<div class="user_name bluetitle size20 bold" />
							<?php echo empty($_name) ? $fname." ".$lname : $_name; ?>

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
                    <a href="myprofile.php">Home</a>&nbsp;&nbsp;>> <span class="size9"><a href="viewprofile.php?userid=<?php echo $userid; ?>">
                            <?php //echo $alias; if($usealias=="1") 
										//	echo $alias;
										//  else 
											echo $fname." ".$lname; ?>
                        </a>&nbsp;&nbsp;<span class="size9">>></span>&nbsp;&nbsp;<span class="bluetitle size11">Blog</span>
                </div>

                <div class="body">
                    <div class="main_link">
                        <div class="inbox_title">
                            <!--<div class="f_left">
                    <img align="absmiddle" src="images/inbox_icon.jpg" />
                </div>-->
                            <div class="f_left bluetitle size30 bold" style="padding-top:5px; width:auto;">
                                <?php //if($usealias=="1") 
										//	echo $alias;
										 // else 
											echo $fname." ".$lname; ?>
                                <?php $basicvis = false; ?>'s Blog
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

                                            <div name="newboxes" id="posts">
                                                <div>

                                                    <?php
				
					$query_post=" 										
					select 					
					blogpostid,posttext,postimage,postvideo,postembedvideolink,postedbyuserid,postedonuserid,datetimeposted,privacylevel,
					(select CONCAT(fname,' ',lname) from tbluser where userid=p.postedbyuserid) as poster,
					(select thumb_profile from tbluser where userid=p.postedbyuserid) as posterpic					
					from tblblogposts p 
					where postedonuserid = ".$userid; 
					
					if($isfriend)
						$query_post=$query_post." and privacylevel in (2,3) ";
					else
						$query_post=$query_post." and privacylevel = 2 ";
					
					$query_post=$query_post." order by datetimeposted desc ";//limit 10";	
					
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
                                                                    <?php echo $row["datetimeposted"]; ?>
																	
																	<div>
																		<a href="javascript:void(0);" onclick="open_report_spam('<?php echo $row['blogpostid'];?>');">Report Spam</a>
																		<?php
																		if(!empty($cnfsmsg) && $row['blogpostid']==$post_id_spammed){
																		echo "<br /><span style='color:#CA0002'>[ ".$cnfsmsg." ]</span>";
																		}
																		?>
																	</div>
																	<div id="report_spam<?php echo $row['blogpostid'];?>" style="display:none;">
																	   <form action="" method="post">
																			Your Reason/Details
									<textarea cols="36" rows="3" name="reson_details<?php echo $row['blogpostid'];?>" id="reson_details<?php echo $row['blogpostid'];?>"></textarea>
																			<br />
																			<input type="hidden" name="spantitle" id="spantitle" value="<?php echo $row["poster"]; ?>">
																			<input type="hidden" name="spamid" id="spamid" value="<?php echo $row["blogpostid"]; ?>">
															<input type="submit" value="report" onclick="return verifyreason('<?php echo $row['blogpostid'];?>');">
															&nbsp;&nbsp;<input type="button" onclick="close_report_spam('<?php echo $row['blogpostid'];?>');" value="cancel">
																			
																	   </form>
																	</div>
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
										if($rowc["posterpic"]=="")
											$rowc["posterpic"]="empty_profile.jpg";
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
                                                                        <?php echo $rowc["datetimeposted"]; ?>
                                                                        <?php 
													                    if($rowc["postedbyuserid"]==$_SESSION["userid"])
													                    {
													                    ?> | <a class="bluelink" href="delmycomment.php?blogcommentid=<?php echo $rowc["blogcommentid"]; ?>&userid=<?php echo $userid; ?>">Delete</a>
                                                                        <?php 
														                }
														                ?>
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
                                                                <img src="profilepics/<?php echo $_SESSION["profilepic"]; ?>" style="background-color:#FFFFFF" width="50" height="50" border="0">
                                                            </div>
                                                            <div style="float:left;display:inline;padding-left:10px;min-height:50px;width:530px;background-color:#e7e7e7;">
                                                                <div style="float:left;">
                                                                    <a class="bluelink" href="viewprofile.php?userid=<?php echo $_SESSION["userid"]; ?>"><?php echo $_SESSION["fname"]; ?>
                                                                    </a>
                                                                </div>
                                                                <div style="clear:both;"></div>
                                                                <div style="float:left;">

                                                                    <form action="postblogcomment.php" method="post" enctype="multipart/form-data" >
                                                                        <input type="hidden" id="userid" name="userid" value="<?php echo $userid; ?>" />
                                                                        <input type="hidden" id="blogpostid" name="blogpostid" value="<?php echo $row["blogpostid"]; ?>" />
                                                                        <input type="hidden" id="postedbyuserid" name="postedbyuserid" value="<?php echo $_SESSION["userid"]; ?>" />
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
                                            <!-- end of profile -->



                                        </td>
                                    </tr>



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
			function open_report_spam(id){
			document.getElementById("report_spam"+id).style.display="inline";
			}
			function close_report_spam(id){
			document.getElementById("report_spam"+id).style.display="none";
			}
			function verifyreason(id){
			if(document.getElementById("reson_details"+id).value==''){
			alert('Please add your reason');
			return false;
			}else{
			return true;
			}
			}
			</script>
<?php include "footer.php"; ?>