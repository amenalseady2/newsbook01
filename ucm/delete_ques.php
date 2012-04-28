<?php
include "header_inner.php";
require_once('classes/tc_calendar.php');
$delete = "DELETE FROM tblsurveyquestions where questionid = '".$_REQUEST["id"]."'";
if(!mysql_query($delete))
{
	die(mysql_error());
}
else
{
$delete = "DELETE FROM tblsurveyquesanswers where questionid = '".$_REQUEST["id"]."'";
if(!mysql_query($delete))
{
	die(mysql_error());
}
else
{
echo "<script>window.location='vqa.php?msg=Survey Questions have been deleted Successfully';</script>";
}
}
?>