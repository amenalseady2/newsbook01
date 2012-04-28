<?php	session_start();

require_once('class.phpmailer.php');
include("class.smtp.php"); // optional, gets called from within class.phpmailer.php if not already loaded

include "connection.php";
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
?>
