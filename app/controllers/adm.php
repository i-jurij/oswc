<?php
namespace App\Controllers;

class Adm extends Home
{
	protected $param = [];
    public $model;
	public $view;

	public function index($path = [], $get_query = [], $post_query = [])
    {		
		$session = new \App\Lib\Session();
		$session->start();
		//load login form, get post with login and pass
		$auth = new \App\Lib\Auth;

		if (isset($_POST["login"]) && isset($_POST["password"])) 
		{ //Если логин и пароль были отправлены
		   $inp_login = $_POST["login"];
		   $inp_password = $_POST["password"];
		
		    $dbi = new \App\Lib\Db_init_sqlite; $dbt = $dbi->db;
		    $res = $dbt->select("users", "password", [
		        "username" => $inp_login
		    ]);
		    $password = (isset($res[0])) ? $res[0] : '';
		
		    if (!$auth->auth($inp_password, $inp_login, $password)) { //Если логин и пароль введен не правильно
		        echo '<h2 style="color:red;">Неправильный логин или пароль.</h2>';
		        include 'app/view/login.php';
		    }
		             
		    if (isset($_GET["is_exit"])) 
		    { //Если нажата кнопка выхода
		        if ($_GET["is_exit"] == 1) {
		            $auth->out(); //Выходим
		            header("Location: ?is_exit=0"); //Редирект после выхода
		        }
		    }
		
		   if ($auth->isAuth()) 
		    { // Если пользователь авторизован, приветствуем:  
		        echo "Здравствуйте, " . $auth->getLogin() ;
		        echo "<br/><br/><a href='?is_exit=1'>Выйти</a>"; //Показываем кнопку выхода

				$arr = explode('\\', static::class);
				$class = array_pop($arr);
				$full_name_class = '\App\Models\\'.$class;
				$this->model = new $full_name_class;
				$data = $this->model->get_data($path, $get_query, $post_query);	
				$this->view->generate(APPROOT.DS.'view/'.mb_strtolower($class).'.php', APPROOT.DS.'templates/templ.php', $data);
		    } 
		 }
		 else 
		 { //Если не авторизован, показываем форму ввода логина и пароля
		    include 'app/view/login.php';
		 }
    }
}