<?php 

session_start();
$_SESSION["userid"]='';
		$_SESSION["fname"] = '';
		$_SESSION["email"] = '';
		$_SESSION["usertypeid"]= '';		
session_destroy();
echo"<script>location.href='index.php'</script>";
?>