<div class="content text_left" id="pro"><a href="javascript:void(0);" id="a_pro">Показать/скрыть справку</a>
    <p class="mar display_none"> К разделу администрирования имеют доступ все пользователи, на которых в таблице "users" базы данных
        есть записи: username, password, status (поля для email и email_status пока не используются).<br />
        Username и password могут быть какими угодно (длина поля username - 25 символов, password - 255).<br />
        Status имеет три категории: admin, moder, user.<br />
        По умолчанию между ними нет различий, их нужно установить в файле app/view/adm.php путем разделения
        массива страниц на части, доступных для разных категорий пользователей. Пример в самом файле.
        Статусы, соответственно, можно еще добавить.
    </p>
</div>

<div class="margintb1 ">
    <div>
        <?php
            print ' <a href="'.URLROOT.'/change_pass/add" class="buttons change">Добавить</a>
                    <a href="'.URLROOT.'/change_pass/delete" class="buttons change">Удалить</a>
                    <a href="'.URLROOT.'/change_pass/change" class="buttons change">Изменить</a>';
        ?>
    </div>
    <div>
        <?php
            if (isset($data['users'])) {
                foreach ($data['users'] as $name) {
                    print $name.PHP_EOL;
                }
            } 

            //print_r($data['path']);
            //print '<pre>';
            //print_r($_SERVER);
            //print '</pre>';

            if (isset($data['reg'])) {
                print $data['reg'];
            } 
            
        ?>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() { 
        document.querySelector('#a_pro').addEventListener('click', function(e) {
            //console.log('Button clicked' + e.target.innerText);
            //document.getElementById('prolog').style.display = 'none';

            //document.getElementById(id).style.display = 'none'; // hide
            //document.getElementById(id).style.display = ''; // show

            //document.querySelector('#prolog').style.visibility = "visible"; // show
            //document.getElementById('prolog').style.visibility = "hidden"; // hide

            document.querySelector('#pro p').classList.toggle('display_none');
        })
    });
</script>