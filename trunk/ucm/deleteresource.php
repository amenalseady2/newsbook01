<?php
include "header_inner.php";
$delete = "DELETE FROM tblresources where resourceid = '".$_REQUEST["resourceid"]."'";
if(!mysql_query($delete))
{
	die(mysql_error());
}
else
{
echo "<script>window.location='resources.php?msg=User Resources have been deleted Successfully';</script>";
}
?>