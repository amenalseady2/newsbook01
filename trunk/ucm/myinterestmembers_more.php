<?php session_start();
include("connection.php");
include("common.php");
 
if(!isset($_SESSION["userid"]) || $_SESSION["userid"] =="")
{	 
	echo "<script>location.href='index.php' </script>"; 
}
else
{ 
	if(isSet($_POST['membintcounter']) && isSet($_POST['uname']) && isSet($_POST['diseaseid']))
    {
	$userid=$_SESSION["userid"];  
	 
	$searchcounter = $_POST['membintcounter']; 
	$disease_id = $_POST['diseaseid'];
	
	$uname=$_POST['uname'];
	$uname = str_replace("'","''",$_POST["uname"]);
	$uname=str_replace("\"","''",$uname);
	$uname=stripslashes($uname);
 
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
	

	$where = '';				
	if($uname!='' && trim($uname)!='') 				
	   $where = $where. " AND (fname like '%".$uname."%' or lname like '%".$uname."%' or concat( fname, ' ', lname ) like '%".$uname."%')";
 	
	if($where!='')
		$query_reqs = $query_reqs.$where;  
	
	$query_reqs = $query_reqs." limit ".$searchcounter.",10";		 
	}
} ?>
				<input type="hidden" id="diseaseid_h" name="diseaseid_h" value="<?php echo $disease_id; ?>" />
  
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
										$message=$message."<div class='searchlist'><a class='reqlisttitle' href='viewprofile.php?userid=".$row["userid"]."'><img src='profilepics/".$row["profilepic"]."' style='background-color:#FFFFFF' width='50'  border='0'></a></div>";
									else
										$message=$message."<div class='searchlist'><a class='reqlisttitle' href='viewprofile.php?userid=".$row["userid"]."'><img src='profilepics/empty_profile.jpg' style='background-color:#FFFFFF' width='50'  border='0'></a></div>";
											
									$message=$message."<div class='reqlist'>";
									$message=$message."<a class='frndlisttitle' href='viewprofile.php?userid=".$row['userid']."'>".$row['sendername']."</a><br><b>Interested in :</b> ".$row['strdisease']."<br/><b>Type :</b> ".$row['strusertype'];//<br/>";
 									$message=$message."</div>";
									
									$message=$message."<div class='frnd-list-action'>";
 
									$message=$message."<div style='clear:both;'></div>";
									$message=$message."</div>";
									
									$message=$message."<div style='clear:both;'></div>";
									
									$message=$message."</div>";//end of req-general
								}
							}
							else 
							{
								$message="<div class='req-general'>No more Members of Your Interest Found.</div>";
							}
							
							echo $message; 		
							 
							if($search_id) {
							?> 
                            <div id="moresearchmyint<?php echo $search_id; ?>" class="morebox">
                                <a href="#" class="moresearchmyint" id="<?php echo $search_id; ?>"> <img src="images/more.png" alt="more" width="40" height="40" border="0"/>
                                </a>
							</div>
							<?php } ?>	
 