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
window.location = "delete_user.php?id="+id;
}
}
function ConfirmUpdate(id,status)
{

var conf = confirm("Are you sure you want to change the status to "+status+"? ");
if(conf){
window.location = "update_user.php?id="+id+"&status="+status;}
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
                                    <td style="width:250px;" class="whitetitle size12 bold">&nbsp;EmailID</td> 
									<td style="width:80px;" class="whitetitle size12 bold">&nbsp;Country</td>
                                    <td style="width:80px;" class="whitetitle size12 bold">&nbsp;City</td>                                        
									<td style="width:30px;" class="whitetitle size12 bold" align="left">&nbsp;Status</td>
                                    <td style="width:35px;" class="whitetitle size12 bold" align="right">&nbsp;Action&nbsp;&nbsp;</td>
                                   </tr>
                            <?php
							$sql = "select * from tbluser order by date_joined desc";
							$sql_fe=mysql_query($sql);							
                            $count=mysql_num_rows($sql_fe);
							if($count>0)
							{							
								while($row=mysql_fetch_array($sql_fe))
								{	
								
							?>
                                <tr style="height:25px;">
                                   <td align="left" class="size11"><?php echo $row["fname"].' '.$row["lname"];?></td>   									
                                    <td    class="size11">&nbsp;<?php echo $row["email"];?></td> 
									<td  class="size11">&nbsp;<?php echo country_name($row['countryid']);?></td>                                       
									<td class="size11">&nbsp;<?php echo $row['city'];?></td>
                                    <td  class="size11" align="right" nowrap="nowrap">&nbsp;<?php echo ($row["isactive"] == '1') ? 'Active' : 'Inactive';?><a class='bluelink' href="javascript:ConfirmUpdate('<?php echo $row["userid"] ?>','<?php echo ($row["isactive"] != '0') ? 'Inactive' : 'Active';?>');" > [change]</a></td>
                                    <td  class="size11" align="right">&nbsp;<a class='bluelink' href="javascript:ConfirmDelete('<?php echo $row["userid"]?>');">Delete</a></td>
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