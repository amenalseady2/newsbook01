 <div class="footer">
 
    	<ul class="links">
        <?php if (isset($_SESSION['email'])) {?>
        	<li><a href="myprofile.php" title="My Profile">My Profile</a></li><?php } else{ ?>
            <li><a href="index.php" title="My Profile">My Profile</a></li> <?php } ?>
            <li><a href="aboutus.php" title="My Profile">About</a></li>
             <li><a href="whyus.php" title="My Profile">Why Us</a></li>
            <li><a href="contact.php" title="My Profile">Contact</a></li>
            <li><a href="terms.php" title="My Profile">Terms</a></li>
            <li><a href="privacymain.php" title="My Profile">Privacy</a></li>
            <li><a href="bugreport.php" title="My Profile">Report bugs or areas to improve</a></li>
        </ul>
        <p>Copyright © 2011-2012. YOUCUREME. All Rights Reserved.</p>
    </div>
    
    <?php if($page == "editprofile.php")
			{
	?>
				<script type="text/javascript" src="jquery.js"></script>
				<script type="text/javascript" src="validation.js"></script>
	<?php 	}	
			elseif($page == "index.php")
			{
	?>
				<script type="text/javascript" src="jquery.js"></script>
				<script type="text/javascript" src="validation2.js"></script>
	<?php	}	
			elseif($page == "login.php" || $page == "recover.php")
			{
	?>
				<script type="text/javascript" src="jquery.js"></script>
				<script type="text/javascript" src="validation3.js"></script>
	<?php		}
			elseif($page == "bugreport.php")
			{
	?>
				<script type="text/javascript" src="jquery.js"></script>
				<script type="text/javascript" src="validation4.js"></script>
	<?php		} ?>
	
<map name="Map" id="Map"><area shape="rect" coords="28,311,148,332" href="#" /></map>