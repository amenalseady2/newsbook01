<?php

include "header_inner.php";

$del_query=sprintf("delete from tblsecondary_interests where userid = '%s' and diseaseid='%s'",$_REQUEST["userid"],$_REQUEST["diseaseid"]);

if (! mysql_query ( $del_query )) {
	die ( mysql_error () );
} else { ?>
	
	<script>

	window.location='editprofile.php?userid='+<?php echo $_REQUEST['userid'] ?>+'&msg=Interest removed successfully';
	
	</script>


<?php } ?>