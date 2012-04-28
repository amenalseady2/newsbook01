 <?php session_start();
include("connection.php");
include("common.php");

function ismyfriend($userid,$friendwith)
{
	$query_frnds="SELECT friendshipid, tblfriends.userid, friendwith, friendshipstatus
	FROM tblfriends
	WHERE tblfriends.userid = ".$userid." and friendwith = ".$friendwith."
	AND friendshipstatus = 2";
	$result=mysql_query($query_frnds);
	$num=mysql_num_rows($result);
	if($num>0)
		return true;
	else
		return false;
}

function isfriendpendingapproval($userid,$friendwith)
{
	$query_frnds="SELECT friendshipid, tblfriends.userid, friendwith, friendshipstatus
	FROM tblfriends
	WHERE tblfriends.userid = ".$userid." and friendwith = ".$friendwith."
	AND friendshipstatus = 4";
	$result=mysql_query($query_frnds);
	$num=mysql_num_rows($result);
	if($num>0)
		return true;
	else
		return false;
}

if(isSet($_POST['searchcounter']) && isSet($_POST['uname']) && isSet($_POST['usertypeid']) && isSet($_POST['diseaseid']))
{
if(isSet($_SESSION["userid"]))
   $userid=$_SESSION["userid"];

$searchcounter=$_POST['searchcounter'];
$uname=$_POST['uname'];
$usertypeid=$_POST['usertypeid'];
$diseaseid=$_POST['diseaseid'];
	
$uname = str_replace("'","''",$_POST["uname"]);
$uname=str_replace("\"","''",$uname);
$uname=stripslashes($uname);
$usertypeid = $_POST["usertypeid"];
$diseaseid = $_POST["diseaseid"];

$where = '';

				$where = '';
				
				if($uname!='' && trim($uname)!='')
					//$where = $where. " and alias like '%".$uname."%'"; //" and (fname like '%".$uname."%' or lname like '%".$uname."%' or concat( fname, ' ', lname ) like '%".$uname."%')"; 
				
				$where = $where. " and CASE WHEN usealias=1 THEN alias like '%".$uname."%' ELSE (fname like '%".$uname."%' or lname like '%".$uname."%' or concat( fname, ' ', lname ) like '%".$uname."%') END";
				
				if($usertypeid!='0')
				{
					//if($where=='')
					//	$where = $where. " tbluser.usertypeid = ".$usertypeid;
					//else 
						$where = $where. " and tbluser.usertypeid = ".$usertypeid; 
				}
				
				if($diseaseid!='0')
				{
					//if($where=='')
					//	$where = $where. " tbluser.diseaseid = ".$diseaseid;
					//else 
						$where = $where. " and tbluser.diseaseid = ".$diseaseid; 
				}
				
/*
if($uname!='' && trim($uname)!='')
	$where = $where. " and alias like '%".$uname."%'"; //" and (fname like '%".$uname."%' or lname like '%".$uname."%' or concat( fname, ' ', lname ) like '%".$uname."%')"; 

if($usertypeid!='0')
{ 
		$where = $where. " and tbluser.usertypeid = ".$usertypeid; 
}

if($diseaseid!='0')
{
		$where = $where. " and tbluser.diseaseid = ".$diseaseid; 
}
*/

$query = "
select 
	userid,
	fname,
	lname,
	profilepic,
	dob,
	genderid,
	tbluser.usertypeid,
	tbluser.diseaseid, 
	city,
	countryid,
	email,
	password,
	website,
	iam,
	ilike,
	myexperience,
	isactive,
	usealias,
	alias,
	strusertype,
	strdisease,
	access_msg,
	access_pic
from tbluser,tbldisease,tblusertype where tbluser.isactive=1 and tbldisease.diseaseid=tbluser.diseaseid and tblusertype.usertypeid=tbluser.usertypeid and tbluser.userid <> ".$userid."";
 
if($where!='')
	$query=$query.$where;
	
$query=$query." limit ".$searchcounter.",10";	
	
//echo $query;	
		
$result=mysql_query($query);
$count=mysql_num_rows($result);
$message="";
if($count>0)
{
	while($row=mysql_fetch_array($result))
	{									
		$search_id=$row['userid'];
									
		$message=$message."<div class='req-general'>";		
																				
		if((($_SESSION["userid"] != $row["userid"]) && ismyfriend($_SESSION["userid"],$row["userid"])  && !isfriendpendingapproval($_SESSION["userid"],$row["userid"]) && ($row["profilepic"]!="" && $row["access_pic"]!="1"))	|| ($row["profilepic"]!="" && $row["access_pic"]=="2") )
			$message=$message."<div class='searchlist'><a class='reqlisttitle' href='viewprofile.php?userid=".$row["userid"]."'><img src='profilepics/".$row["profilepic"]."' style='background-color:#FFFFFF' width='50' border='0'></a></div>";
		else
			$message=$message."<div class='searchlist'><a class='reqlisttitle' href='viewprofile.php?userid=".$row["userid"]."'><img src='profilepics/empty_profile.jpg' style='background-color:#FFFFFF' width='50'  border='0'></a></div>";
			
		$message=$message."<div class='searchlist'>";
		$message=$message."<a class='reqlisttitle' href='viewprofile.php?userid=".$row["userid"]."'>";
		
		if($row['usealias'] && $row['alias']!="")
			$message=$message.$row['alias'];
		else
			$message=$message.$row['fname']." ".$row['lname'];
			
		
		$message=$message."</a><br><b>Interested in :</b> ".$row['strdisease']."<br/><b>Type :</b> ".$row['strusertype'];
		$message=$message."</div>";
		
		$message=$message."<div class='search-list-action'>";
		
		if( ($_SESSION["userid"] != $row["userid"]) && ismyfriend($_SESSION["userid"],$row["userid"])  && !isfriendpendingapproval($_SESSION["userid"],$row["userid"]) )
		{
			if($row['access_msg']!='1')
			{
				$message=$message."<div class='friend-list-button'>";
				$message=$message."<a href='#' onclick='openmsg(".$_SESSION["userid"].",".$row["userid"].");'>Message</a>";
																
				$message=$message."</div><div style='width:5px;float:left;'>&nbsp;</div>";
			}
			else
			{
				$message=$message."<div class='no-button'>&nbsp;";
				$message=$message."</div><div style='width:5px;float:left;'>&nbsp;</div>";
			}
			
			$message=$message."<div class='friend-list-button'>";
			$message=$message."<a href='confrimfriend2.php?ui=".$_SESSION["userid"].
			"&fw=".$row["userid"]."&n=".$_SESSION["fname"]."&stat=un&uname=".$uname."&usertypeid=".$usertypeid."&diseaseid=".$diseaseid."'>Unfriend</a>";
			$message=$message."</div>";
		}
		elseif( ($_SESSION["userid"] != $row["userid"]) && !ismyfriend($_SESSION["userid"],$row["userid"])   && !isfriendpendingapproval($_SESSION["userid"],$row["userid"]))
		{
			if($row['access_msg']=='2')
			{
				$message=$message."<div class='friend-list-button'>";
				$message=$message."<a href='#' onclick='openmsg(".$_SESSION["userid"].",".$row["userid"].");'>Message</a>";
				$message=$message."</div><div style='width:5px;float:left;'>&nbsp;</div>";
			}
			else
			{
				$message=$message."<div class='no-button'>&nbsp;";
				$message=$message."</div><div style='width:5px;float:left;'>&nbsp;</div>";
			}
			
			$message=$message."<div class='friend-list-button'>";
			$message=$message."<a href='addfriend2.php?ui=".$row["userid"].
			"&fw=".$_SESSION["userid"]."&n=".$_SESSION["fname"]."&uname=".$uname."&usertypeid=".$usertypeid."&diseaseid=".$diseaseid."'>Add Friend</a>";
			$message=$message."</div>";
		}
		elseif( ($_SESSION["userid"] != $row["userid"]) && !ismyfriend($_SESSION["userid"],$row["userid"])   && isfriendpendingapproval($_SESSION["userid"],$row["userid"]))
		{
			if($row['access_msg']=='2')
			{
				$message=$message."<div class='friend-list-button'>";
				$message=$message."<a href='#' onclick='openmsg(".$_SESSION["userid"].",".$row["userid"].");'>Message</a>";
				$message=$message."</div><div style='width:5px;float:left;'>&nbsp;</div>";
			}
			else
			{
				$message=$message."<div class='no-button'>&nbsp;";
				$message=$message."</div><div style='width:5px;float:left;'>&nbsp;</div>";
			}
			
			$message=$message."<div class='friend-list-button'>";
			$message=$message."Friend Request Sent";
			$message=$message."</div>";
		}
		
		$message=$message."<div style='clear:both;'></div>";
		$message=$message."</div>";
		
		$message=$message."<div style='clear:both;'></div>";
		
		$message=$message."</div>";
	}
//}
//else 
//{
//	$message="<div class='req-general'>No Members Found.</div>";
//}
	
echo $message;
?>
<div id="moresearch<?php echo $search_id; ?>" class="morebox">
	<a href="#" class="moresearch" id="<?php echo $search_id; ?>"><img src="images/more.png" alt="more" width="40" height="40" border="0"/></a>
</div>
<?php
}
}
?>