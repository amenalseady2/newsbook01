<?php
include "header_inner.php";
if($_GET['action'] == 'status')
{
	
	
	echo $sql = "update tbldisease set disease_status='".$_GET["strinterest"]."' where diseaseid ='".$_REQUEST["id"]."'";
	if(!mysql_query($sql))
	{
		die(mysql_error());
	}
	else
{
echo "<script>window.location='viewinterest.php?msg=Interest have been updated Successfully';</script>";
}

}
else
{
$delete = "DELETE FROM tbldisease where diseaseid = '".$_REQUEST["id"]."'";
if(!mysql_query($delete))
{
	die(mysql_error());
}
else
{
echo "<script>window.location='viewinterest.php?msg=Interest have been deleted Successfully';</script>";
}
}
?>