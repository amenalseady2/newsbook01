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
							
		$input=str_replace("�","&aacute;",$input);	
		$input=str_replace("�","&agrave;",$input);
		$input=str_replace("�","&acirc;",$input);
		$input=str_replace("�","&ccedil;",$input);				
		$input=str_replace("�","&eacute;",$input);
		$input=str_replace("�","&egrave;",$input);
		$input=str_replace("�","&ecirc;",$input);
		$input=str_replace("�","&icirc;",$input);
		$input=str_replace("�","&iuml;",$input);
		$input=str_replace("�","&Aacute;",$input);
		$input=str_replace("�","&Agrave;",$input);
		$input=str_replace("�","&Acirc;",$input);
		$input=str_replace("�","&Ccedil;",$input);				
		$input=str_replace("�","&Eacute;",$input);
		$input=str_replace("�","&Egrave;",$input);
		$input=str_replace("�","&Ecirc;",$input);
		$input=str_replace("�","&Icirc;",$input);
		$input=str_replace("�","&Iuml;",$input);
		
		$input=str_replace("�","&acirc;",$input);
		$input=str_replace("�","&ccedil;",$input);				
		$input=str_replace("�","&eacute;",$input);
		$input=str_replace("�","&egrave;",$input);
		$input=str_replace("�","&ecirc;",$input);
		$input=str_replace("�","&icirc;",$input);
		$input=str_replace("�","&iuml;",$input);
		$input=str_replace("�","&Acirc;",$input);
		$input=str_replace("�","&Ccedil;",$input);				
		$input=str_replace("�","&Eacute;",$input);
		$input=str_replace("�","&Egrave;",$input);
		$input=str_replace("�","&Ecirc;",$input);
		$input=str_replace("�","&Icirc;",$input);
		$input=str_replace("�","&Iuml;",$input);
		
		return $input;
	}
?>