<?php
session_start();

require ("Graphic.php");

$text = create_captcha_text();

$graphic = new Graphic(180,45,$text);

unset ($_SESSION['captcha']);

$_SESSION['captcha'] = $graphic->display();

function create_captcha_text()
{
 $pool = '0123456789abcdefghjkmnopqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ';
 $str = '';
 for ($i = 0; $i < 5; $i++)
 {
  $str .= substr($pool, mt_rand(0, strlen($pool) -1), 1);
 }
 return $str;
} 

?>