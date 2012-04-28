<?php
include "header_inner.php";
require_once('classes/tc_calendar.php');
$sql = "select * from tblbugs where id ='".$_REQUEST["id"]."'";
$result = mysql_query($sql);
if(mysql_num_rows($result))
{
$row=mysql_fetch_array($result);
if($row['status']=="Pending")
{
$update = "UPDATE tblbugs set status='Send' WHERE id=".$_REQUEST['id']."";
mysql_query($update);
}

echo "<script>window.location=' viewbugs.php?msg=Bugs Details have been Updated Successfully';</script>";
}
?>