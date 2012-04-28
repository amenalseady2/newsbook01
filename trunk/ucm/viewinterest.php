<?php 
include "header_inner.php";
 ?>
<script>
function ConfirmDelete(id)
{
	var result = confirm("Are you sure you want to Delete this Interest ?");
	if (result==true)
	{
		window.location = "delete_interest.php?id="+id;
	}
}
function ConfirmUpdate(id,strinterest)
{
	var result = confirm("Are you sure you want to update the status ?");
	if (result==true)
	{
		window.location = "delete_interest.php?id="+id+"&strinterest="+strinterest+"&action=status";
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
                    <a href="viewuser.php" class="whitetitle size12">Home</a>&nbsp;&nbsp;<span class="size12">>></span>&nbsp;&nbsp;<span class="whitetitle size12">Manage Interests</span>
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
                        <table cellpadding="0" cellspacing="0" style="width:100%; border:0px;">
                        <tr style="height:25px;width:100%" align="right">
                        <td style="width:100%;" colspan="2"><a class='bluelink' href="manage_interest.php">Add New Interest</a></td>  									
                        </tr>
                           <tr style="background-color:#3d84d6;height:25px;width:100%" align="left">
                           <td width="44%" class="whitetitle size12 bold" style="width:85%;">&nbsp;Interest</td>   
						   <td width="45%" class="whitetitle size12 bold" style="width:15%;">&nbsp;Status</td> 									
                           <td width="11%" class="whitetitle size12 bold" style="width:15%;">&nbsp;Action</td> 
					       </tr>                          
                       <?php    
						    $q="select * from tbldisease order by diseaseid";	
						    $r=mysql_query($q);
						    if($r)
						    {	
							    $n=mysql_num_rows($r);
							    if($n>0)
							    {			
								   
								    while($rw=mysql_fetch_array($r))
								    {?>
									  <tr style="height:25px;">
                                    <td  class="size11">&nbsp;<?php echo $rw['strdisease']?></td>
									 <td  class="size11" nowrap="nowrap">&nbsp;<?php echo $rw['disease_status']?><a class='bluelink' href="javascript:ConfirmUpdate('<?php echo $rw["diseaseid"] ?>','<?php echo ($rw["disease_status"] == 'Inactive') ? 'Active' : 'Inactive';?>');" > [change]</a></td>
                                    <td  class="size11" >&nbsp;<a class='bluelink' href='edit_interest.php?id=<?php echo $rw["diseaseid"]?>'>Edit</a>&nbsp;|&nbsp;<a class='bluelink' href="javascript:ConfirmDelete('<?php echo $rw["diseaseid"]?>');">Delete</a></td>
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