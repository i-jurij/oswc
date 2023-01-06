<?php
	if (!empty($data['page_list']) && is_array($data['page_list'])) {
		foreach ($data['page_list'] as $page) {
			if ($page['page_alias'] !== "home") {
				print '<article class="main_section_article ">
				<a class="main_section_article_content_a" href="' . $page['page_alias'] . '" >
				<div class="main_section_article_imgdiv">
				<img src="' . $page['page_img'] . '" alt="Фото ' . $page['page_title'] . '" class="main_section_article_imgdiv_img" />
				</div>

				<div class="main_section_article_content">
					<h3>' . $page['page_title'] . '</h3>
					<span>
					' . $page['page_meta_description'] . '
					</span>
				</div>
				</a>
			</article>'
			. PHP_EOL;
			}
		}
	} 
?>

<div class="content" style="text-align: left;"> 
<?php
print '<a href="'.URLROOT.'/adm">Enter</a>'; 


	if (!empty($data['page_db_data'][0]['page_content'])) {
		if (is_array($data['page_db_data'][0]['page_content'])) {
			echo '<table>';
			foreach($data['page_db_data'][0]['page_content'] as $row)
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
		} else {
			print htmlentities($data['page_db_data'][0]['page_content']);
		}
	}

/*
print '<pre>';
print_r($data['page_list']);
print '</pre>';
*/
?>
</div>
<script type="text/javascript">

</script>