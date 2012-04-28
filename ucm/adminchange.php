<?php 
include "header_inner.php";
require_once('classes/tc_calendar.php');
if(isset($_POST['submit']) == "Change")
{
	$update="update tbluser set email='".$_POST['email']."', Password='".$_POST['pwd']."' where userid='".$_POST['MemberID']."' and user_previlege = '5' ";
	mysql_query($update);
	}
 ?>
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
                           <div style="text-align:left;color:#fff">&nbsp;&nbsp;<strong>Manage</strong></div>
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
                    <a href="adminhome.php" class="whitetitle size12">Home</a>&nbsp;&nbsp;<span class="size12">>></span>&nbsp;&nbsp;<span class="whitetitle size12">Account Settings</span>
                </div>

                <div class="body">
                    <div class="main_link">
                        <div class="inbox_title">
                        <div class="email_table">
                            <form method="post" action="adminchange.php"> 
                    
                        <table align="center" width="60%" style="margin:0 auto">
                    <tr><td><p><label for="oldusename" >Old Username:</label></p></td>
                          <td><p><input type="text"  id="MemberID" name="MemberID"  placeholder="Enter Questions" class="text_feild" value="<?php echo $username ?>"  readonly="readonly"/></p></td></tr>     		
                    <tr><td><p><label for="newusename">New Email:</label></p></td><td><p><input type="text" id="email" name="email"  placeholder="Enter New Email ID"  class="text_feild" /></p></td></tr>
                  <tr><td>
                  <p>       <label for="newpass1">Password:</label></p></td>
              <td><p><input type="password" id="pwd" name="pwd"  placeholder="Enter New Password "  class="text_feild"  /></p></td></tr>
            
                <tr><td align="center"></td>
               <td>
                <input type="submit" id="submit" name="submit" value="Change"/> 
                </td>
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