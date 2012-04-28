 <?php session_start();
include("connection.php");
include("common.php");

if(isSet($_POST['notcounter'])) 
{
//$userid=$_SESSION["userid"];

$notcounter=$_POST['notcounter'];
	
$query_nots=" 
	SELECT * FROM tblfeeds
	order by created desc limit ".$notcounter.",10";	
		
$result=mysql_query($query_nots);
$count=mysql_num_rows($result);
$message="";
if($count>0)
{
	while($row=mysql_fetch_array($result))
	{
		$notificationid=$row['id'];
									
		$message=$message."<div class='not-general'>";	
			
		$message=$message."<div class='notlist'>";
		//$message=$message.$row['message'];//<br/>";
		$message=$message.get_name_link($row['userid'])." ".$row['message'];//<br/>";
		$message=$message."</div>";
		
		$message=$message."<div >";/*class='frnd-list-action'*/
	
		$message=$message."<div class='notifications-date'>
						".$row["created"]."
					</div>";
		$message=$message."<div style='clear:both;'></div>";
		$message=$message."</div>";  
		
		$message=$message."<div style='clear:both;'></div>";
		
		$message=$message."</div>";
	
	}
	
	echo $message;
?>
<div id="morefeeds<?php echo $notificationid; ?>" class="morebox">
	<a href="#" class="morefeeds" id="<?php echo $notificationid; ?>"><img src="images/more.png" alt="more" width="40" height="40" border="0"/></a>
</div>
<?php
}
}
?>

<?php 
function get_name_link($userid){
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
	$result =	mysql_query($query);
	if(mysql_num_rows($result)>0){
		$row=mysql_fetch_array($result);
		
		//$image = '<a href="viewprofile.php?userid='.$userid.'"><img src="profilepics/'.$row['profilepic'].'" style="background-color:#FFFFFF" width="50" height="50" border="0"/></a>';
		
		$rslt = "<a href='viewprofile.php?userid=".$userid."'>".  $row['fname']." ".$row['lname']."</a>";
		return $rslt;
	}
	else{
		return '';
	}
}
?>