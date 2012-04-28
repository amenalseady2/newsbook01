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
          <h2 class="h2heads">Privacy:</h2>  
          <h2 class="smallheadings">Your Privacy Is Extremely Important To Us And We Place a Lot Of Emphasis On This.</h2>
 <p class="headcontent">As a registered member, you will be able to control many aspects of your privacy settings in your profile and how you choose to share the information with other members, friends and the general public. We understand the risk of discomfort in disclosing such personal issues and we offer members great control on their profile privacy settings and members can change privacy settings at any time. Members are also free to opt out of surveys and discussions at their discretion.</p>
 <h2 class="smallheadings">What information do we collect? </h2>
<p class="headcontent">We collect information from you when you register on our site, respond to a survey; fill out a form or social network. You may enter as much or as little information as you like and you may control many elements of you profile settings </p><br />
<p class="headcontent">When registering on our site, as appropriate, you will be asked to enter your name, interest, status, and e-mail address that uniquely identifies members.</p><br />
<p class="headcontent">  …Health information is information members upload about their health status, as part of their user profile or in answer to UCureMe research surveys. Examples of health information are details about conditions or diseases that members have, including the first notice of symptoms, date of diagnosis, co-existing conditions, family history, quality of life effects, list and severity of symptoms, timeline or progression of disease, results of health tests or genetic tests or biometric measurements, details of treatments tried (e.g. dates, dosages, measures of effectiveness in terms of quality of life), and other factors that could possibly be related to or associated with the disease or condition (e.g. environmental factors, personal traits, ethnicity, and other factors as defined by members). </p><br />
<p class="headcontent">  …As part of UCureMe's focus on research to understand disease, members will have the choice to participate in short, periodic surveys, which are optional and voluntary. To provide the highest quality features and benefits for a great member experience, we may also review members' personal information internally as needed. </p><br />
<p class="headcontent">  …Resource Information - Resource information is information members post about treatments, doctors, studies, things to read, or support resources, or reviews about their experience with the posted resources. Members can choose to post resources and/or reviews anonymously or with a screenname linked to their profile. Posted resources and reviews will be visible to all members and visitors to the site, to help people learn from each others' experiences. </p><br />
 <h2 class="smallheadings">What do we use your information for? </h2>
<p class="headcontent">Any of the information we collect from you may be used in one of the following ways: </p>
<p class="headcontent">1) To personalize your experience <br />(your information helps us to better respond to your individual needs)</p>
<p class="headcontent">2) To improve our website <br />(we continually strive to improve our website offerings based on the information and feedback we receive from you)</p>
<p class="headcontent">3) To send periodic emails</p>
<p class="headcontent">4) To authenticate members and usage</p>
<p class="headcontent">5) We publish publicly and anonymously the results compiled from ongoing surveys in all interest categories. </p>
<p class="headcontent">6) The information from the ongoing surveys is shared with researchers for scientific research. Researchers will have access to the users information and answers of individual members surveys to learn from and in the event, to communicate with the users to advance scientific knowledge.</p>
<p class="headcontent">7) We may use your information for research purposes. Researchers around the world are working together to take the valuable information you are providing in an aim to improve quality of life.</p>
<p class="headcontent">8) The email address you provide may be used to send you information, respond to inquiries, and/or other requests or questions.</p>
<h2 class="smallheadings">How do we protect your information? </h2>
<p class="headcontent">We implement a variety of security measures to maintain the safety of your personal information when you enter, submit, or access your personal information. </p>
<h2 class="smallheadings">Do we disclose any information to outside parties? </h2>
<p class="headcontent">We do not sell, trade, or otherwise transfer to outside parties your personally identifiable information. This does not include trusted third parties who assist us in operating our website, conducting our business, or servicing you, so long as those parties agree to keep this information confidential. We may also release your information when we believe release is appropriate to comply with the law, enforce our site policies, or protect others or our rights, property, or safety. However, non-personally identifiable visitor information may be provided to other parties for marketing, advertising, or other uses.</p>
<h2 class="smallheadings">Third party links </h2>
<p class="headcontent">Occasionally, at our discretion, we may include or offer third party products or services on our website. These third party sites have separate and independent privacy policies. We therefore have no responsibility or liability for the content and activities of these linked sites. Nonetheless, we seek to protect the integrity of our site and welcome any feedback about these sites.</p>
<h2 class="smallheadings">Online Privacy Protection Act Compliance</h2>
<p class="headcontent">Because we value your privacy we have taken the necessary precautions to be in compliance with the California Online Privacy Protection Act. We therefore will not distribute your personal information to outside parties without your consent.</p>
<p class="headcontent">As part of the Online Privacy Protection Act, all users of our site may make any changes to their information at anytime by logging into their control panel and going to the 'Edit Profile' page.</p>
<h2 class="smallheadings">Online Privacy Policy Only </h2>
<p class="headcontent">This online privacy policy applies only to information collected through our website and not to information collected offline. </p>
<h2 class="smallheadings">Protecting Your Privacy</h2>
<p class="headcontent">Protecting your personal information is a shared responsibility and we encourage our members to keep their passwords and other sensitive information private and secure. We keep your data secure using commercially reasonable technical precautions</p>
<h2 class="smallheadings">Risks  </h2>
<p class="headcontent">UCureMe has no way of guaranteeing the validity of the information that members may submit and make publicly available.</p>
<p class="headcontent">UCureMe has no way to authenticate the identity of any member,.</p>
<p class="headcontent">Any information you review is at your own risk. </p>
<h2 class="smallheadings">Terms and Conditions </h2>
<p class="headcontent">Please also visit our Terms and Conditions section establishing the use, disclaimers, and limitations of liability governing the use of our website at <a href="http://www.ucureme.com" target="_blank" class="links">www.ucureme.com</a></p>
<h2 class="smallheadings">Your Consent </h2>
<p class="headcontent">By using our site, you consent to our privacy policy.</p>
<h2 class="smallheadings">Changes to our Privacy Policy </h2>
<p class="headcontent">If we decide to change our privacy policy, we will post those changes on this page. </p>
<h2 class="smallheadings">Contacting Us </h2>   
     
                    <p class="headcontent">We will make our best effort to contact you within a short delay, provided your requests are within our abilities to help you. </p>               
                    <ul>
                        <p><span><a href="mailto:info@ucureme.com" class="links">info@ucureme.com</a></span></p>
                        <p><a href="http://www.ucureme.com" target="_blank" class="links">www.ucureme.com</a></span></p>
                        <p><span class="spanss">3551 Blvd St Charles Suite 284</span></p>
                        <p><span class="spanss">Kirkland, Quebec H9H3C4</span></p>
                        <p><span class="spanss">Canada</span></p>
                       
                    </ul>

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
