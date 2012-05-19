<?php
include "header_inner.php";
include "smtpmailer.php";
require_once('classes/tc_calendar.php');
$sql = "select * from tbluser where userid ='".$_REQUEST["id"]."'";
$result = mysql_query($sql);
$result_set = mysql_fetch_object($result);

$fname=$result_set->fname;
$lname=$result_set->lname;
$email=$result_set->email;
$user_status = $_REQUEST["status"];

if($user_status == 'Active'){
	$update = "UPDATE tbluser set isactive='1' WHERE userid=".$_REQUEST['id']."";
		mysql_query($update);
} else {
	$update1 = "UPDATE tbluser set isactive='0' WHERE userid=".$_REQUEST['id']."";
		mysql_query($update1);


/*if(mysql_num_rows($result))
{
$row=mysql_fetch_array($result);
	if($row['isactive']==0)
	{
		$update = "UPDATE tbluser set isactive='1' WHERE userid=".$_REQUEST['id']."";
		mysql_query($update);
		}
	else
		{
		$update1 = "UPDATE tbluser set isactive='0' WHERE userid=".$_REQUEST['id']."";
		mysql_query($update1);
	}
*/		
		$EmailTo = $email;	 
					$EmailFrom = "info@youcureme.com";
					$Emailfrom_name = "YouCureMe.com"; 
					$EmailSubject = "YouCureMe: Your profile is blocked temporarily!";
														
					$EmailMsg = "Hello ".$fname." ".$lname. ",<br />This email is to inform you that your profile has been temporarily blocked from YouCureMe.com by Administrator.<br />For more details, you can contact the Administrator via website<br />YouCureMe.com"; 
									
					send_smtpmail($EmailTo, $EmailFrom, $Emailfrom_name, $EmailSubject, $EmailMsg);
					
//echo "<script>window.location=' viewuser.php?msg=User details have been updated Successfully';</script>";
}
echo "<script>window.location=' viewuser.php?msg=User details have been updated Successfully';</script>";
?>