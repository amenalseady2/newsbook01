<?php	session_start();
	include "connection.php";
	define('BASE_DIR_DLOAD',$_SERVER['DOCUMENT_ROOT'].'/profilepics');
	$allowed_ext = array (							
					// images
					'gif' => 'image/gif',
					'png' => 'image/png',
					'jpg' => 'image/jpeg',
					'jpeg' => 'image/jpeg',
					'GIF' => 'image/gif',
					'PNG' => 'image/png',
					'JPG' => 'image/jpeg',
					'JPEG' => 'image/jpeg'
					
				);
	
function thumbnail_listing($image_path, $size = '63x59') {
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
  $thumb = dirname($image_path).'/tl-'.basename($image_path);
  exec('convert '. escapeshellarg($image_path).' -crop ' . $crop_size .' -thumbnail '.$size.' '. escapeshellarg($thumb));
 
  return $thumb;
}


function thumbnail_profile($image_path, $size = '246x300') {
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
  $thumb = dirname($image_path).'/tp-'.basename($image_path);
  exec('convert '. escapeshellarg($image_path).' -crop ' . $crop_size .' -thumbnail '.$size.' '. escapeshellarg($thumb));
 
  return $thumb;
}
				
	if($_SERVER['REQUEST_METHOD']=='POST')
	{
		$userid=$_POST["userid"];
		$profilepic="";
		$thumb_listing="";
		$thumb_profile="";
							
		if( $_FILES["profilepic"]["error"]==0 )
		{		
			$file=basename($_FILES["profilepic"]["name"]);	
			$e = explode(".", $_FILES['profilepic']['name']);
			$extension = $e[count($e)-1]; 
			$fileName=$e[count($e)-2]."_".$_SESSION["userid"]."_".date("Y").date("m").date("d").time().".".$extension;					
			$profilepic=$fileName;
			$thumb_listing="tl-".$profilepic;
			$thumb_profile="tp-".$profilepic;
			//echo $profilepic;
		}
	
		if ($profilepic!="")
		{
			if (array_key_exists($extension, $allowed_ext)) 
			{			
				if(move_uploaded_file($_FILES["profilepic"]["tmp_name"],BASE_DIR_DLOAD."/".$profilepic))
				{
					try
					{
						// for listing thumbnail
						$thumb1 = thumbnail_listing(BASE_DIR_DLOAD."/".$profilepic,'50x50');
						move_uploaded_file($thumb1,BASE_DIR_DLOAD."/tl_".thumb_listing);
						//echo "executed<img src='".$thumb_listing."'>".$thumb1."fuck".$thumb_listing;
						
						// for profile thumbnail
						$thumb2 = thumbnail_profile(BASE_DIR_DLOAD."/".$profilepic,'246x300');
						move_uploaded_file($thumb2,BASE_DIR_DLOAD."/".$thumb_profile);
						//echo "executed<img src='".$thumb_profile."'>".$thumb_profile;
						
						//return;
						
					}
					catch(exception $ex)
					{
						echo $ex;//return;
					}
				}
				else
				{
					echo "<script>location.href='editprofile.php?userid=".$userid."&msg=Profile Picture uploading failed.' </script>";	
				}
				
				try
				{
						
					$query="update tbluser set profilepic='".$profilepic."',thumb_listing='".$thumb_listing."',thumb_profile='".$thumb_profile."' where userid=".$userid;	
					if(mysql_query($query))
					{
						//echo "query executed";
						//success
						$_SESSION["profilepic"]=$thumb_listing;//$profilepic;
					}
					else
					{
						echo mysql_error();
					}	
					
				}
				catch(exception $ex)
				{
					echo $ex;//return;
				}			
			}
			else
				echo "<script>location.href='editprofile.php?userid=".$userid."&msg=Profile Picture type is not allowed.' </script>";
		}
		
		$fname=str_replace("'","''",$_POST["fname"]);
		$fname=str_replace("\"","''",$fname);
		$fname=stripslashes($fname);
		
		$lname=str_replace("'","''",$_POST["lname"]);
		$lname=str_replace("\"","''",$lname);
		$lname=stripslashes($lname);
					
		$email=str_replace("'","''",$_POST["email"]);
		$email=str_replace("\"","''",$email);
		$email=stripslashes($email);
		
		$password=str_replace("'","''",$_POST["pwd"]);
		$password=str_replace("\"","''",$password);
		$password=stripslashes($password);

		$dob=$_POST["dob"];
		
		$date = new DateTime($dob);
		$dob = $date->format('Y-m-d');

		//$_date = DateTime::createFromFormat('d M Y',$dob);
		//$dob = $_date->format('Y-m-d');
		
		 // $datetime = strtotime($dob);
        // $dob = date("y-m-d", $datetime);
		
		$genderid=$_POST["genderid"];
		$usertypeid=$_POST["usertypeid"];
		$diseaseid=$_POST["disease_ids"][0];
		
		$city=str_replace("'","''",$_POST["city"]);
		$city=str_replace("\"","''",$city);
		$city=stripslashes($city);
		
		$countryid=$_POST["countryid"];
		
		$website=str_replace("'","''",$_POST["website"]);
		$website=str_replace("\"","''",$website);
		$website=stripslashes($website);
		
		$iam=str_replace("'","''",$_POST["iam"]);
		$iam=str_replace("\"","''",$iam);
		$iam=stripslashes($iam);
		
		$ilike=str_replace("'","''",$_POST["ilike"]);
		$ilike=str_replace("\"","''",$ilike);
		$ilike=stripslashes($ilike);
		
		$myexperience=str_replace("'","''",$_POST["myexperience"]);
		$myexperience=str_replace("\"","''",$myexperience);
		$myexperience=stripslashes($myexperience);
		
		$usealias = 0;
		
		if(isset($_POST["usealias"]))
		   $usealias=$_POST["usealias"];
		if ($usealias)
			$usealias = 1;
			
		$alias=str_replace("'","''",$_POST["alias"]);
		$alias=str_replace("\"","''",$alias);
		$alias=stripslashes($alias);
		
		$rcvemail4msgs=0;
		if(isset($_POST["rcvemail4msgs"]))
		{
		$rcvemail4msgs=$_POST["rcvemail4msgs"];
		if ($rcvemail4msgs)
			$rcvemail4msgs = 1;
		else
			$rcvemail4msgs = 0;	
		}
		
		$rcvemail4notifications=0;
		if(isset($_POST["rcvemail4notifications"]))
		{
		$rcvemail4notifications=$_POST["rcvemail4notifications"];
		if ($rcvemail4notifications)
			$rcvemail4notifications = 1;
		else
			$rcvemail4notifications = 0;	
		}
		
		if ($password!="")
		{
			try
			{	
				$query="update tbluser
						set
							password='".$password."'
						where 
							userid=".$userid;
				
				if(mysql_query($query))
				{
				
				}
				else
				{
					echo mysql_error();
				}
			}
			catch(exception $ex)
			{
				echo $ex;
			}
		}
						
		try
		{	
			$query="update tbluser
					set
						fname='".$fname."',
						lname='".$lname."',
						dob='".$dob."',
						genderid=".$genderid.",
						usertypeid=".$usertypeid.",
						diseaseid=".$diseaseid.",
						city='".$city."',
						countryid=".$countryid.",
						email='".$email."',
						website='".$website."',
						iam='".$iam."',
						ilike='".$ilike."',
						myexperience='".$myexperience."',
						usealias=".$usealias.",
						alias='".$alias."',
						rcvemail4msgs = ".$rcvemail4msgs.",
						rcvemail4notifications = ".$rcvemail4notifications."
					where 
						userid=".$userid;
		
				if (mysql_query ( $query )) {
					foreach ( $_POST ["disease_ids"] as $disease_id ) {
						if (!exists_already ( $disease_id, $userid )) {

							$query = sprintf ( "
									insert into tblsecondary_interests 
										(
										userid, 
										diseaseid
										)
										values
										( 
										'%s', 
										'%s'
										)", $userid, $disease_id );
							
					mysql_query ( $query );
						}
				}
				
				$_SESSION["fname"] = $fname." ".$lname;
				$_SESSION["email"] = $email;
				$_SESSION["usertypeid"]=$usertypeid;
				//header("location: myprofile.php?msg=Info updated successfully");
				echo "<script>location.href='myprofile.php?msg=User Profile Updated successfully' </script>";
			}
			else
			{
				echo mysql_error();
			}
		}
		catch(exception $ex)
		{
			echo $ex;
		}
	}

function exists_already($diseaseid, $userid) {
	
	$m_query = sprintf ( "select * from tblsecondary_interests where userid='%s' and diseaseid='%s'", $userid, $diseaseid );
	$m_rslt = mysql_query ( $m_query );
	if(mysql_num_rows($m_rslt)>0){
		return true;
	} else 
		return false;
	
}
?>
