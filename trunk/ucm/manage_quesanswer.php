<?php 
include "header_inner.php";
if(isset($_POST["Submit"]))
{
$sql = "insert into tblsurveyquesanswers value(null,'".$_POST["stranswer"]."','".$_POST["question_id"]."')";
if(!mysql_query($sql))
{die(mysql_error());}
else
{
echo "<script>window.location='manage_quesanswer.php?Ques_id=".$_POST["question_id"]."&msg=Answer Inserted';</script>";
}
}

?><script>
function ConfirmDelete(qid,aid)
{
var result = confirm("Are you sure you want to Delete this Answer ?");
if (result==true)
{
window.location = "delete_ans.php?qid="+qid+"&aid="+aid;
}
}
</script>
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
                    <a href="adminhome.php" class="whitetitle size12">Home</a>&nbsp;&nbsp;<span class="size12">>></span>&nbsp;&nbsp;<span class="whitetitle size12">Manage Question & Answers</span>
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
                      <form action="manage_quesanswer.php" name="addnewanser" id="addnewanser" method="post" onsubmit="return uques(this)">
                      <input type="hidden" name="question_id" id="question_id" value="<?php echo $_REQUEST["Ques_id"]?>">
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
                           <td><p><input type="text"  id="strquestion" name="strquestion"  size="60" value="<?php echo $rw['strquestion']?>" readonly="readonly"/></p></td>
                           </tr>
                       <?php
							   }
							 }
						   }?>		
                       <tr>
                       <td><p><label for="strquestion" ><strong>Answer:</strong></label></p></td>
                       <td><p><input type="text"  id="stranswer" name="stranswer"  placeholder="Enter Answer" size="60"/></p></td>
                       </tr>
                       <tr>
                       <td align="center"></td>
                       <td><input type="submit"  name="Submit" id="submit-subscirbe" value="Submit" class="submit"></td>
                       </tr>
                       </table>
						</form>
                                    
                        
                       </div>
                    </div> 
                    <div style="clear:both"></div>
                    <div class="inbox_title">
                      <div class="email_table">
                        <table cellpadding="0" cellspacing="0" style="width:100%; border:0px;">
                          <tr style="height:25px;width:100%" align="right">
                           <td style="width:100%;" colspan="2"><br /></td>
					       </tr>
                           <tr style="background-color:#3d84d6;height:25px;width:100%" align="left">
                           <td style="width:85%;" class="whitetitle size12 bold">&nbsp;Answer</td>   									
                           <td style="width:15%;" class="whitetitle size12 bold">&nbsp;Action</td> 
					       </tr>
                            <?php
							$sql = "select * from  tblsurveyquesanswers where questionid='".$_REQUEST["Ques_id"]."'";
							$sql_fe=mysql_query($sql);							
                            $count=mysql_num_rows($sql_fe);
							if($count>0)
							{							
								while($row=mysql_fetch_array($sql_fe))
								{	
						     ?>
                              <tr style="height:25px;">
                                    <td  class="size11">&nbsp;<?php echo $row['strqans']?></td>
                                    <td  class="size11" >&nbsp;<a class="bluelink" href="javascript:updaye_answes('<?php echo $row["questionid"]?>','<?php echo $row["qansid"]?>');">Edit</a>&nbsp;|&nbsp;<a class='bluelink' href="javascript:ConfirmDelete('<?php echo $row["questionid"]?>','<?php echo $row["qansid"]?>');">Delete</a></td>
                              </tr>
								<?php	 
								} 								
								?>
                             
							<?php								
							} 
							?>
                        
                        </table>
                                    
                        
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