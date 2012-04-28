<?php 

include "connection.php";

if(!isset($_POST['pages']))
exit;

$interest_list = explode(",", $_POST['pages']);

$order = 1;
foreach($interest_list as $interest){
$query = sprintf("update tblsubdisease 
					set	`order` = '%s'
					where `subdiseaseid` = '%s' ",
					$order++,
					$interest);			

mysql_query($query);
}

?>