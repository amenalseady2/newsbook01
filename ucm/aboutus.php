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

if(isset($_POST["Signup_button_main"])=="signup")
	{	
	
			$fname=str_replace("'","''",$_POST["fname"]);
			$fname=str_replace("\"","''",$fname);
			$fname=stripslashes($fname);
		
			$lname=str_replace("'","''",$_POST["lname"]);
			$lname=str_replace("\"","''",$lname);
			$lname=stripslashes($lname); 
						
			$email=str_replace("''","''",$_POST["email"]);
			$email=str_replace("\"","''",$email);
			$email=stripslashes($email);
			
			$password=str_replace("''","''",$_POST["pwd"]);
			$password=str_replace("\"","''",$password);
			$password=stripslashes($password);
			
			$profilepic='';
			$dob='';
			$genderid='';
			$usertypeid=$_POST["usertypeid"];
			$diseaseid=$_POST["diseaseid"];
			$city='';
			$countryid='';
			$website='';
			$iam='';
			$ilike='';
			$myexperience='';
			$isactive=0;
							
			try
			{	
				$query="select * from tbluser where email='".$email."'";
				$result=mysql_query($query);
				$num=mysql_num_rows($result);
				if($num>0)
				{
					echo "<script>location.href='index.php?msg=Email already exists. Please select different email.' </script>";
				}
				else
				{
$query="insert into tbluser(fname,lname,profilepic,dob,genderid,usertypeid,diseaseid,city,countryid,email,password,website,iam,ilike,myexperience,isactive) 
		values('".$fname."','".$lname."','".$profilepic."','".$dob."','".$genderid."','".$usertypeid."','".$diseaseid."','".$city."','".$countryid."','".$email."','".$password."','".$website."','".$iam."','".$ilike."','".$myexperience."',".$isactive.")";
		echo $query;
															
					if(mysql_query($query))
					{
						$userid=mysql_insert_id();
					
					/* Send an account activation link via SMTP email to the registered user */		
					$EmailTo = $email;	 
					$EmailFrom = "info@youcureme.com";
					$Emailfrom_name = "YouCureMe.com"; 
					$EmailSubject = "YouCureMe: Confirm your email!";
					$key = encrypt_userid($userid);
					
									
					$EmailMsg =  
					"Hello ".$fname." ".$lname. ",
						Thank you for signing up with YouCureMe. 
						Please click on the following link to verify your email address.".
						"http://www.youcureme.com/verify.php?u=".$userid."&k=".$key.""."<br />".
						"YouCureMe.com"; 
									
					send_smtpmail($EmailTo, $EmailFrom, $Emailfrom_name, $EmailSubject, $EmailMsg);	 					
					echo "<script>location.href='confirm.php?u=".$userid."&k=".$key."&email=".$email."'</script>";			
						//this is original flow
					//	echo "<script>location.href='confirm.php?email=".$email."' 					
						
				/*******************************************************************************************************************/
						//temp flow 
												
						/*$_SESSION["userid"]=$userid;
						$_SESSION["fname"] = $fname." ".$lname;
						$_SESSION["email"] = $email;
						$_SESSION["usertypeid"]=$usertypeid;
						$_SESSION["profilepic"]="empty_profile.jpg"; */
						
						echo "<script>location.href='verify.php?userid=".$userid."' </script>";
						
				/*******************************************************************************************************************/ 				 
					}
					else
					{
						echo mysql_error();
					}
				}
			}
			catch(exception $ex)
			{
				echo $ex;
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
      
           <p><?php  if(isset($_GET['msg']))
                { ?>
                    <div class="txttitle red size12 bold"><?php echo $_GET['msg']; ?></div>
                <?php 
                }
           ?></p>
           <div style="clear:both"></div>
             <div class="top">
                <div class="bottom_footer">
                    <h2 class="h2heads">About us:</h2>
                    <p>A global initiative, a global improvement, a global game changer, UCureMe.com</p>
                    <p>Here is the idea behind this social network that will not only be the buzz, but also actually make a difference in improving the health and well being of people around the world.</p>
                     <h2 class="h2heads">UCureMe is:</h2>
                    <ul>
                        <p>A global social network for those who are dealing with a medical condition(s).</p>
                        <p>A global social network that serves as a support for the families / friends of those with medical conditions.</p>
                        <p>A global social network for medical professionals to interact with each other and those affected by the medical conditions.</p>
                        <p>A global social network that informs on the latest global trends for causes, risk management, treatments, and options for patients, breaking through the limitations of regional and national boundaries.</p>
                        <p>Regional patient networks are starting up and exist at hospitals and within communities. This site aims to bring them and their resources together to provide a powerhouse of information, data and a community that will improve people lives.</p>
                        <p>A resource to help define the causes of illness. Each patient/family member as an initiation will complete and partake in an ongoing survey. This survey will provide valuable research and insight on the causes of the illness faced on a regional, national and global level.</p>
                    </ul>
                    <p>This unique compilation of data will help paint a clear picture on the causes of illness to better aid research and inform the public on the health risks we face in our day-to-day lives. This ongoing data will be statistically analyzed and made available to the public.</p>
                </div>
               
            </div>    </div>
            <div style="clear:both"></div>
<div class="bottom_wrapper">
	<div class="bottom_footer">
    	<h2></h2>
      
    </div>
   <?php include "footer_new.php"; ?>
</div>
            
        </body>
</html>
