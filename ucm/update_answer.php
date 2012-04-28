
<link rel="stylesheet" href="css/styles.css" type="text/css" />
<?php session_start();
if(isset($_SESSION["userid"])=="" && isset($_SESSION["fname"]) =="" && $_SESSION["email"]=="")
{
	echo "<script>window.location='admin.php';</script>";
}
include_once 'common.php';
include "connection.php";
if(isset($_POST["Submit"]))
{
$sql = "update tblsurveyquesanswers set strqans='".$_POST["stranswer"]."' where questionid='".$_POST["question_id"]."' and qansid='".$_POST["answer_id"]."'";
if(!mysql_query($sql))
{die(mysql_error());}
else
{
echo "<script>window.opener.location.href=window.opener.location.href;window.close();</script>";
}
}

?>
 <div style="margin:0 auto;background:#6CADF7;padding-top:40px;">
                        <div style="margin:0 auto;width:450px;height:262px;">
                        <?php
						if(isset($msg))
						{
							echo "<p>".$msg."</p>";
						}
						?>
                        <?php    
						$q="select * from tblsurveyquesanswers where questionid='".$_REQUEST["ques_id"]."' and qansid='".$_REQUEST["ans_id"]."'";	
						$r=mysql_query($q);
						?>
                      <form action="update_answer.php" name="updateans" id="updateans" method="post" onsubmit="return uques(this)">
                      <input type="hidden" name="question_id" id="question_id" value="<?php echo $_REQUEST["ques_id"]?>">
                       <input type="hidden" name="answer_id" id="answer_id" value="<?php echo $_REQUEST["ans_id"]?>">
                       <p>&nbsp;</p>
                      <table align="center" width="80%" style="margin:0 auto">
                       <?php
					    if($r)
						 {	
						 $n=mysql_num_rows($r);
						 if($n>0)
						 {			
						  while($rw=mysql_fetch_array($r))
						  {?> 
                          <tr>
                           <td><p><label for="strquestion" >Answer:</label></p></td>
                           <td><p><input type="text"  id="stranswer" name="stranswer"  size="40" value="<?php echo $rw['strqans']?>"/></p></td>
                           </tr>
                       <?php
							   }
							 }
						   }?>		
                     
                       <tr>
                       <td align="center"></td>
                       <td><input type="submit"  name="Submit" id="submit-subscirbe" value="Submit" class="submit"></td>
                       </tr>
                       </table>
						</form>
                             
                       </div>
                   