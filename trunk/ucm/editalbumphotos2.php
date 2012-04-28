<?php include "connection.php";

$albumid=$_POST["albumid"];
$albumname=$_POST["albumname"];
$totalpics=$_POST["totalpics"];

$arrdesc;
$arrids;
for($j=1;$j<=$totalpics;$j++)
{
	$arrdesc[$j]=$_POST["description_".$j];
	$arrids[$j]=$_POST["pic_id_".$j];
	
	//echo "<br/> desc: ".$arrdesc[$j]."   pic id:  ".$arrids[$j];
	
	try
	{	
		$query="update tblphotos
				set
					description='".$arrdesc[$j]."'
				where 
					photoid =".$arrids[$j];
		
		if(!mysql_query($query))
		{
			echo mysql_error();
		}
	}
	catch(exception $ex)
	{
		echo $ex;
	}
}


if(isset($_POST["cover"]))
{
	$cover=$_POST["cover"];
	if ($cover)
	{
		//echo "<br/> cover: ".$cover;//[0];
	}
	
	try
	{	
		$query="update tblalbums
				set
					coverphotoid=".$cover."
				where 
					albumid=".$albumid;
		
		if(!mysql_query($query))
		{
			echo mysql_error();
		}
	}
	catch(exception $ex)
	{
		echo $ex;
	}
	
}

if(isset($_POST["del"]))
{
	$arr=$_POST["del"];
	for($i=0;$i<count($arr);$i++)
	{
		//echo "<br/> del id is: ".$arr[$i];
	}
	if ($arr)
	{
		$photoid=implode(",",$arr);
	}
	else 	
		$photoid="";	
	//echo "<br/> del id is: ".$photoid;
	
	try
	{	
		$query="delete from tblphotos where photoid in (".$photoid.")";
		
		//echo $query;
				
		if(!mysql_query($query))
		{
			echo mysql_error();
		}
	}
	catch(exception $ex)
	{
		echo $ex;
	}
	
}

header("location: viewalbum.php?albumid=".$albumid."&albumname=".$albumname);

?>