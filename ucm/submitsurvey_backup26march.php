<?php	session_start();
include "connection.php";

if($_SERVER['REQUEST_METHOD']=='POST')
{
	$userid=$_POST["userid"];
	
	
	$qnum=1;
	$query_questions="select questionid, strquestion from tblsurveyquestions order by questionid";					
	$sqlq=mysql_query($query_questions);
	$countq=mysql_num_rows($sqlq);
	if($countq>0)
	{							
		while($rowq=mysql_fetch_array($sqlq))
		{
		    if($_POST["q".$rowq["questionid"]])
		    {
		        $ans=$_POST["q".$rowq["questionid"]];
	            try
	            {
	                $queryansers="insert into tblusersurveyans(questionid,qansid,userid) values(".$rowq["questionid"].",$ans,$userid)";	
		
		            if(!mysql_query($queryansers))
		            {
			            header("location: survey.php?msg=Error submitting survey results. Please try again.");
		            }
		            else
		            {
			            echo mysql_error();
		            }
	            }
	            catch(exception $ex)
	            {
		            echo $ex;
	            }
	        }
	    }
    }
	header("location: survey.php?msg=Survey results submitted successfully. Thank you.");
}
?>
