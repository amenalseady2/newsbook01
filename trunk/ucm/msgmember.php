<?php include "header_inner.php";
//include "Mail.php";
include "smtpmailer.php";
//include "mail_class.php";
?>

<html>
<head>
<script type="text/javascript" src="/ckeditor/ckeditor.js"></script>
</head>
</html>

<?php
$recieverid = 0;
$senderid = 0;

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
    if($_SERVER['REQUEST_METHOD']=='POST')
{	
		$msg=str_replace("'","''",$_POST["msg"]);
		$msg=str_replace("\"","''",$msg);
		$msg=stripslashes($msg);
		
		$recieverid=$_POST["recieverid"];
		$senderid=$_POST["senderid"];
		$msgtime=date("Y-m-d H:i:s");
		$isread=0;
		$isdeleted=0;
		$dest_email = "www.youcureme.com";
		try
		{	
			$query="insert into tblmsgs(
			msg,
			recieverid,
			senderid,
			msgtime,
			isread,
			isdeleted) 
			values
			('".$msg."',
			".$recieverid.",".$senderid.",'".$msgtime."',".$isread.",".$isdeleted.")";
							
			//echo $query;							
			if(mysql_query($query))
			{
				/* get email address of the receiver */
			    $qry ="SELECT email, fname FROM tbluser WHERE userid = ".$recieverid."";
				$res = mysql_query($qry );
				if($res)
				{
					$num = mysql_num_rows($res);
					if($num>0)
					{
						$row=mysql_fetch_array($res);
						$dest_email = $row["email"];	
						$fname = $row["fname"]. " " .$row["lname"];
					}
				}
				
					
				/* Send this message as SMTP mail to the receiver */ 
				/*if (new Mail('$dest_email','YouCureMe: Message from member!','$msg')) 
				{
				 
					echo 'Mail is sent -> OK';
				}
				else {
					echo 'Mail is not sent';
				} */

				
				$EmailTo = $dest_email;	 
				$EmailFrom = "info@youcureme.com";
				$Emailfrom_name = "YouCureMe.com"; 
				$EmailSubject = "YouCureMe Message" ;	 				
				$EmailMsg = "Message From ".$_SESSION["fname"]."<br/> ".$msg; 
				
				//$EmailMsg = "<html><p><img src='http://localhost/youcureme/ckeditor/plugins/smiley/images/shades_smile.gif'><br /><br />Hello,</p><p>Have You taken a look at <a href='http://www.mysite.co.uk'>www.mysite.co.uk</a>. Take a look at <a href='http://www.mysite.co.uk/example.php'>www.mysite.co.uk/example.php</a> to see the site in full </p></body></html>";
				//http://localhost/youcureme/ckeditor/plugins/smiley/images/shades_smile.gif
				//$EmailMsg = "<html><body><p><br />".$msg."</p></body></html>";
				//$EmailMsg = "<html><p><body".$msg."</body></p></html>";
				//GOOD $EmailMsg  = "<html><body background ='http://localhost/youcureme/ckeditor/plugins/smiley/images/shades_smile.gif'><p><img src='http://localhost/youcureme/ckeditor/plugins/smiley/images/shades_smile.gif'><br /><br />Hello,</p><p>Have You taken a look at <a href='http://www.mysite.co.uk'>www.mysite.co.uk</a>. Take a look at <a href='http://localhost/youcureme/ckeditor/plugins/smiley/images/shades_smile.gif</a> to see the site in full </p></body></html>";
				//good $EmailMsg  = "<html><body><p><img src='http://localhost/youcureme/ckeditor/plugins/smiley/images/shades_smile.gif'><br /><br />Hello,</p><p>Have You taken a look at <a href='http://www.mysite.co.uk'>www.mysite.co.uk</a>. Take a look at <a href='http://localhost/youcureme/ckeditor/plugins/smiley/images/shades_smile.gif</a> to see the site in full </p></body></html>";
				//$EmailMsg  = "<html><body><p><img src='http://localhost/youcureme/ckeditor/plugins/smiley/images/shades_smile.gif'><br /><br />Hello,</p><p>Take a look at <img src='http://localhost/youcureme/ckeditor/plugins/smiley/images/shades_smile.gif'</p></body></html>";
				
				//$EmailMsg = "<html><body>".$msg."</body></html>";
				//$EmailMsg = nl2br($EmailMsg); 
				
				/*$EmailMsg = "<html><head></head><body><p>". 
				" <img alt=''cool'' height=''20'' src=''http://localhost/youcureme/ckeditor/plugins/smiley/images/shades_smile.gif'' title=''cool'' width=''20'' />".
				"<p></body><html>"; */
				 
				$msg_sent = send_smtpmail($EmailTo, $EmailFrom, $Emailfrom_name, $EmailSubject, $EmailMsg);	 
				
				if($msg_sent) {				    
				   //echo "<script>alert($EmailMsg);location.href='viewprofile.php?userid=$recieverid' /script>"; 
				   //echo "<script>alert('Your message sent successfull.');location.href='viewprofile.php?userid=$recieverid' /script>"; 
					echo "<script>location.href='viewprofile.php?userid=".$recieverid."&msg=Message has been sent.'</script>";				   
				}
				else
					echo "<script>location.href='viewprofile.php?userid=".$recieverid."&msg=Mailer problem - Not able to send your message.'</script>";

				   //echo "<script>alert('Mailer problem - Not able to send your message.');location.href='viewprofile.php?userid=$recieverid' /script>"; 
				//window.close();/script>"; 
			}
			else
			{
				echo mysql_error();
			}
		}
		catch(exception $ex)
		{
			echo $ex;
		}
}
else
{
	$viewerid=$_SESSION["userid"];
	$userid=$_GET["recieverid"];
	
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
	
	$recieverid = $_GET["recieverid"];
		$senderid = $_GET["senderid"];
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
										//  else 
											echo $fname." ".$lname; ?>
                        </div>
                         	<div class="ul_msg">
                            <ul>

                                <?php if($access_msg=="2" || ($access_msg=="3" && $isfriend==true))
													{
													?>
                            	<li><a href="msgmember.php?recieverid=<?php echo $userid; ?>&senderid=<?php echo $viewerid; ?>&msg='Message has been sent'">Send a Message</a></li>


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
                    <a href="myprofile.php" class="whitetitle size12">Home</a>&nbsp;&nbsp;<span class="size9">>></span>
                    <a href="viewprofile.php?userid=<?php echo $_GET["recieverid"]; ?>">
                    <?php //if($usealias=="1")echo $alias; else 
											echo $fname."".$lname; ?>
                        </a> &nbsp;&nbsp;<span class="size12">>> </span>&nbsp;&nbsp;<span class="whitetitle size12">Send Message</span>
                </div>

                <div class="body"> 
                    <div class="main_link">
                        <div class="inbox_title">
                           
                            <div class="f_left bluetitle size30 bold" style="padding-top:5px; width:auto;">
                                <?php if($usealias=="1") 
											echo $alias;
										  else 
											echo $fname." ".$lname; ?>
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
                                        <td style="width:561px;" align="left" colspan="5" class="bluetitle size14 bold">Send a Message</td>
                                    </tr> 
                                    <tr>
                                        <td style="width:36px; height:12px; " colspan="5" align="left" valign="top"></td>
                                    </tr> 
                                    <form action="msgmember.php" method="post" > 
                                        <input type="hidden" id="recieverid" name="recieverid" value="<?php echo $_REQUEST["recieverid"]; ?>" />
                                        <input type="hidden" id="senderid" name="senderid" value="<?php echo $_REQUEST["senderid"]; ?>"/>
										<tr>  
                                            <td colspan="5" style="width:761px;" align="left" class="size12">                                                  
												<textarea id="msg" name="msg">&lt;p&gt;&lt;/p&gt;</textarea>
												<script type="text/javascript"> CKEDITOR.replace( 'msg', { skin : 'office2003'}); </script>	
												 <script type="text/javascript"> CKEDITOR.on( 'instanceReady', function( ev )
												{
													ev.editor.dataProcessor.writer.selfClosingEnd = '>';
												}); </script>  
											</td>   
										</tr>
										<tr>
                                            <td style="width:36px; height:5px;" colspan="5" align="left" valign="top"></td>
                                        </tr>
                                        <tr>
                                          <td colspan="5" style="width:561px;" align="left" >
                                                <input id="submit-subscirbe" type="button" onclick="history.go(-1)" value="Back" />&nbsp;<input id="submit-subscirbe" type="submit" value="Send" />
                                          </td>
                                        </tr> 
                                    </form>  
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
        
<?php include "footer.php"; ?>