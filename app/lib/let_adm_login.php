<?php
namespace App\Lib;

class Let_adm_login
{
    public $let = false;

    function __construct()
    {
        //load login form, get post with login and pass
		$auth = new \App\Lib\Auth;

		//Если логин и пароль были отправлены isset($_POST["login"]) && isset($_POST["password"])
		if (strlen(filter_has_var( INPUT_POST, "login" )) < 256 && filter_has_var( INPUT_POST, "password" ) ) 
		{ 
			$args = array('login' => FILTER_SANITIZE_SPECIAL_CHARS, 'password' => FILTER_SANITIZE_SPECIAL_CHARS);
			$post_inputs = filter_input_array(INPUT_POST, $args);
			$inp_login = $post_inputs["login"];
			$inp_password = $post_inputs["password"];
			
		    $dbi = new \App\Lib\Db_init_sqlite; $dbt = $dbi->db;
		    $res = $dbt->select("users", "password", [
		        "username" => $inp_login
		    ]);
		    $password = (isset($res[0])) ? $res[0] : '';
		
		    if (!$auth->auth($inp_password, $inp_login, $password, $login = 'admin')) { //Если логин и пароль введен не правильно
		        $message = '<span style="color:red;">Неправильный логин или пароль.</span>';
		        include 'app/view/login.php';
		    }
		    /*         
		    if (isset($_GET["is_exit"])) 
		    { //Если нажата кнопка выхода
		        if ($_GET["is_exit"] == 1) {
		            $auth->out(); //Выходим
		            header("Location: ?is_exit=0"); //Редирект после выхода
		        }
		    }
            */
		   if ($auth->isAuth()) 
		    { // Если пользователь авторизован, приветствуем:  
		      //  echo "Здравствуйте, " . $auth->getLogin() ;
		      //  echo "<br/><br/><a href='?is_exit=1'>Выйти</a>"; //Показываем кнопку выхода
              //$this->let = "Здравствуйте, " . $auth->getLogin() . "<br/><br/><a href='?is_exit=1'>Выйти</a>";
                $request_url = explode('adm', strtok(filter_var($_SERVER['REQUEST_URI'], FILTER_SANITIZE_URL), '?'));
                $res_url = $request_url[0].'adm/';
                $this->let = "Здравствуйте, " . $auth->getLogin() . '<br/><br/><a href="'.$res_url.'exit">Выйти</a>';
		    } 
		 }
		 else 
		 { //Если не авторизован, показываем форму ввода логина и пароля
            $message = "Enter data for log in";
		    include 'app/view/login.php';
		 }
    }

}