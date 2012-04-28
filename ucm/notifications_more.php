 <?php session_start();
include("connection.php");
include("common.php");

if(isSet($_POST['notcounter']))//isSet($_SESSION['userid'])  && 
{
$userid=$_SESSION["userid"];

$notcounter=$_POST['notcounter'];
	
$query_nots=" 
	SELECT notificationid,userid,notification_type,notification,notificationtime
	FROM tblnotifications
	WHERE userid = ".$userid." order by notificationtime desc limit ".$notcounter.",10";	
		
$result=mysql_query($query_nots);
$count=mysql_num_rows($result);
$message="";
if($count>0)
{
	while($row=mysql_fetch_array($result))
	{
		$notificationid=$row['notificationid'];
									
		$message=$message."<div class='not-general'>";	
			
		$message=$message."<div class='notlist'>";
		$message=$message.$row['notification'];//<br/>";
		$message=$message."</div>";
		
		$message=$message."<div >";/*class='frnd-list-action'*/
	
		$message=$message."<div class='notifications-date'>
						".$row["notificationtime"]."
					</div>";
		$message=$message."<div style='clear:both;'></div>";
		$message=$message."</div>";  
		
		$message=$message."<div style='clear:both;'></div>";
		
		$message=$message."</div>";//end of req-general
	
	}
	
	echo $message;
?>
<div id="morenots<?php echo $notificationid; ?>" class="morebox">
	<a href="#" class="morenots" id="<?php echo $notificationid; ?>"><img src="images/more.png" alt="more" width="40" height="40" border="0"/></a>
</div>
<?php
}
}
?>