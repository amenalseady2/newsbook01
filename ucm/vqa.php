<?php 
include "header_inner.php";
?>
<script>
function ConfirmDelete(id)
{
var result = confirm("Are you sure you want to Delete this Record ?");
if (result==true)
{
window.location = "delete_ques.php?id="+id;
}
}
function ConfirmUpdate(id)
{
window.location = "update_ques.php?id="+id;
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
                    <a href="viewuser.php" class="whitetitle size12">Home</a>&nbsp;&nbsp;<span class="size12">>></span>&nbsp;&nbsp;<span class="whitetitle size12">Manage Question & Answers</span>
                </div>

                <div class="body">
                    <div class="main_link">
                        <div class="inbox_title">
                        <div class="email_table">
                            <div class="email_table">
                      <?php 
					  if(isset($_REQUEST["msg"]))
					  {
						  echo "<span>".$_REQUEST["msg"]."<span>";
					  }
                      ?>
                         <table cellpadding="0" cellspacing="0" style="width:100%; border:0px;">
                          <tr style="height:25px;width:100%" align="right">
                           <td style="width:100%;" colspan="2"><a class='bluelink' href="aqa.php">Add New Question</a></td>  									
                         
					       </tr>
                           <tr style="background-color:#3d84d6;height:25px;width:100%" align="left">
                           <td style="width:75%;" class="whitetitle size12 bold">&nbsp;Question</td>   									
                           <td style="width:25%;" class="whitetitle size12 bold">&nbsp;Action</td> 
					       </tr>
                            <?php
							$sql = "select * from  tblsurveyquestions order by questionid";
							$sql_fe=mysql_query($sql);							
                            $count=mysql_num_rows($sql_fe);
							if($count>0)
							{							
								while($row=mysql_fetch_array($sql_fe))
								{	
						  ?>
                              <tr style="height:25px;">
                                    <td  class="size11">&nbsp;<?php echo $row['strquestion']?></td>
                                    <td  class="size11" >&nbsp;<a class='bluelink' href='edit_question.php?Ques_id=<?php echo $row["questionid"]?>'>Interest</a>&nbsp;|<a class='bluelink' href='edit_question_subinterest.php?Ques_id=<?php echo $row["questionid"]?>'>SubInterest</a>&nbsp;|&nbsp;<a class='bluelink' href='manage_quesanswer.php?Ques_id=<?php echo $row["questionid"]?>'>Manage&nbsp;Answer</a>&nbsp;|&nbsp;<a class='bluelink' href="javascript:ConfirmDelete('<?php echo $row["questionid"]?>');">Delete</a></td>
                                   </tr>
								<?php	 
								} 								
								?>
                             
							<?php								
							} 
							else
							{ ?>
								<tr><td><span> <?php echo "Sorry no record found";?> </span></td></tr>
							<?php }
							?>
                        
                        </table>
                                    
                                </div>
                    </div>
                </div>
                
                
          </div>
        </div>	  

</div>
<div style="clear:both"></div>
 <div style="width:100%;margin:0 auto;background:#D7D7D7">   
    	<?php include "footer.php"; ?>
                  
                  </div>