<?php

if (!empty($data['res'])) {
    echo ' <div class="content">
                <div style="max-width:70rem;max-height:35rem;overflow:auto;" class="margin_bottom_1rem pad text_left" >'.$data['res'].'</div>
            </div>';
} else {
    if (!empty($data['logs'])) {
        echo '<form action="'.URLROOT.'/Logsview/view/" method="post" id="logs_view">';
        foreach ($data['logs'] as $value) {
            $ar = explode('#', $value);
            echo ' <label class="display_inline_block back shad rad pad margin_bottom_1rem">
                        <input type="radio" name="log" class="buttons" value="'.$ar[1].'" />
                        <span>'.$ar[0].'</span>
                    </label>';
        }
        echo '     <p>
                        <button type="submit" class="buttons">Далее</button>
                    </p>
                </form>';
    } else {
        echo '<div class="content"><p>Каталог не существует, к нему нет права доступа или пуст.</p></div>';
    }
}
