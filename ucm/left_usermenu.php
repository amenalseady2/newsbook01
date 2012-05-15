<div class="left_contant">
                	<div class="user_info">

                    	<div class="user_img">
                        	<!--<img src="images/user-img.jpg" />-->
                            <img src="profilepics/<?php echo $profilepic; ?>" width="63" border="0" />
                        </div>
<?php 


$query_frnds_count="SELECT COUNT(*)	
	FROM tblfriends, tbluser,tbldisease,tblusertype
	WHERE tblfriends.userid = ".$userid."	
	AND tbluser.userid <> ".$userid."
	AND tbluser.isactive=1
	AND friendshipstatus = 2
	AND tblfriends.friendwith = tbluser.userid
	AND tbldisease.diseaseid=tbluser.diseaseid and tblusertype.usertypeid=tbluser.usertypeid ";
	$result_frnds_count=mysql_query($query_frnds_count);
	$num_frnds_count=mysql_num_rows($result_frnds_count);
	
	if($num_frnds_count>0)
	{
		$row_frnds_count=mysql_fetch_array($result_frnds_count);	
	
		$frnds_count=$row_frnds_count[0];//." ".$userid;
	}

	// getting the count for members with my interest
	//////////////////////  PREPARING THE SECONDARY INTERESTS ARRAY ////////////////////
	
	$m_query = sprintf ( "select * from tblsecondary_interests where userid='%s'", $userid );
	$m_rslt = mysql_query ( $m_query );
	
	$secondary_interests_ids = "(".$disease_id;
	while ( $m_rw = mysql_fetch_array ( $m_rslt ) ) {
		$secondary_interests_ids .= ",".$m_rw['diseaseid'];
	}
	$secondary_interests_ids  .= ')';
	
	/////////////////////
	
	$query_reqs=" 
		SELECT tbluser.userid,CONCAT( fname, ' ', lname ) AS sendername, 
	thumb_profile as profilepic,access_pic,strusertype,strdisease,usealias,
	alias,					
	access_msg,
	access_pic
	FROM tbluser
	INNER JOIN tbldisease ON tbldisease.diseaseid=tbluser.diseaseid
	INNER JOIN tblusertype ON tblusertype.usertypeid=tbluser.usertypeid
	LEFT JOIN tblsecondary_interests on (tblsecondary_interests.userid = tbluser.userid)
	WHERE tbluser.userid <> $userid
	AND tbluser.isactive=1 
	and (tbluser.diseaseid = $disease_id or  tblsecondary_interests.diseaseid IN $secondary_interests_ids)";
	$query_reqs=$query_reqs." limit 10";
	
	$rslt_count = mysql_query($query_reqs);
	$members_with_my_interest_count = 	mysql_num_rows($rslt_count);
	
	

?>						
						<?php
							$_name='';
							if(isset($_SESSION["userid"])){
							
							/******************************************* LOAD USER INFO **********************************************************/
							$query="select 
										access_name,
										alias
									from tbluser,tblusertype,tbldisease,tblcountry where userid=".$_SESSION["userid"]." and 
									tblusertype.usertypeid=tbluser.usertypeid and tbldisease.diseaseid=tbluser.diseaseid and tblcountry.countryid=tbluser.countryid";
									
							$result=mysql_query($query);
							$num=mysql_num_rows($result);
							if($num>0)
							{
								$row=mysql_fetch_array($result);	
								
								if($row["access_name"]==1 && !empty($row["alias"]))		
									$_name=$row["alias"];
							}	
						}
						
						?>
						
                        <div class="user_data">
                        	<div class="user_name bluetitle size20 bold" /><?php echo empty($_name) ? $username : $_name; ?></div>
                         	<div class="ul_msg">
                            <ul>
							    <li><a href="messages.php?mode=inbox&page=1">My Messages(<?php echo isset($inbox_items) ? $inbox_items : '';?>)</a></li>
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
								<li><a href="messages.php?mode=inbox&page=1">My Messages(<?php echo isset($inbox_items) ? $inbox_items : '00';;?>)</a></li>
                               <li>
                                    <a href="notifications.php">My Notifications</a>
                                </li>
                                
                            </ul>
                        </div>
                    </div>
                    <div class="profile_links">
                    	<div class="title_txt">
                        	<div class="plusimg"><img src="images/plus_icon.jpg" /></div>
                            <div class="txttitle whitetitle size12 bold">Make a Difference</div>
                        </div>
                        <div class="txt_links">
                        	<ul><li><a href="resources.php">Health Resources</a></li>  </ul>
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
                            <div class="txttitle whitetitle size12 bold">Connect with Others</div>
                        </div>
                        <div class="txt_links">
                            <ul>
                            	<li><a href="members.php">Search All Members</a></li>
								<li>
                                    <a href="myinterestmembers.php">Members With My Interest(<?php echo $members_with_my_interest_count;?>)</a>
                                </li> 
								<li><a href="reachout.php">Reach Out(<?php echo $ps_match_count;?>)</a></li>
                            </ul>
                        </div>
                    </div>
                     <div class="profile_links">
                    	<div class="title_txt">
                        	<div class="plusimg"><img src="images/plus_icon.jpg" /></div>
                            <div class="txttitle whitetitle size12 bold">My Interest Wall</div>
                        </div>
                        <div class="txt_links">
                            <ul>
                            	<li><a href="interest_wall.php">My Interest Wall</a></li><li><a href="friends_activity.php">My Friends Activity</a></li>
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
                    </div>
