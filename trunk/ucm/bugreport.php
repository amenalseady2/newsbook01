<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>You Cure Me</title>
<?php include "header_new.php"; 
//echo $_SERVER['DOCUMENT_ROOT'];
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


$msg='';
if(isset($_POST["bug_submit"])=='Send')
{   
 
  if($_POST["security_code"]==$_POST["usecurity_code"] )
	 {
	
	  
	  if(isset($_POST["bug_ind"]))
	  { $bug_ind = $_POST["bug_ind"];
	  
	 }
	   
	if(isset($_POST["sug_ind"]))
	  { $sug_ind = $_POST["sug_ind"];
	  
		}
	   
	  
	   
	$Emailfrom_name=str_replace("''","''",$_POST["fname"]);
	$Emailfrom_name=str_replace("\"","''",$Emailfrom_name);
	$Emailfrom_name=stripslashes($Emailfrom_name);	
	
	$EmailFrom=str_replace("'","''",$_POST["email"]);
	$EmailFrom=str_replace("\"","''",$EmailFrom);
	$EmailFrom=stripslashes($EmailFrom);
	
	$user_subject=str_replace("''","''",$_POST["subject"]);
	$user_subject=str_replace("\"","''",$user_subject);
	$user_subject=stripslashes($user_subject);	
	
	$brmsg=str_replace("'","''",$_POST["brmsg"]);
	$brmsg=str_replace("\"","''",$brmsg);
	$brmsg=stripslashes($brmsg);
	$msgtime=date("Y-m-d H:i:s");
	$bs="";
	if($_POST['info'] == 'bug')
	{
		$bs.="Bug";
	echo $sql="insert into tblbugs values ('null', '".$EmailFrom."', '".$Emailfrom_name."', '".$user_subject."','".$bs."', '".$brmsg."','".$msgtime."','Pending')";
	
	mysql_query($sql);
	}
	else
	{
			$bs.="Suggestion";
		$sql="insert into tblbugs values ('null', '".$EmailFrom."', '".$Emailfrom_name."', '".$user_subject."', '".$bs."','".$brmsg."','".$msgtime."', 'Pending')";
		mysql_query($sql);
	}
	  
	
	 
	//$Emailfrom_name = "YouCureMe.com";  
	$EmailTo = "info@youcureme.com";
	//$EmailTo = "ipod1965@hotmail.com";
	$EmailSubject = 'YouCureMe.com:Bug Reporting/Areas of improvement - Info received';	
	
	$msg = "Hi Marc\r\n\n";
	$msg.= "The following ";
	
	if($bug_ind == 'bug' and $sug_ind == 'sug')
		$msg.= "Bug/Suggestion ";
	else
	if($bug_ind == 'bug')
	  {$msg.= "Bug ";
	  
	  }
	else
	if($sug_ind == 'sug')
	  { $msg.= "Suggestion ";
	  }

	$msg.= "has been received from a visitor/member $Emailfrom_name of your website www.YouCureMe.com on $msgtime\r\n";
	$msg.= "=============== User's Message Start ======================\r\n";
	$msg.= $user_subject."\r\n\n";
	$msg.= $brmsg."\r\n";
	$msg.= "=============== User's Message End   ======================\r\n"; 
	$EmailMsg = nl2br($msg); 
	$admin_email = get_admin_email();
	if($admin_email){
		$msg_sent = send_smtpmail($admin_email, $EmailFrom, $Emailfrom_name, $EmailSubject, $EmailMsg);	
	}
		 
	$msg_sent = send_smtpmail($EmailTo, $EmailFrom, $Emailfrom_name, $EmailSubject, $EmailMsg);
	if (isset($_SESSION['email']) or (($_SESSION['admin'])== 5))
	{ 
		if($msg_sent)
	  echo "<script>location.href='myprofile.php?&msg=Thanks $Emailfrom_name. Your Report has been sent to the Site Administrator.'</script>"; 
	else   
	  echo "<script>recover.href='myprofile.php?&msg=Email Problem - Cannot send your report.'</script>";
	} else {
		if($msg_sent)
	  echo "<script>location.href='index.php?&msg=Thanks $Emailfrom_name. Your Report has been sent to the Site Administrator.'</script>"; 
	else   
	  echo "<script>recover.href='index.php?&msg=Email Problem - Cannot send your report.'</script>";
}
	
	echo "Please go <a href='javascript:history.go(-1)'>back</a> and try again."; 
	}
	else
	{
		 //Insert your code for showing an error message here
		$msg="Sorry, you have provided an invalid security code";
    }
	
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
        <?php if (isset($_SESSION['email'])) {?>
        	<a href="myprofile.php"><img src="images/logo_2.png" alt="You Cure Me"></a>
            <?php } else if (isset($_SESSION['admin'])== 5) {
				 ?><a href="viewuser.php"><img src="images/logo_2.png" alt="You Cure Me"></a> <?php } else { ?>
              <a href="index.php"><img src="images/logo_2.png" alt="You Cure Me"></a> <?php } ?>
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
        	 <?php require("right_login.php");?>
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
			      <form action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" name="bugs" id="bugs" method="post" onSubmit="return check_bug(this);">
			      <table width="450px" align="left">
									<tr>
                                    <td align="left" class="meages">
								<?php 
									if (isset($msgEmail)!=''){
										echo $msgEmail;
									}
								?> </td>
                                </tr>
				     </table>
                   <div>
                  <label>
                 <strong>To:&nbsp;<?php  echo "www.YouCureMe.com"; ?></strong></br> 
                  </label>
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
					 id="brmsg" name="brmsg" rows="3" cols="110"></textarea>
                     </td>
                    </tr>
                     <tr><td><br /></td></tr>	 
                   <tr>
				   <td  align="left" class="blacktxt_1 bold"> 
				
                   &nbsp;&nbsp;&nbsp;
                   <div style="font-size:30px;font-family:'Courier New', Courier, monospace;text-decoration:line-through;background:url(images/texture10.jpg) no-repeat center;width:150px;height:40px;padding:5px;text-align:center"><?php $random_chars='';
$characters = array(
"A","B","C","D","E","F","G","H","J","K","L","M",
"N","P","Q","R","S","T","U","V","W","X","Y","Z","a","b","c","d","e","f","g","h","j","k","l","m",
"n","p","q","r","s","t","u","v","w","x","y","z",
"1","2","3","4","5","6","7","8","9");

//make an "empty container" or array for our keys
$keys = array();

//first count of $keys is empty so "1", remaining count is 1-6 = total 7 times
while(count($keys) < 7) {
    //"0" because we use this to FIND ARRAY KEYS which has a 0 value
    //"-1" because were only concerned of number of keys which is 32 not 33
    //count($characters) = 33
    $x = mt_rand(0, count($characters)-1);
    if(!in_array($x, $keys)) {
       $keys[] = $x;
    }
}

foreach($keys as $key){
   $random_chars .= $characters[$key];
}
echo $random_chars;
?>

                   
                   </div>&nbsp;&nbsp;&nbsp;<input id="security_code" name="security_code" type="hidden" value="<?php echo $random_chars?>">
					
                    </td>
					</tr> 
                    <tr>
                    <td align="left" ><strong>Enter Captch Code:</strong>
                     <input id="usecurity_code" name="usecurity_code" type="text" maxlength="7" style= "background:#FCFED6 ; font-size: 16px; color: #9b2220;width:180px;height:30px" />					
                    </td></tr>
 					<tr>
                    <td align="left" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br />
					<input id="submit-subscirbe" type="button" onclick="history.go(-1)" value="Cancel" />&nbsp;
                    <input id="bug_submit" name="bug_submit"  type="submit" class="submit" value="Send" />
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

<?php

function get_admin_email(){
	
	$query="select email from tbluser where userid=1";
	$result=mysql_query($query);
	$num=mysql_num_rows($result);
	if($num>0)
	{
		$row=mysql_fetch_array($result);	
				
		return $row["email"];
	}
	else 
		return false;
}

?>