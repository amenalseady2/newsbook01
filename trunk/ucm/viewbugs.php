<?php 
include "header_inner.php";
require_once('classes/tc_calendar.php');
?>
<script>
function ConfirmDelete(id)
{
var result = confirm("Are you sure you want to Delete this Record ?");
if (result==true)
{
window.location = "delete_bugs.php?id="+id;
}
}
function ConfirmUpdate(id)
{
window.location = "update_bugs.php?id="+id;
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
                    <a href="viewuser.php" class="whitetitle size12">Home</a>&nbsp;&nbsp;<span class="size12">>></span>&nbsp;&nbsp;<span class="whitetitle size12">Manage Users</span>
                </div>

                <div class="body">
                    <div class="main_link">
                        <div class="inbox_title">
                        <div class="email_table">
                      <?php 
					  if(isset($_REQUEST["msg"]))
					  {
						  echo "<span>".$_REQUEST["msg"]."<span>";
					  }
                      ?>
                         <table cellpadding="0" cellspacing="0" style="width:686px; border:0px;">
                                <tr style="background-color:#3d84d6;height:25px;" align="left">
                                    <td style="width:130px;" class="whitetitle size12 bold">&nbsp;Name</td>   									
                                    <td style="width:130px;" class="whitetitle size12 bold">&nbsp;EmailID</td> 
                                    <td style="width:35px;" class="whitetitle size12 bold">&nbsp;Subject</td>
									<td style="width:60px;" class="whitetitle size12 bold">&nbsp;Suggestion/Bugs</td>
                                    <td style="width:310px;" class="whitetitle size12 bold">&nbsp;Message</td>
                                    <!--td style="width:35px;" class="whitetitle size12 bold" align="right">&nbsp;Status&nbsp;&nbsp;</td-->
                                    <td style="width:35px;" class="whitetitle size12 bold" align="right">&nbsp;Action&nbsp;&nbsp;</td>
                                   </tr>
                            <?php
							$sql = "select * from tblbugs order by date desc";
							$sql_fe=mysql_query($sql);							
                            $count=mysql_num_rows($sql_fe);
							if($count>0)
							{							
								while($row=mysql_fetch_array($sql_fe))
								{	
								
							?>
                                <tr style="height:25px;">
                                   <td align="left" class="size11"><?php echo $row["from_name"];?></td>   									
                                    <td    class="size11">&nbsp;<?php echo $row["email_from"];?></td> 
									<td  class="size11">&nbsp;<?php echo $row['sub'];?></td>                                       
									<td class="size11">&nbsp;<?php echo $row['msg_type'];?></td>
                                    <td class="size11">&nbsp;<?php echo $row['message'];?></td>
                                    <!--td  class="size11" align="right">&nbsp;<a class='bluelink' href="javascript:ConfirmUpdate('<?//php echo $row["bugid"] ?>');" ><?//php echo $row['status']?></a></td-->
                                    <td  class="size11" align="right">&nbsp;<a class='bluelink' href="javascript:ConfirmDelete('<?php echo $row["id"]?>');">Delete</a></td>
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


<div style="clear:both"></div>
 <div style="width:100%;margin:0 auto;background:#D7D7D7">   
    <div class="footer">
    	<?php include "footer.php"; ?>
       
    </div>
                  
                  </div>