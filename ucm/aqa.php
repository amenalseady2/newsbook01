<?php 
include "header_inner.php";
if(isset($_POST["Submit"]))
{
$sql = "insert into tblsurveyquestions value(null,'".mysql_escape_string($_POST["strquestion"])."')";
if(!mysql_query($sql))
{die(mysql_error());}
else
{
 $qid=mysql_insert_id();
 $fields = $_POST["fields"];
   if (is_array($fields))
   {
   foreach ($fields as $key=>$val) 
   {
	   $sql = "insert into tbladmin_q_disease value(null,'".$qid."','".mysql_escape_string($val)."')";
	   if(!mysql_query($sql))
         {
	
		 }   
   } 
   }	
   echo "<script>window.location='vqa.php?msg=Question Inserted';</script>";

}
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
                    <a href="viewuser.php" class="whitetitle size12">Home</a>&nbsp;&nbsp;<span class="size12">>></span>&nbsp;&nbsp;<span class="whitetitle size12">Manage Question & Answers</span>
                </div>

                <div class="body">
                    <div class="main_link">
                        <div class="inbox_title">
                        <div class="email_table">
                        <?php
						if(isset($msg))
						{
							echo "<p>".$msg."</p>";
						}
						?>
                      <form action="aqa.php" name="aqa" id="aqa" method="post" onsubmit="return add_ques(this)">
                      <table align="center" width="80%" style="margin:0 auto">		
                       <tr>
                       <td><p><label for="strquestion" ><strong>Question:</strong></label></p></td>
                       <td><p><input type="text"  id="strquestion" name="strquestion"  placeholder="Enter Questions" size="60"/></p></td>
                       </tr>
                          <tr>
                       <td valign="top"><p><label for="strquestion" ><strong>Interest:</strong></label></p></td>
                       <td><p>
                         <table  width="100%" style="margin:0 auto">
                        <?php
							$sqlproduct = "SELECT * FROM tbldisease";
							$resultproduct= mysql_query($sqlproduct, $connection);
							if (mysql_num_rows($resultproduct)!=0)
							{
							$check=0;
							while($row = mysql_fetch_array($resultproduct))
							{
							if ($check == 0)
							{
							echo "<Tr class='tr_soll'>";
							$check=1;
							}
							if ($check <= 3)
							{echo "
							<td align='left'><input type='checkbox' name='fields[]' value='".$row["diseaseid"]."'>".$row["strdisease"]."</td>";
							if ($check != 4) echo"";
							$check ++ ;
							
							}
							if ($check ==4)
							{
							echo "</Tr>";
							$check=0;
							}						
							}
							}		
			?>
            
                    </table>   
                                            
                       </p></td>
                       </tr>
                       <tr>
                       <td align="center"></td>
                       <td><input type="submit"  name="Submit" id="submit-subscirbe" value="Submit" class="submit"></td>
                       </tr>
                       </table>
						</form>
                       </div>
                    </div>
                </div>
                
                
          </div>
        </div>	  


<div style="clear:both"></div>
 
     	<?php include "footer.php"; ?>
