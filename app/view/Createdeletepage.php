<div class="content">
    <p style="margin:0; padding:1rem;" id="p_pro">Показать/скрыть справку</p>
    <p class="margin_rlb1 text_left display_none" id="pro">
    Создание страниц:<br />
    Данные для шаблона страницы:<br />
    * page_alias(100) - короткое имя для страницы для URL, латиницей, должен иметь уникальное значение,
    те не может быть страниц с одинаковым alias, состоит только из букв, цифр, дефисов, подчеркиваний количеством до 100, обязателен;<br />
    page_templates(100) - путь к шаблону без public/templates, если шаблон отличается от основного, например "first/templ.php", вес файла до 100КБ ;<br />
    * page_title(100) - название страницы;<br />
    page_meta_description(255) - описание страницы сайта для отображения в результатах поиска и для SEO;<br />
    page_meta_keywords - набор ключевых фраз для страницы;<br />
    page_robots - правила для поисковых роботов (https://yandex.ru/support/webmaster/controlling-robot/meta-robots.html);<br />
    * page_h1 - заголовок страницы, обычно такой же как title;<br />
    * page_img - путь к изображению страницы в меню на главной странице без public/imgs/pages, например "about/about.jpg", вес - до 1МБ, формат - jpg, png, webp;<br />
    page_content - html|php содержимое страницы;<br />
    * page_access - уровень доступа к странице: admin, moder, user.<br />
    * page_admin - 1 для страницы из админки, пусто для обычной.<br />
    <small>* - обязательно для заполнения.</small><br /><br />
    Удаление страниц:<br />
    Выберите шаблон страницы для удаления (если его название без расширения не совпадает с "page_alias", например после ручного добавления)<br />
    Если имя шаблона совпадает с "page_alias", он будет удален автоматически.<br />
    НЕ УДАЛЯЙТЕ шаблоны adm_templ.php и templ.php, пока не подготовите их замену.<br />
    Выберите нужную страницу или несколько страниц.<br />
    Нажмите на кнопку "Delete".<br />
    Будут удалены записи из базы данных, контроллер, модель, вид, изображение и шаблон.
    </p>
</div>

<div class=" ">
<?php
if (!empty($data['colname'])) {
    echo ' <div class="margin_bottom_1rem" style="max-width:55rem;">
                <form action="" method="post" enctype="multipart/form-data" name="create_page" id="create_page" >
                    <div class="back shad rad pad margin_rlb1">
                        <p class="margin_rlb1">Выберите файлы</p>
                        <label class="display_inline_block margin_bottom_1rem">Файл шаблона (при необходимости, .php, .html, < 300KB):<br />
                            <input type="hidden" name="MAX_FILE_SIZE" value="307200" />
                            <input type="file" name="template" accept=".php, .html, text/html, text/php, text/x-php, text/plain">
                        </label>';

    echo '             <label class="display_inline_block margin_bottom_1rem">Файл изображения страницы (jpg, png, webp, < 1MB):<br />
                            <input type="hidden" name="MAX_FILE_SIZE" value="1024000" />
                            <input type="file" name="picture" accept="image/jpeg, image/pjpeg, image/png, image/webp">
                        </label>';
    echo '         </div>
                    <div class="margin_bottom_1rem back shad rad pad mar">
                        <p class="margin_rlb1">Введите данные (alias обязателен)</p>';
    foreach ($data['colname'] as $key => $val) {
        $required = ($key === 'page_alias') ? 'required' : '';
        $value = '';
        if ($val != 'INTEGER' && $val != 'CHAR') {
            if (strpos($val, 'ARCHAR')) {
                $arr = explode('(', $val);
                if (!empty($arr[1])) {
                    $length = rtrim($arr[1], ')');
                } else {
                    $length = 255;
                }
            }
            if ($val === 'TEXT') {
                $length = 65535;
            }
            $value = ($key === 'page_robots') ? 'INDEX, FOLLOW' : '';
        }
        $length = (isset($length)) ? $length : 1;

        if ($key != 'page_id' && $key != 'page_img') {
            echo ' <label class="display_inline_block margin_bottom_1rem">'.$key.' ('.$length.')<br />
                                            <input type="text" name="'.$key.'" maxlength="'.$length.'" value="'.$value.'" '.$required.' />
                                        </label>'.PHP_EOL;
        }
        unset($value, $length, $type);
    }
    echo '         </div>
                    <div class="margin_bottom_1rem">
                        <button type="submit" form="create_page" class="buttons" id="create_page_sub">Create</button>
                        <button type="reset" form="create_page" class="buttons">Reset</button>
                    </div>
                </form>
            </div>
            ';
    unset($required, $type, $length, $value, $key, $val);
} elseif (!empty($data['templates_list'])) {
    echo ' <div class="">
                <form action="" method="post" name="del_page" id="del_page">
                    <div class="">';
    if (is_array($data['templates_list']) && !empty($data['templates_list'])) {
        echo '         <div class="back shad rad pad margin_rlb1">
                            <p class="margin_rl1" id="del_template">Показать шаблоны для удаления</p>
                            <div class="display_none margin_top_1rem" id="del_template_div">';
        foreach ($data['templates_list'] as $value) {
            if ($value !== 'adm_templ.php') {
                echo '         <label class="display_inline_block margin_bottom_1rem shad rad pad">
                                    <input type="checkbox" name="delete_template[]" value="'.$value.'" />
                                "'.$value.'"</label>';
            }
        }
        echo '             </div>
                        </div>';
    }
    echo '             <div class="back shad rad pad margin_rlb1">
                            <p class="margin_rlb1 ">Выберите страницу для удаления</p>
                            <p><b>Обычные страницы:</b></p>';

    if (!empty($data['pagename']) && is_array($data['pagename'])) {
        foreach ($data['pagename'] as $value) {
            $titl = (!empty($value['page_title'])) ? $value['page_title'] : $value['page_alias'];
            echo '     <div class="display_inline_block">
                                <label class="display_inline_block shad rad pad05 margint0b1rl05" style="height:100%;">
                                    <input type="checkbox" name="delete_page[]" value="'.$value['page_alias'].'" />
                                    '.$titl.'
                                </label>
                            </div>'.PHP_EOL;
        }
    } else {
        echo 'Список страниц пуст.';
    }
    echo '                 <p><b>Cтраницы админ раздела:</b></p>';
    if (!empty($data['adm_pagename']) && is_array($data['adm_pagename'])) {
        foreach ($data['adm_pagename'] as $val) {
            $tit = (!empty($val['page_title'])) ? $val['page_title'] : $val['page_alias'];
            echo '     <div class="display_inline_block">
                                <label class="display_inline_block shad rad pad05 margint0b1rl05" style="height:100%;">
                                    <input type="checkbox" name="delete_page[]" value="'.$val['page_alias'].'" />
                                    '.$tit.'
                                </label>
                            </div>'.PHP_EOL;
        }
        echo '          </div>';
    } else {
        echo 'Список административных страниц пуст.';
    }

    echo '         </div>
                    <div class="margin_bottom_1rem">
                        <button type="submit" form="del_page" class="buttons" id="del_page_sub">Delete</button>
                        <button type="reset" form="del_page" class="buttons">Reset</button>
                    </div>
                </form>
            </div>
            ';
    unset($titl, $tit, $data, $value, $key, $val);
} elseif (!empty($data['res'])) {
    echo '<div class="back shad rad pad margin_rlb1">'.$data['res'].'</div>';
} else {
    echo '<div class="margin_bottom_1rem ">
                <div class="display_inline_block">
                <a href="'.URLROOT.'/Createdeletepage/create/" class="buttons display_inline_block mar">Создать</a>
                <a href="'.URLROOT.'/Createdeletepage/delete/" class="buttons display_inline_block mar">Удалить</a>
                </div>
            </div>
            ';
}
?>
</div>
