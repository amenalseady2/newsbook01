<?php 
include "header_inner.php";
require_once('classes/tc_calendar.php');
if(isset($_GET["resourcetypeid"]) && isset($_GET["name"]))
		{
			$name = str_replace("'","''",$_GET["name"]);
			$name=str_replace("\"","''",$name);
			$name=stripslashes($name);
			$resourcetypeid = $_GET["resourcetypeid"];
			
			$where = '';
			
			if($resourcetypeid!='0')
			{ 
					$where = $where. " and tblresources.resourcetypeid = ".$resourcetypeid; 
			}
			
			$query1 = "
			select resourceid,postedby,dateposted,link,description,subject,tblresources.resourcetypeid,strresourcetype,fname,lname
from tblresourcetype,tblresources,tbluser where tblresources.resourcetypeid=tblresourcetype.resourcetypeid ";
				
			if($where!='')
			{
				$query1=$query1.$where;
			}
			$query1=$query1." order by dateposted desc ";
			//$query1=$query1." limit 10";
		}
	
?>
<script>
function ConfirmDelete(id)
{
var result = confirm("Are you sure you want to Delete this Record ?");
if (result==true)
{
window.location = "delete_aresources.php?id="+id;
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
                    <a href="viewresouces.php" class="whitetitle size12">Home</a>&nbsp;&nbsp;<span class="size12">>></span>&nbsp;&nbsp;<span class="whitetitle size12">Manage Users</span>
                </div>

                <div class="body">
                    <div class="main_link">
                        <div class="inbox_title">
                        <div class="email_table">
                       <form action="resources.php" method="post" enctype="multipart/form-data" >
                                <input type="hidden" id="resourcetypeid_h" name="resourcetypeid_h" value=""<?php echo $resourcetypeid; ?>" />

                                <div class="browsediv">
                        	<div class="browse_inner">	
                            	<div class="titlebrowse">Resources - <?php echo $name; ?></div>
                                <div style='clear:both;'>
                                    <br/>
                                </div>
                                <?php
													
							$sql=mysql_query($query1);
							if($sql)
							{
								$count=mysql_num_rows($sql);		
								$message="";
								if($count>0)
								{							
									while($row=mysql_fetch_array($sql))
									{	
										$search_id=$row['resourceid'];
									
										$message=$message."<div class='txtW_area1_2'>
                           	          <div class='smalltxt2'>".$row['dateposted']."</div>
                                            <div class='titlemain2'><span class='titlered'><a href='viewadminresource.php?resourceid=".$row["resourceid"]."'>".$row['subject']."</a></span><br />
                                        <strong>Posted by : </strong><span class='txtred'>".$row['fname']." ".$row['lname']."</span></div>
                                      <a href='deleteresources.php?resourceid=".$row["resourceid"]."'><strong><span class='txtred'>Delete</span></strong></a>
									  </div>
                                      <div style='clear:both;'></div>";	
										
									}
								}
								else 
								{
									$message="<div class='txtred'>No Resources Found.</div>";
								}
								
								echo $message;  
							}
							else
								echo "<div class='txtred'>No Resources Found.</div>";
							?>
                        </div>
                        </div>  
                        </div>
                      </form>  
                      </div></div></div></div>     
       

<div style="clear:both"></div>
 <div style="width:100%;margin:0 auto;background:#D7D7D7">   
    <div class="footer">
    	<?php include "footer.php"; ?>
       
    </div>
                  
                  </div>