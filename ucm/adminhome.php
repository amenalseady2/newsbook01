<?php 
include "header_inner.php";
require_once('classes/tc_calendar.php');
$sql = "select * from tbluser as tu, tblcountry as tc where tu.countryid = tc.CountryID";
$result = mysql_query($sql);
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
                        <h2>You Cure Me</h2>
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