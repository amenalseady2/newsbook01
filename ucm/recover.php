<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>You Cure Me</title>
<?php include "header_new.php"; ?>
<?php $email='';
if(isset($_REQUEST["email"])!="")
{
	$email=$_REQUEST["email"];
}
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
	$email=str_replace("'","''",$_POST["email_to"]);
	$email=str_replace("\"","''",$email);
	$email=stripslashes($email);	
	 
	$EmailTo = $email;
	
	$query="select * from tbluser where email='".$email."'";
	$result = mysql_query($query);
	$num = mysql_num_rows($result);
	 
	if($num > 0)
	{
		$row=mysql_fetch_array($result);
		$first=$row['fname'];
		$last=$row['lname'];
		$your_password=$row['password'];  
		
		// Send email to the user	 
		$EmailMsg1 = "Hi $first $last \r\n\n";
		$EmailMsg1.= "Your password for login to our website www.YouCureMe.com \r\n";
		$EmailMsg1.=" is $your_password \r\n\n";		
		$EmailMsg1.="YouCureMe Team";
		//$EmailMsg = $EmailMsg1;
		$EmailMsg = nl2br($EmailMsg1);
		
		$EmailFrom = "info@youcureme.com";
		$Emailfrom_name = "YouCureMe.com"; 
		$EmailSubject = "Your password here!";	
		
		//$EmailMsg = "<html><p><body".$EmailMsg1."</body></p></html>";
		
		$msg_sent = send_smtpmail($EmailTo, $EmailFrom, $Emailfrom_name, $EmailSubject, $EmailMsg);	 
		if($msg_sent)
		   echo "<script>location.href='index.php?&msg=Your Password Has Been Sent To Your Email Address.'</script>"; 
		else   
		   echo "<script>recover.href='index.php?&msg=Email Problem - Cannot send password to your e-mail address.'</script>";      
	}
	else
	{	 
		echo "<script>location.href='recover.php?&msg=Your Email is not found in our records.'</script>";
	}
}

?>
<script type="text/javascript">
function check_recover(theform)
{
   var emailRegex = /^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$/;
	if(theform.email_to.value =="")
	{
	 theform.email_to.style.border="1px solid red";
	return false;
	}
	if(!theform.email_to.value.match(emailRegex))
	{
	theform.email_to.style.border="1px solid red";
	return false;
	}
	
  return true;
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
        	<h2>"Sign up, share your experiences, make friends, learn from each other, make a difference!"</h2>
        </div>
        <div class="top_banner">
        	<img src="images/banner_img.png" alt="">
        </div>
  <div class="sign_up_top"></div>
        <div class="sign_up">
        	    
            <h3>Recover Password</h3>
            <p>it's anonymous and free.</p>
            <form action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="post" onSubmit="return check_recover(this)" >
            	<fieldset> 
                <div>
            	<label>
                Enter your email <span class="required">*</span>                
                </label>
                </div>                
                <span class="small">(no spam, we're here to do good)</span>
                <div>
                <input class="text_feild" name="email_to" id="email_to" type="text"  placeholder="Enter Your email">
                </div>
                <div style="clear:both"></div>
                <div><br />
                <button type="submit"  name="submit" id="submit" value="Submit" class="submit" style="margin-right:25px;"></button>                            
                </div>
                </fieldset>
            </form>
            <div style="height:180px"></div>
            
                <div class="sign_up_bottom"></div>
        </div>
        </div>
        </div>
        <div style="clear:both"><br /></div>
      
<div class="bottom_wrapper">
	<div class="bottom_footer">
    	<h2></h2>
       
    </div>
   <?php include "footer_new.php"; ?>
</div>
</body>
</html>

