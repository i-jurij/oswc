<div class="adm_content">
<?php
	if (!empty($data['page_list']) && is_array($data['page_list'])) {
		foreach ($data['page_list'] as $page) {
			if ($page['page_alias'] !== "adm") {
                    if ($page['page_alias'] == 'recall' or $page['page_alias'] == 'recall_yes' or $page['page_alias'] == 'master_app' or $page['page_alias'] == 'date_app')
                    {
                      $zvonki_zapisi[] = $page['page_alias'] . '#' . $page['page_title'];
                    }
                    elseif ( $page['page_alias'] == 'contacts' or $page['page_alias'] == 'grafik' or $page['page_alias'] == 'price' or $page['page_alias'] == 'masters' )
                    {
                      $redaktors[] = $page['page_alias'] . '#' . $page['page_title'];
                    }
                    else
                    {
                      $oth[] = $page['page_alias'] . '#' . $page['page_title'];
                    }
			}
		}                
	} 

    $arr = array('zvonki_zapisi', 'oth', 'redaktors');
    foreach ($arr as $value) {
        if (!empty($$value)) {
            echo '<div class="pad margin_bottom_1rem">';
            foreach ($$value as $value11)
            {
                list($alias, $title) = explode('#', $value11);
                echo '<a href="' . $alias . '" class="buttons">' . $title . '</a> ';
            }
            echo "</div>";
        }
    }
/*
            if (!empty($zvonki_zapisi)) {
                echo '<div class="pad margin_bottom_1rem">';
                foreach ($zvonki_zapisi as $value11)
                {
                    list($alias, $title) = explode('#', $value11);
                    echo '<a href="' . $alias . '" class="buttons">' . $title . '</a> ';
                }
                echo "</div>";
            }

            if (!empty($oth)) {
            echo '<div class="pad margin_bottom_1rem">';
            foreach ($oth as $value22)
            {
              echo '<a href="' . $value22 . '" class="buttons">' . mb_ucfirst(translit_to_cyr(str_replace('_', ' ', $value22))) . '</a> ';
            }
            echo "</div>";
            }

            if (!empty($redaktors)) {
                echo '<div class="pad margin_bottom_1rem">';
                foreach ($redaktors as $value33)
                {
                  echo '<a href="' . $value33 . '" class="buttons">' . mb_ucfirst(translit_to_cyr(str_replace('_', ' ', $value33))) . '</a> ';
                }
                echo "</div>";
            }
*/
?>
</div>
