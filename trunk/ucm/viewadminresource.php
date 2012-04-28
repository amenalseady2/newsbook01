<?php 
include "header_inner.php";
require_once('classes/tc_calendar.php');
$fname='';
	$lname='';
	$strresourcetype='';
	$subject='';
	$description='';
	$link='';
	$dateposted='';
	$postedby=0;
		
	$heading_title=$item['Profile_title'];
	$heading_msg=$item['Profile_msg'];
if(isset($_GET["resourceid"]))
	{
		$resourceid=$_GET["resourceid"];
		$query="
		select 
		resourceid,postedby,dateposted,link,description,subject,tblresources.resourcetypeid,embedvideolink,strresourcetype,fname,lname
        from tblresourcetype,tblresources,tbluser 
        where tblresources.resourcetypeid=tblresourcetype.resourcetypeid
        and resourceid=".$resourceid;
        //echo $query;
		$result=mysql_query($query);
		$num=mysql_num_rows($result);
		if($num>0)
		{
			$row=mysql_fetch_array($result);	
				
			$postedby=$row["postedby"];
			$fname=$row["fname"];
			$subject=$row["subject"];
			$description=$row["description"];
			$lname=$row["lname"];
			$link=$row["link"];
			$embedvideolink = $row["embedvideolink"];
			$dateposted=$row["dateposted"];
			$strresourcetype=$row["strresourcetype"];
		}
	}
	
	
?>
<script>
function ConfirmDelete(id)
{
var result = confirm("Are you sure you want to Delete this Record ?");
if (result==true)
{
window.location = "deleteresources.php?id="+id;
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
                       <table cellpadding="0" cellspacing="0" style="width:686px; border:0px;">
                    <tr>
                        <tr>
                            <td style="width:36px; height:12px; " colspan="5" align="left" valign="top"></td>
                        </tr>
                        
                        <td style="width:125px;" align="left" class="bold size12">
                            <?php echo "Subject :"; ?>
                        </td>
                        <td colspan="4" style="width:561px;" align="left" class="size12">
                                <?php echo $subject; ?>
                        </td>
                    </tr>
                    <tr>
                        <td style="width:36px; height:3px; " colspan="5" align="left" valign="top"></td>
                    </tr>
                    <tr>

                        <td style="width:125px;" align="left" class="bold size12">
                            <?php echo "Posted By :"; ?>
                        </td>
                        <td colspan="4" style="width:561px;" align="left" class="size12">
                            <a class="bluelink" href="viewprofile.php?userid=<?php echo $postedby; ?>"><?php echo $fname." ".$lname; ?></a>
                        </td>
                    </tr>
                    <tr>
                        <td style="width:36px; height:3px;" colspan="5" align="left" valign="top"></td>
                    </tr>
                    <tr>

                        <td style="width:125px;" align="left" class="bold size12">
                            <?php echo "Date :"; ?>
                        </td>
                        <td colspan="4" style="width:561px;" align="left" class="size12">
                            <?php echo $dateposted; ?>
                        </td>
                    </tr>
                    <tr>
                        <td style="width:36px; height:3px;;" colspan="5" align="left" valign="top"></td>
                    </tr>
                    <tr>

                        <td style="width:125px;" align="left" class="bold size12">
                            <?php echo "Resource Type :"; ?>
                        </td>
                        <td colspan="4" style="width:561px;" align="left" class="size12">
                            <?php echo $strresourcetype; ?>
                        </td>
                    </tr>
                    <tr>
                        <td style="width:36px; height:3px;" colspan="5" align="left" valign="top"></td>
                    </tr>
                    <tr>
                        <td style="width:125px;" align="left" class="bold size12">
                            <?php echo "Link :"; ?>
                        </td>
                        <td colspan="4" style="width:561px;" align="left" class="size12">
                            <a class="bluelink" href="<?php echo $link; ?>">
                                <?php echo $link; ?> <br /><br />
                            </a>
                        </td>
                    </tr>
					
					 
                    <tr>
                        <td style="width:125px;" align="left" class="bold size12">
                            <?php echo "Embeded Video:"; ?><br />
                        </td>
                        <td colspan="4" style="width:561px;" align="left" class="size12">
                            <a class="bluelink" href="<?php echo $embedvideolink; ?>">
                                <?php echo $embedvideolink; ?>                        </a>    
                        </td> 
                    </tr> 
					 
					
                    <tr>
                        <td style="width:36px; height:3px; " colspan="5" align="left" valign="top"></td>
                    </tr>
                    <tr>

                        <td style="width:125px;" align="left" class="bold size12">
                            <br /><br /><?php echo "Details :"; ?>
                        </td>
                        <td colspan="4" style="width:561px;" align="left" class="size12">
                             <br /><br />   <?php echo $description; ?>
                        </td>
                    </tr>
                    <tr>
                        <td style="width:36px; height:3px;" colspan="5" align="left" valign="top"></td>
                    </tr> 
                    <td style="width:125px;" align="left" class="bold size12">&nbsp;
                           
                    </td>
                     
                    <td colspan="2" style="width:256px;" align="left" class="bold size14">
                         <br />
                        <a href="deleteresources.php?resourceid=<?php echo $resourceid; ?>">Delete <br /><br />
                        </a>
                        
                    </td>                       
					</tr> </table>
                    </div></div></div></div></div>
<div style="clear:both"></div>
 <div style="width:100%;margin:0 auto;background:#D7D7D7">   
    <div class="footer">
    	<?php include "footer.php"; ?>
       
    </div>
                  
                  </div>