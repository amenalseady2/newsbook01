<?php session_start();
include("connection.php");
include("common.php");

if(!isset($_SESSION["userid"]) || $_SESSION["userid"] =="")
{	 
	echo "<script>location.href='index.php' </script>"; 
}
else
{ 
	if(isSet($_POST['myfriendscounter']))
    {
	$userid=$_SESSION["userid"];  	
	$searchcounter = $_POST['myfriendscounter'];  	
	
	$uname=$_POST['uname'];
	$uname = str_replace("'","''",$_POST["uname"]);
	$uname=str_replace("\"","''",$uname);
	$uname=stripslashes($uname);
	
	$query_reqs=" 
	SELECT friendshipid, tblfriends.userid, friendwith, CONCAT( fname, ' ', lname ) AS sendername, friendshipstatus, 
	thumb_profile as profilepic,access_pic,strusertype,strdisease
	FROM tblfriends, tbluser,tbldisease,tblusertype
	WHERE tblfriends.userid = ".$userid."
	AND tbluser.userid <> ".$userid."
	AND friendshipstatus = 2
	AND tblfriends.friendwith = tbluser.userid
	AND tbldisease.diseaseid=tbluser.diseaseid and tblusertype.usertypeid=tbluser.usertypeid  order by tblfriends.userid desc"; 	
	
	//$where = '';				
	//if($uname!='' && trim($uname)!='') 				
	//   $where = $where. " AND (fname like '%".$uname."%' or lname like '%".$uname."%' or concat( fname, ' ', lname ) like '%".$uname."%')";
 	
	//if($where!='')
	//	$query_reqs = $query_reqs.$where;  
	
	$query_reqs = $query_reqs." limit ".$searchcounter.",10"; 	 
	}
} 
 							$search_id = 0;
							$sql=mysql_query($query_reqs);							
							$count=mysql_num_rows($sql);		
							$message="";
							if($count>0)
							{							
								while($row=mysql_fetch_array($sql))
								{	 
									$search_id=$row['friendwith'];
									
									$message=$message."<div class='req-general'>";
										
										if($row["profilepic"]!="" && $row["access_pic"]!="1")
											$message=$message."<div class='searchlist'><a class='reqlisttitle' href='viewprofile.php?userid=".$row["userid"]."'><img src='profilepics/".$row["profilepic"]."' style='background-color:#FFFFFF' width='50'  border='0'></a></div>";
										else
											$message=$message."<div class='searchlist'><a class='reqlisttitle' href='viewprofile.php?userid=".$row["userid"]."'><img src='profilepics/empty_profile.jpg' style='background-color:#FFFFFF' width='50'  border='0'></a></div>";
	 										
									$message=$message."<div class='reqlist'>";
									$message=$message."<a class='frndlisttitle' href='viewprofile.php?userid=".$row['friendwith']."'>".$row['sendername']."</a><br><b>Interested in :</b> ".$row['strdisease']."<br/><b>Type :</b> ".$row['strusertype'];//<br/>";
									$message=$message."</div>";
									
									$message=$message."<div class='frnd-list-action'>";
 
									$message=$message."<div class='friend-req-confirm'>
													<a href='confrimfriend.php?ui=".$_SESSION["userid"]."&fw=".$row["friendwith"]."
												&n=".$_SESSION["fname"]."&stat=un'>Unfriend</a>
												</div>";
									$message=$message."<div style='clear:both;'></div>";
									$message=$message."</div>";
									
									$message=$message."<div style='clear:both;'></div>";
									
									$message=$message."</div>";//end of req-general
								}
							}
							else 
							{
									$message="<div class='req-general'>No More Friends Found.</div>";
							}							
							echo $message; 	
							if($search_id) {
							?>								
							<div id="msmyfriends<?php echo $search_id; ?>" class="morebox">
                                 <a href="#" class="msmyfriends" id="<?php echo $search_id; ?>"> <img src="images/more.png" alt="more" width="40" height="40" border="0"/>
                                 </a>
                            </div> 
							<?php } ?>
							