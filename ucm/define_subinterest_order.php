<?php 
include "header_inner.php";
error_reporting(0);
?>

<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script> 
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.3/jquery-ui.min.js"></script>

<style type="text/css">         
 .menu li {
	list-style: none;             
	padding: 10px;             
	margin-bottom: 5px;             
	border: 1px solid #000;
	background-color: #C0C0C0;             
	width: 150px;         
	}     
</style> 


<style>
table {
    border-collapse: collapse;
}
td {
    padding-bottom: 2em;
}
</style>

<style>
	.toggler { width: 500px; height: 50px; }
	#button { padding: .5em 1em; text-decoration: none; }
	#effect { width: 240px; height: 50px; padding: 0.4em; position: relative; }
	#effect h3 { margin: 0; padding: 0.4em; text-align: center; }
	</style>
	
<script type="text/javascript">
$(document).ready(function(){

$(function() {
		// run the currently selected effect
		function runEffect() {
			// get effect type from 
			var selectedEffect = $( "#effectTypes" ).val();

			// most effect types need no options passed by default
			var options = {};
			// some effects have required parameters
			if ( selectedEffect === "scale" ) {
				options = { percent: 100 };
			} else if ( selectedEffect === "size" ) {
				options = { to: { width: 280, height: 185 } };
			}

			// run the effect
			$( "#effect" ).show( selectedEffect, options, 500, callback );
		};

		//callback function to bring a hidden box back
		function callback() {
			setTimeout(function() {
				$( "#effect:visible" ).removeAttr( "style" ).fadeOut();
			}, 1000 );
		};

		// set effect from select menu value
		$( "#button" ).click(function() {
			runEffect();
			return false;
		});

		$( "#effect" ).hide();
	});

$('.menu').sortable();

$(".menu").sortable({
     update: function(event, ui) {
	 
	 var pageOrder = $(this).sortable('toArray').toString();
	 $.post("define_subinterest_order_ajax.php", { pages: pageOrder } );
	 $( "#button" ).click();
     }
  });
});
</script>

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
<div class="body_menu whitetitle"><a href="viewuser.php"
	class="whitetitle size12">Home</a>&nbsp;&nbsp;<span class="size12">>></span>&nbsp;&nbsp;<span
	class="whitetitle size12">Manage Question & Answers</span></div>


	
<div class="body">
	<h1 style="color: #0D4C94;">SubInterest Order</h1>
Please drag to order the list!
<div class="demo">

<div class="toggler">
	<div id="effect" class="ui-widget-content ui-corner-all">
		<h3 class="ui-widget-header ui-corner-all"></h3>
		<p>
			<strong style="color: #0D4C94;">SubInterest Order has been saved !</strong>
		</p>
	</div>
</div>

<select name="effects" id="effectTypes" style="display:none">
	<option value="bounce" selected="selected">Bounce</option>
</select>

<a href="#" id="button" class="ui-state-default ui-corner-all" style="display:none;">Run Effect</a>

</div><!-- End demo -->
	
	<?php


	
$query_to_order =  sprintf("select `diseaseid`, `strdisease`, `disease_status`, `Interestsuggestedby`, `order` from tbldisease order by `order` asc");
$result_interest = mysql_query($query_to_order);
while($rowp = mysql_fetch_array($result_interest)){

echo "<h2>$rowp[1]</h2>";

$query_to_order1 =  sprintf("select 	subdiseaseid, 	strsubdisease, 	diseaseid, `order` from tblsubdisease where diseaseid = '%s' order by `order` asc",
					$rowp[0]);
$result = mysql_query($query_to_order1);

?>
<ul class="menu" >         
<?php
$srno = 1;
while($row = mysql_fetch_array($result)){
	
	echo "<li id=$row[0] class='menu'>";
	echo $row[1];
	echo "</li>";
}
echo "</ul> ";
echo "<hr>";

}
?>

</div>
</div>
<div style="clear: both"></div>
<div style="width: 100%; margin: 0 auto; background: #D7D7D7">
<div class="footer">
<?php include "footer.php"; ?>
</div>
</div>