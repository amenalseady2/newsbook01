<?php
include "header_inner.php";
include "smtpmailer.php";
$sql = "select * from tbluser where userid ='".$_REQUEST["id"]."'";
$result = mysql_query($sql);
$result_set = mysql_fetch_object($result);

$fname=$result_set->fname;
$lname=$result_set->lname;
$email=$result_set->email;


mysql_query("DELETE FROM tblphotos WHERE userid='".$_REQUEST["id"]."'");
mysql_query("DELETE FROM tblblogposts WHERE postedbyuserid	='".$_REQUEST["id"]."'");
mysql_query("DELETE FROM tblblogcomments WHERE postedbyuserid	='".$_REQUEST["id"]."'");
$delete = "DELETE FROM tbluser WHERE userid='".$_REQUEST["id"]."'";
if(!mysql_query($delete))
{
	die(mysql_error());
}
else
{
$EmailTo = $email;	 
					$EmailFrom = "info@youcureme.com";
					$Emailfrom_name = "YouCureMe.com"; 
					$EmailSubject = "YouCureMe: Your profile is blocked temporarily!";
														
					$EmailMsg = "Hello ".$fname." ".$lname. ",<br />This email is to inform you that your profile has been deleted from YouCureMe.com by Administrator.<br />For more details, you can contact the Administrator via website<br />YouCureMe.com"; 
									
					send_smtpmail($EmailTo, $EmailFrom, $Emailfrom_name, $EmailSubject, $EmailMsg);
					
echo "<script>window.location='viewuser.php?msg=User Profile have been deleted Successfully';</script>";
}
?>