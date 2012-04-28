<?php
include "header_inner.php";
if($_GET['action'] == 'status')
{
	
	
	echo $sql = "update tblsubdisease set disease_status='".$_GET["strinterest"]."' where subdiseaseid ='".$_REQUEST["id"]."'";
	if(!mysql_query($sql))
	{
		die(mysql_error());
	}
	else
{
echo "<script>window.location='viewsubinterest.php?msg=SubInterest have been updated Successfully';</script>";
}

}
else
{
$delete = "DELETE FROM tblsubdisease where subdiseaseid = '".$_REQUEST["id"]."'";
if(!mysql_query($delete))
{
	die(mysql_error());
}
else
{
echo "<script>window.location='viewsubinterest.php?msg=SubInterest have been deleted Successfully';</script>";
}
}
?>