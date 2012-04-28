<?php
include "header_inner.php";

$delete = "DELETE FROM tblbugs WHERE id ='".$_REQUEST["id"]."'";
if(!mysql_query($delete))
{
	die(mysql_error());
}
else
{
echo "<script>window.location='viewbugs.php?msg=Bug Reports have been deleted Successfully';</script>";
}
?>