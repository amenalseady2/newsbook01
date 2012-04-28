<?php	
session_start();
//require_once('class.phpmailer.php');
//include("class.smtp.php"); // optional, gets called from within class.phpmailer.php if not already loaded

include "connection.php";
include "encrypt_decrypt.php";
//include "Mail.php";
include "smtpmailer.php";
/*
$mail             = new PHPMailer();

//$body             = file_get_contents('contents.html');
$body             = "Test email.";//eregi_replace("[\]",'',$body);

$mail->IsSMTP(); // telling the class to use SMTP
$mail->Host       = "smtp.gmail.com";//"mail.youcureme.com"; // SMTP server
$mail->SMTPDebug  = 2;                     // enables SMTP debug information (for testing)
                                           // 1 = errors and messages
                                           // 2 = messages only
$mail->SMTPAuth   = true;                  // enable SMTP authentication
$mail->SMTPSecure = "ssl";//"tls";                 // sets the prefix to the servier
$mail->Host       = "smtp.gmail.com";      // sets GMAIL as the SMTP server
$mail->Port       = 465;//587;                   // set the SMTP port for the GMAIL server
$mail->Username   = "info@youcureme.com";//"yourusername@gmail.com";  // GMAIL username
$mail->Password   = "youcur3m3";            // GMAIL password

$mail->From='info@youcureme.com';//SetFrom('info@youcureme.com', 'YouCureMe.com');

$mail->AddReplyTo("info@youcureme.com","YouCureMe.com");

$mail->Subject    = "PHPMailer Test Subject via smtp (Gmail), basic";

$mail->AltBody    = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test

$mail->Body=$body;//MsgHTML($body);

$address = "sufian_engineer@hotmail.com";
$mail->AddAddress($address, "Sufian Baig");

//$mail->AddAttachment("images/phpmailer.gif");      // attachment
//$mail->AddAttachment("images/phpmailer_mini.gif"); // attachment

if(!$mail->Send()) {
  echo "Mailer Error: " . $mail->ErrorInfo;
} else {
  echo "Message sent!";
}

return;

*/

	if(isset($_POST["Signup_button"]))
	{	
			/* As we eliminated fname and lname from signup screen, we assume they are balnk for the present */
			$fname = "";
			$lname = "";
			
			/*$fname=str_replace("'","''",$_POST["fname"]);
			$fname=str_replace("\"","''",$fname);
			$fname=stripslashes($fname);
			
			$lname = "";
			$lname=str_replace("'","''",$_POST["lname"]);
			$lname=str_replace("\"","''",$lname);
			$lname=stripslashes($lname); */
						
			$email=str_replace("'","''",$_POST["email"]);
			$email=str_replace("\"","''",$email);
			$email=stripslashes($email);
			
			$password=str_replace("'","''",$_POST["pwd"]);
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
						
						/*------------------ EMAIL TO USER ------------------------------*/ 
						/* Send an account activation link via SMTP email to the registered user */		
					
						$EmailTo = $email;	 
						$EmailFrom = "info@youcureme.com";
						$Emailfrom_name = "YouCureMe.com"; 
						$EmailSubject = "YouCureMe: Confirm your email!";
						$key = encrypt_userid($userid); 
						$link = "<a href='http://localhost/youcureme/verify.php?u=$userid&k=$key'>Click Here</a>";		 

						$EmailMsg = "<p>
						Dear Member, <br /><br />
						Thank you for signing up with YouCureMe.com<br /><br />
						Please click on the following link to verify your email address and complete the registration.
						<br /><br />$link<br /><br />
						Administrator <br />YouCureMe.com</p>";  
 				
						send_smtpmail($EmailTo, $EmailFrom, $Emailfrom_name, $EmailSubject, $EmailMsg);	 
					
						//unset($_SESSION['userid']); 
						//session_unset(); 

						echo "<script>location.href='confirm.php?email=".$email."' </script>";
					
					//echo "<script>location.href='confirm.php?u=".$userid."&k=".$key."'</script>";
					//echo $key;	
					//echo decrypt_userid($key);
					/*
						$from = "info@youcureme.com";
						$to = $email;
						$subject = "YouCureMe: Confirm your email!";
						$body = "Hi ".$fname.",
									Please click on the following link to verify your email address.".
									"http://www.youcureme.com/verify.php?userid=".$userid."<br/>".
									"YouCureMe.com";
				
						$host = "ssl://smtp.gmail.com";
						$port = "465";
						$username = "info@youcureme.com";
						$password = "youcur3m3";
				
						$headers = array ('From' => $from,
						  'To' => $to,
						  'Subject' => $subject);
						$smtp = Mail::factory('smtp',
						  array ('host' => $host,
							'port' => $port,
							'auth' => true,
							'username' => $username,
							'password' => $password));
				
						$mail = $smtp->send($to, $headers, $body);
				
						if (PEAR::isError($mail)) {
						  echo("<p>" . $mail->getMessage() . "</p>");
						 } else {
						  echo("<p>Message successfully sent!</p>");
						 }						
						*/
						//this is original flow
						//echo "<script>location.href='confirm.php?email=".$email."' </script>";						
						
				/*******************************************************************************************************************/
						//temp flow 
												
						/*$_SESSION["userid"]=$userid;
						$_SESSION["fname"] = $fname." ".$lname;
						$_SESSION["email"] = $email;
						$_SESSION["usertypeid"]=$usertypeid;
						$_SESSION["profilepic"]="empty_profile.jpg"; */
						
						//echo "<script>location.href='verify.php?userid=".$userid."' </script>";
						
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
