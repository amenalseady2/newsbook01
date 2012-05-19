<?php include "header_inner.php";

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

$access_name=0;
$access_pic=0;
$access_dob=0;
$access_gender=0;
$access_disease=0;
$access_loc=0;
$access_email=0;
$access_web=0;
$access_iam=0;
$access_ilike=0;
$access_exp=0;
$access_photos=0;
$access_friends=0;
$access_blog=0;
$access_msg=0;


$frnds_count=0;
$usealias=0;
$alias="";


if(!isset($_SESSION["userid"]) || $_SESSION["userid"] =="")
{	header("location: index.php");   }
else
{

	$viewerid=$_SESSION["userid"];
	$userid=$_GET["userid"];
		
	$albumid=$_GET["albumid"];
	$albumname=$_GET["albumname"];
	
/*	if($viewerid==$userid)
		echo "<script>location.href='myprofile.php' </script>";*/
	
	/******************************************* LOAD USER INFO & PRIVACY SETTINGS *****************************************************************/
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
				isactive,
				access_name,
				access_pic,
				access_dob,
				access_gender,
				access_disease,
				access_loc,
				access_email,
				access_web,
				access_iam,
				access_ilike,
				access_exp,
				access_photos,
				access_friends,
				access_blog,
				access_msg,
				alias,
				usealias
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
		
		$access_name=$row["access_name"];
		$access_pic=$row["access_pic"];
		$access_dob=$row["access_dob"];
		$access_gender=$row["access_gender"];
		$access_disease=$row["access_disease"];
		$access_loc=$row["access_loc"];
		$access_email=$row["access_email"];
		$access_web=$row["access_web"];
		$access_iam=$row["access_iam"];
		$access_ilike=$row["access_ilike"];
		$access_exp=$row["access_exp"];
		$access_photos=$row["access_photos"];
		$access_friends=$row["access_friends"];
		$access_blog=$row["access_blog"];
		$access_msg=$row["access_msg"];
		
		
		$usealias=$row["usealias"];
		$alias=$row["alias"];
	}
	
	
	/******************************************* CHECK FRIENDSHIP STATUS *****************************************************************/
	$isfriend=false;
	$ispendingfreind=false;
	$queryfrnd="select 
			friendshipid,	friendshipstatus 			
			from tblfriends
			where userid=".$userid." and friendwith=".$viewerid." and friendshipstatus <> 3";//." and friendshipstatus = 2";
			
	$resultfrnd=mysql_query($queryfrnd);
	if($resultfrnd)
	{
		$num=mysql_num_rows($resultfrnd);
		if($num>0)
		{
			$row=mysql_fetch_array($resultfrnd);
			if($row["friendshipstatus"]=="2")
				$isfriend=true;
			elseif($row["friendshipstatus"]=="1")
				$ispendingfreind=true;
		}
	}

	/******************************************* LOAD ALBUM PHOTOS **********************************************************/
	
	$query_photos="
		select photoid,dateadded,description,picname,spicname,gvpicname,epicname from tblphotos where albumid = ".$albumid;


	$sql=mysql_query($query_photos);
	$message="";
	$rowcount=0;
	$count=mysql_num_rows($sql);



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
	
		$frnds_count=$row_frnds_count[0];//." ".$userid;
	}
	
}
?>	

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
                        	<!--<img src="images/user-img.jpg" />-->
                            <?php if($access_pic=="2" || ($access_pic=="3" && $isfriend==true))
					{
					?>
                            <img src="profilepics/<?php echo $profilepic; ?>" width="63" height="59" border="0" />
<?php
					}
					else
					{
					?>
                            <img src="profilepics/empty_profile.jpg" style="background-color:#FFFFFF" width="63" height="59" border="0">
                                <?php
					}
					?>
                            </div>
                        <div class="user_data">
                        	<div class="user_name bluetitle size20 bold" />
                            <?php //if($usealias=="1") 
									//		echo $alias;
										//  else 
											echo $fname." ".$lname; ?>
                        </div>
                         	<div class="ul_msg">
                            <ul>

                                <?php if($access_msg=="2" || ($access_msg=="3" && $isfriend==true))
													{
													?>
                            	<li><a href="msgmember.php?recieverid=<?php echo $userid; ?>&senderid=<?php echo $viewerid; ?>">Send a Message</a></li>


                                <?php 
                                }
                                if($access_blog=="2" || ($access_blog=="3" && $isfriend==true))
													{
													?>
                                <li><a href="blog.php?userid=<?php echo $userid; ?>">View Blog</a></li>
                                <?php
													}
													?>
                            </ul>
                            </div> 
                        </div>
                    </div>
                    <div class="profile_links">
                    	<div class="title_txt">
                        	<div class="plusimg"><img src="images/plus_icon.jpg" /></div>
                            <div class="txttitle whitetitle size12 bold">Profile</div>
                        </div>
                        <div class="txt_links">
                        	<ul>
                                <li>
                                    <a href="viewprofile.php?userid=<?php echo $userid; ?>">View Profile</a>
                                </li>
                                <li>
                                    <a href="userphotos.php?userid=<?php echo $userid; ?>">View Photos</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                <?php if($access_friends=="2" || ($access_friends=="3" && $isfriend==true))
				{
				?>
                    <div class="profile_links">
                    	<div class="title_txt">
                        	<div class="plusimg"><img src="images/plus_icon.jpg" /></div>
                            <div class="txttitle whitetitle size12 bold">Contacts</div>
                        </div>
                        <div class="txt_links">
                        	<ul>
                            	<li><a href="viewfriends.php?userid=<?php echo $userid; ?>">View Friends (<?php echo $frnds_count;?>)</a></li>
                            </ul>
                        </div>
                    </div>
                <?php 
                }
				?>
                </div>
            </div>
          <div class="body_main">
                <div class="body_menu whitetitle">
                    <a href="myprofile.php">Home</a>&nbsp;&nbsp;<span class="size9">>> </span> <a href="viewprofile.php?userid=<?php echo $userid; ?>">
                            <?php //echo $alias; if($usealias=="1") 
										//	echo $alias;
										//  else 
											echo $fname." ".$lname; ?>
                        </a>&nbsp;&nbsp;<span class="size9">>></span>&nbsp;&nbsp;<span class="bluetitle size11">Interest Wall</span>
                </div>

                <div class="body">
                    <div class="main_link">
                        <div class="inbox_title">
                       
                            <div class="f_left bluetitle size30 bold" style="padding-top:5px; width:auto;">
                                <?php //if($usealias=="1") 
										//	echo $alias;
										 // else 
											echo $fname." ".$lname; ?>
                                <?php $basicvis = false; ?>'s Blog
                            </div>
                            
                        </div>

                        <div class="email_table">
					<div name="newboxes" id="photos" >								
						<div>
						<div style="display:inline;">
	
							<div class="msgslinksection">
								<div align="right" style="margin : 0 10px 0 0;float:right;"></div>
							</div>
							<div style="clear:both;"></div>
						</div>
						<div style="clear:both;"></div>
						
					
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
										select photoid,dateadded,description,picname,spicname from tblphotos where albumid = ".$albumid;
									
									
						echo "<li>
							<a class='thumb' name='leaf' href='albumphotos/".$row['gvpicname']."'>
								<img src='albumphotos/".$row['spicname']."' alt='".$row['description']."' />
							</a>
							<div class='caption'>
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
					
					
					
					
						<div name="newboxes" id="inbox" style="display:none;" >							
						<div id="msg-list">
							
							<div style='display:inline;float:left;'>
								<div class='form-text'>Send a Message</div>
								<div style='clear:both;'></div>
							</div>
							<!--<div style='clear:both;'></div> -->
							
							<form action="sendmsg.php" method="post" >
							
							<input type="hidden" id="msgid" name="msgid" value="<?php echo $_GET["msgid"]; ?>" />
							<input type="hidden" id="mode" name="mode" value="<?php echo $_GET["mode"]; ?>" />
							<input type="hidden" id="recieverid" name="recieverid" value="<?php echo $_GET["userid"]; ?>" />
							<input type="hidden" id="senderid" name="senderid" value="<?php echo $_SESSION["userid"]; ?>" />
							
							
							<div style='display:inline;float:left;'>
								<div><textarea class="form-text-area"  id="msg" name="msg" rows="4" cols="600" style="width:680px;"></textarea></div>
								<div style='clear:both;'></div>
							</div>
							<div style='clear:both;'></div>					
							<div><input id="submit-subscirbe" type="submit" value="Send" /></div>
						
							</form>
						
			    </div>
                    </div>
                </div>
                
                
          </div>
        </div>	  
          </div>
        </div>	
<?php include "footer.php"; ?>
	
