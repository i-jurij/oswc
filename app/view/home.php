<?php
	if (isset($data['content'])) {
		echo '<table>';
		foreach($data['content'] as $row)
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
		}
		echo '</table>';
	}

print '<pre>';
foreach ($data as $key => $value) {
	if ($key != 'content') {
		if (is_array($data[$key])) {
			print_r($data[$key]);
		} else {
			print $key.' - '.$data[$key].'<br />';
		}
		
	}
}
print '</pre>';
?>
