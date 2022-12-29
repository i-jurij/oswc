<?php
	if (isset($data)) {
		echo '<table>';
		foreach($data as $row)
		{
			if (is_array($row)) {
				echo '<tr>';
				foreach ($row as $value) {
					echo '<td>'.htmlentities($value).'</td>';
				}
				echo '</tr>';
			}
			else {
				print htmlentities($row).'<br />';
			}
			//echo '<tr><td>'.$row['N'].'</td><td>'.$row['Controller'].'</td><td>'.$row['Desc'].'</td></tr>';
		}
		echo '</table>';
	}	
?>
