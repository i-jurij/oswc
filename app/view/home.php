<table>
Home
<tr><td>N</td><td>Page</td><td>Desc</td></tr>
<?php

	foreach($data as $row)
	{
		echo '<tr><td>'.$row['N'].'</td><td>'.$row['Controller'].'</td><td>'.$row['Description'].'</td></tr>';
	}
	
?>
</table>