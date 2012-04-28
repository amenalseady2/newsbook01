<?php	session_start();
	include "connection.php";

	if($_SERVER['REQUEST_METHOD']=='POST')
	{	
	
			$msg=str_replace("'","''",$_POST["msg"]);
			$msg=str_replace("\"","''",$msg);
			$msg=stripslashes($msg);
			
			$mode=$_POST["mode"];
			$msgid=$_POST["msgid"];
			$recieverid=$_POST["recieverid"];
			$senderid=$_POST["senderid"];
			$msgtime=date("Y-m-d H:i:s");
			$isread=0;
			$isdeleted=0;
			
			try
			{	
				$query="insert into tblmsgs(
				msg,
				recieverid,
				senderid,
				msgtime,
				isread,
				isdeleted) 
				values
				('".$msg."',
				".$recieverid.",".$senderid.",'".$msgtime."',".$isread.",".$isdeleted.")";
							
				//echo $query;								
				if(mysql_query($query))
				{
					echo "<script>location.href='messages.php?mode=".$mode."&msg=Message sent successfully.' </script>";
				}
				else
				{
					echo mysql_error();
				}
			}
			catch(exception $ex)
			{
				echo $ex;
			}
	}

?>
