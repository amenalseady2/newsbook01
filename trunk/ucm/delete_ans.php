<?php
include "header_inner.php";
require_once('classes/tc_calendar.php');
$delete = "DELETE FROM tblsurveyquesanswers where qansid = '".$_REQUEST["aid"]."' and questionid = '".$_REQUEST["qid"]."'";
if(!mysql_query($delete))
{
	die(mysql_error());
}
else
{
echo "<script>window.location='manage_quesanswer.php?Ques_id=".$_REQUEST["qid"]."msg=Answer have been deleted Successfully';</script>";
}
?>