<div class="content"><p style="margin:0; padding:1rem;" id="p_pro">Показать/скрыть справку</p>
    <p class="margin_rlb1 text_left display_none" id="pro"> К разделу администрирования имеют доступ все пользователи, на которых в таблице "users" базы данных
        есть записи: username, password, status (поля для email и email_status пока не используются).<br />
        Username - длина поля 25 символов (буквы или цифры), password - 120 любых символов.<br />
        Status имеет три категории: admin, moder, user.<br />
        По умолчанию между ними нет различий, их нужно установить в файле app/view/adm.php путем разделения
        массива страниц на части, доступных для разных категорий пользователей. Пример в самом файле.
        Статусы, соответственно, можно еще добавить.
    </p>
</div>

<div class="content margintb1 ">
    <div>
        <?php
            if ( !isset($data['res']) && !isset($data['users_del']) && !isset($data['users_change']) ) 
            {
                print ' <div class="margintb1 ">
                            <a href="'.URLROOT.'/change_pass/add" class="buttons display_inline_block mar add">Добавить</a>
                            <a href="'.URLROOT.'/change_pass/delete" class="buttons display_inline_block mar del">Удалить</a>
                            <a href="'.URLROOT.'/change_pass/change" class="buttons display_inline_block mar change">Изменить</a>
                        </div>';
            }

            if (!empty($data['users_del'])) {
                $users = $data['users_del'];
                $check = 'delete[]';
            } elseif (!empty($data['users_change'])) {
                $users = $data['users_change'];
                $check = 'change[]';
            } elseif (!empty($data['users_del']) && !empty($data['users_change'])) {
                $data['res'] = "Error!";
            }

            if ( isset($users) && isset($check))
            {
                print '<form action="" method="post" name="users" class="">';
                foreach ($users as $name) {
                    print '<label class="checkbox-btn"><input type="checkbox" value="'.$name.'" name="'.$check.'" /><span>'.$name.'</span></label>';
                }
                print '<button type="submit" class="buttons">Выбрать</button></form>';
            }
            $pad = '';
            if (!empty($data['res'])) 
            {
                $pad = 'pad';
                print '<div class="'.$pad.'">'.$data['res'].'</div>';
            }

            //print_r($data['path']);
            //print '<pre>';
            //print_r($_SERVER);
            //print '</pre>';
        ?>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() { 
        document.querySelector('#p_pro').addEventListener('click', function(e) {
            document.querySelector('#pro').classList.toggle('display_none');
        })

        /* event on each element with >buttons class
        let elArr = document.querySelectorAll(".buttons");
        elArr.forEach(function(ev) {
            ev.addEventListener("click", function() {
                console.log('Button clicked' + ev.target.innerText);
            });
        })
        */

        //document.getElementById(id).style.display = 'none'; // hide
        //document.getElementById(id).style.display = ''; // show
        //document.querySelector('#prolog').style.visibility = "visible"; // show
        //document.getElementById('prolog').style.visibility = "hidden"; // hide

    }); 
</script>