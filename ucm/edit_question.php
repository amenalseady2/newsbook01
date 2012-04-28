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
                <div class="body_menu whitetitle">
                    <a href="viewuser.php" class="whitetitle size12">Home</a>&nbsp;&nbsp;<span class="size12">>></span>&nbsp;&nbsp;<span class="whitetitle size12">Manage Question & Answers</span>
                </div>

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
                      <form action="edit_question.php" name="Question" id="Question" method="post" onsubmit="return add_ques(this)">
					  <input type="hidden" name="Quesid" id="Quesid" value="<?php echo $_REQUEST["Ques_id"]?>">
                      <table align="center" width="80%" style="margin:0 auto">		
                       <?php
					    if($r)
						   {	
							 $n=mysql_num_rows($r);
							 if($n>0)
							 {			
							  while($rw=mysql_fetch_array($r))
							   {?> 
                           <tr>
                           <td><p><label for="strquestion" ><strong>Question:</strong></label></p></td>
                           <td><p><input type="text"  id="strquestion" name="strquestion"  size="60" value="<?php echo $rw['strquestion']?>"/></p></td>
                           </tr>
                       <?php
							   }
							 }
						   }?>
                          <tr>
                       <td valign="top"><p><label for="strquestion" ><strong>Interest:</strong></label></p></td>
                       <td><p>
                         <table cellpadding="3" cellspacing="2" width="100%" style="margin:0 auto">
                        <?php
						        
								$j=1;
								$sqlproduct = "SELECT * FROM tbladmin_q_disease where q_id='".$_REQUEST["Ques_id"]."'";
								$resultproduct= mysql_query($sqlproduct);
								
								$i=1;
								if(mysql_num_rows($resultproduct)!=0)
								{
								
								 while($row = mysql_fetch_array($resultproduct))
								 {
								
								 $idss.="diseaseid !=".$row["interest_id"];
								 if(mysql_num_rows($resultproduct)!=$i++)
								 {$idss.=" and ";	}
							     $sqld = "SELECT * FROM tbldisease where diseaseid='".$row["interest_id"]."'";								 
								 $redd=mysql_query($sqld);
							     $rowd= mysql_fetch_array($redd);
  							     if(($rowd["0"]==$row["interest_id"]))
								 {echo "
								 <Tr class='tr_soll'><td align='left'><input type='checkbox' checked name='fields[]' value='".$rowd["diseaseid"]."'>".$rowd["strdisease"]."
								 </td></Tr>";
								 }
								
								}
								$sqld = "SELECT * FROM tbldisease where $idss";								 
								$redd=mysql_query($sqld);
							    while($rowd= mysql_fetch_array($redd))
								{
								 echo "
								 <Tr class='tr_soll'><td align='left'><input type='checkbox' name='fields[]' value='".$rowd["diseaseid"]."'>".$rowd["strdisease"]."
								 </td></Tr>";								 	
								}
								
								}
								else
								{
								echo "<Tr class='tr_soll'><td align='left'>No Interrest Found<br />
								 </td></Tr>";
								?>
                        <tr>
                          <td><p>
                         <table  width="100%" style="margin:0 auto">
                        <?php
							$sqlproduct = "SELECT * FROM tbldisease";
							$resultproduct= mysql_query($sqlproduct, $connection);
							if (mysql_num_rows($resultproduct)!=0)
							{
							$check=0;
							while($row = mysql_fetch_array($resultproduct))
							{
							if ($check == 0)
							{
							echo "<Tr class='tr_soll'>";
							$check=1;
							}
							if ($check <= 3)
							{echo "
							<td align='left'><input type='checkbox' name='fields[]' value='".$row["diseaseid"]."'>".$row["strdisease"]."</td>";
							if ($check != 4) echo"";
							$check ++ ;
							
							}
							if ($check ==4)
							{
							echo "</Tr>";
							$check=0;
							}						
							}
							}		
			?>
            
                    </table>   
                                            
                       </p></td>
                       </tr> 
							<?php	}
								
								
								
								
							
			        ?>            
                    </table>   
                      
                       </p></td>
                       </tr>   
                       <tr>
                       <td align="center"></td>
                       <td><input type="submit"  name="Submit" id="submit-subscirbe" value="Submit" class="submit"></td>
                       </tr>
                       </table>
						</form>
                       </div>
                    </div>
                </div>
                
                
          </div>
        </div>	  


<div style="clear:both"></div>
 <div style="width:100%;margin:0 auto;background:#D7D7D7">   
    <div class="footer">
    	<?php include "footer.php"; ?>
       
    </div>
                  
                  </div>