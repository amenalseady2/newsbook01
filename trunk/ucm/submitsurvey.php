<?php
session_start ();
include "connection.php";

if ($_SERVER ['REQUEST_METHOD'] == 'POST') {
	$userid = $_POST ["userid"];
	
	$qnum = 1;
	$query_questions = "select questionid, strquestion from tblsurveyquestions order by questionid";
	$sqlq = mysql_query ( $query_questions );
	$countq = mysql_num_rows ( $sqlq );
	if ($countq > 0) {
		
		while ( $rowq = mysql_fetch_array ( $sqlq ) ) {
			
			if (! isset ( $_POST ["q" . $rowq ["questionid"]] ))
				continue;
			
			$ans = $_POST ["q" . $rowq ["questionid"]];
			$queryansers = "insert into tblusersurveyans(questionid,qansid,userid) values(" . $rowq ["questionid"] . ",$ans,$userid)";
			mysql_query ( $queryansers );
		}
		header ( "location: surveyresults2.php?msg=Survey results submitted successfully. Thank you." );
	}
}
?>