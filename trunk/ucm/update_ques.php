<?php 
include "header_inner.php";
require_once('classes/tc_calendar.php');

if(isset($_POST['Update']))
	{
	
	$update = "UPDATE  tblsurveyquesanswers as tu, tblsurveyquestions as tc SET tu.strquestion='".$_POST["strquestion"]."',tc.strqans='".$_POST["strqans"]."' WHERE tu.questionid='".$_REQUEST['id']."' and tc.questionid '".$_REQUEST['id']."'";
	if(mysql_query($update))
	{
		echo "<script>window.location='vqa.php?msg=Question Answer have been updated Successfully.';</script>";	
	}
	else
	{mysql_error();}
} ?>
<div class="warpper">
        	<div class="left_side">
            	<div class="left_contant">
                	<div class="user_info">
                    	<div class="user_img">
                        	<!--<img src="images/user-img.jpg" />-->
                            <img src="profilepics/<?php echo $_SESSION["profilepic"] ?>" width="63" height="59" border="0" />

                            </div>
                        <div class="user_data">
                        	<div class="user_name bluetitle size20 bold" /><?php echo $username; ?></div>
                        </div>
                    </div>
                    <div class="profile_links">
                    	<div class="title_txt">
                            <div class="txttitle whitetitle size12 bold">Manage</div>
                        </div>
                        <div class="txt_links">
                        	<ul>
                            	<li><a href="viewuser.php">User Details</a></li>
                                <li>
                                    <a href="aqa.php">Add Question and Answers</a>
                                </li>
                                <li>
                                    <a href="vqa.php">View Question and Answers</a>
                                </li>
                                <li>
                                    <a href="adminchange.php">Change Admin</a>
                                </li>
                            </ul>
                        </div>
                                  
                </div>
            </div>
            </div> 
            <div class="body_main">
                <div class="body_menu whitetitle">
                    <a href="viewuser.php" class="whitetitle size12">Home</a>&nbsp;&nbsp;<span class="size12">>></span>&nbsp;&nbsp;<span class="whitetitle size12">Account Settings</span>
                </div>

                <div class="body">
                    <div class="main_link">
                        <div class="inbox_title">
                        <div class="email_table">
                            <table align="center">		
                            <?php $sql = "select * from  tblsurveyquesanswers as tu, tblsurveyquestions as tc where tu.questionid = tc.questionid";
									$result = mysql_query($sql);
									if (mysql_num_rows($result)!=0){
											
											$row = mysql_fetch_array($result);	
											{ ?>
										
<tr><td><p><label for="strquestion" >Question:</label></p></td>
              <td><p><input type="text" width="200px" id="strquestion" name="strquestion" value="<?php echo $row['strquestion']?>"  /></p></td></tr>
             <tr><td>
             <p>
                <label for="strqans">Answers:</label></p></td>
              <td><p><input type="text" id="strqans" name="strqans" value="<?php echo $row['strqans']?>"></textarea></p></td></tr> 
              <tr><td><label></label></td><td><p><input type="text" id="strqans" name="strqans"  value="<?php echo $row['strqans']?>"></textarea></p></td></tr>              
               <tr><td><label></label></td><td><p><input type="text" id="strqans" name="strqans" value="<?php echo $row['strqans'] ?>"  ></textarea></p></td></tr>
                <tr><td><label></label></td><td><p><input type="text" id="strqans" name="strqans" value="<?php echo $row['strqans'] ?>"  ></textarea></p></td></tr>
                 <tr><td><label></label></td><td><p><input type="text" id="strqans" name="strqans" value="<?php echo $row['strqans'] ?>"  ></textarea></p></td></tr>
                <tr><td align="center"></td>
               <td>
                <input type="submit" id="submit" name="Update" value="Update"/> 
                </td>
                </tr>
               <?php  } ?>
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