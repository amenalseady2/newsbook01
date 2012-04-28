<?php include "header_inner.php";

	function thumbnail($image_path, $size = '200x150') {
  list($width, $height) = getimagesize($image_path);
  $image_aspect = $width / $height;
 
  list($thumb_width, $thumb_height) = explode('x', $size);
  $thumb_aspect = $thumb_width / $thumb_height;
 
  if ($image_aspect > $thumb_aspect) {
    $crop_height = $height;
    $crop_width = round($crop_height * $thumb_aspect);
  } else {
    $crop_width = $width;
    $crop_height = round($crop_width / $thumb_aspect);
  }
 
  $crop_x_offset = round(($width - $crop_width) / 2);
  $crop_y_offset = round(($height - $crop_height) / 2);
 
  // crop parameter
  $crop_size = $crop_width.'x'.$crop_height.'+'.$crop_x_offset.'+'.$crop_y_offset;
 
  // thumbnail is created next to original image with th- prefix.
  $thumb = dirname($image_path).'/th-'.basename($image_path);
  exec('convert '. escapeshellarg($image_path).' -crop ' . $crop_size .' -thumbnail '.$size.' '. escapeshellarg($thumb));
 
  return $thumb;
}



$fname='';
$lname='';
$email='';
$password='';
$profilepic='';
$gender="";
$usertype="";
$disease="";
$diseaseid = "0";
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
	
	$albumid=$_GET["albumid"];
	$albumname=$_GET["albumname"];
	
	$isnew=0;
	if(isset($_GET["isnew"]))
		$isnew=1;

if(!isset($_SESSION["userid"]) || $_SESSION["userid"] =="")
{	//header("location: login.php");   
		echo "<script>location.href='index.php' </script>";

}
else
{
	$userid=$_SESSION["userid"];
	
	$albumid=$_GET["albumid"];
	$albumname=$_GET["albumname"];

	if(isset($_GET['msg']))
	{ ?>
	  <div class="txttitle whitetitle size12 bold"><?php echo $_GET['msg']; ?></div>
	<?php 
	}
	
	$isnew=0;
	if(isset($_GET["isnew"]))
		$isnew=1;
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

	$query_req_count="SELECT COUNT(*)
	FROM tblfriends
	WHERE tblfriends.userid = ".$userid."
	AND friendshipstatus =1";
	$result_req_count=mysql_query($query_req_count);
	$num_req_count=mysql_num_rows($result_req_count);
	if($num_req_count>0)
	{
		$row_req_count=mysql_fetch_array($result_req_count);	
				
		$req_count=$row_req_count[0];//." ".$userid;
	}
		
	/* Now that the new client has been inserted into the database, we will extract all the
	   existing users with matching medical interest and update the notification in their
	   profiles */

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
	AND (tbluser.usertypeid=3 OR tbluser.usertypeid=4)";	
	$result_ps_count=mysql_query($query_ps_count);
	$num_ps_count=mysql_num_rows($result_ps_count);
	if($num_ps_count>0)
	{
		$row_ps_count=mysql_fetch_array($result_ps_count);				
		$ps_match_count = $row_ps_count[0];   
	}

	$query_frnds_count="SELECT COUNT(*)
	FROM tblfriends
	WHERE tblfriends.userid = ".$userid."
	AND friendshipstatus = 2";
	$result_frnds_count=mysql_query($query_frnds_count);
	$num_frnds_count=mysql_num_rows($result_frnds_count);
	if($num_frnds_count>0)
	{
		$row_frnds_count=mysql_fetch_array($result_frnds_count);	
				
		$frnds_count=$row_frnds_count[0];//." ".$userid;
	}
		
	
	
} ?>

<script type="text/javascript">
    /*function fileSelected()
    {
    var file = document.getElementById('fileToUpload').files[0];
    if (file)
    {
    var fileSize = 0;
    if (file.size > 1024 * 1024)
    fileSize = (Math.round(file.size * 100 / (1024 * 1024)) / 100).toString() + 'MB';
    else
    fileSize = (Math.round(file.size * 100 / 1024) / 100).toString() + 'KB';

    document.getElementById('fileName').innerHTML = 'Name: ' + file.name;
    document.getElementById('fileSize').innerHTML = 'Size: ' + fileSize;
    document.getElementById('fileType').innerHTML = 'Type: ' + file.type;


    fileSize = Math.round(file.size * 100 / (1024 * 1024)) / 100;
    if(fileSize>1)
    {
    alert("file size upper than 1 MB is not allowed.")
    document.getElementById('fileToUpload').value="";
    }
    }
    }
    */
    function fileSelected(piccontrol)
    {
    var file = document.getElementById(piccontrol).files[0];
    if (file)
    {
    var fileSize = 0;
    if (file.size > 1024 * 1024)
    fileSize = (Math.round(file.size * 100 / (1024 * 1024)) / 100).toString() + 'MB';
    else
    fileSize = (Math.round(file.size * 100 / 1024) / 100).toString() + 'KB';

    fileSize = Math.round(file.size * 100 / (1024 * 1024)) / 100;
    if(fileSize>1)
    {
    document.getElementById(piccontrol+'msg').innerHTML="Max 1 MB allowed.";
    document.getElementById(piccontrol).value="";
    }
    else
    document.getElementById(piccontrol+'msg').innerHTML="";

    }
    }

</script>

<div class="warpper">
        	<div class="left_side">
            	<div class="left_contant">
                	<div class="user_info">
                    	<div class="user_img">
                        	<img src="profilepics/<?php echo $profilepic; ?>" width="63" height="59" border="0" />
                        </div>
                        <div class="user_data">
                        	<div class="user_name bluetitle size20 bold" /><?php echo $username; ?></div>
                         	<div class="ul_msg">
                            <ul>
                            	<li><a href="messages.php?mode=inbox">My Messages</a></li>
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
                            	<li><a href="messages.php?mode=inbox">My Messages</a></li>
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
                                <!-- <li><a href="medicalhistory.php">Medical History</a></li> -->
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
                    </div>
                </div>
            </div>

<div class="body_main">
    <div class="body_menu whitetitle">
        <a href="myprofile.php" class="whitetitle size12">Home</a>&nbsp;&nbsp;<span class="size12">>></span>&nbsp;&nbsp;<a href="photos.php">Photos</a>&nbsp;&nbsp;<span class="size12">>></span>&nbsp;&nbsp;<span class="whitetitle size12">Upload Photos</span>
    </div>
    <div class="body">
        <div class="main_link">
            <div class="inbox_title">
                <!--<div class="f_left">
                    <img align="absmiddle" src="images/inbox_icon.jpg" />
                </div>--> 
                <div class="f_left bluetitle size30 bold" style="padding-top:5px; width:auto;">
                    Upload Photos to <?php echo $albumname; ?>
                </div> <BR /><BR /><BR />
	            <div class="red bold size12">Note: Picture size should not exceed 1MB</div>                
                <div class="right_img">
                    <div class="inbox_img">
                        <!--<img src="images/inbox-img.jpg" />
                        <br />
                        <a href="#">Inbox</a>-->
                    </div>
                    <div class="outbox_img">
                        <!--<img src="images/inbox-img.jpg" />
                        <br />
                        <a href="#">Outbox</a>-->
                    </div>
                    <div class="notification_img">
                        <!--<img src="images/inbox-img.jpg" />
                        <br />
                        <a href="#">Notification</a>-->
                        
                    </div>
                </div>
            </div>
            
            <div class="email_table">              
                <table cellpadding="0" cellspacing="0" style="width:686px; border:0px;">
                <form action="uploadphotos2.php" method="post" enctype="multipart/form-data" >
                    <tr>
                        <tr>
                            <td style="width:36px; height:12px; " colspan="5" align="left" valign="top"></td>
                        </tr>
                        
                        <td style="width:125px;" align="left" class="bold size12">
                            <input class="pic-upload" type="file" id="pic1" name="pic1" onchange="fileSelected('pic1');"/>
                        </td>
                        <td colspan="4" style="width:561px;" align="left" class="size12">
                            <div class="uploadphoto-alert" id="pic1msg"></div>
                        </td>
                    </tr>
                    <tr>
                        <td style="width:36px; height:3px; " colspan="5" align="left" valign="top"></td>
                    </tr>
                    <tr>

                        <td style="width:125px;" align="left" class="bold size12">
                            <input class="pic-upload" type="file" id="pic2" name="pic2" onchange="fileSelected('pic2');"/>
                        </td>
                        <td colspan="4" style="width:561px;" align="left" class="size12">
                            <div class="uploadphoto-alert" id="pic2msg"></div>

                        </td>
                    </tr>
                    <tr>
                        <td style="width:36px; height:3px; " colspan="5" align="left" valign="top"></td>
                    </tr>
                    <tr>
                        <td style="width:125px;" align="left" class="bold size12">
                            <input class="pic-upload" type="file" id="pic3" name="pic3" onchange="fileSelected('pic3');"/>
                        </td>
                        <td colspan="4" style="width:561px;" align="left" class="size12">
                            <div class="uploadphoto-alert" id="pic3msg"></div>
                        </td>
                    </tr>
                    <tr>
                        <td style="width:36px; height:3px; " colspan="5" align="left" valign="top"></td>
                    </tr>
                    <tr>
                        <td style="width:125px;" align="left" class="bold size12">
                            <input class="pic-upload" type="file" id="pic4" name="pic4" onchange="fileSelected('pic4');"/>
                        </td>
                        <td colspan="4" style="width:561px;" align="left" class="size12">
                            <div class="uploadphoto-alert" id="pic4msg"></div>
                        </td>
                    </tr>
                    <tr>
                        <td style="width:36px; height:3px; " colspan="5" align="left" valign="top"></td>
                    </tr>
                    <tr>
                        <td style="width:125px;" align="left" class="bold size12">
                            <input class="pic-upload" type="file" id="pic5" name="pic5" onchange="fileSelected('pic5');"/>
                        </td>
                        <td colspan="4" style="width:561px;" align="left" class="size12">
                            <div class="uploadphoto-alert" id="pic5msg"></div>
                        </td>
                    </tr>
                    <tr>
                        <td style="width:36px; height:3px; " colspan="5" align="left" valign="top"></td>
                    </tr>
                    <tr>
                        <td style="width:125px;" align="left" class="bold size12">
                            <input class="pic-upload" type="file" id="pic6" name="pic6" onchange="fileSelected('pic6');"/>
                        </td>
                        <td colspan="4" style="width:561px;" align="left" class="size12">
                            <div class="uploadphoto-alert" id="pic6msg"></div>
                        </td>
                    </tr>
                    <tr>
                        <td style="width:36px; height:3px; " colspan="5" align="left" valign="top"></td>
                    </tr>
                    <tr>
                        <td style="width:125px;" align="left" class="bold size12">
                            <input class="pic-upload" type="file" id="pic7" name="pic7" onchange="fileSelected('pic7');"/>
                        </td>
                        <td colspan="4" style="width:561px;" align="left" class="size12">
                            <div class="uploadphoto-alert" id="pic7msg"></div>
                        </td>
                    </tr>
                    <tr>
                        <td style="width:36px; height:3px; " colspan="5" align="left" valign="top"></td>
                    </tr>
                    <tr>
                        <td style="width:125px;" align="left" class="bold size12">
                            <input class="pic-upload" type="file" id="pic8" name="pic8" onchange="fileSelected('pic8');"/>
                        </td>
                        <td colspan="4" style="width:561px;" align="left" class="size12">
                            <div class="uploadphoto-alert" id="pic8msg"></div>
                        </td>
                    </tr>
                    <tr>
                        <td style="width:36px; height:3px; " colspan="5" align="left" valign="top"></td>
                    </tr>
                    <tr>
                        <td style="width:125px;" align="left" class="bold size12">
                            <input class="pic-upload" type="file" id="pic9" name="pic9" onchange="fileSelected('pic9');"/>
                        </td>
                        <td colspan="4" style="width:561px;" align="left" class="size12">
                            <div class="uploadphoto-alert" id="pic9msg"></div>
                        </td>
                    </tr>
                    <tr>
                        <td style="width:36px; height:3px; " colspan="5" align="left" valign="top"></td>
                    </tr>
                    <tr>
                        <td style="width:125px;" align="left" class="bold size12">
                            <input class="pic-upload" type="file" id="pic10" name="pic10" onchange="fileSelected('pic10');"/>
                        </td>
                        <td colspan="4" style="width:561px;" align="left" class="size12">
                            <div class="uploadphoto-alert" id="pic10msg"></div>
                        </td>
                    </tr> 
                    <!--<tr>
                        <td style="width:36px; height:3px;" colspan="5" align="left" valign="top"></td>
                    </tr>-->
                    <tr>
                        <td style="width:36px; height:10px;" colspan="5" align="left" valign="top"></td>
                    </tr>

                    <tr>
                    <td colspan="5" style="width:561px;" align="left" >


                        <input type="hidden" id="albumid" name="albumid" value="<?php echo $albumid; ?>" />
                        <input type="hidden" id="albumname" name="albumname" value="<?php echo $albumname; ?>" />
                        <input type="hidden" id="isnew" name="isnew" value="<?php echo $isnew; ?>" />


                        <input id="submit-subscirbe" type="button" onclick="history.go(-1)" value="Cancel" />&nbsp;<input id="submit-subscirbe" type="submit" value="Upload" />
                    </td>
                    </tr>
                    <tr>
                        <td style="width:36px; height:3px;" colspan="5" align="left" valign="top"></td>
                    </tr>

                </form>
                </table>
            </div>
        </div>
    </div>
</div>
            
        </div>	  
        
<?php include "footer.php"; ?>