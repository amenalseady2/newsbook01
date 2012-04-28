<?php 

include "connection.php";

if(!isset($_POST['pages']))
exit;

$interest_list = explode(",", $_POST['pages']);

$order = 1;
foreach($interest_list as $interest){
$query = sprintf("update tbldisease 
					set	`order` = '%s'
					where `diseaseid` = '%s' ",
					$order++,
					$interest);			

mysql_query($query);
}

?>