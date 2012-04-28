<table  class="table1" border="0" bordercolordark="#CCCCFF" style="margin:0 auto">
<th>View Question</th>
<tr>
<td><strong>S/No</strong></td>
<td><strong>Question</strong></td>
</tr>
<?php
$sql ="select * from category";
$result =mysql_query($sql);
$i=0;
 while($row = mysql_fetch_array($result))
{
  $i=$i+1;
?>
<tr><td><?php echo $i;?></td>
<td><?php echo $row["category_name"];?></td>
<td><a href="edit_category_design.php?category_id=<?php echo $row["category_id"];?>">Edit</a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:confirmation(<?php echo $row["category_id"];?>);">Delete</a></td>
</tr>
<?php }
?>
</table>