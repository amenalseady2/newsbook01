<?php 
include "header_inner.php";
if(isset($_POST["Submit"]))
{
//$sql = "insert into tbldisease(`diseaseid`,`strdisease`,`disease_status`) value(null,'".$_POST["strinterest"]."','Inactive')";
$sql = "insert into tbldisease(`strdisease`) value('".$_POST["strinterest"]."')";
if(!mysql_query($sql))
{die(mysql_error());}
else
{
echo "<script>window.location='viewinterest.php?msg=Interests Inserted';</script>";
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
                      <form action="manage_interest.php" name="Interest" id="Interest" method="post" onsubmit="return interest(this)">
                      <table align="center" width="80%" style="margin:0 auto">		
                       <tr>
                       <td><p><label for="strquestion" ><strong>Interest:</strong></label></p></td>
                       <td><p><input type="text"  id="strinterest" name="strinterest"  placeholder="Enter Interest" size="60"/></p></td>
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