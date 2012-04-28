<?php 
include "header_inner.php";
if(isset($_POST["Submit"]))
{
$sql = "update tblsubdisease set strsubdisease='".$_POST["strsubdisease"]."' where subdiseaseid ='".$_POST["subdiseaseid"]."'";
if(!mysql_query($sql))
{die(mysql_error());}
else
{
echo "<script>window.location='viewsubinterest.php?msg=SubInterest Update Successfully';</script>";
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
                         <?php    
						    $q="select * from tblsubdisease where subdiseaseid='".$_REQUEST["id"]."'";	
							
						    $r=mysql_query($q);
						   ?>
                      <form action="edit_subinterest.php" name="Interest" id="Interest" method="post" onsubmit="return interest(this)">
					  <input type="hidden" name="subdiseaseid" id="subdiseaseid" value="<?php echo $_REQUEST["id"]?>">
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
                           <td><p><label for="strquestion" ><strong>SubInterest:</strong></label></p></td>
                           <td><p><input type="text"  id="strinterest" name="strsubdisease"  size="60" value="<?php echo $rw['strsubdisease']?>"/></p></td>
                           </tr>
                       <?php
							   }
							 }
						   }?>
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