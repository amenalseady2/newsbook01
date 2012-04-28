<?php session_start();?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>You Cure Me</title>
<link rel="stylesheet" href="css/styles.css" type="text/css" />
<?php 
//if(isset($_SESSION["userid"])=="" && isset($_SESSION["fname"]) =="" && $_SESSION["email"]=="")
//{
//
//echo "<script>window.location='admin.php';</script>";
//}
include_once 'common.php';
include "connection.php";
$pageName = explode("/",$_SERVER['PHP_SELF']); 
$page = end($pageName);
$username='Guest';
if(isset($_SESSION["fname"]))
	$username=$_SESSION["fname"];

$_name = $_SESSION["fname"];

if(isset($_SESSION["userid"])){
	
	/******************************************* LOAD USER INFO **********************************************************/
	$query="select 
				access_name,
				alias
			from tbluser  where userid=".$_SESSION["userid"];
			
	$result=mysql_query($query);
	$num=mysql_num_rows($result);
	if($num>0)
	{
		$row=mysql_fetch_array($result);	
		
		if($row["access_name"]==1 && !empty($row["alias"]))		
			$_name=$row["alias"];
	}	
}
	
function country_name($id)
{
$sql = "select * from tblcountry where CountryID='".$id."' limit 0,1";
$sql_fe=mysql_query($sql);							
$count=mysql_num_rows($sql_fe);
if($count>0)
{							
$row=mysql_fetch_array($sql_fe);
return $row["CountryName"];
}
else
{
return "No country";
}
}
?>


<link href="css/calendar.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="css/general.css" type="text/css" media="screen" />
<script type="text/JavaScript">
<!--
function MM_swapImgRestore() { //v3.0
  var i,x,a=document.MM_sr; for(i=0;a&&i<a.length&&(x=a[i])&&x.oSrc;i++) x.src=x.oSrc;
}

function MM_preloadImages() { //v3.0
  var d=document; if(d.images){ if(!d.MM_p) d.MM_p=new Array();
    var i,j=d.MM_p.length,a=MM_preloadImages.arguments; for(i=0; i<a.length; i++)
    if (a[i].indexOf("#")!=0){ d.MM_p[j]=new Image; d.MM_p[j++].src=a[i];}}
}

function MM_findObj(n, d) { //v4.01
  var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
  if(!x && d.getElementById) x=d.getElementById(n); return x;
}

function MM_swapImage() { //v3.0
  var i,j=0,x,a=MM_swapImage.arguments; document.MM_sr=new Array; for(i=0;i<(a.length-2);i+=3)
   if ((x=MM_findObj(a[i]))!=null){document.MM_sr[j++]=x; if(!x.oSrc) x.oSrc=x.src; x.src=a[i+2];}
}
//-->

function validate()
{
	//if(document.frm.agree_terms.checked == false)//!document.getElementById("agree_terms").checked)
//	{
//		alert("Please accept our terms and conditions before applying.");
//		return false;
//	}
	return true;
}

function showhidealias() 
{
	//if(document.getElementById("usealias").checked)
//		document.getElementById("tr_alias").style.display="block";
//	else
//		document.getElementById("tr_alias").style.display="none";
}

function showdivpic()
{
		document.getElementById("divpic").style.display="inline";
}

function showdivvid()
{
		document.getElementById("divvid").style.display="inline";
}

function showembedvid()
{
		document.getElementById("embedvid").style.display="inline";
}
</script>

<!-- ___________________________________________ JavaScript for Calendar ___________________________________________  -->	
<!-- ___________________________________________ JavaScript for Calendar ___________________________________________  -->	
<script language="javascript" src="js/calendar.js"></script>

<!-- ___________________________________________ JavaScript for Profile links ___________________________________________  -->	
<!-- ___________________________________________ JavaScript for Profile links ___________________________________________  -->	
<script type="text/javascript" src="js/div.js"></script>
<script type="text/javascript" src="js/i2o2_validation.js"></script>


<script type="text/javascript" src="js/jquery.min.js"></script>
<!-- __________________________________ AJAX/JQUERY SCRIPT FOR MSGS _________________________________________________ -->
<!-- __________________________________ AJAX/JQUERY SCRIPT FOR MSGS _________________________________________________ -->
<script type="text/javascript">
$(function() {
//More Button 
$('.moremsgs').live("click",function() 
{

var ID = $(this).attr("id");//alert("clicked for MSG with ID:  "+ID);
var msgcounter = document.getElementById("msgcounter").value; 
var typemsg = document.getElementById("typemsg").value;
if(ID)
{
$("#moremsgs"+ID).html('<img src="images/more.png" alt="more" width="40" height="40" border="0"/>');

$.ajax({
type: "POST",
url: "msgs_more.php",
data: "lastmsg="+ ID + "&msgcounter="+msgcounter+ "&typemsg="+typemsg, 
cache: false,
success: function(html){
$("ol#updates").append(html);
$("#moremsgs"+ID).remove();
}
});
}
else
{
$(".morebox").html('The End');

}

document.getElementById("msgcounter").value = msgcounter*1+10;
return false;

});
});
</script>



<!-- __________________________________ AJAX/JQUERY SCRIPT FOR NOTIFICATIONS _________________________________________________ -->
<!-- __________________________________ AJAX/JQUERY SCRIPT FOR NOTIFICATIONS _________________________________________________ -->
<script type="text/javascript">
$(function() {
//More Button 
$('.morenots').live("click",function() 
{

var ID = $(this).attr("id");//alert("clicked for MSG with ID:  "+ID);
var notcounter = document.getElementById("notcounter").value; 
if(ID)
{
$("#morenots"+ID).html('<img src="images/more.png" alt="more" width="40" height="40" border="0"/>');

$.ajax({
type: "POST",
url: "notifications_more.php",
data: "notcounter="+notcounter, 
cache: false,
success: function(html){
$("ol#updates").append(html);
$("#morenots"+ID).remove();
}
});
}
else
{
$(".morebox").html('The End');
}

document.getElementById("notcounter").value = notcounter*1+10;
return false;

});
});
</script>



<!-- __________________________________ AJAX/JQUERY SCRIPT FOR MEMBER SEARCH _________________________________________________ -->
<!-- __________________________________ AJAX/JQUERY SCRIPT FOR MEMBER SEARCH _________________________________________________ -->
<script type="text/javascript">
$(function() {
//More Button 
$('.moresearch').live("click",function() 
{
//alert("cliked");
var ID = $(this).attr("id");//alert("clicked for MSG with ID:  "+ID);
var searchcounter = document.getElementById("searchcounter").value; 

var uname = document.getElementById("uname_h").value; 
var usertypeid = document.getElementById("usertypeid_h").value; 
var diseaseid = document.getElementById("diseaseid_h").value; 

//alert(ID +' '+searchcounter+' '+diseaseid);
if(ID)
{
$("#moresearch"+ID).html('<img src="images/more.png" alt="more" width="40" height="40" border="0"/>');

$.ajax({
type: "POST",
url: "search_more.php",
data: "searchcounter="+searchcounter + "&uname="+uname+ "&usertypeid="+usertypeid+ "&diseaseid="+diseaseid, 
cache: false,
success: function(html){
$("ol#updates").append(html);
$("#moresearch"+ID).remove();
}
});
}
else
{
$(".moresearch").html('The End');
}

document.getElementById("searchcounter").value = searchcounter*1+10;
return false;

});
});
</script>

<!-- __________________________________ AJAX/JQUERY SCRIPT FOR REACHOUT MORE ITEMS_________________________________________________ -->
<script type="text/javascript">
$(function() {
//More Button 
$('.msreachout').live("click",function() 
{
//alert("cliked");
var ID = $(this).attr("id");//alert("clicked for MSG with ID:  "+ID);
var searchcounter = document.getElementById("searchcounter").value; 
var diseaseid = document.getElementById("diseaseid_h").value; 

//alert(ID +' '+searchcounter+' '+diseaseid);
if(ID)
{
$("#msreachout"+ID).html('<img src="images/more.png" alt="more" width="40" height="40" border="0"/>');

$.ajax({
type: "POST",
url: "reachout_more.php",
data: "searchcounter=" + searchcounter + "&diseaseid="+diseaseid, 
cache: false,
success: function(html){
$("ol#updates").append(html);
$("#msreachout"+ID).remove();
}
});
}
else
{
$(".msreachout").html('The End');
}

document.getElementById("searchcounter").value = searchcounter*1+10;
return false;

});
});
</script>




<!-- __________________________________ AJAX/JQUERY SCRIPT FOR MEMBERS WITH MY INTEREST SEARCH _________________________________________________ -->
<!-- __________________________________ AJAX/JQUERY SCRIPT FOR MEMBERS WITH MY INTEREST SEARCH _________________________________________________ -->
<script type="text/javascript">
$(function() {
//More Button 
$('.moresearchmyint').live("click",function() 
{
//alert("cliked");
var ID = $(this).attr("id");//alert("clicked for MSG with ID:  "+ID);
var membintcounter = document.getElementById("membintcounter").value; 
var uname = document.getElementById("uname_h").value; 
var diseaseid = document.getElementById("diseaseid_h").value; 

//alert(ID +' '+membintcounter+' '+uname+' '+diseaseid);
if(ID)
{
$("#moresearchmyint"+ID).html('<img src="images/more.png" alt="more" width="40" height="40" border="0"/>');

$.ajax({
type: "POST",
url: "myinterestmembers_more.php",
data: "membintcounter="+membintcounter + "&uname="+uname+ "&diseaseid="+diseaseid, 
cache: false,
success: function(html){
$("ol#updates").append(html);
$("#moresearchmyint"+ID).remove();
}
});
}
else
{
$(".moresearchmyint").html('The End');
}

document.getElementById("membintcounter").value = membintcounter*1+10;
return false;

});
});
</script>

<!-- __________________________________ AJAX/JQUERY SCRIPT FOR MYFRIENDS SEARCH _________________________________________________ -->
<!-- __________________________________ AJAX/JQUERY SCRIPT FOR MYFRIENDS SEARCH _________________________________________________ -->
<script type="text/javascript">
$(function() {
//More Button 
$('.msmyfriends').live("click",function() 
{
//alert("cliked");
var ID = $(this).attr("id");//alert("clicked for MSG with ID:  "+ID);
var myfriendscounter = document.getElementById("myfriendscounter").value; 
var uname = document.getElementById("uname_h").value; 
 
//alert(ID +' '+myfriendscounter+' '+uname);
if(ID)
{
$("#msmyfriends"+ID).html('<img src="images/more.png" alt="more" width="40" height="40" border="0"/>');

$.ajax({
type: "POST",
url: "myfriends_more.php",
data: "myfriendscounter="+myfriendscounter + "&uname="+uname, 
cache: false,
success: function(html){
$("ol#updates").append(html);
$("#msmyfriends"+ID).remove();
}
});
}
else
{
$(".msmyfriends").html('The End');
}

document.getElementById("myfriendscounter").value = myfriendscounter*1+10;
return false;

});
});
</script> 

<!-- __________________________________ AJAX/JQUERY SCRIPT FOR RESOURCES _________________________________________________ -->
<!-- __________________________________ AJAX/JQUERY SCRIPT FOR RESOURCES _________________________________________________ -->
<script type="text/javascript">
$(function() {
//More Button 
$('.moresearchr').live("click",function() 
{
alert("cliked");
var ID = $(this).attr("id");//alert("clicked for MSG with ID:  "+ID);
var searchcounter = document.getElementById("searchcounter").value; 

var uname = document.getElementById("uname_h").value; 
var resourcetypeid = document.getElementById("resourcetypeid_h").value;

//alert (uname+"  "+resourcetypeid);
if(ID)
{
$("#moresearchr"+ID).html('<img src="images/more.png" alt="more" width="40" height="40" border="0"/>');

$.ajax({
type: "POST",
url: "res_more.php",
data: "searchcounter="+searchcounter + "&uname="+uname+ "&resourcetypeid="+resourcetypeid, 
cache: false,
success: function(html){
$("ol#updates").append(html);
$("#moresearchr"+ID).remove();
}
});
}
else
{
$(".moresearchr").html('The End');
}

document.getElementById("searchcounter").value = searchcounter*1+10;
return false;

});
});
</script>

<!-- __________________________________ AJAX/JQUERY SCRIPT FOR FRIENDS REQUEST_________________________________________________ -->
<!-- __________________________________ AJAX/JQUERY SCRIPT FOR FRIENDS REQUEST_________________________________________________ -->
<script type="text/javascript">
$(function() {
//More Button 
$('.msfreqs').live("click",function() 
{
//alert("cliked");
var ID = $(this).attr("id");//alert("clicked for MSG with ID:  "+ID);
var searchcounter = document.getElementById("searchcounter").value; 

if(ID)
{
$("#msfreqs"+ID).html('<img src="images/more.png" alt="more" width="40" height="40" border="0"/>');

$.ajax({
type: "POST",
url: "reqs_more.php",
data: "searchcounter="+searchcounter, 
cache: false,
success: function(html){
$("ol#updates").append(html);
$("#msfreqs"+ID).remove();
}
});
}
else
{
$(".msfreqs").html('The End');
}

document.getElementById("searchcounter").value = searchcounter*1+10;
return false;

});
});
</script>

<!--<script src="js/jquery2.js" type="text/javascript"></script> -->
<script src="js/main.js" type="text/javascript"></script>

<meta name="google-site-verification" content="U8L39oEgN8-VZu2ofTfAENnGftm2-dKN0_aV62_2H3Y" />
</head>

<body>
<input type="hidden" id="msgcounter" name="msgcounter" value="10" />
	<input type="hidden" id="notcounter" name="notcounter" value="10" />
	<input type="hidden" id="searchcounter" name="searchcounter" value="10" />
	<input type="hidden" id="membintcounter" name="membintcounter" value="10" />
	<input type="hidden" id="myfriendscounter" name="myfriendscounter" value="10" />	
	<input type="hidden" id="msguserid" name="msguserid" value="<?php if(isset($_GET["msguserid"])) { echo $_GET["msguserid"]; } else { echo "0"; }?>" />
	
<div class="mainbg">
	<div class="container">
    	<div class="header">
        	<div class="logo">
            You Cure Me
            </div>
            <div class="top_search">
            	<div class="top_menu" style ='width:600px;' align = "right"> 
               	<?php if(!isset($_SESSION["userid"])){ ?>
					<a href="index.php"><?php echo $item['top_link_1']; ?></a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="resources.php"><?php echo $item['top_link_5']; ?></a><!-- | <a href="register.php?action=new"><?php echo $item['top_link_2']; ?></a> -->
				<?php }
					  elseif(isset($_SESSION["admin"])==5)
					   {?>
                       <a href="logout_admin.php"><?php echo $item['top_link_6']; ?></a> | <a href="viewuser.php"><?php echo $item['top_link_7']; ?></a>	
                     <?php  }
                      else
                      {?>
				 <a href="myprofile.php"><?php echo "Welcome ".$_name; ?></a>  | <a href="logout.php"><?php echo $item['top_link_6']; ?></a> | <a href="editprofile.php?userid=<?php echo $_SESSION["userid"]; ?>"><?php echo $item['top_link_7']; ?></a>	
				<?php }  ?>
				</div>
          <div class="search_bar">
          <div style="width:170px; float:left;">
          		<form action="search.php" method='post'>
                	&nbsp;<input name="searchstr" style="width:160px; height:16px; border:0px; color:#a8a8a8; font-size:11px;" onfocus="if(this.value=='Enter any keyword to search') this.value='';" onblur="if(this.value=='') this.value='Enter any keyword to search';" value="Enter any keyword to search" alt="Enter any keyword to search" type="text" />
                </form>
          </div>
                
                <div style="float:right; width:20px;"><a href="#"><img class="img_border f_right" src="images/search_btn.jpg" /></a></div>
              </div>  
            </div>
        </div>
		
		<script type="text/JavaScript">
		jQuery(document).ready(function($) {
			// Code using $ as usual goes here.
			
						
			$(".user_img img").removeAttr("height");  
			var w;
			var h;
			var images = $(this).find('img');
			
			 images.each(function(){
				w = $(this).attr("width");
				h = $(this).attr("height");
				if (w==50 && h == 50)
					$(this).removeAttr("height");  				
			 });
			
	  });
		
	
	
		</script>