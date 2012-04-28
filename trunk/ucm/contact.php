<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>You Cure Me</title>
<?php include "header_new.php"; ?>
<?php 
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

?>

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
       
        <div class="clear"></div>  
        <div class="top">
                <div class="bottom_footer">
                    <h2 class="h2heads"> Contact:</h2>   
                    <p class="headcontent">We will make our best effort to contact you within a short delay, provided your requests are within our abilities to help you. </p>               
                    <ul>
                        <p><span><a href="mailto:info@ucureme.com" class="links">info@ucureme.com</a></span></p>
                        <p><a href="http://www.ucureme.com" target="_blank" class="links">www.ucureme.com</a></span></p>
                        <p><span class="spanss">3551 Blvd St Charles Suite 284</span></p>
                        <p><span class="spanss">Kirkland, Quebec H9H3C4</span></p>
                        <p><span class="spanss">Canada</span></p>
                       
                    </ul>
                   
                </div>               
            </div>
            </div>
<div class="bottom_wrapper">
	<div class="bottom_footer">
    	<h2></h2>
       
    </div>
   <?php include "footer_new.php"; ?>
</div>
</body>
</html>
