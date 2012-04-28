<?php include "header_inner.php";

$fname='';
$lname='';
$email='';
$password='';
$profilepic='';
$gender="";
$usertype="";
$disease="";
$disease_id = 1;
$city='';
$country="";
$website='';
$iam='';
$ilike='';
$myexperience='';
$age=0;

$req_count=0;
$frnds_count=0;

	$uname = '';
	$usertypeid = $_SESSION["usertypeid"];
	$resourcetypeid = "0";
	$diseaseid = "0";
	

$_type='';
$_diseaseid = '';

if(isset($_POST['usertypeid'])){
	$_type = $_POST['usertypeid'];
}
if(isset($_POST['diseaseid'])){
	$_diseaseid = $_POST['diseaseid'];
}


if(!isset($_SESSION["userid"]) || $_SESSION["userid"] =="")
{	//header("location: login.php");   
		echo "<script>location.href='index.php' </script>";

}
else
{
	$userid=$_SESSION["userid"];
	
	/******************************************* LOAD USER INFO **********************************************************/
	$query="select 
				fname,
				lname,
				thumb_profile as profilepic,
				dob,
				genderid,
				strusertype as usertype,
				strdisease as disease,
				city,
				CountryName as country,
				email,
				password,
				website,
				iam,
				ilike,
				myexperience,
				isactive
			from tbluser,tblusertype,tbldisease,tblcountry where userid=".$userid." and 
			tblusertype.usertypeid=tbluser.usertypeid and tbldisease.diseaseid=tbluser.diseaseid and tblcountry.countryid=tbluser.countryid";
			
	$result=mysql_query($query);
	$num=mysql_num_rows($result);
	if($num>0)
	{
		$row=mysql_fetch_array($result);	
				
		$fname=$row["fname"];
		$lname=$row["lname"];
		$email=$row["email"];
		$password=$row["password"];
		if($row["profilepic"]!="")
			$profilepic=$row["profilepic"];
		else
			$profilepic="empty_profile.jpg";
		$dob=$row["dob"];
		if($row["genderid"]=="1")
			$gender="Male";
		else
			$gender="Female";
		$usertype=$row["usertype"];
		$disease=$row["disease"];
		$city=$row["city"];
		$country=$row["country"];
		$website=$row["website"];
		$iam=$row["iam"];
		$ilike=$row["ilike"];
		$myexperience=$row["myexperience"];
		$isactive=$row["isactive"];	
		$dateofbirth=explode("-",$dob);
		$age=date("Y")-$dateofbirth[0];
	}
	
	if(isset($_GET['msg']))
	{ ?>
	  <div class="txttitle whitetitle size12 bold"><?php echo $_GET['msg']; ?></div>
	<?php 
	}
		/*******************************************************************************************************************/
	/* Now that the new client has been inserted into the database, we will extract all the
	   existing users with matching medical interest and update the notification in their
	   profiles */

		$read_msgs=" 
			select 
			msgid ,	msg ,
			(select CONCAT(fname,' ',lname) from tbluser where userid=m.senderid) as username,
			m.senderid as userid,
			(select CONCAT(fname,' ',lname) from tbluser where userid=m.recieverid) as reciever,
			msgtime,senderid,recieverid	
			from tblmsgs m
			where isread = 1 and recieverid = ".$userid;	 
		$sql=mysql_query($read_msgs);							
		$read_msg_count = mysql_num_rows($sql); 
		
		$query_msgs=" 
			select 
			msgid ,	msg , isread,
			(select CONCAT(fname,' ',lname) from tbluser where userid=m.senderid) as username,
			m.senderid as userid,
			(select CONCAT(fname,' ',lname) from tbluser where userid=m.recieverid) as reciever,
			msgtime,senderid,recieverid	
			from tblmsgs m
			where recieverid = ".$userid;	 
			
			$sql=mysql_query($query_msgs);							
			$total_items=mysql_num_rows($sql); 			
			$inbox_items = $total_items - $read_msg_count; 
	                            	
	/* First get the disease id of this user */
	
	$disease_id = 1;

    $qry_disease ="SELECT diseaseid 
    FROM tbluser 
	WHERE userid = ".$userid."";
	$result_disease = mysql_query($qry_disease );
 	if($result_disease)
	{
		$num = mysql_num_rows($result_disease);
		if($num>0)
		{
			$row=mysql_fetch_array($result_disease);
			$disease_id = $row["diseaseid"];				 
		}
	}
	
	/* Get the members count with matching medical interests */
      $disease_match_count = 0;

	$query_disease_count="SELECT COUNT(*)
	FROM tbluser
	WHERE diseaseid = $disease_id";	 
	$result_disease_count=mysql_query($query_disease_count);
	$num_disease_count=mysql_num_rows($result_disease_count);
	if($num_disease_count>0)
	{
		$row_disease_count=mysql_fetch_array($result_disease_count);				
		$disease_match_count = $row_disease_count[0];   
	}

		/* Get the Medical Professions and Survivors/councillors count with matching medical interests */
    $ps_match_count = 0;

	$query_ps_count="SELECT COUNT(*)
	FROM tbluser,tbldisease,tblusertype
	WHERE tbluser.userid <> ".$userid."
	AND tbluser.diseaseid = $disease_id	 
	AND tbldisease.diseaseid=tbluser.diseaseid and tblusertype.usertypeid=tbluser.usertypeid
	AND (tbluser.usertypeid=5 OR tbluser.usertypeid=4)";	
	$result_ps_count=mysql_query($query_ps_count);
	$num_ps_count=mysql_num_rows($result_ps_count);
	if($num_ps_count>0)
	{
		$row_ps_count=mysql_fetch_array($result_ps_count);				
		$ps_match_count = $row_ps_count[0];   
	}
			
	$query_req_count="SELECT COUNT(*)	 
	FROM tblfriends, tbluser,tbldisease,tblusertype
	WHERE tblfriends.userid = ".$userid."
	AND friendshipstatus =1
	AND tblfriends.friendwith = tbluser.userid
	AND tbldisease.diseaseid=tbluser.diseaseid and tblusertype.usertypeid=tbluser.usertypeid";
	$result_req_count=mysql_query($query_req_count);
	$num_req_count=mysql_num_rows($result_req_count);
	if($num_req_count>0)
	{
		$row_req_count=mysql_fetch_array($result_req_count);	
				
		$req_count=$row_req_count[0];//." ".$userid;
	}
		

		$query_frnds_count="SELECT COUNT(*)	
	FROM tblfriends, tbluser,tbldisease,tblusertype
	WHERE tblfriends.userid = ".$userid."	
	AND tbluser.userid <> ".$userid."
	AND friendshipstatus = 2
	AND tblfriends.friendwith = tbluser.userid
	AND tbldisease.diseaseid=tbluser.diseaseid and tblusertype.usertypeid=tbluser.usertypeid ";
	$result_frnds_count=mysql_query($query_frnds_count);
	$num_frnds_count=mysql_num_rows($result_frnds_count);
	
	if($num_frnds_count>0)
	{
		$row_frnds_count=mysql_fetch_array($result_frnds_count);	
	
		$frnds_count=$row_frnds_count[0];
	}
	
	
	$query1 = '';
	$query2 = '';
	$query3 = '';
	$query4 = '';
	$query5 = '';
	$query6 = '';
	$where = '';
		
	if(!isset($_SESSION["userid"]) || $_SESSION["userid"] =="")
	{	
		header("location: index.php");
		echo "<script>location.href='index.php' </script>";   
	}
	else
	{
		$query1 = "
				select resourceid,postedby,dateposted,link,description,subject,strresourcetype,fname,lname
from tblresourcetype,tblresources,tbluser where tblresources.resourcetypeid=tblresourcetype.resourcetypeid and tblresources.postedby=tbluser.userid  and tblresources.resourcetypeid = 4 ";
				
				$query2 = "
				select resourceid,postedby,dateposted,link,description,subject,strresourcetype,fname,lname
from tblresourcetype,tblresources,tbluser where tblresources.resourcetypeid=tblresourcetype.resourcetypeid and tblresources.postedby=tbluser.userid  and tblresources.resourcetypeid = 2 ";
	
				
				$query3 = "
				select resourceid,postedby,dateposted,link,description,subject,strresourcetype,fname,lname
from tblresourcetype,tblresources,tbluser where tblresources.resourcetypeid=tblresourcetype.resourcetypeid and tblresources.postedby=tbluser.userid  and tblresources.resourcetypeid = 1 ";
				
				
				$query4 = "
				select resourceid,postedby,dateposted,link,description,subject,strresourcetype,fname,lname
from tblresourcetype,tblresources,tbluser where tblresources.resourcetypeid=tblresourcetype.resourcetypeid and tblresources.postedby=tbluser.userid  and tblresources.resourcetypeid = 5 ";
				
				
				$query5 = "
				select resourceid,postedby,dateposted,link,description,subject,strresourcetype,fname,lname
from tblresourcetype,tblresources,tbluser where tblresources.resourcetypeid=tblresourcetype.resourcetypeid and tblresources.postedby=tbluser.userid  and tblresources.resourcetypeid = 3 ";
				
				
				$query6 = "
				select resourceid,postedby,dateposted,link,description,subject,strresourcetype,fname,lname
from tblresourcetype,tblresources,tbluser where tblresources.resourcetypeid=tblresourcetype.resourcetypeid and tblresources.postedby=tbluser.userid  and tblresources.resourcetypeid = 6 ";
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
						$where = $where. " and tblresources.diseaseid = ".$diseaseid; 
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
						$where = $where. " and tblresources.resourcetypeid = ".$resourcetypeid; 
				}
				
				
				
				if($diseaseid!='0')
				{
						$where = $where. " and tblresources.diseaseid = ".$diseaseid; 
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
	}
	
	
} ?>
        <div class="warpper">
        	<div class="left_side">
            	<!--<div class="left_contant">
                	<div class="user_info">
                    	<div class="user_img">
                        	<img src="profilepics/<?php echo $profilepic; ?>" width="63" height="59" border="0" />
                        </div>
                        <div class="user_data">
                        	<div class="user_name bluetitle size20 bold" /><?php echo $username; ?></div>
                         	<div class="ul_msg">
                            <ul>
								<li><a href="messages.php?mode=inbox&page=1">My Messages(<?php echo $inbox_items;?>)</a></li>
                               <li><a href="myblog.php">My Blog</a></li>
                            </ul>
                            </div>   
                        </div>
                    </div>
                    <div class="profile_links">
                    	<div class="title_txt">
                        	<div class="plusimg"><img src="images/plus_icon.jpg" /></div>
                            <div class="txttitle whitetitle size12 bold">My Profile</div>
                        </div>
                        <div class="txt_links">
                        	<ul>
                            	<li><a href="editprofile.php?userid=<?php echo $_SESSION["userid"]; ?>">Account Settings</a></li>
                                <li>
                                    <a href="privacy.php">Privacy Settings</a>
                                </li>
                                <li>
                                    <a href="myprofile.php">My Profile</a>
                                </li>
                                <li>
                                    <a href="photos.php">My Photos</a>
                                </li>
                                <li>
                                    <a href="myblog.php">My Blog</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="profile_links">
                    	<div class="title_txt">
                        	<div class="plusimg"><img src="images/plus_icon.jpg" /></div>
                            <div class="txttitle whitetitle size12 bold">My Messages</div>
                        </div>
                        <div class="txt_links">
                        	<ul>
							<li><a href="messages.php?mode=inbox&page=1">My Messages(<?php echo $inbox_items;?>)</a></li>
                         <li>
                                    <a href="notifications.php">My Notifications</a>
                                </li>
								<li>
									<a href="myinterestmembers.php">
									Members With My Interest (<?php echo $disease_match_count-1;?>)
									</a>
                                </li>    
                            </ul>
                        </div>
                    </div>
                    <div class="profile_links">
                    	<div class="title_txt">
                        	<div class="plusimg"><img src="images/plus_icon.jpg" /></div>
                            <div class="txttitle whitetitle size12 bold">Social medical discovery</div>
                        </div>
                        <div class="txt_links">
                        	<ul>
                            	<li><a href="members.php">Search All Members</a></li>
                            </ul>
                            <ul>
                                <li>
                                    <a href="survey.php">Surveys</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="profile_links">
                    	<div class="title_txt">
                        	<div class="plusimg"><img src="images/plus_icon.jpg" /></div>
                            <div class="txttitle whitetitle size12 bold">Health Centre</div>
                        </div>
                        <div class="txt_links">
                        	<ul>
                            	<li><a href="resources.php">Health Resources</a></li>
                                <!-- <li><a href="medicalhistory.php">Medical History</a></li> 
								<li><a href="reachout.php">Reach Out(<?php echo $ps_match_count;?>)</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="profile_links">
                    	<div class="title_txt">
                        	<div class="plusimg"><img src="images/plus_icon.jpg" /></div>
                            <div class="txttitle whitetitle size12 bold">My Contacts</div>
                        </div>
                        <div class="txt_links">
                        	<ul>
                            	<li><a href="myfriends.php">
                                    My Friends (<?php echo $frnds_count;?>)
                                </a></li>
                                <li>
                                    <a href="importcontacts.php">Import Contacts</a>
                                </li>
                                <li>
                                    <a href="reqs.php">
                                        Friend Requests (<?php echo $req_count;?>)
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>-->
                    <?php
					require("left_usermenu.php");?>
                </div>
            </div>
            <div class="body_main">
            	<div class="body_menu whitetitle">
            		<a href="myprofile.php" class="whitetitle size12">Home</a>&nbsp;&nbsp;<span class="size12">>></span>&nbsp;&nbsp;<span class="whitetitle size12">Resources</span>
            	</div>
                <div class="body">
               	  <div class="main_link">
						<div class="divb">
                            <div class="f_left bluetitle size30 bold" style="padding-top:5px; width:auto;">
                    Resources
                </div>
                <div class="right_img">
                    <div class="inbox_img">
                    </div>
                    <div class="outbox_img">
                    </div>
                    <div class="notification_img">                        
                    </div>
                </div>
            </div>

            <div class="menulinks">
                <div class="top-i"></div>
                <div class="centertxt">
                          <table cellpadding="0" cellspacing="0" style="width:660px; border:0px;">
                     
                        <tr>
                            <td style="width:660px;" colspan="3" align="left" valign="top">
                                <table cellpadding="0" cellspacing="0" style="width:660px; border:0px;">
                                    <form action="resources.php" method="post" enctype="multipart/form-data" >
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
														if ($rw["resourcetypeid"]==$_type ) echo "selected='yes'";
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
											        $q="select diseaseid,strdisease from tbldisease where disease_status='Active' order by `order` asc";	
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
														        if ($rw["diseaseid"]==$_diseaseid) echo "selected='yes'";
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
                </div>
                <div class="bottom-i"></div>
            </div>
                        
                        
                  		<div class="divw">
                        	<div class="w_div" style="display:<?php	echo ($_type == 4 || $_type == 0) ? '' : 'none;' ?>">
                            	<div class="w_titlediv">
                                	<div class="w_titlediv_inner">
                                    	<div class="titlewebsite"  >Articles</div>

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
                                            <div class='titlemain'><span class='titlered'><a href='viewresource.php?resourceid=".$row["resourceid"]."'>".$row['subject']."</a></span><br />
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
                                      [ <a href="viewallresources.php?resourcetypeid=4&name=Articles">View all</a> ]<?php /*if($_SESSION["usertypeid"]=="3" || $_SESSION["usertypeid"]=="4") {*/?>&nbsp;&nbsp;[ <a href="addresource.php?resourcetypeid=4">Create</a> ]<?php //} ?> </div>
                                    </div>
                                </div>
                            </div>
                            <div class="w_div1" style="display:<?php	echo ($_type == 2 || $_type == 0) ? '' : 'none;' ?>">
                            	<div class="w_titlediv">
                                	<div class="w_titlediv_inner">
                                    	<div class="titlewebsite">Websites</div>
                                        <?php
													
//                            print_r($query2);
//                            exit;
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
                                            <div class='titlemain'><span class='titlered'><a href='viewresource.php?resourceid=".$row["resourceid"]."'>".$row['subject']."</a></span><br />
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
                                      [ <a href="viewallresources.php?resourcetypeid=2&name=Websites">View all</a> ]<?php //if($_SESSION["usertypeid"]=="3" || $_SESSION["usertypeid"]=="4") {?>&nbsp;&nbsp;[ <a href="addresource.php?resourcetypeid=2">Create</a> ]<?php //} ?> </div>
                                    </div>
                                </div>
                            </div>
                            <div class="w_div2"  style="display:<?php	echo ($_type == 1 || $_type == 0) ? '' : 'none;' ?>">
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
                                            <div class='titlemain'><span class='titlered'><a href='viewresource.php?resourceid=".$row["resourceid"]."'>".$row['subject']."</a></span><br />
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
                                      [ <a href="viewallresources.php?resourcetypeid=1&name=Videos">View all</a> ]<?php //if($_SESSION["usertypeid"]=="3" || $_SESSION["usertypeid"]=="4") {?>&nbsp;&nbsp;[ <a href="addresource.php?resourcetypeid=1">Create</a> ]<?php //} ?> </div>
                                    </div>
                                </div>
                            </div>
                            <div class="w_div3" style="display:<?php	echo ($_type == 5 || $_type == 0) ? '' : 'none;' ?>">
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
                                            <div class='titlemain'><span class='titlered'><a href='viewresource.php?resourceid=".$row["resourceid"]."'>".$row['subject']."</a></span><br />
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
                                      [ <a href="viewallresources.php?resourcetypeid=5&name=Teleconferences">View all</a> ]<?php //if($_SESSION["usertypeid"]=="3" || $_SESSION["usertypeid"]=="4") {?>&nbsp;&nbsp;[ <a href="addresource.php?resourcetypeid=5">Create</a> ]<?php //} ?> </div>
                                    </div>
                                </div>
                            </div>
                        </div>



















                      <div class="w_div2" style="display:<?php	echo ($_type == 3 || $_type == 0) ? '' : 'none;' ?>">
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
                                            <div class='titlemain'><span class='titlered'><a href='viewresource.php?resourceid=".$row["resourceid"]."'>".$row['subject']."</a></span><br />
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
                                      [ <a href="viewallresources.php?resourcetypeid=3&name=Organizations">View all</a> ]<?php //if($_SESSION["usertypeid"]=="3" || $_SESSION["usertypeid"]=="4") {?>&nbsp;&nbsp;[ <a href="addresource.php?resourcetypeid=3">Create</a> ]<?php// } ?>
                                  </div>
                              </div>
                          </div>
                      </div>
                      <div class="w_div3"  style="display:<?php	echo ($_type == 6 || $_type == 0) ? '' : 'none;' ?>">
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
                                            <div class='titlemain'><span class='titlered'><a href='viewresource.php?resourceid=".$row["resourceid"]."'>".$row['subject']."</a></span><br />
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
                                      [ <a href="viewallresources.php?resourcetypeid=6&name=Other Resources">View all</a> ]<?php //if($_SESSION["usertypeid"]=="3" || $_SESSION["usertypeid"]=="4") {?>&nbsp;&nbsp;[ <a href="addresource.php?resourcetypeid=6">Create</a> ]<?php //} ?>
                                  </div>
                              </div>
                          </div>
                      </div>
                  <!--</div>-->







              </div>		
                </div>
          </div>
        </div>	  
        
<?php include "footer.php"; ?>