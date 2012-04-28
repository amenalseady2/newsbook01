<?php 
include "header_inner.php";
require_once('classes/tc_calendar.php');
include "smtpmailer.php";
function encrypt_userid($text)  
{      
	return trim(base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, SALT, $text, 
	MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, 
	MCRYPT_MODE_ECB), MCRYPT_RAND))));  
}   

?>
<?php 
if($_POST)
{
  // print_r($_POST);die();
					$EmailTo = $_POST['message'];	 
					$EmailFrom = "info@youcureme.com";
					$Emailfrom_name = "YouCureMe.com"; 
					$EmailSubject = "YouCureMe";
					//$key = encrypt_userid($_POST['userid']);					
								
					$EmailMsg = $_POST['message'];
									
					send_smtpmail($EmailTo, $EmailFrom, $Emailfrom_name, $EmailSubject, $EmailMsg);	 

}
?>
        <div class="warpper">
        	<div class="left_side">
            	<div class="left_contant">
                	<div class="user_info">
             
                      	<div class="user_name bluetitle size16 bold" /><?php echo $_SESSION["fname"];?></div>
                    </div>
                    <div class="profile_links">
                    	<div class="title_txt">
                        	<div class="plusimg"><img src="images/plus_icon.jpg" /></div>
                            <div class="txttitle whitetitle size12 bold">Manages</div>
                        </div>
                        <div class="txt_links">
                        	<?php
							include("left_admin_menu.php");
							?>
                        </div>
                    </div>
                    
                  
            </div>
            </div> 
            <div class="body_main">
                <div class="body_menu whitetitle">
                    <a href="viewuser.php" class="whitetitle size12">Home</a>&nbsp;&nbsp;<span class="size12">>></span>&nbsp;&nbsp;<span class="whitetitle size12">Manage Users</span>
                </div>

                <div class="body">
                    <div class="main_link">
                        <div class="inbox_title">
                        <div class="email_table">
                      <table align="center" cellpadding="0" cellpadding="0">
					  <form action="" method="post">
					  <tr><td  style="color: #000000;
    float: left;
    font-size: 12px;
    font-weight: bold;
    padding: 25px 41px 5px;;">Email</td><td><input type="text" name="email" id="email" class="inputtxt"></td></tr>
					  <tr><td  style="color: #000000;
    float: left;
    font-size: 12px;
    font-weight: bold;
    padding: 25px 41px 5px;">Message</td><td><textarea name="message" class="inputtxt"></textarea></td></tr>
	
	 <tr><td>&nbsp;</td><td  style="color: #000000;float: left;font-size: 12px;font-weight: bold;padding: 25px 41px 5px;" ><input type="submit" name="send" value="Send"</td></tr>
	
					  </table>
					  </form>
                                    
                                </div>
                    </div>
                </div>
                
                
          </div>
        </div>	  


<div style="clear:both"></div>
 <div style="width:100%;margin:0 auto;background:#D7D7D7">   
    <div class="footer">
    	<?php include "footer.php"; ?>
       
    </div>
                  
                  </div>