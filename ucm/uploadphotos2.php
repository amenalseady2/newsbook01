<img src="images/progress.gif" width="100" height="100" />
<?php	session_start();
include "connection.php";

define('BASE_DIR',$_SERVER['DOCUMENT_ROOT'].'/albumphotos');
define('BASE_DIR_DLOAD',$_SERVER['DOCUMENT_ROOT'].'/albumphotos');

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



function thumbnail_small($image_path, $size = '75x75') {
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
  $thumb = dirname($image_path).'/s-'.basename($image_path);
  exec('convert '. escapeshellarg($image_path).' -crop ' . $crop_size .' -thumbnail '.$size.' '. escapeshellarg($thumb));
 
  return $thumb;
}


function thumbnail_gview($image_path, $size = '500x500') {
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
  $thumb = dirname($image_path).'/gv-'.basename($image_path);
  exec('convert '. escapeshellarg($image_path).' -crop ' . $crop_size .' -thumbnail '.$size.' '. escapeshellarg($thumb));
 
  return $thumb;
}
	

function thumbnail_edit($image_path, $size = '180x120') {
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
  $thumb = dirname($image_path).'/e-'.basename($image_path);
  exec('convert '. escapeshellarg($image_path).' -crop ' . $crop_size .' -thumbnail '.$size.' '. escapeshellarg($thumb));
 
  return $thumb;
}
		


if($_SERVER['REQUEST_METHOD']=='POST')
{	
	
	$albumid=$_POST["albumid"];
	$albumname=$_POST["albumname"];
	$isnew=$_POST["isnew"];
	$userid=$_SESSION["userid"];	
	$dateadded=date("Y-m-d H:i:s");
	$description='';
	
	$pic1='';
	$pic2='';
	$pic3='';
	$pic4='';
	$pic5='';
	$pic6='';	
	$pic7='';
	$pic8='';
	$pic9='';
	$pic10='';					 
	$pic1s='';
	$pic2s='';
	$pic3s='';
	$pic4s='';
	$pic5s='';
	
	$thumb_small="";
	$thumb_galleryview="";
	$thumb_edit="";
	
	$extension_1='';
	$extension_2='';
	$extension_3='';
	$extension_4='';
	$extension_5='';

	$status1 = true;
	$status2 = true;
	$status3 = true;
	$status4 = true;
	$status5 = true;
	$status6 = true;
	$status7 = true;
	$status8 = true;
	$status9 = true;
	$status10 = true;
	
	if( $_FILES["pic1"]["error"]==0 )
	{
		$file=basename($_FILES["pic1"]["name"]);			
		//echo("pic1 is :  ".$_FILES['pic1']['size']);
		$e = explode(".", $_FILES['pic1']['name']);
		$extension_1 = $e[count($e)-1]; 
		$fileName=$e[count($e)-2]."_".$_SESSION["userid"]."_".date("Y").date("m").date("d").time().".".$extension_1;			
		//echo "<br/> pic1 name: ".$fileName; //exit;			
		$pic1=$fileName;
		$thumb_small="s-".$pic1;
		$thumb_galleryview="gv-".$pic1;
		$thumb_edit="e-".$pic1;            
	}
	/*
	if( $_FILES["pic2"]["error"]==0 )
	{	
		$file=basename($_FILES["pic2"]["name"]);			
		//echo("pic2 is :  ".$_FILES['pic2']['size']);
		$e = explode(".", $_FILES['pic2']['name']);
		$extension_2 = $e[count($e)-1]; 
		$fileName=$e[count($e)-2]."_".$_SESSION["userid"]."_".date("Y").date("m").date("d").time().".".$extension_2;			
		//echo "<br/> pic2 name: ".$fileName; //exit;			
		$pic2=$fileName;
		$pic2s="s-".$pic2;   
	}
	
	if( $_FILES["pic3"]["error"]==0 )
	{		
		$file=basename($_FILES["pic3"]["name"]);			
		//echo("pic3 is :  ".$_FILES['pic3']['size']);
		$e = explode(".", $_FILES['pic3']['name']);
		$extension_3 = $e[count($e)-1]; 
		$fileName=$e[count($e)-2]."_".$_SESSION["userid"]."_".date("Y").date("m").date("d").time().".".$extension_3;			
		//echo "<br/> pic3 name: ".$fileName; //exit;			
		$pic3=$fileName;
		$pic3s="s-".$pic3;   
	}
	
	if( $_FILES["pic4"]["error"]==0 )
	{		
		$file=basename($_FILES["pic4"]["name"]);			
		//echo("pic4 is :  ".$_FILES['pic4']['size']);
		$e = explode(".", $_FILES['pic4']['name']);
		$extension_4 = $e[count($e)-1]; 
		$fileName=$e[count($e)-2]."_".$_SESSION["userid"]."_".date("Y").date("m").date("d").time().".".$extension_4;			
		//echo "<br/> pic3 name: ".$fileName; //exit;			
		$pic4=$fileName;
		$pic4s="s-".$pic4;   
	}
	
	if( $_FILES["pic5"]["error"]==0 )
	{		
		$file=basename($_FILES["pic5"]["name"]);			
		//echo("pic5 is :  ".$_FILES['pic5']['size']);
		$e = explode(".", $_FILES['pic5']['name']);
		$extension_5 = $e[count($e)-1]; 
		$fileName=$e[count($e)-2]."_".$_SESSION["userid"]."_".date("Y").date("m").date("d").time().".".$extension_5;			
		//echo "<br/> pic5 name: ".$fileName; //exit;			
		$pic5=$fileName;
		$pic5s="s-".$pic5;   
	}	*/
		
	if ($pic1!="")
	{	
		if (array_key_exists($extension_1, $allowed_ext)) 
		{
		    if(move_uploaded_file($_FILES["pic1"]["tmp_name"],BASE_DIR_DLOAD."/".$pic1))
			{
				try
				{
					// for small
					$thumb1 = thumbnail_small(BASE_DIR_DLOAD."/".$pic1,'75x75');
					move_uploaded_file($thumb1,BASE_DIR_DLOAD."/".$thumb_small);
					//echo "executed<img src='".$thumb_listing."'>".$thumb1."fuck".$thumb_listing;
					
					// for gallery
					$thumb2 = thumbnail_gview(BASE_DIR_DLOAD."/".$pic1,'500x500');
					move_uploaded_file($thumb2,BASE_DIR_DLOAD."/".$thumb_galleryview);
					//echo "executed<img src='".$thumb_profile."'>".$thumb_profile;
					
					// for edit
					$thumb2 = thumbnail_edit(BASE_DIR_DLOAD."/".$pic1,'180x120');
					move_uploaded_file($thumb2,BASE_DIR_DLOAD."/".$thumb_edit);
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
			    echo "<script>location.href='uploadphotos.php?albumid=".$albumid."&albumname=".$albumname."&msg=Picture 1 uploading failed.' </script>";
			}
		}
		else
			echo "<script>location.href='uploadphotos.php?albumid=".$albumid."&albumname=".$albumname."&msg=Picture 1's file type is not allowed.' </script>";
			
		try
		{	
			$query="insert into tblphotos(
				albumid,
				description,
				picname,
				spicname,
				gvpicname,
				epicname,
				dateadded,
				isdeleted,
				userid) 
				values
				('".$albumid."','".$description."','".$pic1."','".$thumb_small."','".$thumb_galleryview."','".$thumb_edit."','".$dateadded."',0,".$userid.")";
															
				if(!mysql_query($query))
				{
					$status1 = false;
					echo mysql_error();
				}
				else
				{
					$albumlink = "<a href='viewuseralbum.php?albumid=".$albumid."&albumname=".$albumname."&userid=".$userid."'>photo</a>";
					generate_notif_and_friend_activity($userid,$albumlink );
					
					
					
					
					
					$photoid=mysql_insert_id();
					if($isnew=="1")
					{
						$queryalbum="update tblalbums set coverphotoid=".$photoid." where albumid=".$albumid;
																	
						if(!mysql_query($queryalbum))
						{
							$status1 = false;
							echo mysql_error();
						}
					}
				}
		}
		catch(exception $ex)
		{
			$status1 = false;
			echo $ex;
		}	
	}
		
		
	

	if( $_FILES["pic2"]["error"]==0 )
	{
		$file=basename($_FILES["pic2"]["name"]);			
		//echo("pic1 is :  ".$_FILES['pic2']['size']);
		$e = explode(".", $_FILES['pic2']['name']);
		$extension_1 = $e[count($e)-1]; 
		$fileName=$e[count($e)-2]."_".$_SESSION["userid"]."_".date("Y").date("m").date("d").time().".".$extension_1;			
		//echo "<br/> pic1 name: ".$fileName; //exit;			
		$pic2=$fileName;
		$thumb_small="s-".$pic2;
		$thumb_galleryview="gv-".$pic2;
		$thumb_edit="e-".$pic2;            
	}
		
	if ($pic2!="")
	{	
		if (array_key_exists($extension_1, $allowed_ext)) 
		{
		    if(move_uploaded_file($_FILES["pic2"]["tmp_name"],BASE_DIR_DLOAD."/".$pic2))
			{
				try
				{
					// for small
					$thumb1 = thumbnail_small(BASE_DIR_DLOAD."/".$pic2,'75x75');
					move_uploaded_file($thumb1,BASE_DIR_DLOAD."/".$thumb_small);
					//echo "executed<img src='".$thumb_listing."'>".$thumb1."fuck".$thumb_listing;
					
					// for gallery
					$thumb2 = thumbnail_gview(BASE_DIR_DLOAD."/".$pic2,'500x500');
					move_uploaded_file($thumb2,BASE_DIR_DLOAD."/".$thumb_galleryview);
					//echo "executed<img src='".$thumb_profile."'>".$thumb_profile;
					
					// for edit
					$thumb2 = thumbnail_edit(BASE_DIR_DLOAD."/".$pic2,'180x120');
					move_uploaded_file($thumb2,BASE_DIR_DLOAD."/".$thumb_edit);
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
			    echo "<script>location.href='uploadphotos.php?albumid=".$albumid."&albumname=".$albumname."&msg=Picture 2 uploading failed.' </script>";
			}
		}
		else
			echo "<script>location.href='uploadphotos.php?albumid=".$albumid."&albumname=".$albumname."&msg=Picture 2's file type is not allowed.' </script>";
				
		try
		{	
			$albumlink = "<a href='viewuseralbum.php?albumid=".$albumid."&albumname=".$albumname."&userid=".$userid."'>photo</a>";
			generate_notif_and_friend_activity($userid,$albumlink );
					
			$query="insert into tblphotos(
				albumid,
				description,
				picname,
				spicname,
				gvpicname,
				epicname,
				dateadded,
				isdeleted,
				userid) 
				values
				('".$albumid."','".$description."','".$pic2."','".$thumb_small."','".$thumb_galleryview."','".$thumb_edit."','".$dateadded."',0,".$userid.")";
															
				if(!mysql_query($query))
				{
					$status2 = false;
					echo mysql_error();
				}
		}
		catch(exception $ex)
		{
			$status2 = false;
			echo $ex;
		}	
	}
		
		
	

	if( $_FILES["pic3"]["error"]==0 )
	{
		$file=basename($_FILES["pic33"]["name"]);			
		//echo("pic1 is :  ".$_FILES['pic1']['size']);
		$e = explode(".", $_FILES['pic3']['name']);
		$extension_1 = $e[count($e)-1]; 
		$fileName=$e[count($e)-2]."_".$_SESSION["userid"]."_".date("Y").date("m").date("d").time().".".$extension_1;			
		//echo "<br/> pic1 name: ".$fileName; //exit;			
		$pic3=$fileName;
		$thumb_small="s-".$pic3;
		$thumb_galleryview="gv-".$pic3;
		$thumb_edit="e-".$pic3;            
	}
		
	if ($pic3!="")
	{	
		if (array_key_exists($extension_1, $allowed_ext)) 
		{
		    if(move_uploaded_file($_FILES["pic3"]["tmp_name"],BASE_DIR_DLOAD."/".$pic3))
			{
				try
				{
					// for small
					$thumb1 = thumbnail_small(BASE_DIR_DLOAD."/".$pic3,'75x75');
					move_uploaded_file($thumb1,BASE_DIR_DLOAD."/".$thumb_small);
					//echo "executed<img src='".$thumb_listing."'>".$thumb1."fuck".$thumb_listing;
					
					// for gallery
					$thumb2 = thumbnail_gview(BASE_DIR_DLOAD."/".$pic3,'500x500');
					move_uploaded_file($thumb2,BASE_DIR_DLOAD."/".$thumb_galleryview);
					//echo "executed<img src='".$thumb_profile."'>".$thumb_profile;
					
					// for edit
					$thumb2 = thumbnail_edit(BASE_DIR_DLOAD."/".$pic3,'180x120');
					move_uploaded_file($thumb2,BASE_DIR_DLOAD."/".$thumb_edit);
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
			    echo "<script>location.href='uploadphotos.php?albumid=".$albumid."&albumname=".$albumname."&msg=Picture 3 uploading failed.' </script>";
			}
		}
		else
			echo "<script>location.href='uploadphotos.php?albumid=".$albumid."&albumname=".$albumname."&msg=Picture 3's file type is not allowed.' </script>";
				
		try
		{	
			$albumlink = "<a href='viewuseralbum.php?albumid=".$albumid."&albumname=".$albumname."&userid=".$userid."'>photo</a>";
			generate_notif_and_friend_activity($userid,$albumlink );
			
			$query="insert into tblphotos(
				albumid,
				description,
				picname,
				spicname,
				gvpicname,
				epicname,
				dateadded,
				isdeleted,
				userid) 
				values
				('".$albumid."','".$description."','".$pic3."','".$thumb_small."','".$thumb_galleryview."','".$thumb_edit."','".$dateadded."',0,".$userid.")";
															
				if(!mysql_query($query))
				{
					$status3 = false;
					echo mysql_error();
				}
		}
		catch(exception $ex)
		{
			$status3 = false;
			echo $ex;
		}	
	}
		
	

	if( $_FILES["pic4"]["error"]==0 )
	{
		$file=basename($_FILES["pic4"]["name"]);			
		//echo("pic1 is :  ".$_FILES['pic4']['size']);
		$e = explode(".", $_FILES['pic4']['name']);
		$extension_1 = $e[count($e)-1]; 
		$fileName=$e[count($e)-2]."_".$_SESSION["userid"]."_".date("Y").date("m").date("d").time().".".$extension_1;			
		//echo "<br/> pic1 name: ".$fileName; //exit;			
		$pic4=$fileName;
		$thumb_small="s-".$pic4;
		$thumb_galleryview="gv-".$pic4;
		$thumb_edit="e-".$pic4;            
	}
		
	if ($pic4!="")
	{	
		if (array_key_exists($extension_1, $allowed_ext)) 
		{
		    if(move_uploaded_file($_FILES["pic4"]["tmp_name"],BASE_DIR_DLOAD."/".$pic4))
			{
				try
				{
					// for small
					$thumb1 = thumbnail_small(BASE_DIR_DLOAD."/".$pic4,'75x75');
					move_uploaded_file($thumb1,BASE_DIR_DLOAD."/".$thumb_small);
					//echo "executed<img src='".$thumb_listing."'>".$thumb1."fuck".$thumb_listing;
					
					// for gallery
					$thumb2 = thumbnail_gview(BASE_DIR_DLOAD."/".$pic4,'500x500');
					move_uploaded_file($thumb2,BASE_DIR_DLOAD."/".$thumb_galleryview);
					//echo "executed<img src='".$thumb_profile."'>".$thumb_profile;
					
					// for edit
					$thumb2 = thumbnail_edit(BASE_DIR_DLOAD."/".$pic4,'180x120');
					move_uploaded_file($thumb2,BASE_DIR_DLOAD."/".$thumb_edit);
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
			    echo "<script>location.href='uploadphotos.php?albumid=".$albumid."&albumname=".$albumname."&msg=Picture 4 uploading failed.' </script>";
			}
		}
		else
			echo "<script>location.href='uploadphotos.php?albumid=".$albumid."&albumname=".$albumname."&msg=Picture 4's file type is not allowed.' </script>";
			
		try
		{	
			$albumlink = "<a href='viewuseralbum.php?albumid=".$albumid."&albumname=".$albumname."&userid=".$userid."'>photo</a>";
			generate_notif_and_friend_activity($userid,$albumlink );
			
			$query="insert into tblphotos(
				albumid,
				description,
				picname,
				spicname,
				gvpicname,
				epicname,
				dateadded,
				isdeleted,
				userid) 
				values
				('".$albumid."','".$description."','".$pic4."','".$thumb_small."','".$thumb_galleryview."','".$thumb_edit."','".$dateadded."',0,".$userid.")";
															
				if(!mysql_query($query))
				{
					$status4 = false;
					echo mysql_error();
				}
		}
		catch(exception $ex)
		{
			$status4 = false;
			echo $ex;
		}	
	}
		
	

	if( $_FILES["pic5"]["error"]==0 )
	{
		$file=basename($_FILES["pic5"]["name"]);			
		//echo("pic1 is :  ".$_FILES['pic1']['size']);
		$e = explode(".", $_FILES['pic5']['name']);
		$extension_1 = $e[count($e)-1]; 
		$fileName=$e[count($e)-2]."_".$_SESSION["userid"]."_".date("Y").date("m").date("d").time().".".$extension_1;			
		//echo "<br/> pic1 name: ".$fileName; //exit;			
		$pic5=$fileName;
		$thumb_small="s-".$pic5;
		$thumb_galleryview="gv-".$pic5;
		$thumb_edit="e-".$pic5;            
	}	
		
	if ($pic5!="")
	{	
		if (array_key_exists($extension_1, $allowed_ext)) 
		{
		    if(move_uploaded_file($_FILES["pic5"]["tmp_name"],BASE_DIR_DLOAD."/".$pic5))
			{
				try
				{
					// for small
					$thumb1 = thumbnail_small(BASE_DIR_DLOAD."/".$pic5,'75x75');
					move_uploaded_file($thumb1,BASE_DIR_DLOAD."/".$thumb_small);
					//echo "executed<img src='".$thumb_listing."'>".$thumb1."fuck".$thumb_listing;
					
					// for gallery
					$thumb2 = thumbnail_gview(BASE_DIR_DLOAD."/".$pic5,'500x500');
					move_uploaded_file($thumb2,BASE_DIR_DLOAD."/".$thumb_galleryview);
					//echo "executed<img src='".$thumb_profile."'>".$thumb_profile;
					
					// for edit
					$thumb2 = thumbnail_edit(BASE_DIR_DLOAD."/".$pic5,'180x120');
					move_uploaded_file($thumb2,BASE_DIR_DLOAD."/".$thumb_edit);
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
			    echo "<script>location.href='uploadphotos.php?albumid=".$albumid."&albumname=".$albumname."&msg=Picture 5 uploading failed.' </script>";
			}
		}
		else
			echo "<script>location.href='uploadphotos.php?albumid=".$albumid."&albumname=".$albumname."&msg=Picture 5's file type is not allowed.' </script>";
				
		try
		{	
			$albumlink = "<a href='viewuseralbum.php?albumid=".$albumid."&albumname=".$albumname."&userid=".$userid."'>photo</a>";
			generate_notif_and_friend_activity($userid,$albumlink );
			
			$query="insert into tblphotos(
				albumid,
				description,
				picname,
				spicname,
				gvpicname,
				epicname,
				dateadded,
				isdeleted,
				userid) 
				values
				('".$albumid."','".$description."','".$pic5."','".$thumb_small."','".$thumb_galleryview."','".$thumb_edit."','".$dateadded."',0,".$userid.")";
															
				if(!mysql_query($query))
				{
					$status5 = false;
					echo mysql_error();
				}
		}
		catch(exception $ex)
		{
			$status5 = false;
			echo $ex;
		}	
	}

	

	if( $_FILES["pic6"]["error"]==0 )
	{
		$file=basename($_FILES["pic6"]["name"]);			
		//echo("pic1 is :  ".$_FILES['pic6']['size']);
		$e = explode(".", $_FILES['pic6']['name']);
		$extension_1 = $e[count($e)-1]; 
		$fileName=$e[count($e)-2]."_".$_SESSION["userid"]."_".date("Y").date("m").date("d").time().".".$extension_1;			
		//echo "<br/> pic1 name: ".$fileName; //exit;			
		$pic6=$fileName;
		$thumb_small="s-".$pic6;
		$thumb_galleryview="gv-".$pic6;
		$thumb_edit="e-".$pic6;            
	}
		
	if ($pic6!="")
	{	
		if (array_key_exists($extension_1, $allowed_ext)) 
		{
		    if(move_uploaded_file($_FILES["pic6"]["tmp_name"],BASE_DIR_DLOAD."/".$pic6))
			{
				try
				{
					// for small
					$thumb1 = thumbnail_small(BASE_DIR_DLOAD."/".$pic6,'75x75');
					move_uploaded_file($thumb1,BASE_DIR_DLOAD."/".$thumb_small);
					//echo "executed<img src='".$thumb_listing."'>".$thumb1."fuck".$thumb_listing;
					
					// for gallery
					$thumb2 = thumbnail_gview(BASE_DIR_DLOAD."/".$pic6,'500x500');
					move_uploaded_file($thumb2,BASE_DIR_DLOAD."/".$thumb_galleryview);
					//echo "executed<img src='".$thumb_profile."'>".$thumb_profile;
					
					// for edit
					$thumb2 = thumbnail_edit(BASE_DIR_DLOAD."/".$pic6,'180x120');
					move_uploaded_file($thumb2,BASE_DIR_DLOAD."/".$thumb_edit);
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
			    echo "<script>location.href='uploadphotos.php?albumid=".$albumid."&albumname=".$albumname."&msg=Picture 6 uploading failed.' </script>";
			}
		}
		else
			echo "<script>location.href='uploadphotos.php?albumid=".$albumid."&albumname=".$albumname."&msg=Picture 6's file type is not allowed.' </script>";
			
		try
		{	
			$albumlink = "<a href='viewuseralbum.php?albumid=".$albumid."&albumname=".$albumname."&userid=".$userid."'>photo</a>";
			generate_notif_and_friend_activity($userid,$albumlink );
			
			$query="insert into tblphotos(
				albumid,
				description,
				picname,
				spicname,
				gvpicname,
				epicname,
				dateadded,
				isdeleted,
				userid) 
				values
				('".$albumid."','".$description."','".$pic6."','".$thumb_small."','".$thumb_galleryview."','".$thumb_edit."','".$dateadded."',0,".$userid.")";
															
				if(!mysql_query($query))
				{
					$status6 = false;
					echo mysql_error();
				}
		}
		catch(exception $ex)
		{
			$status6 = false;
			echo $ex;
		}	
	}
		
	

	if( $_FILES["pic7"]["error"]==0 )
	{
		$file=basename($_FILES["pic7"]["name"]);			
		//echo("pic1 is :  ".$_FILES['pic7']['size']);
		$e = explode(".", $_FILES['pic7']['name']);
		$extension_1 = $e[count($e)-1]; 
		$fileName=$e[count($e)-2]."_".$_SESSION["userid"]."_".date("Y").date("m").date("d").time().".".$extension_1;			
		//echo "<br/> pic1 name: ".$fileName; //exit;			
		$pic7=$fileName;
		$thumb_small="s-".$pic7;
		$thumb_galleryview="gv-".$pic7;
		$thumb_edit="e-".$pic7;            
	}
		
	if ($pic7!="")
	{	
		if (array_key_exists($extension_1, $allowed_ext)) 
		{
		    if(move_uploaded_file($_FILES["pic7"]["tmp_name"],BASE_DIR_DLOAD."/".$pic7))
			{
				try
				{
					// for small
					$thumb1 = thumbnail_small(BASE_DIR_DLOAD."/".$pic7,'75x75');
					move_uploaded_file($thumb1,BASE_DIR_DLOAD."/".$thumb_small);
					//echo "executed<img src='".$thumb_listing."'>".$thumb1."fuck".$thumb_listing;
					
					// for gallery
					$thumb2 = thumbnail_gview(BASE_DIR_DLOAD."/".$pic7,'500x500');
					move_uploaded_file($thumb2,BASE_DIR_DLOAD."/".$thumb_galleryview);
					//echo "executed<img src='".$thumb_profile."'>".$thumb_profile;
					
					// for edit
					$thumb2 = thumbnail_edit(BASE_DIR_DLOAD."/".$pic7,'180x120');
					move_uploaded_file($thumb2,BASE_DIR_DLOAD."/".$thumb_edit);
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
			    echo "<script>location.href='uploadphotos.php?albumid=".$albumid."&albumname=".$albumname."&msg=Picture 7 uploading failed.' </script>";
			}
		}
		else
			echo "<script>location.href='uploadphotos.php?albumid=".$albumid."&albumname=".$albumname."&msg=Picture 7's file type is not allowed.' </script>";
			
		try
		{	$albumlink = "<a href='viewuseralbum.php?albumid=".$albumid."&albumname=".$albumname."&userid=".$userid."'>photo</a>";
			generate_notif_and_friend_activity($userid,$albumlink );
			
			$query="insert into tblphotos(
				albumid,
				description,
				picname,
				spicname,
				gvpicname,
				epicname,
				dateadded,
				isdeleted,
				userid) 
				values
				('".$albumid."','".$description."','".$pic7."','".$thumb_small."','".$thumb_galleryview."','".$thumb_edit."','".$dateadded."',0,".$userid.")";
															
				if(!mysql_query($query))
				{
					$status7 = false;
					echo mysql_error();
				}
		}
		catch(exception $ex)
		{
			$status7 = false;
			echo $ex;
		}	
	}
		
	

	if( $_FILES["pic8"]["error"]==0 )
	{
		$file=basename($_FILES["pic8"]["name"]);			
		//echo("pic1 is :  ".$_FILES['pic8']['size']);
		$e = explode(".", $_FILES['pic8']['name']);
		$extension_1 = $e[count($e)-1]; 
		$fileName=$e[count($e)-2]."_".$_SESSION["userid"]."_".date("Y").date("m").date("d").time().".".$extension_1;			
		//echo "<br/> pic1 name: ".$fileName; //exit;			
		$pic8=$fileName;
		$thumb_small="s-".$pic8;
		$thumb_galleryview="gv-".$pic8;
		$thumb_edit="e-".$pic8;            
	}
		
	if ($pic8!="")
	{	
		if (array_key_exists($extension_1, $allowed_ext)) 
		{
		    if(move_uploaded_file($_FILES["pic8"]["tmp_name"],BASE_DIR_DLOAD."/".$pic8))
			{
				try
				{
					// for small
					$thumb1 = thumbnail_small(BASE_DIR_DLOAD."/".$pic8,'85x85');
					move_uploaded_file($thumb1,BASE_DIR_DLOAD."/".$thumb_small);
					//echo "executed<img src='".$thumb_listing."'>".$thumb1."fuck".$thumb_listing;
					
					// for gallery
					$thumb2 = thumbnail_gview(BASE_DIR_DLOAD."/".$pic8,'500x500');
					move_uploaded_file($thumb2,BASE_DIR_DLOAD."/".$thumb_galleryview);
					//echo "executed<img src='".$thumb_profile."'>".$thumb_profile;
					
					// for edit
					$thumb2 = thumbnail_edit(BASE_DIR_DLOAD."/".$pic8,'180x120');
					move_uploaded_file($thumb2,BASE_DIR_DLOAD."/".$thumb_edit);
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
			    echo "<script>location.href='uploadphotos.php?albumid=".$albumid."&albumname=".$albumname."&msg=Picture 8 uploading failed.' </script>";
			}
		}
		else
			echo "<script>location.href='uploadphotos.php?albumid=".$albumid."&albumname=".$albumname."&msg=Picture 8's file type is not allowed.' </script>";
			
		try
		{	
			$albumlink = "<a href='viewuseralbum.php?albumid=".$albumid."&albumname=".$albumname."&userid=".$userid."'>photo</a>";
			generate_notif_and_friend_activity($userid,$albumlink );
			
			$query="insert into tblphotos(
				albumid,
				description,
				picname,
				spicname,
				gvpicname,
				epicname,
				dateadded,
				isdeleted,
				userid) 
				values
				('".$albumid."','".$description."','".$pic8."','".$thumb_small."','".$thumb_galleryview."','".$thumb_edit."','".$dateadded."',0,".$userid.")";
															
				if(!mysql_query($query))
				{
					$status8 = false;
					echo mysql_error();
				}
		}
		catch(exception $ex)
		{
			$status8 = false;
			echo $ex;
		}	
	}
		
	

	if( $_FILES["pic9"]["error"]==0 )
	{
		$file=basename($_FILES["pic9"]["name"]);			
		//echo("pic1 is :  ".$_FILES['pic9']['size']);
		$e = explode(".", $_FILES['pic9']['name']);
		$extension_1 = $e[count($e)-1]; 
		$fileName=$e[count($e)-2]."_".$_SESSION["userid"]."_".date("Y").date("m").date("d").time().".".$extension_1;			
		//echo "<br/> pic1 name: ".$fileName; //exit;			
		$pic9=$fileName;
		$thumb_small="s-".$pic9;
		$thumb_galleryview="gv-".$pic9;
		$thumb_edit="e-".$pic9;            
	}
		
	if ($pic9!="")
	{	
		if (array_key_exists($extension_1, $allowed_ext)) 
		{
		    if(move_uploaded_file($_FILES["pic9"]["tmp_name"],BASE_DIR_DLOAD."/".$pic9))
			{
				try
				{
					// for small
					$thumb1 = thumbnail_small(BASE_DIR_DLOAD."/".$pic9,'95x95');
					move_uploaded_file($thumb1,BASE_DIR_DLOAD."/".$thumb_small);
					//echo "executed<img src='".$thumb_listing."'>".$thumb1."fuck".$thumb_listing;
					
					// for gallery
					$thumb2 = thumbnail_gview(BASE_DIR_DLOAD."/".$pic9,'500x500');
					move_uploaded_file($thumb2,BASE_DIR_DLOAD."/".$thumb_galleryview);
					//echo "executed<img src='".$thumb_profile."'>".$thumb_profile;
					
					// for edit
					$thumb2 = thumbnail_edit(BASE_DIR_DLOAD."/".$pic9,'190x120');
					move_uploaded_file($thumb2,BASE_DIR_DLOAD."/".$thumb_edit);
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
			    echo "<script>location.href='uploadphotos.php?albumid=".$albumid."&albumname=".$albumname."&msg=Picture 9 uploading failed.' </script>";
			}
		}
		else
			echo "<script>location.href='uploadphotos.php?albumid=".$albumid."&albumname=".$albumname."&msg=Picture 9's file type is not allowed.' </script>";
			
		try
		{	
			$albumlink = "<a href='viewuseralbum.php?albumid=".$albumid."&albumname=".$albumname."&userid=".$userid."'>photo</a>";
			generate_notif_and_friend_activity($userid,$albumlink );
			
			$query="insert into tblphotos(
				albumid,
				description,
				picname,
				spicname,
				gvpicname,
				epicname,
				dateadded,
				isdeleted,
				userid) 
				values
				('".$albumid."','".$description."','".$pic9."','".$thumb_small."','".$thumb_galleryview."','".$thumb_edit."','".$dateadded."',0,".$userid.")";
															
				if(!mysql_query($query))
				{
					$status9 = false;
					echo mysql_error();
				}
		}
		catch(exception $ex)
		{
			$status9 = false;
			echo $ex;
		}	
	}
		
	

	if( $_FILES["pic10"]["error"]==0 )
	{
		$file=basename($_FILES["pic10"]["name"]);			
		//echo("pic1 is :  ".$_FILES['pic10']['size']);
		$e = explode(".", $_FILES['pic10']['name']);
		$extension_1 = $e[count($e)-1]; 
		$fileName=$e[count($e)-2]."_".$_SESSION["userid"]."_".date("Y").date("m").date("d").time().".".$extension_1;			
		//echo "<br/> pic1 name: ".$fileName; //exit;			
		$pic10=$fileName;
		$thumb_small="s-".$pic10;
		$thumb_galleryview="gv-".$pic10;
		$thumb_edit="e-".$pic10;            
	}
		
	if ($pic10!="")
	{	
		if (array_key_exists($extension_1, $allowed_ext)) 
		{
		    if(move_uploaded_file($_FILES["pic10"]["tmp_name"],BASE_DIR_DLOAD."/".$pic10))
			{
				try
				{
					// for small
					$thumb1 = thumbnail_small(BASE_DIR_DLOAD."/".$pic10,'105x105');
					move_uploaded_file($thumb1,BASE_DIR_DLOAD."/".$thumb_small);
					//echo "executed<img src='".$thumb_listing."'>".$thumb1."fuck".$thumb_listing;
					
					// for gallery
					$thumb2 = thumbnail_gview(BASE_DIR_DLOAD."/".$pic10,'500x500');
					move_uploaded_file($thumb2,BASE_DIR_DLOAD."/".$thumb_galleryview);
					//echo "executed<img src='".$thumb_profile."'>".$thumb_profile;
					
					// for edit
					$thumb2 = thumbnail_edit(BASE_DIR_DLOAD."/".$pic10,'1100x120');
					move_uploaded_file($thumb2,BASE_DIR_DLOAD."/".$thumb_edit);
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
			    echo "<script>location.href='uploadphotos.php?albumid=".$albumid."&albumname=".$albumname."&msg=Picture 10 uploading failed.' </script>";
			}
		}
		else
			echo "<script>location.href='uploadphotos.php?albumid=".$albumid."&albumname=".$albumname."&msg=Picture 10's file type is not allowed.' </script>";
			
		try
		{	
			$albumlink = "<a href='viewuseralbum.php?albumid=".$albumid."&albumname=".$albumname."&userid=".$userid."'>photo</a>";
			generate_notif_and_friend_activity($userid,$albumlink );
			
			$query="insert into tblphotos(
				albumid,
				description,
				picname,
				spicname,
				gvpicname,
				epicname,
				dateadded,
				isdeleted,
				userid) 
				values
				('".$albumid."','".$description."','".$pic10."','".$thumb_small."','".$thumb_galleryview."','".$thumb_edit."','".$dateadded."',0,".$userid.")";
															
				if(!mysql_query($query))
				{
					$status10 = false;
					echo mysql_error();
				}
		}
		catch(exception $ex)
		{
			$status10 = false;
			echo $ex;
		}	
	}
		
											
	if($status1 && $status2 && $status3  && $status4  && $status5 && $status6 && $status7 && $status8  && $status9  && $status10)
	{		
		echo "<script>location.href='viewalbum.php?albumid=".$albumid."&albumname=".$albumname."' </script>";
	}
}

?>





<?php 

function generate_notif_and_friend_activity($userid,$albumlink ){
	
				//	make a feed message
				$feed_message = " has posted a " . $albumlink;
				
				//insert into my friends activity page
				$query = sprintf("INSERT INTO `tblfeeds`  (`userid`,`message`)VALUES ('%s', '%s')",
							mysql_real_escape_string($userid),
							mysql_real_escape_string($feed_message));

											
			  	mysql_query($query);
			  	
				$notification_msg = get_name_link($userid) . " has posted a " . $albumlink;

				$query_get_friends_ids=sprintf("select friendwith from tblfriends where userid='%s' and friendshipstatus=2",$userid);
					
				$result_get_friends_ids =mysql_query($query_get_friends_ids);
				while($_row=mysql_fetch_array($result_get_friends_ids))
				{
					$friendid = $_row['friendwith'];
					
					$notification_query = sprintf("insert into `tblnotifications`	( `userid`, `notification_type`, `notification`, `notificationtime` )
						values	(		'%s',		1,		'%s',		now()	)",
						mysql_real_escape_string($friendid),
						mysql_real_escape_string($notification_msg));
						
					mysql_query($notification_query);		
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


