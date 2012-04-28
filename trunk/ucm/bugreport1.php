<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>You Cure Me</title>
<?php include "header_new.php"; 
//echo $_SERVER['DOCUMENT_ROOT'];
include_once $_SERVER['DOCUMENT_ROOT'].'/securimage/securimage.php';   
$img = new Securimage(); 
$bug_ind = 'unchecked';
$sug_ind = 'unchecked';

if(isset($_POST["login_button"])=="signin")
{
	$email = $_POST["email"];
	$password = $_POST["pwd"];
	
	//First check whether this user was a disabled one.
	$query="select userid,fname,lname,email,usertypeid,user_previlege,isactive,thumb_profile as profilepic from tbluser where email='".$email."' AND password='".$password."'
			AND isactive=0 AND user_previlege=0";
	$result=mysql_query($query);
	$num=mysql_num_rows($result);
	if($num > 0)
	{ 
		  // Disabled user. Redirect the user for reporting this to the administrator
		  echo "<script>location.href='bugreport.php?msg=Your Email has been disabled. Please contact the System Administrator' </script>";
 	}
	
	//user is not a disabled one.
	$query="select userid,fname,lname,email,usertypeid,user_previlege,isactive,thumb_profile as profilepic from tbluser where email='".$email."' AND password='".$password."' AND isactive=1";
	$result=mysql_query($query);
	$num=mysql_num_rows($result);
	if($num > 0)
	{
		$row=mysql_fetch_array($result);   
		
		if($row["user_previlege"] == 0)
		{
		  // Disabled user. Redirect the user for reporting this to the administrator
		  echo "<script>location.href='bugreport.php?msg=Your Email has been disabled. Please contact the System Administrator' </script>";
		}
		
		$_SESSION["userid"]=$row["userid"];
		$_SESSION["fname"] = $row["fname"]." ".$row["lname"];
		$_SESSION["email"] = $row["email"];
		$_SESSION["usertypeid"]=$row["usertypeid"];		
		
		if($row["profilepic"]!="")
			$_SESSION["profilepic"]=$row["profilepic"];
		else
			$_SESSION["profilepic"]="empty_profile.jpg";
		
		echo "<script>location.href='myprofile.php' </script>";		
	}
	else
	{		
		echo "<script>location.href='index.php?msg=Invalid login username/password.' </script>";
	}
}



if($_SERVER['REQUEST_METHOD']=='POST')
{   
 
  if( $_SESSION["security_code"] == $_POST['security_code'] && $_POST['security_code']!="" )
  {   
	
	if(isset($_POST["bug_ind"]))
	   $bug_ind = $_POST["bug_ind"];
	   
	if(isset($_POST["sug_ind"]))
	   $sug_ind = $_POST["sug_ind"];
	   
	$Emailfrom_name=$_POST["fname"];	
	
	$EmailFrom=str_replace("'","''",$_POST["email"]);
	$EmailFrom=str_replace("\"","''",$EmailFrom);
	$EmailFrom=stripslashes($EmailFrom);	
	
	$brmsg=str_replace("'","''",$_POST["brmsg"]);
	$brmsg=str_replace("\"","''",$brmsg);
	$brmsg=stripslashes($brmsg);
	  
	$msgtime=date("Y-m-d H:i:s"); 
	//$Emailfrom_name = "YouCureMe.com";  
	$EmailTo = "info@youcureme.com";
	//$EmailTo = "ipod1965@hotmail.com";
	$EmailSubject = 'YouCureMe.com:Bug Reporting/Areas of improvement - Info received';	
	$user_subject = $_POST["subject"];
	
	$msg = "Hi Marc\r\n\n";
	$msg.= "The following ";
	
	if($bug_ind == 'bug' and $sug_ind == 'sug')
		$msg.= "Bug/Suggestion ";
	else
	if($bug_ind == 'bug')
	  $msg.= "Bug ";
	else
	if($sug_ind == 'sug')
	  $msg.= "Suggestion ";	

	$msg.= "has been received from a visitor/member $Emailfrom_name of your website www.YouCureMe.com on $msgtime\r\n";
	$msg.= "=============== User's Message Start ======================\r\n";
	$msg.= $user_subject."\r\n\n";
	$msg.= $brmsg."\r\n";
	$msg.= "=============== User's Message End   ======================\r\n"; 
	$EmailMsg = nl2br($msg); 
	
	$msg_sent = send_smtpmail($EmailTo, $EmailFrom, $Emailfrom_name, $EmailSubject, $EmailMsg);	 
	 
	if($msg_sent)
	  echo "<script>location.href='index.php?&msg=Thanks $Emailfrom_name. Your Report has been sent to the Site Administrator.'</script>"; 
	else   
	  echo "<script>recover.href='index.php?&msg=Email Problem - Cannot send your report.'</script>";      
		//echo "Please go <a href='javascript:history.go(-1)'>back</a> and try again."; 
	}
	else
	{echo "<script>location.href='bugreport.php?msg=The security code entered was incorrect.</script>";  
	}
}

if(isset($_GET['msg']))
{ ?>
	<div class="txttitle whitetitle size12 bold"><?php echo $_GET['msg']; ?></div>
<?php 
}
?> 
<script type="text/javascript">
rC = function(radioEl) {
	if(radioEl.checked == true) {
		radioEl.checked = false;
		return false;
	}
	else {
		radioEl.checked = true;
		return true;
	}
}
</script>

</head>
<body>
  <div id="wrapper">  
  	<div id="top_header">
    	<div id="logo">
        	<a href="index.php"><img src="images/logo_2.png" alt="You Cure Me"></a>
        </div>
        <div id="layer1">
                  <span id="close">
                  <a href="javascript:setVisible('layer1')" style="text-decoration: none"><img src="images/close.png" alt="Close"></a>
                  </span>
                  <h2>You Cure Me Login</h2>
                  <form action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="post"  onSubmit="return signins(this);">
                  <div>
                  <label>
                  Your Email: <span class="required">*</span> 
                  </label>
                  </div>
                  <div>
                 <input class="text_feild" name="email" id="email" type="text" />
                  </div>
                  <div>
                  <label>
                  Password <span class="required">*</span> 
                  </label>
                  </div>
                  <div>
                 <input class="text_feild" name="pwd" id="pwd" type="password" />
                    </div>
                    <div>
                     
                  <button type="submit"  name="login_button" id="login_button" value="signin" class="submit">
                  </button>
                  <span style="padding-top:16px;"><a href="recover.php">Forgot your password?</a> </span>
                  </div>
                
                  </form>
         </div>
        <div class="right_links">
        	<h2>Already Have an Account?</h2>
            <div class="login_button">
            <a href="index.php" onclick="setVisible('layer1');return false" target="_self">
            <img src="images/login_but.png" alt="Login"></a>
            </div>
        </div>
        </div>       
        <div class="clear"></div>
        <div class="top">
            <p><?php  if(isset($_GET['msg']))
                { ?>
                    <div class="txttitle red size12 bold"><?php echo $_GET['msg']; ?></div><br />
                <?php 
                }
           ?></p> 
         </div>
        <p>&nbsp;</p>
       <div style="width:100%;margin:0 auto;background:url(images/sign_up_bgg.png) repeat-y scroll 0 0 transparent;border-radius:8px;border:1px solid #CCC">
       	<div class="login_homeform_1_bug">    
                	<br><div class="login_title_1">Email YouCureMe.com Regarding</div> 
                	<br><br><div class="login_title_1">Bug Reporting/Areas to Improve</div><br>
				 <div style="height:20px;"></div>
			      <form action="bugreport.php" method="post" >
			     
				  
					 <div>
                  <span class="blacktxt_1 bold" >To:&nbsp;</span><span class="blacktxt_1 bold" ><?php  echo "www.YouCureMe.com"; ?></span></br>
					</div>					
				
					 <div>
                  <label>
                  Your Email: <span class="required">*</span> 
                  </label>
                  </div>
                  <div>
                 <input class="text_feild" name="email" id="email" type="text" />
                  </div>
                  			
				 <div>
                  <label>
                 From: <span class="required">*</span> 
                  </label>
                  </div>
                  <div>
                  <input class="text_feild" name="fname" id="fname" type="text" />
                  </div>
					 <div>
                  <label>
                 Subject:  
                  </label>
                  </div>
                  <div>
                 <input class="text_feild" name="subject" id="subject" type="text" />
                  </div>
				 <div>
                  <label>
                  Enter The Information:
                  </label>
                  </div>
                  <div><br />
                  <table style="100%" align="left">
                   <tr> 
                   <td class="bold size12" width="50%"> 
					<Input type="Radio" id="sug_ind" Name="info" value="sug"<?PHP print $sug_ind; ?> onClick="return false" onMouseDown="rC(this)" style="width:40px;" checked="checked"><strong style="color:#000">Suggestion</strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<Input type="Radio" id="bug_ind" Name="info" value="bug"<?PHP print $bug_ind; ?> onClick="return false" onMouseDown="rC(this)" style="width:40px;"><strong style="color:#000">Bug</strong> </td>
					</tr> 
                     <tr>
                     <td align="left" class="size12">
                     <textarea style="height:200px;border:1px #bcbcbc solid; padding-left:3px; scrollbar-base-color:lightblue;"
					 id="brmsg" name="brmsg" rows="3" cols="93"></textarea>
                     </td>
                    </tr>
                     <tr><td><br /></td></tr>	 
                   <tr>
				   <td  align="left" class="blacktxt_1 bold"> 
					<!--<img id="captcha" src="/securimage/securimage_show.php" alt="CAPTCHA Image" /> 
                   	  <input type="text" name="captcha_code" value="Math.random();">
					<a href="#" onclick="document.getElementById('captcha').src = '/securimage/securimage_show.php?' + Math.random(); return false"></a>
					<a style="border-style: none;" href="#" title="Refresh Image" onclick="document.getElementById('captcha').src = './securimage/securimage_show.php?sid=' + Math.random(); this.blur(); return false">
					<img src="securimage/images/refresh.png" align="top" alt="Reload Image" onclick="this.blur()" border="0">	</a>									
					<br /><object type="application/x-shockwave-flash" align="bottom" data="/securimage/securimage_play.swf?audio_file=/securimage/securimage_play.php&amp;bgColor1=#fff&amp;bgColor2=#fff&amp;iconColor=#777&amp;borderWidth=1&amp;borderColor=#000" width="29" height="29"> <param name="movie" value="/securimage/securimage_play.swf?audio_file=/securimage/securimage_play.php&amp;bgColor1=#fff&amp;bgColor2=#fff&amp;iconColor=#777&amp;borderWidth=1&amp;borderColor=#000" /> </object>
 					Enter Code :-->
                   &nbsp;&nbsp;&nbsp;<img src="CaptchaSecurityImages.php?width=100&height=40&characters=5" />&nbsp;&nbsp;&nbsp;
					 <input id="security_code" name="security_code" type="text" maxlength="6" style= "background: #FFFF00; font-size: 16px; color: #9b2220;width:120px" /><!--<input type="text" name="captcha_code" size="12" /> -->									
					<input type="hidden" name="code" value="<?php echo $_SESSION["security_code"]?>">
                    </td>
					</tr> 
 					<tr>
                    <td align="left" >
					<input id="submit-subscirbe" type="button" onclick="history.go(-1)" value="Cancel" />&nbsp;<input id="submit-subscirbe" type="submit" value="Send" />
                    </td> 
                    
					</tr> 
					 <tr>
                      <td style="width:103px;" align="left" valign="top">&nbsp;</td>
                      </tr>
                     </table>
                        </div>	 
	      </form>	
          <div style="clear:both"></div>
       
       
        </div> <div style="clear:both"></div>
        </div>  
        
        </div>
   
     
<div class="bottom_wrapper">
	<div class="bottom_footer">
    	<h2></h2>
        <p> </p>
        <p></p>
        <p></p>
    </div>
   <?php include "footer_new.php"; ?>
</div>
</body>
</html>

