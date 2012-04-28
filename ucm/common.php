<?php
//session_start();
//header('Cache-control: private'); // IE 6 FIX
if(isset($_GET["lang"]))
{
//echo "<h1>lang is set by query string -> ".$_GET["lang"]."<br/>";

$lang = $_GET["lang"];

// register the session and set the cookie
$_SESSION['lang'] = $lang;

setcookie("lang", $lang, time() + (3600 * 24 * 30));
}
else if(isset($_SESSION['lang']))
{
//echo "<h1>lang is set by session -> ".$_SESSION["lang"]."<br/>";
$lang = $_SESSION['lang'];
}
else if(isset($_COOKIE['lang']))
{
//echo "<h1>lang is set by cookie -> ".$_COOKIE["lang"]."<br/>";
$lang = $_COOKIE['lang'];
}
else
{
$lang = 'en';
}

switch ($lang) {
  case 'en':
  $lang_file = 'lang.en.php';
  break;

  case 'fr':
  $lang_file = 'lang.fr.php';
  break;
}

include_once 'languages/'.$lang_file;
//echo "<h1>language file is: ".$lang_file."</h1>";
?>