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
unset ($_SESSION["admin"]);
if(isset($_POST["admin_login"])=="admin_signin")
{

	$email = $_POST["email"];
	$password = $_POST["pwd"];
	$query="select userid, user_previlege, fname,lname,email,usertypeid,thumb_profile as profilepic from tbluser where email='".$email."' AND password='".$password."' AND (isactive=1 or isactive=5)";
	$result=mysql_query($query);
	$num=mysql_num_rows($result);
	
	if($num>0)
	{
		$row=mysql_fetch_array($result);
		if($row["user_previlege"] == 5)
		{
			//Admin user
			 $_SESSION["userid"]=$row["userid"];
			 $_SESSION["fname"] = $row["fname"]." ".$row["lname"];
			 //$_SESSION["email"] = $row["email"];
			 $_SESSION["usertypeid"]=$row["usertypeid"];
			 $_SESSION["user_previlege"]=$row["user_previlege"];
		
			if($row["profilepic"]!="")
				$_SESSION["profilepic"]=$row["profilepic"];
			else
				$_SESSION["profilepic"]="empty_profile.jpg";
		
			$_SESSION["admin"] = 5;
			echo "<script>location.href='viewuser.php' </script>";			 
		}
		elseif($row["user_previlege"] == 10)
		{
		//Admin user
			 $_SESSION["userid"]=$row["userid"];
			 $_SESSION["fname"] = $row["fname"]." ".$row["lname"];
			 //$_SESSION["email"] = $row["email"];
			 $_SESSION["usertypeid"]=$row["usertypeid"];		
		
			if($row["profilepic"]!="")
				$_SESSION["profilepic"]=$row["profilepic"];
			else
				$_SESSION["profilepic"]="empty_profile.jpg";
		
			$_SESSION["admin"] = 5;
			echo "<script>location.href='super_admin.php' </script>";			
		}
		else
		{		 
			echo "<script>location.href='admin.php?msg=Invalid Admin credentials. Please login again!!!'</script>";
		}
	}
	else
	{		 
		echo "<script>location.href='admin.php?msg=Invalid Admin credentials. Please login again!!!' </script>";
	}
}

?>
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
        	<h2>"Sign up, share your experiences, make friends, learn from each other, make a difference!"</h2>
        </div>
        <div class="top_banner">
        	<img src="images/banner_img.png" alt="">
        </div>
  <div class="sign_up_top"></div>
        <div class="sign_up">   
        <h3>Admin&nbsp;Panel</h3>
            <p></p>

           <?php  if(isset($_GET['msg']))
                { ?>
                    <div class="txttitle red size12 bold" style="margin-left:15px;"><?php echo $_GET['msg']; ?></div><br />
                <?php 
                }
           ?>     	    
           <form action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="post" onSubmit="return check_admin(this)">
                
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
                  Password: <span class="required">*</span> 
                  </label>
                  </div>
                  <div>
                 <input class="text_feild" name="pwd" id="pwd" type="password" />
                  </div>
					 <div>
                     
                  <button type="submit"  name="admin_login" id="admin_login" value="admin_signin" class="submit" style="margin-right:23px;">
                  </button>
                  <span style="padding-top:16px;"><a href="recover.php"><br />Forgot your password?</a> </span>
                  </div>    
                   <div>                     
                  <br />
                    </div>
        </form>
            <div style="height:185px"></div>
            
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

