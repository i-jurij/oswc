<?php
	if (isset($data)) {
		foreach($data as $row)
		{
			if (is_array($row)) {
				echo '<tr>';
				foreach ($row as $value) {
					echo '<td>'.$value.'</td>';
				}
				echo '</tr>';
			}
			else {
				print $row.'<br />';
			}
			//echo '<tr><td>'.$row['N'].'</td><td>'.$row['Controller'].'</td><td>'.$row['Desc'].'</td></tr>';
		}
	}	
?>
