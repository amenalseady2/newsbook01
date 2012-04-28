<?php 
	/*$host="localhost";
	$user="youcureme";
	$pass="";
	$dBase="test";
	*/
	
	$host="173.208.45.140";
	$user="youcureme";
	$pass="Cur3 th3 w0rld";
	$dBase="youcureme";
	

	$connection=mysql_connect($host,$user,$pass) or die("failed to connect!!");
	mysql_select_db($dBase,$connection) or die("not connected!!");
	
	$numOfRecordsToDisplay=10;	
	
	function filter($input)
	{
		$input=str_replace("'","''",$input);
		$input=str_replace("\"","''",$input);
		$input=stripslashes($input);
							
		$input=str_replace("á","&aacute;",$input);	
		$input=str_replace("à","&agrave;",$input);
		$input=str_replace("â","&acirc;",$input);
		$input=str_replace("ç","&ccedil;",$input);				
		$input=str_replace("é","&eacute;",$input);
		$input=str_replace("è","&egrave;",$input);
		$input=str_replace("ê","&ecirc;",$input);
		$input=str_replace("î","&icirc;",$input);
		$input=str_replace("ï","&iuml;",$input);
		$input=str_replace("Á","&Aacute;",$input);
		$input=str_replace("À","&Agrave;",$input);
		$input=str_replace("Â","&Acirc;",$input);
		$input=str_replace("Ç","&Ccedil;",$input);				
		$input=str_replace("É","&Eacute;",$input);
		$input=str_replace("È","&Egrave;",$input);
		$input=str_replace("Ê","&Ecirc;",$input);
		$input=str_replace("Î","&Icirc;",$input);
		$input=str_replace("Ï","&Iuml;",$input);
		
		$input=str_replace("â","&acirc;",$input);
		$input=str_replace("ç","&ccedil;",$input);				
		$input=str_replace("é","&eacute;",$input);
		$input=str_replace("è","&egrave;",$input);
		$input=str_replace("ê","&ecirc;",$input);
		$input=str_replace("î","&icirc;",$input);
		$input=str_replace("ï","&iuml;",$input);
		$input=str_replace("Â","&Acirc;",$input);
		$input=str_replace("Ç","&Ccedil;",$input);				
		$input=str_replace("É","&Eacute;",$input);
		$input=str_replace("È","&Egrave;",$input);
		$input=str_replace("Ê","&Ecirc;",$input);
		$input=str_replace("Î","&Icirc;",$input);
		$input=str_replace("Ï","&Iuml;",$input);
		
		return $input;
	}
?>