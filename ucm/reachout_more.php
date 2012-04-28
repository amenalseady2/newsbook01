<?php session_start();
include("connection.php");
include("common.php"); 

//echo "Welcome hello";

if( (!isset($_SESSION["userid"])) || $_SESSION["userid"] =="" || (!isset($_POST['searchcounter'])) )
{	 
	echo "<script>location.href='index.php' </script>"; 
}
else
{ 
	if(isSet($_POST['searchcounter'])) 
    {
		$userid=$_SESSION["userid"];
		$searchcounter=$_POST['searchcounter'];
		$diseaseid = $_POST["diseaseid"];

		$query_reqs=" 
		SELECT tbluser.userid,CONCAT( fname, ' ', lname ) AS sendername, 
		thumb_profile as profilepic,access_msg,access_pic,strusertype,strdisease
		FROM tbluser,tbldisease,tblusertype
		WHERE tbluser.userid <> ".$userid."	
		AND tbluser.diseaseid = $diseaseid	 
		AND tbldisease.diseaseid=tbluser.diseaseid 
		and tblusertype.usertypeid=tbluser.usertypeid
		AND (tbluser.usertypeid=4 OR tbluser.usertypeid=5)";	
			
		$query_reqs = $query_reqs." limit ".$searchcounter.",2"; 	 
	}
}  
?>
				
  
                <?php
 							$search_id = 0;
							
							$sql=mysql_query($query_reqs);							
							$count=mysql_num_rows($sql);		
							$message="";
							if($count>0)
							{							
								while($row=mysql_fetch_array($sql))
								{			
									    $search_id=$row['userid'];
										
										$message=$message."<div class='req-general'>";
										
										if($row["profilepic"]!="" && $row["access_pic"]!="1")
											$message=$message."<div class='searchlist'><a class='reqlisttitle' href='viewprofile.php?userid=".$row["userid"]."'><img src='profilepics/".$row["profilepic"]."' style='background-color:#FFFFFF' width='50' border='0'></a></div>";
										else
											$message=$message."<div class='searchlist'><a class='reqlisttitle' href='viewprofile.php?userid=".$row["userid"]."'><img src='profilepics/empty_profile.jpg' style='background-color:#FFFFFF' width='50' border='0'></a></div>";
											
											
									$message=$message."<div class='reqlist'>";
									$message=$message."<a class='frndlisttitle' href='viewprofile.php?userid=".$row['userid']."'>".$row['sendername']."</a><br><b>Interested in :</b> ".$row['strdisease']."<br/><b>Type :</b> ".$row['strusertype'];//<br/>";
									$message=$message."</div>";
									
									$message=$message."<div class='frnd-list-action'>";
									$message=$message."<div style='clear:both;'></div>";
									$message=$message."</div>";
									
									$message=$message."<div style='clear:both;'></div>";
									
									$message=$message."</div>"; 
								}
							}
							else 
							{
								$message="<div class='req-general'>No More Professionals/Survivors/Councillors With Your Interest Found.</div>";
							}
							
							echo $message; 
	 
							if($search_id) {
							?>
                            <div id="msreachout<?php echo $search_id; ?>" class="morebox">
                                 <a href="#" class="msreachout" id="<?php echo $search_id; ?>"> <img src="images/more.png" alt="more" width="40" height="40" border="0"/>
                                 </a>
                            </div>
							<?php } ?>	
