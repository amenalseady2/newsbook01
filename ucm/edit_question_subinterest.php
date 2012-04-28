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
//   	echo $key.' '.$val.'<br>';
//   	
//   	echo 'test->> ' . $_POST[$val].'<br>';
//   	continue
//   		

   		if(!empty($val)){
		   $sql = "insert into tbladmin_q_disease value(null,'".$qid."','".mysql_escape_string($val)."')";
		   mysql_query($sql);
	   		
		   if(isset($_POST[$val]) && !empty($_POST[$val])){
			   $query_to_insert_subinterest = 
				sprintf("insert into tblsubdisease_question (diseaseid, subdiseaseid,q_id)values('%s' ,'%s', '%s')",
				$val,$_POST[$val],$qid);
				mysql_query($query_to_insert_subinterest);
		   }
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
<div class="email_table">
                        <?php
						if(isset($msg))
						{
							echo "<p>".$msg."</p>";
						}
						?>
                         <?php    
						    $q="select * from tblsurveyquestions where questionid='".$_REQUEST["Ques_id"]."'";	
							
						    $r=mysql_query($q);
						   ?>
                      <form action="edit_question_subinterest.php" name="Question"
	id="Question" method="post" onsubmit="return add_ques(this)"><input
	type="hidden" name="Quesid" id="Quesid"
	value="<?php echo $_REQUEST["Ques_id"]?>">
<table align="center" width="80%" style="margin: 0 auto">		
                       <?php
					    if($r)
						   {	
							 $n=mysql_num_rows($r);
							 if($n>0)
							 {			
							  while($rw=mysql_fetch_array($r))
							   {?> 
                           <tr>
		<td>
		<p><label for="strquestion"><strong>Question:</strong></label></p>
		</td>
		<td>
		<p><input type="text" id="strquestion" name="strquestion" size="60"
			value="<?php echo $rw['strquestion']?>" /></p>
		</td>
	</tr>
                       <?php
							   }
							 }
						   }?>
                          <tr>
		<td valign="top">
		<p><label for="strquestion"><strong>Interest:</strong></label></p>
		</td>
		<td>
		
		<p>
		
		
		<table cellpadding="3" cellspacing="2" width="100%"
			style="margin: 0 auto">
                        <?php
						        
								$j=1;
								$sqlproduct = "SELECT * FROM tbladmin_q_disease where q_id='".$_REQUEST["Ques_id"]."'";
								$resultproduct= mysql_query($sqlproduct);
								$checked_interests = array();
								while($row = mysql_fetch_array($resultproduct))
								{
									 $checked_interests[]=$row['interest_id'];
								}
								$i=1;
								
								
								
								?>
                        <tr >
				<td>
				<p>
				
				<table width="100%" style="margin: 0 auto">
                        <?php
							$sqlproduct = "SELECT * FROM tbldisease";
							$resultproduct= mysql_query($sqlproduct, $connection);
							if (mysql_num_rows($resultproduct)!=0)
							{
								while($row = mysql_fetch_array($resultproduct))
								{
									
									if(in_array($row["diseaseid"], $checked_interests)){
										$checked = 'checked';
									} else{
										$checked = '';
									}
									
									
									echo "<Tr class='tr_soll'>";
									echo "
									<td align='left'><input  type='checkbox' name='fields[]' value='".$row["diseaseid"]."' ".$checked." >".$row["strdisease"];
									
									$query_check_subinterest="select subdiseaseid,diseaseid, q_id from tblsubdisease_question
															  where diseaseid=".$row['diseaseid']." and  q_id=".$_GET['Ques_id'];
									
									$rslt_sb = mysql_query($query_check_subinterest);
									if(mysql_num_rows($rslt_sb)>0){
										$row = mysql_fetch_array($rslt_sb);
										$selected_subint_id = $row['subdiseaseid']; 	
									}
									else $selected_subint_id =null;
									
									
									$sqlsubdisease = "SELECT * FROM tblsubdisease";
									$resultsubdisease= mysql_query($sqlsubdisease, $connection);
									echo "</td>";
									echo "<td>";
									if (mysql_num_rows($resultsubdisease)!=0){
										echo "<select name=".$row['diseaseid']." class='subcategory' style='width:100%'>";
										echo "<option value='' ></option>";
									while($subrow = mysql_fetch_array($resultsubdisease)){		

									if($selected_subint_id == $subrow['subdiseaseid']){
										$str = "selected='selected'";
									} else{
										$str = "";
									}
										
									echo "<option ".$str." value='".$subrow["subdiseaseid"]."'>".$subrow["strsubdisease"]."</option>";									
									}
										
										echo "</select>";
									}
									else{
										?>
										<select class="subcategory" disable style='width:100%'>
								<option>-----Empty-----</option>
							</select>
										<?php 
									}
									
									echo "</td>";
									
									echo "</Tr>";
									
									
								}
							}		
			?>
            
                    </table>

				</p>
				</td>
			</tr> 
							<?php	
								
								
								
								
							
			        ?>            
                    </table>

		</p>
		</td>
	</tr>
	<tr>
		<td align="center"></td>
		<td><input type="submit" name="Submit" id="submit-subscirbe"
			value="Submit" class="submit"></td>
	</tr>
</table>
</form>
</div>
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