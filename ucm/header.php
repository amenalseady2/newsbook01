<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>You Cure Me</title>
<link rel="stylesheet" href="css/styles.css" type="text/css" />
</head>

<body>
<?php session_start();
include_once 'common.php';
include "connection.php";
$pageName = explode("/",$_SERVER['PHP_SELF']); 
$page = end($pageName);
$username='Guest';
if(isset($_SESSION["fname"]))
	$username=$_SESSION["fname"];
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

document.getElementById("myfriendscounter").value = myfriendscounter*1+10;
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

<!-- __________________________________ AJAX/JQUERY SCRIPT FOR REACHOUT SEARCH _________________________________________________ -->

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
</head>

<?php

/******************************************
Script is currently set to accept 2 parameters, triggered by $feature value.
for example, get_languages( 'data' ):
1. 'header' - sets header values, for redirects etc. No data is returned
2. 'data' - for language data handling, ie for stats, etc.
	Returns an array of the following 4 item array for each language the os supports:
	1. full language abbreviation, like en-ca
	2. primary language, like en
	3. full language string, like English (Canada)
	4. primary language string, like English
*******************************************/

// choice of redirection header or just getting language data
// to call this you only need to use the $feature parameter

/*
function get_languages( $feature, $spare='' )
{
	// get the languages
	$a_languages = languages();
	$index = '';
	$complete = '';
	$found = false;// set to default value
	//prepare user language array
	$user_languages = array();

	//check to see if language is set
	if ( isset( $_SERVER["HTTP_ACCEPT_LANGUAGE"] ) )
	{
		$languages = strtolower( $_SERVER["HTTP_ACCEPT_LANGUAGE"] );
		// $languages = ' fr-ch;q=0.3, da, en-us;q=0.8, en;q=0.5, fr;q=0.3';
		// need to remove spaces from strings to avoid error
		$languages = str_replace( ' ', '', $languages );
		$languages = explode( ",", $languages );
		//$languages = explode( ",", $test);// this is for testing purposes only

		foreach ( $languages as $language_list )
		{
			// pull out the language, place languages into array of full and primary
			// string structure:
			$temp_array = array();
			// slice out the part before ; on first step, the part before - on second, place into array
			$temp_array[0] = substr( $language_list, 0, strcspn( $language_list, ';' ) );//full language
			$temp_array[1] = substr( $language_list, 0, 2 );// cut out primary language
			//place this array into main $user_languages language array
			$user_languages[] = $temp_array;
		}

		//start going through each one
		for ( $i = 0; $i < count( $user_languages ); $i++ )
		{
			foreach ( $a_languages as $index => $complete )
			{
				if ( $index == $user_languages[$i][0] )
				{
					// complete language, like english (canada)
					$user_languages[$i][2] = $complete;
					// extract working language, like english
					$user_languages[$i][3] = substr( $complete, 0, strcspn( $complete, ' (' ) );
				}
			}
		}
	}
	else// if no languages found
	{
		$user_languages[0] = array( '','','','' ); //return blank array.
	}
	// print_r($user_languages);
	// return parameters
	if ( $feature == 'data' )
	{
		return $user_languages;
	}

	// this is just a sample, replace target language and file names with your own.
	elseif ( $feature == 'header' )
	{
		switch ( $user_languages[0][1] )// get default primary language, the first one in array that is
		{
			case 'en':
				$location = 'english.php';
				$found = true;
				break;
			case 'sp':
				$location = 'spanish.php';
				$found = true;
				break;
			default:
				break;
		}
		if ( $found )
		{
			header("Location: $location");
		}
		else// make sure you have a default page to send them to
		{
			header("Location: default.php");
		}
	}
}

function languages()
{
// pack abbreviation/language array
// important note: you must have the default language as the last item in each major language, after all the
// en-ca type entries, so en would be last in that case
	$a_languages = array(
	'af' => 'Afrikaans',
	'sq' => 'Albanian',
	'ar-dz' => 'Arabic (Algeria)',
	'ar-bh' => 'Arabic (Bahrain)',
	'ar-eg' => 'Arabic (Egypt)',
	'ar-iq' => 'Arabic (Iraq)',
	'ar-jo' => 'Arabic (Jordan)',
	'ar-kw' => 'Arabic (Kuwait)',
	'ar-lb' => 'Arabic (Lebanon)',
	'ar-ly' => 'Arabic (libya)',
	'ar-ma' => 'Arabic (Morocco)',
	'ar-om' => 'Arabic (Oman)',
	'ar-qa' => 'Arabic (Qatar)',
	'ar-sa' => 'Arabic (Saudi Arabia)',
	'ar-sy' => 'Arabic (Syria)',
	'ar-tn' => 'Arabic (Tunisia)',
	'ar-ae' => 'Arabic (U.A.E.)',
	'ar-ye' => 'Arabic (Yemen)',
	'ar' => 'Arabic',
	'hy' => 'Armenian',
	'as' => 'Assamese',
	'az' => 'Azeri',
	'eu' => 'Basque',
	'be' => 'Belarusian',
	'bn' => 'Bengali',
	'bg' => 'Bulgarian',
	'ca' => 'Catalan',
	'zh-cn' => 'Chinese (China)',
	'zh-hk' => 'Chinese (Hong Kong SAR)',
	'zh-mo' => 'Chinese (Macau SAR)',
	'zh-sg' => 'Chinese (Singapore)',
	'zh-tw' => 'Chinese (Taiwan)',
	'zh' => 'Chinese',
	'hr' => 'Croatian',
	'cs' => 'Czech',
	'da' => 'Danish',
	'div' => 'Divehi',
	'nl-be' => 'Dutch (Belgium)',
	'nl' => 'Dutch (Netherlands)',
	'en-au' => 'English (Australia)',
	'en-bz' => 'English (Belize)',
	'en-ca' => 'English (Canada)',
	'en-ie' => 'English (Ireland)',
	'en-jm' => 'English (Jamaica)',
	'en-nz' => 'English (New Zealand)',
	'en-ph' => 'English (Philippines)',
	'en-za' => 'English (South Africa)',
	'en-tt' => 'English (Trinidad)',
	'en-gb' => 'English (United Kingdom)',
	'en-us' => 'English (United States)',
	'en-zw' => 'English (Zimbabwe)',
	'en' => 'English',
	'us' => 'English (United States)',
	'et' => 'Estonian',
	'fo' => 'Faeroese',
	'fa' => 'Farsi',
	'fi' => 'Finnish',
	'fr-be' => 'French (Belgium)',
	'fr-ca' => 'French (Canada)',
	'fr-lu' => 'French (Luxembourg)',
	'fr-mc' => 'French (Monaco)',
	'fr-ch' => 'French (Switzerland)',
	'fr' => 'French (France)',
	'mk' => 'FYRO Macedonian',
	'gd' => 'Gaelic',
	'ka' => 'Georgian',
	'de-at' => 'German (Austria)',
	'de-li' => 'German (Liechtenstein)',
	'de-lu' => 'German (Luxembourg)',
	'de-ch' => 'German (Switzerland)',
	'de' => 'German (Germany)',
	'el' => 'Greek',
	'gu' => 'Gujarati',
	'he' => 'Hebrew',
	'hi' => 'Hindi',
	'hu' => 'Hungarian',
	'is' => 'Icelandic',
	'id' => 'Indonesian',
	'it-ch' => 'Italian (Switzerland)',
	'it' => 'Italian (Italy)',
	'ja' => 'Japanese',
	'kn' => 'Kannada',
	'kk' => 'Kazakh',
	'kok' => 'Konkani',
	'ko' => 'Korean',
	'kz' => 'Kyrgyz',
	'lv' => 'Latvian',
	'lt' => 'Lithuanian',
	'ms' => 'Malay',
	'ml' => 'Malayalam',
	'mt' => 'Maltese',
	'mr' => 'Marathi',
	'mn' => 'Mongolian (Cyrillic)',
	'ne' => 'Nepali (India)',
	'nb-no' => 'Norwegian (Bokmal)',
	'nn-no' => 'Norwegian (Nynorsk)',
	'no' => 'Norwegian (Bokmal)',
	'or' => 'Oriya',
	'pl' => 'Polish',
	'pt-br' => 'Portuguese (Brazil)',
	'pt' => 'Portuguese (Portugal)',
	'pa' => 'Punjabi',
	'rm' => 'Rhaeto-Romanic',
	'ro-md' => 'Romanian (Moldova)',
	'ro' => 'Romanian',
	'ru-md' => 'Russian (Moldova)',
	'ru' => 'Russian',
	'sa' => 'Sanskrit',
	'sr' => 'Serbian',
	'sk' => 'Slovak',
	'ls' => 'Slovenian',
	'sb' => 'Sorbian',
	'es-ar' => 'Spanish (Argentina)',
	'es-bo' => 'Spanish (Bolivia)',
	'es-cl' => 'Spanish (Chile)',
	'es-co' => 'Spanish (Colombia)',
	'es-cr' => 'Spanish (Costa Rica)',
	'es-do' => 'Spanish (Dominican Republic)',
	'es-ec' => 'Spanish (Ecuador)',
	'es-sv' => 'Spanish (El Salvador)',
	'es-gt' => 'Spanish (Guatemala)',
	'es-hn' => 'Spanish (Honduras)',
	'es-mx' => 'Spanish (Mexico)',
	'es-ni' => 'Spanish (Nicaragua)',
	'es-pa' => 'Spanish (Panama)',
	'es-py' => 'Spanish (Paraguay)',
	'es-pe' => 'Spanish (Peru)',
	'es-pr' => 'Spanish (Puerto Rico)',
	'es-us' => 'Spanish (United States)',
	'es-uy' => 'Spanish (Uruguay)',
	'es-ve' => 'Spanish (Venezuela)',
	'es' => 'Spanish (Traditional Sort)',
	'sx' => 'Sutu',
	'sw' => 'Swahili',
	'sv-fi' => 'Swedish (Finland)',
	'sv' => 'Swedish',
	'syr' => 'Syriac',
	'ta' => 'Tamil',
	'tt' => 'Tatar',
	'te' => 'Telugu',
	'th' => 'Thai',
	'ts' => 'Tsonga',
	'tn' => 'Tswana',
	'tr' => 'Turkish',
	'uk' => 'Ukrainian',
	'ur' => 'Urdu',
	'uz' => 'Uzbek',
	'vi' => 'Vietnamese',
	'xh' => 'Xhosa',
	'yi' => 'Yiddish',
	'zu' => 'Zulu' );

	return $a_languages;
}
*/
?>

<div class="mainbg">
	<div class="container">
    	<div class="header">
        	<!-- <div class="logo"> 
			You Cure Me
            </div> -->
			<a class="logo" href="http://www.youcureme.com">You Cure Me</a>   
            <div class="top_search">
            	<div class="top_menu">
               	<!--<a href="#">Dashboard</a>&nbsp;&nbsp;|&nbsp;&nbsp;-->
               	
               	<?php if(!isset($_SESSION["userid"]))	{ ?>
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="index.php"><?php echo $item['top_link_1']; ?></a><!--&nbsp;&nbsp;|&nbsp;&nbsp;<a href="resources.php"><?php echo $item['top_link_5']; ?></a> | <a href="register.php?action=new"><?php echo $item['top_link_2']; ?></a> -->
				<?php }
					  else
					  {	 ?>
				    <a href="logout.php"><?php echo $item['top_link_6']; ?></a> | <a href="editprofile.php?userid=<?php echo $_SESSION["userid"]; ?>"><?php echo $item['top_link_7']; ?></a>	
				<?php }  ?>
               	</div>
               	<div class="lag_bar" id="google_translate_element"></div><script>
function googleTranslateElementInit() {
  new google.translate.TranslateElement({
    pageLanguage: 'en',
    includedLanguages: 'ar,zh-CN,en,fr,de,it,ru,es,el',
    multilanguagePage: true,
    layout: google.translate.TranslateElement.InlineLayout.SIMPLE
  }, 'google_translate_element');
}
</script><script src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
</script><script src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
               	
          <!--<div class="lag_bar">
          <img style="border:0px;" align="right" src="images/lag.jpg" />
              </div> --> 
            </div>
        </div>
        <!--<div class="warpper">
        	<div class="home_menu">
           	<a href="index.php">Home</a> --><!--<a href="myprofile.php">My Profile</a>-->
        <!--<a href="aboutus.php">About Us</a>
           	<a href="terms.php">Terms of use</a>
           	<a href="links.php">Links</a>
           	<a href="message.php">Message from the founder</a>
           	<a href="association.php">Association near to your home</a><!--<a href="#">Emotions</a>--><!--</div>
      </div> -->
      <div class="banner_home">
      	<img style="border:0px;" src="images/banner.jpg" />
      </div>
	  