<?php include "header.php"; 

if(isset($_GET['msg']))
{ ?>
	<div class="txttitle whitetitle size12 bold"><?php echo $_GET['msg']; ?></div>
<?php 
}
if( (isset($_SESSION["admin"])) and ($_SESSION["admin"] == 5) )
{
	if( ($_SERVER['REQUEST_METHOD']=='POST'))
	{ 
		$email = $_POST["email"];	 
		$query="select * from tbluser where email='".$email."'";
		$result=mysql_query($query);
		$num=mysql_num_rows($result);
		
		if($num > 0)
		{
			//user record exists. Now enable or disable this user as per admin's option
			$row=mysql_fetch_array($result);
			
			$userid = $row['userid'];			
			if(!isset($_POST["ed_option"]))
			{
				echo "<script>location.href='adminpanel.php?msg=Delete Option Not set. Please set the option.' </script>";
				exit;
			}
			else
			$ed_option = $_POST["ed_option"]; 
			
			if($ed_option == 0)
			   $isactive = 0;		//disable option
			else 
			   $isactive = 1;		//enable option
			   
			try
			{	 	
				$query="update tbluser set
							user_previlege = '".$ed_option."',
							isactive = ".$isactive." where userid=".$userid;
				
				if(mysql_query($query))
				{
					/*if($ed_option == 0)
					   //echo "<script>location.href='adminpanel.php?msg=User Status Disabled' </script>";
					   echo "<script>location.href='adminpanel.php?msg=User Deleted' </script>";

					else
					   echo "<script>location.href='adminpanel.php?msg=User Status Enabled' </script>";*/
					   
					//For the time-being,let us delete the user from the tbluser table. This is to avoid to showing up
					//this user in search members option.
					
					$query1="delete from tbluser where userid=".$userid;					 
					
					if(mysql_query($query1))					    
					   echo "<script>location.href='adminpanel.php?msg=User Deleted' </script>";
					else
					   echo "<script>location.href='adminpanel.php?msg=User NOT Deleted' </script>";	
					   
					//Delete this user from other tables.
					$query1="delete from tblalbums where userid=".$userid;
					mysql_query($query1); 					
					
					$query1="delete from tblblogcomments where postedbyuserid = ".$userid;
					mysql_query($query1); 					
 
 					$query1="delete from tblblogposts where postedbyuserid = ".$userid." OR postedonuserid = ".$userid."";
					mysql_query($query1); 					
 
					$query1="delete from tblfriends where userid=".$userid." OR friendwith=".$userid."";
					mysql_query($query1); 					
		
					$query1="delete from tblmsgs where senderid=".$userid." OR recieverid=".$userid."";
					mysql_query($query1); 					

					$query1="delete from tblnotifications where userid=".$userid;
					mysql_query($query1); 

					$query1="delete from tblphotos where userid=".$userid;
					mysql_query($query1); 					
					
					$query1="delete from tblresources where postedby=".$userid;
					mysql_query($query1); 	 
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
		else	
		{
			//No User record found. Wrong email may be.
			echo "<script>location.href='adminpanel.php?msg=User Record not found. Please check the email adress' </script>";
		}
		
	}
	else
	{
	
	}
}
else
{	
	unset($_SESSION["admin"]);
	//echo "<script>location.href='admin.php?msg=Invalid Admin credentials. Please login again!!!' </script>";
	echo "<script>location.href='admin.php' </script>";
} 
?> 
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>You Cure Me</title>
<link href="css/stylesheet.css" rel="stylesheet" type="text/css">
  <script type="text/javascript" src="js/popupDiv.js"></script>
</head>
<body>
<div id="main_outer">
	<div id="wrapper">
    	<div id="logo">
        	<a href="index.php"><img src="images/logo_2.png" alt="You Cure Me"></a>
        </div>


      <div class="right_links">
          </div>
        <div class="clear"><br /></div>
        <div class="top">
        	<?php if(isset($_GET['msg']))
{ ?>
	<div class="txttitle whitetitle size12 bold" align="center"><?php echo $_GET['msg']; ?></div>
<?php 
}
?>

        	<h2>"Sign up, share your experiences, make friends, learn from each other, make a difference!"</h2>
        </div>
        <div class="top_banner">
        	<img src="images/banner_img.png" alt="" height="300px" width="612px">
        </div>
        <div class="sign_up">
        	<h3>YouCureMe Admin Panel Login</h3>
            <p>it's anonymous and free.</p>

					<form action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="post" >
                    <div class="innerform">
                    <div class="txt_sign">
                    	<span class="blacktxt bold">User's Email:</span> <span class="red size12" id="msgemail">*</span>
                    </div>
                    <div class="input_txt">
                    <input class="text_feild" name="email" id="email" type="text" />
                    </div>
                    </div>
                           <table cellpadding="0" cellspacing="0" style="width:686px; border:0px;">
                                <tr> 
                                    <tr>
                                        <td style="width:36px; height:12px; " colspan="1" align="left" valign="top"></td>
                                    </tr> 
									<tr> 
                                        <td  align="left" colspan="1" class="blacktxt_1 bold">Enter The Option:
										<Input  type = 'Radio' id="ed_option" Name ='ed_option' value='0'>Delete The User 										 
										<!--<Input type = 'Radio' id="ed_option" Name ='ed_option' value='1' checked>Enable The User</td>--> 
									</tr> 	 	
							</table>  <BR />
                    <div class="innerform">
                    <div class="txt_sign"></div>
                    <div class="input_txt"> <input id="submit-subscirbe" type="submit" value="Submit" />
					
                    </div>
                    </div>
                  </form>
      <p>&nbsp;</p>
      </div>
       
    <div style="clear:both"><br /><p></p></div>
    </div>
    
</div> <br />
 <div style="clear:both"></div>
 <div style="width:100%;margin:0 auto;background:#D7D7D7">   
    <div class="footer">
    	<?php include "footer.php"; ?>
       
    </div>
</div>
</body>
</html>
