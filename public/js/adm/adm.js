document.addEventListener("DOMContentLoaded", function() { 
       /*
    * event listener for info div in adm/change_pass
    */
    const PRO = document.querySelector('#p_pro');
    if (PRO) {
        PRO.addEventListener('click', function(e) {
            document.querySelector('#pro').classList.toggle('display_none');
        });
    }
    /*
    * event listener for registration form in adm/change_pass/add
    */
    //document.querySelector('.buttons.sub').disabled = true;
    const btn = document.querySelector('.buttons.sub'); 
    const radioButtons = document.querySelectorAll('input[name="reg_status"]');
    if (btn) {
        btn.addEventListener("click", (ev) => {
            ev.preventDefault();
            //check name
            let name = false;
            let mes = '';
            var re = /^[a-zA-Zа-яА-ЯёЁ0-9-_]{3,25}$/;
            var OK = re.exec(document.querySelector('input[name="reg_name"]').value);
            if (!OK)
            mes = mes + "Имя от 3 до 25 букв, цифр, дефисов, подчёркиваний.\n";
            else
            name = true;

            //check pass
            let pass = false;
            let lp = document.querySelector('input[name="reg_password"]').value.length;
            if (lp > 3 && lp < 121) {
                pass = true;
            } else {
                mes = mes + "Пароль от 4 до 120 символов :)\n";
            }

            //check status
            let status = false;
            for (const radioButton of radioButtons) {
                if (radioButton.checked) {
                    //selectedSize = radioButton.value;
                    status = true;
                }
            }
            if (status == false) mes = mes + "Статус пользователя.\n";

            if (name && pass && status) {
                document.querySelector('#reg_form').submit();
            } else {
                alert("Выберите:\n" + mes);
            }
        });
    }

    /*
    * event listener for delete, change user form in adm/change_pass/delete or change
    */
    const SUB = document.querySelector('#del_ch')
    if (SUB) {
        SUB.addEventListener('click', function(ev) {
            ev.preventDefault();
            let form_data = new FormData(document.querySelector(".form_del_ch"));
            if ( form_data.has("delete[]") || form_data.has("change[]"))
            {
                //document.querySelector("#chk_option_error").style.visibility = "hidden";
                document.querySelector(".form_del_ch").submit();
            }
            else
            {
                if (!document.querySelector("#ermes")) {
                    document.querySelector(".form_del_ch").insertAdjacentHTML('afterbegin','<div style="color:red;" id="ermes">Please select at least one user.</div>');
                }
                //document.getElementById("chk_option_error").style.visibility = "visible";
            }
        });
    }

    const CHUD = document.querySelector('#db_change_users_data');
    if (CHUD) {
        CHUD.addEventListener('submit', function(eve) {
            eve.preventDefault();
            var stop = true;
            let inp_names = document.querySelectorAll(".user_name");
            if (inp_names.length > 0) {
                for (let i = 0; i < inp_names.length; i++) {
                    const inp = inp_names[i].value;
                    // проверяем, существует ли элемент в проверяемом массиве имен
                    if (data.includes(inp)) {
                        alert('Имя "' + inp + '" уже существует в базе данных.');
                        inp_names[i].focus();
                        const currentdiv = inp_names[i].parentNode.parentNode;
                        currentdiv.style.color = 'red';
                        currentdiv.parentNode.parentNode.scrollIntoView();
                        stop = false;
                        break;
                    }
                }
            }

            if (stop) {
                CHUD.submit();
            }
        });
    }

});
