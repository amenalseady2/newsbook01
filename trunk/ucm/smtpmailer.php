<?php	
//session_start();

require_once('class.phpmailer.php');
include("class.smtp.php"); // optional, gets called from within class.phpmailer.php if not already loaded
//include "connection.php";

/*
http://www.webdeveloper.com/forum/showthread.php?t=189027
$message = "<html><body background ='http://www.mysite.co.uk/images/grid_medblue.gif'><p><img src='http://www.mysite.co.uk/images/mysite.gif'><br /><br />Hello,</p><p>Have You taken a look at <a href='http://www.mysite.co.uk'>www.mysite.co.uk</a>. Take a look at <a href='http://www.mysite.co.uk/example.php'>www.mysite.co.uk/example.php</a> to see the site in full </p></body></html>";

$message = nl2br($message);
$headers = "MIME-Version: 1.0\r\n";
$headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
$headers .= "From: mysite<info@mysite.co.uk>\r\n";
$headers .= "Return-path: mysite<info@mysite.co.uk>\r\n";
//$headers = "BCC: messagesent@mysite.com\r\n";
*/

define('GUSER', 'info@youcureme.com'); 	// GMail username
define('GPWD', 'youcur3m3'); 			// GMail password

function send_smtpmail($to, $from, $from_name, $subject, $body) 
{ 
	global $error;
	$mail = new PHPMailer();  	// create a new object
	$mail->IsSMTP(); 			// enable SMTP
 try 
 {
	$mail->SMTPDebug = 1;  		// debugging: 1 = errors and messages, 2 = messages only
	$mail->SMTPAuth = true;  	// authentication enabled	 
	$mail->IsBodyHtml = true; 
	//$mail->MsgHTML($body); 	
	$mail->IsHTML(true); 
	
	
	//$mail->GetMailMIME();
	$mail->SMTPSecure = 'ssl'; 
	$mail->SMTPKeepAlive = true; 
	$mail->CharSet = 'utf-8';  
	$mail->Host = 'smtp.gmail.com';
	$mail->Port = 465; 
	$mail->Username = GUSER;  
	$mail->Password = GPWD;           
	$mail->SetFrom($from, $from_name);
	$mail->Subject = $subject;
	$mail->Body = $body;  // Set the HTML version as the normal body  
	$mail->AltBody = 'To view the message, please use an HTML compatible email viewer!'; // optional - MsgHTML will create an alternate automatically
	$mail->AddAddress($to);
	$mail->AddReplyTo("info@youcureme.com","YouCureMe.com"); 
	
	$mail->Send();
    //echo "Message Sent OK<P></P>\n";
	return true;
  } catch (phpmailerException $e) {
    echo $e->errorMessage(); //Pretty error messages from PHPMailer
	return false;
    } catch (Exception $e) {
      echo $e->getMessage(); //Boring error messages from anything else!
	  return false;
      } 
	/*if(!$mail->Send()) {
		$error = 'Mail error: '.$mail->ErrorInfo; 
		return false;
	} else {
		$error = 'Message sent!';
		return true;
	} */
}


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
//$mail->SMTPDebug = true;
$mail->Host       = "smtp.gmail.com";      // sets GMAIL as the SMTP server
$mail->Port       = 465;//587;                   // set the SMTP port for the GMAIL server
$mail->Username   = "info@youcureme.com";//"yourusername@gmail.com";  // GMAIL username
$mail->Password   = "youcur3m3";            // GMAIL password

$mail->From='info@youcureme.com';//SetFrom('info@youcureme.com', 'YouCureMe.com');
$mail->AddReplyTo("info@youcureme.com","YouCureMe.com");
$mail->Subject    = "PHPMailer Test Subject via smtp (Gmail), basic";
$mail->AltBody    = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test
$mail->Body=$body;//MsgHTML($body);
$address = "sufian_engineer@hotmail.com";//"jamaur@gmail.com";
$mail->AddAddress($address, "Jason Maur");

//$mail->AddAttachment("images/phpmailer.gif");      // attachment
//$mail->AddAttachment("images/phpmailer_mini.gif"); // attachment

if(!$mail->Send()) {
  echo "Mailer Error: " . $mail->ErrorInfo;
} else {
  echo "Message sent!";
}
*/
?>
