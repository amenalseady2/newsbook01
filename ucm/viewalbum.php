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


	$albumid=$_GET["albumid"];
	$albumname=$_GET["albumname"];

$fname='';
$lname='';
$email='';
$password='';
$profilepic='';
$gender="";
$usertype="";
$disease="";
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
	



if(!isset($_SESSION["userid"]) || $_SESSION["userid"] =="")
{	//header("location: login.php");   
		echo "<script>location.href='index.php' </script>";

}
else
{
	$userid=$_SESSION["userid"];

	if(isset($_GET['msg']))
	{ ?>
	  <div class="txttitle whitetitle size12 bold"><?php echo $_GET['msg']; ?></div>
	<?php 
	}
	
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
		
	/******************************************* LOAD ALBUM PHOTOS **********************************************************/
	
	$query_photos="
		select photoid,dateadded,description,picname,spicname,gvpicname,epicname from tblphotos where albumid = ".$albumid;


	$sql=mysql_query($query_photos);
	$message="";
	$rowcount=0;
	$count=0;
	if($sql)
	    $count=mysql_num_rows($sql);
	
	
} ?>

<link rel="stylesheet" href="css/basic.css" type="text/css" />
<link rel="stylesheet" href="css/galleriffic-2.css" type="text/css" />
<script type="text/javascript" src="js_viewer/jquery-1.3.2.js"></script>
<script type="text/javascript" src="js_viewer/jquery.galleriffic.js"></script>
<script type="text/javascript" src="js_viewer/jquery.opacityrollover.js"></script>
<!-- We only want the thunbnails to display when javascript is disabled -->
<script type="text/javascript">
    document.write('<style>.noscript { display: none; }</style>');
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
                                <!--<li><a href="medicalhistory.php">Medical History</a></li>-->
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
        <a href="myprofile.php" class="whitetitle size12">Home</a>&nbsp;&nbsp;<span class="size12">>></span>&nbsp;&nbsp;<a href="photos.php">Photos</a>&nbsp;&nbsp;<span class="size9">>></span>&nbsp;&nbsp;<span class="whitetitle size12">My Albums</span>
    </div>
    <div class="body">
        <div class="main_link">
            <div class="inbox_title">
                <!--<div class="f_left">
                    <img align="absmiddle" src="images/inbox_icon.jpg" />
                </div>-->
                <div class="f_left bluetitle size30 bold" style="padding-top:5px; width:auto;"><?php echo $albumname; ?></div>
                <!--<div class="right_img">
                    <div class="inbox_img">
                        <img src="images/inbox-img.jpg" />
                        <br />
                        <a href="#">Inbox</a>
                    </div>-->
                    <!--<div class="outbox_img">
                        <img src="images/inbox-img.jpg" />
                        <br />
                        <a href="#">Outbox</a>
                    </div>-->
                    <div style="float:right; vertical-align:bottom;">
                        <br/>
                        <a class="bluelink" href="uploadphotos.php?albumid=<?php echo $albumid; ?>&albumname=<?php echo $albumname; ?>">Add Photos</a><?php if($count>0) { ?> | <a class="bluelink" href="editalbumphotos.php?albumid=<?php echo $albumid; ?>&albumname=<?php echo $albumname; ?>">Edit Photos</a><?php } ?> | <a class="bluelink" href="editalbum.php?albumid=<?php echo $albumid; ?>">Edit Album</a> | 
						
						<a class="bluelink" href="javascript:void(0);" onclick="javascript:if(confirm('Are you sure you want to delete the album?')){location.href='delalbum.php?albumid=<?php echo $albumid; ?>';}">Delete Album</a>
                        <!--<a class="bluelink" href="createalbum.php">Create Album</a>
                    </div>-->
                </div>
            </div>
            
            <div class="email_table">              
                <table cellpadding="0" cellspacing="0" style="width:686px; border:0px;">
                    
                        <tr>
                            <td style="width:686px;" align="left" valign="top">



                                <?php
							if($count>0)
							{
				?>


                                <div id="page">
			<div id="container">

				<!-- Start Advanced Gallery Html Containers -->
				<div id="gallery" class="content">
					<div id="controls" class="controls"></div>
					<div class="slideshow-container">
						<div id="loading" class="loader"></div>
						<div id="slideshow" class="slideshow"></div>
					</div>
					<div id="caption" class="caption-container"></div>
				</div>
				<div id="thumbs" class="navigation" style="width:100px">
					<ul id="ul_top_hypers" class="thumbs noscript">
<style>

#ul_top_hypers{

  display: inline;

  }
  
#ul_top_hypers li
{
    display: inline;
}
</style>
                                                <?php
						
							//if($count>0)
//							{							
								while($row=mysql_fetch_array($sql))
								{
								
										
									
									
									$query_photos="
										select photoid,dateadded,description,gvpicname as picname,spicname,gvpicname,epicname from tblphotos where albumid = ".$albumid;
									
									
						echo "<li>
							<a class='thumb' name='leaf' href='albumphotos/".$row['gvpicname']."'>
								<img src='albumphotos/".$row['spicname']."' alt='".$row['description']."' />
							</a>
							<div class='caption'>
								<div class='download'>
									<a href='delphoto.php?photoid=".$row['photoid']."'>&nbsp;Delete Image</a>
								</div>
								<div class='download'>
									<a href='albumphotos/".$row['picname']."'>Download Original</a>
								</div>
								<div class='image-desc'>".$row['description']."</div>
							</div>
						</li>";
									//
//									$message=$message."<div class='album-pics' ><a href='albumphotos/".$row['picname']."' class='preview' title='".$row['description']."'>";
//									
//									$message=$message."<img src='albumphotos/".$row['picname']."' border='0' width='156px' height= '115px' alt='".$row['description']."' />";
//									
//									$message=$message."</a><br/></div>";
									
								}
							//}
//							else
//								 $message=$message."No Pictures Found.";
//								 
//							echo $message; 
							?>

                                            </ul>
				</div>
				<div style="clear: both;"></div>
			</div>
		</div>
                                <?php
		}
							else
								 $message=$message."No Pictures Found.";
								 
							echo $message; 
							?>
                                <script type="text/javascript">
                                    jQuery(document).ready(function($) {
                                    // We only want these styles applied when javascript is enabled
                                    $('div.navigation').css({'width' : '600px', 'float' : 'bottom'});
                                    $('div.content').css('display', 'inline');

                                    // Initially set opacity on thumbs and add
                                    // additional styling for hover effect on thumbs
                                    var onMouseOutOpacity = 0.67;
                                    $('#thumbs ul.thumbs li').opacityrollover({
                                    mouseOutOpacity:   onMouseOutOpacity,
                                    mouseOverOpacity:  1.0,
                                    fadeSpeed:         'fast',
                                    exemptionSelector: '.selected'
                                    });

                                    // Initialize Advanced Galleriffic Gallery
                                    var gallery = $('#thumbs').galleriffic({
                                    delay:                     2500,
                                    numThumbs:                 15,
                                    preloadAhead:              10,
                                    enableTopPager:            true,
                                    enableBottomPager:         true,
                                    maxPagesToShow:            7,
                                    imageContainerSel:         '#slideshow',
                                    controlsContainerSel:      '#controls',
                                    captionContainerSel:       '#caption',
                                    loadingContainerSel:       '#loading',
                                    renderSSControls:          true,
                                    renderNavControls:         true,
                                    playLinkText:              'Play Slideshow',
                                    pauseLinkText:             'Pause Slideshow',
                                    prevLinkText:              '&lsaquo; Previous Photo',
                                    nextLinkText:              'Next Photo &rsaquo;',
                                    nextPageLinkText:          'Next &rsaquo;',
                                    prevPageLinkText:          '&lsaquo; Prev',
                                    enableHistory:             false,
                                    autoStart:                 false,
                                    syncTransitions:           true,
                                    defaultTransitionDuration: 900,
                                    onSlideChange:             function(prevIndex, nextIndex) {
                                    // 'this' refers to the gallery, which is an extension of $('#thumbs')
                                    this.find('ul.thumbs').children()
                                    .eq(prevIndex).fadeTo('fast', onMouseOutOpacity).end()
                                    .eq(nextIndex).fadeTo('fast', 1.0);
                                    },
                                    onPageTransitionOut:       function(callback) {
                                    this.fadeTo('fast', 0.0, callback);
                                    },
                                    onPageTransitionIn:        function() {
                                    this.fadeTo('fast', 1.0);
                                    }
                                    });
                                    });
                                </script>



</div> <!-- End of bio -->							
													
					</div><!-- end of photos -->









                            </td>
                        </tr>
                     
                     
                   


                </table>
            </div>
        </div>
    </div>
</div>
            
        </div>	  
        
<?php include "footer.php"; ?>
