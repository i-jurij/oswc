<div class="content persinfo">
    <p> К разделу администрирования имеют доступ все пользователи, на которых в таблице "users" базы данных
        есть записи: username, password, status (поля для email и email_status пока не используются).<br />
        Username и password могут быть какими угодно (длина поля username - 25 символов, password - 255).<br />
        Status имеет три категории: admin, moder, user.<br />
        По умолчанию между ними нет различий, их нужно установить в файле app/view/adm.php путем разделения
        массива страниц на части, доступных для разных категорий пользователей. Пример в самом файле.
        Статусы, соответсвенно, можно еще добавить.
    </p>
</div>

<div class="content persinfo">
<?php
    print ' <button class="buttons">Добавить</button>
            <button class="buttons">Удалить</button>
            <button class="buttons">Изменить</button>';

    foreach ($data['users'] as $name) {
        print $name.PHP_EOL;
    }

    //print_r($data['path']);
    //print '<pre>';
    print_r($data);
    //print '</pre>';
?>
</div>
