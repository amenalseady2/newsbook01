<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>You Cure Me</title>
<?php include "header_new.php"; ?>

<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script> 
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.3/jquery-ui.min.js"></script>


<script type="text/javascript">
$(document).ready(function(){


	//set the initial values
	$.post("index_interest_combo_ajax.php", { id: $("#diseaseid").val() },function(data){
		if(data && data !=""){
			$("#subdiseaseid").empty().html(data);
			$("#subinterest_section").css('display', 'block');
		} else {
			$("#subdiseaseid").empty();
			$("#subinterest_section").css('display', 'none');
		}
		
		
			
		
	});


	//set the subdisease combo on change of disease combo
	$("#diseaseid").change(function(){
		var diseaseid = $(this).val();
		$.post("index_interest_combo_ajax.php", { id: diseaseid },function(data){
				if(data && data !=""){
			$("#subdiseaseid").empty().html(data);
			$("#subinterest_section").css('display', 'block');
		} else {
			$("#subdiseaseid").empty();
			$("#subinterest_section").css('display', 'none');
		}

//			$("#subdiseaseid").empty().html(data);
	});

});


});
</script>


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
			/* As we eliminated fname and lname from signup screen, we assume they are balnk for the present */
			$fname = "";
			$lname = "";
		
 					
			$email=str_replace("''","''",$_POST["email"]);
			$email=str_replace("\"","''",$email);
			$email=stripslashes($email);
			
			$password=str_replace("''","''",$_POST["pwd"]);
			$password=str_replace("\"","''",$password);
			$password=stripslashes($password);
			$diseaseid=$_POST['diseaseid'];
			
			if(isset($_POST['subdiseaseid']))
				$subdiseaseid=$_POST['subdiseaseid'];
			else 
				$subdiseaseid = "";
			$profilepic='';
			$dob='';
			$genderid='';
			$usertypeid=$_POST["usertypeid"];

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
					$countryid = 37;
if(empty($subdiseaseid)) $subdiseaseid="";
					
$query="insert into tbluser(fname,lname,profilepic,dob,genderid,usertypeid,diseaseid,city,countryid,email,password,website,iam,ilike,subdiseaseid,myexperience,isactive,rcvemail4msgs,rcvemail4notifications) 
		values('".$fname."','".$lname."','".$profilepic."','".$dob."','".$genderid."','".$usertypeid."','".$diseaseid."','".$city."','".$countryid."','".$email."','".$password."','".$website."','".$iam."','".$ilike."','".$subdiseaseid."','".$myexperience."',".$isactive.",1,1)";
		//echo $query;
					
					if(mysql_query($query))
					{
						$userid=mysql_insert_id();
					
					   	if($_POST["diseaseid"]!="SUGGEST NEW INTEREST")
						{
						$diseaseid=$_POST["diseaseid"];	
						}
						else
						{
						$str=mysql_query("select max(userid) as userid from tbluser");
						$row=mysql_fetch_row($str);
						$user_id=$row["0"]+1;
						$po=mysql_escape_string($_POST["otherques"]);
						
						//$str=mysql_query("insert into tbldisease(user_id,strdisease,disease_status) value('".$user_id."','".$po."','Deactive')");
						$str=mysql_query("insert into tbldisease(strdisease,Interestsuggestedby) value('".$po."',".$userid.")");
						
						if(!$str)
						{
							die(mysql_error());				
						}
						else
						{
						$diseaseid=mysql_insert_id();
						
						}
						}
						
						
						// if($_POST["subdiseaseid"]!="SUGGEST NEW INTEREST")
						// {
							// $subdiseaseid=$_POST["diseaseid"];	
						// }
						// else
						// {
						// $po1=mysql_escape_string($_POST["otherques1"]);
						// mysql_query("insert into tblsubdisease(strsubdisease,diseaseid) value('".$po1."',".$diseaseid.")");
						// $subdiseaseid=mysql_insert_id();
						// }

						/*------------------ EMAIL TO USER ------------------------------*/ 
						/* Send an account activation link via SMTP email to the registered user */		
					
						$EmailTo = $email;	 
						$EmailFrom = "info@youcureme.com";
						$Emailfrom_name = "YouCureMe.com"; 
						$EmailSubject = "YouCureMe: Confirm your email!";
						$key = encrypt_userid($userid); 
						$link = "<a href='http://www.youcureme.com/verify.php?u=$userid&k=$key'>Click Here</a>";		 

						$EmailMsg = "<p>
						Dear Member, <br /><br />
						Thank you for signing up with YouCureMe.com<br /><br />
						Please click on the following link to verify your email address and complete the registration.
						<br /><br />$link<br /><br />
						Administrator <br />YouCureMe.com</p>";  
 				
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
	<?php 
	
	if(!isset($_SESSION["userid"]) || $_SESSION["userid"] =="")
	{
		$mlink = "index.php";
	}
	else{
		$mlink = "myprofile.php";
	
	}
	
	?>
	
    	<div id="logo">
        	<a href="<?php echo $mlink ?>"><img src="images/logo_2.png" alt="You Cure Me"></a>
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
        	<h2>Sign up, share your experiences, make friends, learn from each other, make a difference!</h2>
        </div>
        <div class="top_banner">
        	<img src="images/banner_img.png" alt="">
        </div>
        <div class="sign_up_top"></div>
        <div class="sign_up">
        	<h3>Sign Up</h3>
            <p>it's anonymous and free.</p>
               <form action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="post"  onSubmit="return signups(this);">
            	<fieldset> 
                <div>
            	<label>
                Enter your email <span class="required">*</span>                
                </label>
                </div>                
                <span class="small"></span><p></p>
                <div class="input_txt">
                 <input type="text" name="email" id="email"  placeholder="Enter Your email" class="text_feild">
                </div>
                <span class="small">Will not be publicly displayed or shared.</span>
                <div>
                <label>
                Choose a password <span class="required">*</span>                
                </label>
                 </div>
   				 <div class="input_txt">               
                <input type="password"  name="pwd" id="pwd" placeholder="Choose Password" class="text_feild">
                </div>                
                <span class="small">6 characters or more.</span>
                <div class="clear"></div>
                <div>
                <label>
                Are You <span class="required">*</span>                
                </label> 
                </div>
                <div>
                 <select class="text_list" name="usertypeid" id="usertypeid">
                    <?php    
						    $q="select usertypeid,strusertype from tblusertype";	
						    $r=mysql_query($q);
						    if($r)
						    {	
							    $n=mysql_num_rows($r);
							    if($n>0)
							    {			
								    $count=0;	
								    while($rw=mysql_fetch_array($r))
								    {
									    echo "<option value='".$rw["usertypeid"]."'";
									    echo " >".$rw["strusertype"]."</option>";
									    $count++;			
								    }
							    }
						    } 
					    ?>
                </select>
                </div>
                
                <span class="small">To integrate you into your community.</span>
                <div class="clear"></div>
                <div>
                <label>
                Your Interest <span class="required">*</span>                
                </label> 
                </div>
                <div>
               <select class="text_list"  name="diseaseid" id="diseaseid" onChange="select_otherfield()">
                    <?php    
						    $q="select diseaseid,strdisease from tbldisease where disease_status='Active' order by `order` asc";	
						    $r=mysql_query($q);
						    if($r)
						    {	
							    $n=mysql_num_rows($r);
							    if($n>0)
							    {			
								    $count=0;	
								    while($rw=mysql_fetch_array($r))
								    {
									    echo "<option value='".$rw["diseaseid"]."'";
									    if ($rw["diseaseid"]=="18") echo "selected='selected'";
									    echo " >".$rw["strdisease"]."</option>";
										
									    $count++;			
								    }
							    }
						    } 
					    ?>
                    <option value="SUGGEST NEW INTEREST">SUGGEST NEW INTEREST</option>
                </select>
                </div>
				<span class="small">To connect you with others sharing the same interests.</span>
				
				   <p></p>
                <div  class="input_txt">               
                <input type="text"  name="otherques" id="otherques" placeholder="Enter New Interest" class="text_feild" style="display:none;margin-left:-3px;"> 
                </div> 
				
				<div class="clear"></div>
            
			 <!--
			<div id="subinterest_section" >
				<div>
                <label>
                  Your SubInterest <span class="required">*</span>                
                </label> 
                </div>
                <div>
               <select class="text_list" name="subdiseaseid" id="subdiseaseid"  onChange="select_otherfieldsub()">
                    <?php    
						 /*   $q="select 	subdiseaseid, strsubdisease, diseaseid from tblsubdisease where disease_status='Active' order by `order` asc";	
						    $r=mysql_query($q);
						    if($r)
						    {	
							    $n=mysql_num_rows($r);
							    if($n>0)
							    {			
								    $count=0;	
								    while($rw=mysql_fetch_array($r))
								    {
									    echo "<option value='".$rw["subdiseaseid"]."'";
									    if ($rw["subdiseaseid"]=="1") echo "selected='selected'";
									    echo " >".$rw["strsubdisease"]."</option>";										
									    $count++;			
								    }
							    }
						    }

						*/							
					    ?>
                </select>
                </div>
			</div>
				<div class="clear"></div>
				   <p></p>
                
				
				<div  class="input_txt">               
                <input type="text"  name="otherques1" id="otherques1" placeholder="Enter New SubInterest" class="text_feild" style="display:none;margin-left:-3px;"> 
                </div>
				-->
				
				
             
                
                <div>
                <button type="submit" id="Signup_button_main" name="Signup_button_main" value="signup" class="submit">
                </button> 
                </div>
                </fieldset>
            </form>
            <div class="sign_up_bottom"></div>
        </div>
        </div>
		
		<!--<div class="usersubmit">
            &nbsp&nbsp&nbspUser submitted photo</div> -->
     
        <div style="clear:both"><br /></div>
        <div class="bottom_wrapper_su">
        <div class="bottom_footer_su">
         <div class="whycureme">
            	<h2>Why UCureme?</h2>
                <ul class="why">
                	<li>You'll connect with people going through the same thing from all parts of the world.</li>
                    <li>You'll arm yourself with new resources to better manage your challenges.</li>
                    <li>You have full control of all aspects of your profile.</li>
                    <li>You can help others by sharing your experiences.</li>
                    <li>It's 100% free!</li>
                </ul>
            </div>
            </div>
</div>
<div class="bottom_wrapper">
	<div class="bottom_footer">
    	<h2>Welcome to PHASE1 of UCureMe.</h2>
        <p>This is a social network with a cause! </p><br />
        <p>It aims to empower all users to a global wealth of resources to better handle medical challenges they or loved ones are facing. </p><br />
        <p>By uniting users with common interests from around the world and medical professionals / counselors / survivors, we want to improve your resources to better manage the challenges that all of us have, or will face during our lifetime.</p><br />
    </div>
   <?php include "footer_new.php"; ?>
</div>
</body>
</html>
