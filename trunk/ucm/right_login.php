<?php
if(!isset($_SESSION["userid"]) || $_SESSION["userid"] =="")
{ 
 ?>       	<h2>Already Have an Account?</h2>
            <div class="login_button">
            <a href="index.php" onclick="setVisible('layer1');return false" target="_self">
            <img src="images/login_but.png" alt="Login"></a>
            </div>
 <?php
}
else
{?>
	       <h2>Welcome <?php echo $_SESSION["fname"] ?></h2>
            <div class="login_button">
            <a href="index.php" onclick="setVisible('layer1');return false" target="_self"></a>
            </div>
<?php }
?>