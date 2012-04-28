<?php 
include "header_inner.php";
error_reporting(0);
if(isset($_POST["Submit"]))
{
$sql_updQ = "update tblsurveyquestions set strquestion = '".$_POST["strquestion"]."' where questionid ='".$_POST["Quesid"]."'";
mysql_query($sql_updQ);
$sql = "delete from tbladmin_q_disease where q_id ='".$_POST["Quesid"]."'";
if(!mysql_query($sql))
{die(mysql_error());}
else
{
   $qid=$_POST["Quesid"];
   $fields = $_POST["fields"];
   if (is_array($fields))
   {
   foreach ($fields as $key=>$val) 
   {
	   $sql = "insert into tbladmin_q_disease value(null,'".$qid."','".mysql_escape_string($val)."')";
	   if(!mysql_query($sql))
         {
	
		 }   
   } 
       echo "<script>window.location='vqa.php?msg=Question Inserted';</script>";
   }
   else
   {	
   echo "<script>window.location='vqa.php?msg=Question Not Inserted';</script>";
   }
}
}
 ?>

<style>
table {
    border-collapse: collapse;
}
td {
    padding-bottom: 2em;
}
</style>

<div class="warpper">
<div class="left_side">
<div class="left_contant">
<div class="user_info">

<div class="user_name bluetitle size16 bold" /><?php echo $_SESSION["fname"];?></div>
</div>
<div class="profile_links">
<div class="title_txt">
<div class="plusimg"><img src="images/plus_icon.jpg" /></div>
<div class="txttitle whitetitle size12 bold">Manages</div>
</div>
<div class="txt_links">
                        	<?php
							include("left_admin_menu.php");
							?>
                        </div>
</div>


</div>
</div>
<div class="body_main">
<div class="body_menu whitetitle"><a href="viewuser.php"
	class="whitetitle size12">Home</a>&nbsp;&nbsp;<span class="size12">>></span>&nbsp;&nbsp;<span
	class="whitetitle size12">Manage Question & Answers</span></div>

<div class="body">
<div class="main_link">
<div class="inbox_title">

<h1 style="color: #0D4C94;">Add Subinterest</h1>

<?php 
if(isset($_POST['subinterest_name'])){
	$query_to_insert_subinterest = 
	sprintf("insert into tblsubdisease (strsubdisease, diseaseid)values('%s','%s')",
	$_POST['subinterest_name'],$_POST['interest_id']);
	if(mysql_query($query_to_insert_subinterest)){
    	print '<b>Subinterest Save Successfully</b><br/><br/>';
	}
}
?>
<form action="add_subinterest.php" method="post">
<table>
<tr>
<td>Interest Name:</td> 
<td>
<select name="interest_id">
<?php 

$query_for_disease_list = "select * from tbldisease";
$rslt_disease_list = mysql_query($query_for_disease_list);

while($row=mysql_fetch_array($rslt_disease_list)){
	echo "<option value='".$row['diseaseid']."'>";
	echo $row['strdisease'];
	echo "</option>";
}

?>
</select>
</td>
</tr>
<tr>
<td>Sub Interest Name:</td>
<td><input type="text" name="subinterest_name" style="width: 100%;"/></td>
</tr>
<tr>
	<td>&nbsp;</td>
	<td><input id="submit-subscirbe" type="submit" value="Save" style="width: 50%;"/></td>
</tr>
</table>






</form>

</div>
</div>


</div>
</div>


<div style="clear: both"></div>
<div style="width: 100%; margin: 0 auto; background: #D7D7D7">
<div class="footer">
<?php include "footer.php"; ?>
</div>
</div>