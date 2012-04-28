<?php
session_start();
include_once 'common.php';
include "connection.php";
$pageName = explode("/",$_SERVER['PHP_SELF']); 
$page = end($pageName);
$username='Guest';
if(isset($_SESSION["fname"]))
	$username=$_SESSION["fname"];
include "encrypt_decrypt.php";
include "smtpmailer.php";
?>
<link rel="stylesheet" href="css/styles.css" type="text/css" /> 
<link href="css/stylesheet.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="js/popupDiv.js"></script>
<script type="text/javascript" src="js/i2o2_validation.js"></script>
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
</script>

<!-- ___________________________________________ JavaScript for Calendar ___________________________________________  -->	
<!-- ___________________________________________ JavaScript for Calendar ___________________________________________  -->	
<script language="javascript" src="js/calendar.js"></script>

<!-- ___________________________________________ JavaScript for Profile links ___________________________________________  -->	
<!-- ___________________________________________ JavaScript for Profile links ___________________________________________  -->	
<script type="text/javascript" src="js/div.js"></script>



<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.0/jquery.min.js"></script>
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
var diseaseid = document.getElementById("diseaseid_h").value; 

//alert(ID +' '+membintcounter+' '+diseaseid);
if(ID)
{
$("#moresearchmyint"+ID).html('<img src="images/more.png" alt="more" width="40" height="40" border="0"/>');

$.ajax({
type: "POST",
url: "myinterestmembers_more.php",
data: "membintcounter="+membintcounter + "&diseaseid="+diseaseid, 
cache: false,
success: function(html){
$("ol#updates").append(html);
$("#moresearchint"+ID).remove();
}
});
}
else
{
$(".moresearchint").html('The End');
}

document.getElementById("searchcounter").value = searchcounter*1+10;
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
 
//alert(ID +' '+myfriendscounter);
if(ID)
{
$("#msmyfriends"+ID).html('<img src="images/more.png" alt="more" width="40" height="40" border="0"/>');

$.ajax({
type: "POST",
url: "myfriends_more.php",
data: "myfriendscounter="+myfriendscounter, 
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

document.getElementById("myfriendscounter").value = myfriendscounter*1+2;
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

alert (uname+"  "+resourcetypeid);
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

<!--<script src="js/jquery2.js" type="text/javascript"></script> -->
<script src="js/main.js" type="text/javascript"></script>
<meta name="google-site-verification" content="U8L39oEgN8-VZu2ofTfAENnGftm2-dKN0_aV62_2H3Y" />

			