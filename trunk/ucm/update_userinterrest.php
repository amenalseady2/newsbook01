<?php
include "header_new.php";
require_once('classes/tc_calendar.php');
//if(isset($_REQUEST["staus"]))
//{
$update = "UPDATE tbldisease set disease_status='Active' WHERE Interestsuggestedby='".$_REQUEST['uid']."' and diseaseid='".$_REQUEST['id']."'";

if(mysql_query($update))
{
//if(isset($_REQUEST['ustatus']))
//{
$update = "UPDATE tbluser set diseaseid='".$_REQUEST['id']."'  WHERE userid=".$_REQUEST['uid'];
mysql_query($update);
//}

$EmailToi = $_REQUEST["email"];	 
$EmailFromi = "info@youcureme.com";
$Emailfrom_name = "YouCureMe.com"; 
$EmailSubject = "YouCureMe:INTEREST IS PENDING APPROVAL";
$EmailMsgi = "YOUR INTEREST HAS BEEN APPROVED. YOU MAY NOW CONTINUE USING UCUREME.COM. PLEASE CLICK ON THE BELOW LINK TO CONTINUE LOGIN ..... <br>
www.youcureme.com"; 
send_smtpmail($EmailToi, $EmailFromi, $Emailfrom_name, $EmailSubject, $EmailMsgi);	 	
echo "<script>window.location=' paddinginterest.php?msg=User disease have been updated Successfully';</script>";
}
else
{
echo "Record Not Avaiable";
}
//}
//else
//{
//echo "Record Not Avaiable";
//}

?>