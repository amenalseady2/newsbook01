<?php
session_start ();
include ("connection.php");
include ("common.php");
if (isset ( $_POST ['interest'] )) {
	?>
<tr>
	<td style="width: 36px; height: 3px;" colspan="5" align="left"
		valign="top"></td>
</tr>
<tr>
	<td style="width: 125px;" align="left" class="bold size12"></td>
	<td colspan="4" style="width: 561px;" align="left" class="size12">
	<select name="disease_ids[]" style="width:160px; border:1px #bcbcbc solid; height:16px; padding-left:3px;">                                            
    <?php
	$q = "select diseaseid,strdisease from tbldisease where diseaseid<>15 order by strdisease";
	$r = mysql_query ( $q );
	if ($r) {
		$n = mysql_num_rows ( $r );
		if ($n > 0) {
			while ( $rw = mysql_fetch_array ( $r ) ) {
				
				echo "<option value='" . $rw ["diseaseid"] . "'>";
				echo $rw ["strdisease"];
				echo "</option>";
			}
		}
	}
	?>
	</select></td>
</tr>
<?php
}
?>