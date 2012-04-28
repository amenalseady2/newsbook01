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
        	 <?php require("right_login.php");?>
        </div>
        </div>       
  <div class="clear"></div>
           
       <div class="top">
                <div class="bottom_footer">       
          <h2 class="h2heads">Why UCureMe:</h2>   
          <p class="headcontent">
         If you had cancer, wouldn't you like to know why 40% of Canadian women and 45% of men will develop cancer during their lifetimes. An estimated 1 out of every 4 Canadians is expected to die from cancer.
         </p>
        <strong>source:</strong>&nbsp;&nbsp;<a href="http://www.cancer.ca/Canada-wide/About%20cancer/Cancer%20statistics/Stats%20at%20a%20glance/General%20cancer%20stats.aspx?sc_lang=en#ixzz1Yc8sbWIe" class="linkss" target="_blank">
http://www.cancer.ca/Canada-wide/About%20cancer/Cancer%20statistics/Stats%20at%20a%20glance/General%20cancer%20stats.aspx?sc_lang=en#ixzz1Yc8sbWIe
</a><br /><br />
<p class="headcontent">Wouldn't you like to know more insight on the causes of the illness through a collective of free flowing unbiased data? Wouldn't you like to know what your treatment options are on a global level to improve your odds of beating it? Wouldn't you like to have better resources to help your loved one through these challenges and gain valuable insight on how you as a loved one can better manage the challenges that await.</p> 
<p class="headcontent">UCureMe.com is a network with a cause and strong niche centered at the core of humanity to improve the quality of life. This network is an invaluable resource for research and development as well as a network that society can fall back on in times of need.
</p>
         </div>
      <div style="clear:both"></div>
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
