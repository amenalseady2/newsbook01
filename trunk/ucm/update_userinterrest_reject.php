<?php
include "header_new.php";
require_once('classes/tc_calendar.php');
//if(isset($_REQUEST["staus"]) && isset($_REQUEST["email"]))
//{
$update = "delete from tbldisease WHERE Interestsuggestedby='".$_REQUEST['uid']."' and diseaseid='".$_REQUEST['id']."'";
//if(mysql_query($update))
{
//$update = "delete from tbluser WHERE userid='".$_REQUEST['uid']."'";
//mysql_query($update);
//}
$EmailToi = $_REQUEST["email"];	 
$EmailFromi = "info@youcureme.com";
$Emailfrom_name = "YouCureMe.com"; 
$EmailSubject = "YouCureMe:INTEREST IS REJECTED By UCUREME.COM";
$EmailMsgi = "YOUR INTEREST HAS BEEN REJECTED"; 
send_smtpmail($EmailToi, $EmailFromi, $Emailfrom_name, $EmailSubject, $EmailMsgi);	 	
echo "<script>window.location=' paddinginterest.php?msg=User disease and Record have been Delete Successfully';</script>";
}
//}
//else
//{
//echo "Record Not Avaiable";
//}

?>