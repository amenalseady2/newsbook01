<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>

<body>
<form action="" method="post">
<table>
<tr>
<td>Question</td>
<td><select id="que" name="que" style="width:500px">
<option value="select">select Question</option>
<?php
include("connection.php");
$sql="select questionid,strquestion from tblsurveyquestions";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result))
{
?>
<option value="<?php echo $row["questionid"];?>"><?php echo $row["strquestion"];?></option>
<?php
}
?>
<option value="other" >other</option>
</select></td>
</tr>
</table>
</form>
</body>
</html>
