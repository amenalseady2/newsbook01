<?php 
include "connection.php";

// if(mysql_query("delete from tbluser where email like 'i2o2test%'"))
// if(mysql_query("update tbluser set access_name=2;"))
	// echo 'done';

$result = mysql_query("
SELECT usealias, alias, fname, lname, friendshipid, tblfriends.userid, friendwith, CONCAT( fname, ' ', lname ) AS sendername, friendshipstatus, 
thumb_profile as profilepic,access_pic,strusertype,strdisease
FROM tblfriends
left join tbluser on tblfriends.friendwith = tbluser.userid
left join tbldisease on tbldisease.diseaseid=tbluser.diseaseid 
left join tblusertype on tblusertype.usertypeid=tbluser.usertypeid 
WHERE  tblfriends.userid = 77	
AND tbluser.userid <> 77
AND friendshipstatus = 2");


print '<pre>';
while($row = mysql_fetch_array($result)){
	print_r($row);	
}