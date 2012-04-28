<?php 

include "connection.php";

if(!isset($_POST['id']))
	echo "";
else{

	$query = sprintf("select 	subdiseaseid, 	strsubdisease, 	diseaseid, `order` from  tblsubdisease where disease_status='Active' and diseaseid='%s' order by `order` asc",$_POST['id']);
	$result = mysql_query($query);

	$str = '';
	$str .= "<option value='' > </option>";
	while($row = mysql_fetch_array($result)){
		$str .= "<option value=$row[0]>$row[1]</option>";
	}
	
	
	$str .= "<option value='SUGGEST NEW INTEREST'>SUGGEST NEW INTEREST</option>";
	

	echo $str;
}

?>