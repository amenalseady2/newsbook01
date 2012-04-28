<?php include "header.php"; ?>
      <div class="body_home">
   		<div class="home_welcomenote">
<div class="welcome_title">
                Welcome to YouCureMe!</div>
<div class="line_home"></div>
                <div class="wnote_txt"> Lorem ipsum dolor sit amet, consectetur adipiscing elit. Cras ac diam diam, in imperdiet leo. Pellentesque lorem augue, ornare sit amet hendrerit eget, euismod id metus. <br />
                  <br />
                  Nam et nibh ac ante adipiscing ornare non id nisl. Suspendisse malesuada vestibulum luctus. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Suspendisse ipsum nisl, porttitor vitae iaculis nec, imperdiet sed diam. Maecenas faucibus mi vel mauris fermentum vel interdum nibh sodales. <br />
                  <br />
            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Cras ac diam diam, in imperdiet leo. Pellentesque lorem augue, ornare sit amet hendrerit eget, euismod id metus. Nam et nibh ac ante adipiscing ornare non id nisl. Suspendisse malesuada vestibulum luctus. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Suspendisse ipsum nisl, porttitor vitae iaculis nec, imperdiet sed diam. Maecenas faucibus mi vel mauris fermentum vel interdum nibh sodales. Proin ligula sem, placerat ac convallis vel, pellentesque ut felis.  Donec dictum tempor aliquet. Donec eu leo sodales erat placerat tincidunt. </div>
        </div>
        
        <?php if(!isset($_SESSION["userid"]))
        {
        ?>
        
        <div class="right_panel_home">
       	  <div class="topbg_form"></div>
<div class="signup_homeform">
                	<div class="signup_title">Sign Up</div>
          <div class="signup_smalltxt">It's free and anyone can join</div>
                    <div class="signup_line"></div>
				<form action="registerstep1.php" method="post"  enctype="multipart/form-data"  id="customForm">
       	    <div class="innerform">
                    <div class="txt_sign">
                    	<span class="blacktxt">First Name:</span> <span class="red size12">*</span>
                    </div>
                    <div class="input_txt">
                    <input class="text_feild"  name="fname" id="fname" type="text" />
</div>
                    </div>
                    <div class="innerform">
                    <div class="txt_sign">
                    	<span class="blacktxt">Last Name:</span> <span class="red size12">*</span>
                    </div>
                    <div class="input_txt">
                    <input class="text_feild" name="lname" id="lname" type="text" />
                    </div>
                    </div>
                    <div class="innerform">
                    <div class="txt_sign">
                    	<span class="blacktxt">Your Email:</span> <span class="red size12">*</span>
                    </div>
                    <div class="input_txt">
                    <input class="text_feild" name="email" id="email" type="text" />
                    </div>
                    </div>
                    <div class="innerform">
                    <div class="txt_sign">
                    	<span class="blacktxt">Password:</span> <span class="red size12">*</span>
                    </div>
                    <div class="input_txt">
                    <input class="text_feild" name="pwd" id="pwd" type="text" />
                    </div>
                    </div>
                    <div class="innerform">
                    <div class="txt_sign">
                    	<span class="blacktxt">Are You:</span> <span class="red size12">*</span>
                    </div>
                    <div class="input_txt">
<select class="text_list" name="usertypeid" id="usertypeid">
                    	<?php    
						    $q="select usertypeid,strusertype from tblusertype";	
						    $r=mysql_query($q);
						    if($r)
						    {	
							    $n=mysql_num_rows($r);
							    if($n>0)
							    {			
								    $count=0;	
								    while($rw=mysql_fetch_array($r))
								    {
									    echo "<option value='".$rw["usertypeid"]."'";
									    echo " >".$rw["strusertype"]."</option>";
									    $count++;			
								    }
							    }
						    } 
					    ?>
                    </select>
                    </div>
                    </div>
                    <div class="innerform">
                    <div class="txt_sign">
                    	<span class="blacktxt">My Interest:</span> <span class="red size12">*</span>
                    </div>
                    <div class="input_txt">
<select class="text_list" name="diseaseid" id="diseaseid">
					    <?php    
						    $q="select diseaseid,strdisease from tbldisease where diseaseid<>15 order by strdisease";	
						    $r=mysql_query($q);
						    if($r)
						    {	
							    $n=mysql_num_rows($r);
							    if($n>0)
							    {			
								    $count=0;	
								    while($rw=mysql_fetch_array($r))
								    {
									    echo "<option value='".$rw["diseaseid"]."'";
									    if ($rw["diseaseid"]=="1") echo "selected='selected'";
									    echo " >".$rw["strdisease"]."</option>";
									    $count++;			
								    }
							    }
						    } 
					    ?>
					    <option value="15">Other</option>
                    </select>
                    </div>
                    </div>
                    <div class="innerform">
                    <div class="txt_sign"></div>
                    <div class="input_txt"> 
									<input id="submit-subscirbe" src="images/submit_btn.png" type="submit" value="Submit"  />
									<!--<a href="#"><img style="border:0px;" src="images/submit_btn.png" /></a>-->
                    </div>
                    </div>
								</form>
          </div>
          <div class="bottombg_form"></div>
        </div>
        
        <?php
        }
        ?>
      </div>	
<?php include "footer.php"; ?>