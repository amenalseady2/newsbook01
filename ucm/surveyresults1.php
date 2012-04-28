<?php
include "connection.php";

//echo $_GET["ans"];

$ans = explode(",", $_GET["arrans"]);
for($i=0;$i<count($ans);$i++)
{
    //echo "<br/>".$ans[$i];
}
for($i=0;$i<count($ans);$i++)
{
    $cnt=0;
    $val="";
    
    if($i%2!=0 && $i!=0)
    {
        //echo "<br/>this is ans id which is:".$ans[$i];
        $query_answers="SELECT strqans , qansid
        FROM tblsurveyquesanswers
        WHERE qansid = ".$ans[$i];	
        $sqla=mysql_query($query_answers);
        $counta=mysql_num_rows($sqla);
        if($counta>0)
        {							
	        $rowa=mysql_fetch_array($sqla);
            //$val=$rowa[0];
            $ans[$i]=$rowa[0];
        }
    }
    else
    {
        $cnt=$ans[$i];
       // echo "<br/>this is count which is:".$cnt;
    }
      //$amounts[$val]= $cnt; 
      //echo "<br/><br/>".$cnt." ".$val;
}

for($i=0;$i<count($ans);$i++)
{
    //echo "<br/>".$ans[$i];
}
for($i=0;$i<count($ans);$i=$i+2)
{
    $amounts[$ans[$i+1]]=$ans[$i];
}

/*
$amounts = Array(
		"cats: "=>10,
		"dogs: "=>200,
		"sheep: "=>500,
		"cows: "=>50,
		"hedgehogs: "=>60
		);
		*/
if(rand(0,1)>0.5) {
    asort($amounts);
}
$bar_height = 15;
$bar_spacing = 10;
$valid_spacings = Array(50,100,150);
$grid_space = 100;
$bar_title_space = 150;
$graph_title = "";
$graph_title_space = 20;
$graph_footer = "";
$graph_footer_space = 20;
// colour of bars
// 0=red, 1=green, 2=blue, 3=random
$bar_colour = rand(0,3);
$pic_width = $bar_title_space+max($amounts)+($grid_space*1.5);
$pic_height = ($bar_height+$bar_spacing+2)*sizeof($amounts)+20+$graph_title_space+$graph_footer_space;    
$pic = ImageCreate($pic_width+1,$pic_height+1);
$white = ImageColorAllocate($pic,255,255,255);
$grey  = ImageColorAllocate($pic,200,200,200);
$lt_grey  = ImageColorAllocate($pic,210,210,210);
$black = ImageColorAllocate($pic,0,0,0);
ImageFilledRectangle($pic,0,0,$pic_width,$pic_height,$white);
ImageString($pic,5,($pic_width/2)-(strlen($graph_title)*5),0,$graph_title,$black);
ImageString($pic, 2,($pic_width/2)-(strlen($graph_footer)*3),$pic_height-$graph_footer_space, $graph_footer, $grey);
for($x_axis=$bar_title_space ; ($x_axis-$bar_title_space)<max($amounts)+$grid_space ; $x_axis+=$grid_space) {
	ImageLine($pic,$x_axis,$graph_title_space,$x_axis,$pic_height-$graph_footer_space,$grey);
	ImageLine($pic,$x_axis,($pic_height-$graph_footer_space-25),$x_axis-($bar_title_space+$grid_space),($pic_height-$graph_footer_space-25),$grey);
	ImageString($pic, 3, $x_axis+5, ($pic_height-$graph_footer_space-20), $x_axis-($bar_title_space), $black);
}
$y_axis=$graph_title_space;
if($bar_colour!=3) {
	$col = 180;
	$decrement = intval($col/count($amounts));
}
foreach($amounts as $key=>$amount) {
	ImageString($pic, 2, ($bar_title_space-(strlen($key)*6)), $y_axis, $key, $black);
	if($bar_colour==3) {
		$tempCol = ImageColorAllocate($pic,rand(50,200),rand(50,200),rand(50,200));
	} else {
		$col -= $decrement;
		if($bar_colour==0) {
			$tempCol = ImageColorAllocate($pic,255,$col,$col);
		} else if($bar_colour==1) {
			$tempCol = ImageColorAllocate($pic,$col,200,$col);
		} else if($bar_colour==2) {
			$tempCol = ImageColorAllocate($pic,$col,$col,255);
		}
	}
	ImageFilledRectangle($pic,($bar_title_space+1),$y_axis,$amount+$bar_title_space,($y_axis+$bar_height),$tempCol);
	if(($amount)<15) {
		ImageString($pic, 2, ($amount+3)+$bar_title_space, $y_axis, $amount, $black);
	} else {
		ImageString($pic, 2, ($amount-(strlen($amount)*6))+$bar_title_space, $y_axis, $amount, $white);
	}
	$y_axis+=($bar_spacing+1)+$bar_height;
}
header("Content-type: image/png");
ImagePNG($pic);
ImageDestroy($pic);
?>