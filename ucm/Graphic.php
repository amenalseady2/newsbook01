<?php

class Graphic
{
private $font = "MacType.ttf";
private $img_width;
private $img_height; 
private $word;

public function __construct($width, $height, $text)
{
  $this->img_width 	= $width;
  $this->img_height 	= $height;
  $this->word 		= $text;
}

public function get_text()
{
   return $this->word;
}
public function display()
{
 /* Determine angle and position */

 $length	= strlen($this->word);
 $angle		= ($length >= 6) ? rand(-($length-6), ($length-6)) : 0;
 $x_axis	= rand(6, (360/$length)-16);			
 $y_axis    	= ($angle >= 0 ) ? rand($this->img_height, $this->img_width) : rand(6, $this->img_height);

 /* Create the image */
 $im = imagecreate($this->img_width, $this->img_height);

 /* Assign colors */ 
 $bg_color	= imagecolorallocate ($im, 70, 100, 200);
 $border_color	= imagecolorallocate ($im, 153, 102, 102);
 $text_color	= imagecolorallocate ($im, 150, 10, 100);
 $grid_color	= imagecolorallocate ($im, 255, 182, 182);
 $shadow_color	= imagecolorallocate ($im, 255, 240, 240);
 
 /* Create the rectangle */ 	
 ImageFilledRectangle($im, 0, 0, $this->img_width, $this->img_height, $bg_color);

 /* Create the spiral pattern */
	
	$theta		= 1;
	$thetac		= 7;
	$radius		= 16;
	$circles	= 20;
	$points		= 32;

	for ($i = 0; $i < ($circles * $points) - 1; $i++)
	{
		$theta = $theta + $thetac;
		$rad = $radius * ($i / $points );
		$x = ($rad * cos($theta)) + $x_axis;
		$y = ($rad * sin($theta)) + $y_axis;
		$theta = $theta + $thetac;
		$rad1 = $radius * (($i + 1) / $points);
		$x1 = ($rad1 * cos($theta)) + $x_axis;
		$y1 = ($rad1 * sin($theta )) + $y_axis;
		imageline($im, $x, $y, $x1, $y1, $grid_color);
		$theta = $theta - $thetac;
	}
 /* Determine the font size */ 
 $font_size = 19;
 $x = rand(0, $this->img_width/($length/1.5));
 $y = $font_size+2;

 /* Write the text */
 for ($i = 0; $i < strlen($this->word); $i++)
 {
   $y = rand($this->img_height/2, $this->img_height-3);     
   imagettftext($im, $font_size, $angle, $x, $y, $text_color, $this->font, substr($this->word, $i, 1));
   $x += $font_size;	
 }
  
 /* Create the border */
 imagerectangle($im, 0, 0, $this->img_width-1, $this->img_height-1, $border_color);		

 /* Tell the browser what kind of file is come in */
 header('Content-Type: image/jpeg');

 /* Output the newly created image in jpeg format */
 imagejpeg($im); 

 /* Free up resources */
 imagedestroy($im);
 return($this->get_text());
}

}
?>
