<?php 
include "header_inner.php";
require_once('classes/tc_calendar.php');
$resourcetypeid = "0";
$diseaseid= "0";
$uname = "";
				$query1 = "
				select resourceid,postedby,dateposted,link,description,subject,tblresources.resourcetypeid,strresourcetype,fname,lname
from tblresourcetype,tblresources,tbluser where tblresources.resourcetypeid=tblresourcetype.resourcetypeid   and tblresources.resourcetypeid = 4 ";
				
				$query2 = "
				select resourceid,postedby,dateposted,link,description,subject,tblresources.resourcetypeid,strresourcetype,fname,lname
from tblresourcetype,tblresources,tbluser where tblresources.resourcetypeid=tblresourcetype.resourcetypeid  and tblresources.resourcetypeid = 2 ";
	
				
				$query3 = "
				select resourceid,postedby,dateposted,link,description,subject,tblresources.resourcetypeid,strresourcetype,fname,lname
from tblresourcetype,tblresources,tbluser where tblresources.resourcetypeid=tblresourcetype.resourcetypeid and tblresources.resourcetypeid = 1 ";
				
				
				$query4 = "
				select resourceid,postedby,dateposted,link,description,subject,tblresources.resourcetypeid,strresourcetype,fname,lname
from tblresourcetype,tblresources,tbluser where tblresources.resourcetypeid=tblresourcetype.resourcetypeid   and tblresources.resourcetypeid = 5 ";
				
				
				$query5 = "
				select resourceid,postedby,dateposted,link,description,subject,tblresources.resourcetypeid,strresourcetype,fname,lname
from tblresourcetype,tblresources,tbluser where tblresources.resourcetypeid=tblresourcetype.resourcetypeid  and tblresources.resourcetypeid = 3 ";
				
				
				$query6 = "
				select resourceid,postedby,dateposted,link,description,subject,tblresources.resourcetypeid,strresourcetype,fname,lname
from tblresourcetype,tblresources,tbluser where tblresources.resourcetypeid=tblresourcetype.resourcetypeid and tblresources.resourcetypeid = 6 ";							
		
		if($_SERVER['REQUEST_METHOD']=='POST')
		{
				$uname = str_replace("'","''",$_POST["uname"]);
				$uname=str_replace("\"","''",$uname);
				$uname=stripslashes($uname);
				//$resourcetypeid = $_POST["resourcetypeid"];
				$diseaseid = $_POST["diseaseid"];
				
				$where = '';
				
				if($uname!='' && trim($uname)!='')				
					$where = $where. " and (link like '%".$uname."%' or description  like '%".$uname."%' or subject like '%".$uname."%')";
				
			
				if($resourcetypeid!='0')
				{ 
						$where = $where. " and tblresources.resourcetypeid = ".$resourcetypeid; 
				}
				if($diseaseid!='0')
				{
						$where = $where. " or tblresources.diseaseid = ".$diseaseid; 
				}

				if($where!='')
				{
					$query1=$query1.$where;
					$query2=$query2.$where;
					$query3=$query3.$where;
					$query4=$query4.$where;
					$query5=$query5.$where;
					$query6=$query6.$where;
				}
					 
				$query1=$query1." order by dateposted desc limit 5";
				$query2=$query2." order by dateposted desc limit 5";
				$query3=$query3." order by dateposted desc limit 5";
				$query4=$query4." order by dateposted desc limit 5";
				$query5=$query5." order by dateposted desc limit 5";
				$query6=$query6." order by dateposted desc limit 5";
				
				
				
		}
		else
		{
			if(isset($_GET["resourcetypeid"]) && isset($_GET["uname"]) && isset($_GET["diseaseid"]))
			{
				$uname = str_replace("'","''",$_GET["uname"]);
				$uname=str_replace("\"","''",$uname);
				$uname=stripslashes($uname);
				$resourcetypeid = $_GET["resourcetypeid"];
				$diseaseid = $_GET["diseaseid"];
				$where = '';
				
				if($uname!='' && trim($uname)!='')
					$where = $where. " and (link like '%".$uname."%' or description  like '%".$uname."%' or subject like '%".$uname."%')";		
				
				if($resourcetypeid!='0')
				{
					//if($where=='')
					//	$where = $where. " tbluser.usertypeid = ".$usertypeid;
					//else 
						$where = $where. " and tbluser.resourcetypeid = ".$resourcetypeid; 
				}
				
				if($diseaseid!='0')
				{
					//if($where=='')
					//	$where = $where. " tbluser.diseaseid = ".$diseaseid;
					//else 
						$where = $where. " and tbluser.diseaseid = ".$diseaseid; 
				}

				if($where!='')
				{
					$query1=$query1.$where;
					$query2=$query2.$where;
					$query3=$query3.$where;
					$query4=$query4.$where;
					$query5=$query5.$where;
					$query6=$query6.$where;
				}
					
				$query1=$query1." order by dateposted desc limit 5";
				$query2=$query2." order by dateposted desc limit 5";
				$query3=$query3." order by dateposted desc limit 5";
				$query4=$query4." order by dateposted desc limit 5";
				$query5=$query5." order by dateposted desc limit 5";
				$query6=$query6." order by dateposted desc limit 5";
				
			}
			else
			{
				
				
									
				$query1=$query1." order by dateposted desc limit 5";
				$query2=$query2." order by dateposted desc limit 5";
				$query3=$query3." order by dateposted desc limit 5";
				$query4=$query4." order by dateposted desc limit 5";
				$query5=$query5." order by dateposted desc limit 5";
				$query6=$query6." order by dateposted desc limit 5";
				
			}
			
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
                       <table cellpadding="0" cellspacing="0" style="width:660px; border:0px;">
                     
                        <tr>
                            <td style="width:660px;" colspan="3" align="left" valign="top">
                                <table cellpadding="0" cellspacing="0" style="width:660px; border:0px;">
                                    <form action="viewresources.php" method="post" enctype="multipart/form-data" >
                                     <input type="hidden" id="uname_h" name="uname_h" value="<?php echo $uname; ?>" />
                                        <input type="hidden" id="resourcetypeid_h" name="resourcetypeid_h" value="<?php echo $resourcetypeid; ?>" />
                                        <input type="hidden" id="diseaseid_h" name="diseaseid_h" value="<?php echo $diseaseid; ?>" /> 

                                        <tr>
                                            <td style="width:58px; height:10px;" align="left" valign="top"></td>
                                            <td style="width:110px;" align="left" valign="top"></td>
                                            <td style="width:35px;" align="left" valign="top"></td>
                                            <td style="width:50px;" align="left" valign="top"></td>
                                            <td style="width:110px;" align="left" valign="top"></td>
                                            <td style="width:35px;" align="left" valign="top"></td>
                                            <td style="width:50px;" align="left" valign="top"></td>
                                            <td style="width:132px;" align="left" valign="top"></td>
                                            <td rowspan="2" align="left" valign="bottom" style="width:80px;">
                                                <!--<a href="#">
                                                <img style="border:0px;" src="images/search-btn.jpg" />
                                            </a>-->
                                                <input id="submit-subscirbe" type="submit" value="Search" />
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="width:58px;" align="left" class="size12">
                                                Name
                                            </td>
                                            <td style="width:110px;" align="left" valign="top">
                                                <input value="<?php echo $uname; ?>" style="width:115px; border:1px #bcbcbc solid; height:18px; padding-left:3px;" name="uname" id="uname" type="text" />
                                            </td>
                                            <td style="width:35px;" align="left">&nbsp;
                                                
                                            </td>
                                            <td style="width:50px;" align="left" class="size12">Type </td>
                                            <td style="width:110px;" align="left" valign="top">
                                                <select name="usertypeid" id="usertypeid" style="width:115px; border:1px #bcbcbc solid; height:22px; padding-left:3px;">
                                                    <option value="0" >Any</option>
                                                    <?php    
											$q="select resourcetypeid,strresourcetype from tblresourcetype";	
											$r=mysql_query($q);
											if($r)
											{	
												$n=mysql_num_rows($r);
												if($n>0)
												{			
													$count=0;	
													while($rw=mysql_fetch_array($r))
													{
														echo "<option value='".$rw["resourcetypeid"]."'";
														if ($rw["resourcetypeid"]==$resourcetypeid) echo "selected='selected'";
														echo " >".$rw["strresourcetype"]."</option>";
														$count++;			
													}
												}
											} 
										?>
                                                </select>
                                            </td>
                                            <td style="width:35px;" align="left">
                                                <!--<img style="border:0px;" src="images/calender.jpg" />--> &nbsp;
                                            </td>
                                            <td style="width:50px;" align="left" class="size12">Interest</td>
                                            <td style="width:132px;" align="left" valign="top">
                                                <select name="diseaseid" id="diseaseid" style="width:115px; border:1px #bcbcbc solid; height:22px; padding-left:3px;">
                                                    <option value="0" >Any</option>
                                                    <?php    
											        $q="select diseaseid,strdisease from tbldisease order by diseaseid";	
						   		  					$r=mysql_query($q);
											        if($r)
											        {	
												        $n=mysql_num_rows($r);
												        if($n>0)
												        {			
													        $count=0;	
													        while($rw=mysql_fetch_array($r))
													        {
												
														        echo "<option value='".$rw["diseaseid"]."'";
														        if ($rw["diseaseid"]==$diseaseid) echo "selected='selected'";
														        echo " >".$rw["strdisease"]."</option>";
														        $count++;			
													        }
												        }
											        } 
										        ?>
                                                    
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="width:660px; height:10px;" colspan="9" align="left"></td>
                                        </tr>
                                    </form>
                                </table>
                            </td>
                        </tr>
                    </table>
								
								  
                      <?php 
					  if(isset($_REQUEST["msg"]))
					  {
						  echo "<span>".$_REQUEST["msg"]."<span>";
					  }
                      ?>
                         <div class="divw">
                        	<div class="w_div">
                            	<div class="w_titlediv">
                                	<div class="w_titlediv_inner">
                                    	<div class="titlewebsite">Articles</div>

                                        <?php
										$search_id ='';	
													
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
									
										$message=$message."<div class='txtW_area1'>
                           	          <div class='smalltxt'>".substr($row['dateposted'],5)."</div>
                                            <div class='titlemain'><span class='titlered'><a href='viewadminresource.php?resourceid=".$row["resourceid"]."'>".$row['subject']."</a></span><br />
                                        <span class='txtred'>".$row['fname']." ".$row['lname']."</span></div>
                                      </div>";	
										
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
                                      <div class="wbottomtxt">
                                      [ <a href="viewaresources.php?resourcetypeid=4&name=Articles">View all</a> ] </div>
                                    </div>
                                </div>
                            </div>
                            <div class="w_div1">
                            	<div class="w_titlediv">
                                	<div class="w_titlediv_inner">
                                    	<div class="titlewebsite">Websites</div>
                                        <?php
													
							$sql=mysql_query($query2);
							if($sql)
							{
								$count=mysql_num_rows($sql);		
								$message="";
								if($count>0)
								{							
									while($row=mysql_fetch_array($sql))
									{	
										$search_id=$row['resourceid'];
									
										$message=$message."<div class='txtW_area1'>
                           	          <div class='smalltxt'>".substr($row['dateposted'],5)."</div>
                                            <div class='titlemain'><span class='titlered'><a href='viewadminresource.php?resourceid=".$row["resourceid"]."'>".$row['subject']."</a></span><br />
                                        <span class='txtred'>".$row['fname']." ".$row['lname']."</span></div>
                                      </div>";	
										
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
                                        <div class="wbottomtxt">
                                      [ <a href="viewaresources.php?resourcetypeid=2&name=Websites">View all</a> ]</div>
                                    </div>
                                </div>
                            </div>
                            <div class="w_div2">
                            	<div class="w_titlediv">
                                	<div class="w_titlediv_inner">
                                    	<div class="titlewebsite">Videos</div>
                                        <?php
													
							$sql=mysql_query($query3);
							if($sql)
							{
								$count=mysql_num_rows($sql);		
								$message="";
								if($count>0)
								{							
									while($row=mysql_fetch_array($sql))
									{	
										$search_id=$row['resourceid'];
									
										$message=$message."<div class='txtW_area1'>
                           	          <div class='smalltxt'>".substr($row['dateposted'],5)."</div>
                                            <div class='titlemain'><span class='titlered'><a href='viewadminresource.php?resourceid=".$row["resourceid"]."'>".$row['subject']."</a></span><br />
                                        <span class='txtred'>".$row['fname']." ".$row['lname']."</span></div>
                                      </div>";	
										
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
                                      <div class="wbottomtxt">
                                      [ <a href="viewaresources.php?resourcetypeid=1&name=Videos">View all</a> ] </div>
                                    </div>
                                </div>
                            </div>
                            <div class="w_div3">
                            	<div class="w_titlediv">
                                	<div class="w_titlediv_inner">
                                    	<div class="titlewebsite">Teleconferences</div>
                                        <?php
													
							$sql=mysql_query($query4);
							if($sql)
							{
								$count=mysql_num_rows($sql);		
								$message="";
								if($count>0)
								{							
									while($row=mysql_fetch_array($sql))
									{	
										$search_id=$row['resourceid'];
									
										$message=$message."<div class='txtW_area1'>
                           	          <div class='smalltxt'>".substr($row['dateposted'],5)."</div>
                                            <div class='titlemain'><span class='titlered'><a href='viewadminresource.php?resourceid=".$row["resourceid"]."'>".$row['subject']."</a></span><br />
                                        <span class='txtred'>".$row['fname']." ".$row['lname']."</span></div>
                                      </div>";	
										
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
                                      <div class="wbottomtxt">
                                      [ <a href="viewaresources.php?resourcetypeid=5&name=Teleconferences">View all</a> ]</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                      <div class="w_div2">
                          <div class="w_titlediv">
                              <div class="w_titlediv_inner">
                                  <div class="titlewebsite">Organizations</div>
                                  <?php
													
							$sql=mysql_query($query5);
							if($sql)
							{
								$count=mysql_num_rows($sql);		
								$message="";
								if($count>0)
								{							
									while($row=mysql_fetch_array($sql))
									{	
										$search_id=$row['resourceid'];
									
										$message=$message."<div class='txtW_area1'>
                           	          <div class='smalltxt'>".substr($row['dateposted'],5)."</div>
                                            <div class='titlemain'><span class='titlered'><a href='viewadminresource.php?resourceid=".$row["resourceid"]."'>".$row['subject']."</a></span><br />
                                        <span class='txtred'>".$row['fname']." ".$row['lname']."</span></div>
                                      </div>";	
										
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
                                  <div class="wbottomtxt">
                                      [ <a href="viewaresources.php?resourcetypeid=3&name=Organizations">View all</a> ]
                                  </div>
                              </div>
                          </div>
                      </div>
                      <div class="w_div3">
                          <div class="w_titlediv">
                              <div class="w_titlediv_inner">
                                  <div class="titlewebsite">Other Resources</div>
                                  <?php
													
							$sql=mysql_query($query6);
							if($sql)
							{
								$count=mysql_num_rows($sql);		
								$message="";
								if($count>0)
								{							
									while($row=mysql_fetch_array($sql))
									{	
										$search_id=$row['resourceid'];
									
										$message=$message."<div class='txtW_area1'>
                           	          <div class='smalltxt'>".substr($row['dateposted'],5)."</div>
                                            <div class='titlemain'><span class='titlered'><a href='viewadminresource.php?resourceid=".$row["resourceid"]."'>".$row['subject']."</a></span><br />
                                        <span class='txtred'>".$row['fname']." ".$row['lname']."</span></div>
                                      </div>";	
										
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
                                  <div class="wbottomtxt">
                                      [ <a href="viewaresources.php?resourcetypeid=6&name=Other Resources">View all</a> ] 
                                 
                                    
                                </div>
                    </div>
                </div>
                
                
          </div>
        </div>	  </div></div></div></div>


<div style="clear:both"></div>
 <div style="width:100%;margin:0 auto;background:#D7D7D7">   
    <div class="footer">
    	<?php include "footer.php"; ?>
       
    </div>
                  
                  </div>