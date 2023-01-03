<?php
namespace App\Controllers;

class Adm extends Home
{
	protected $table = "adm_pages";
	protected string $template = TEMPLATEROOT.DS.'adm_templ.php';
	protected $auth;
	protected $login;

	public function __construct()
	{
		$this->auth = new \App\Lib\Auth;
		$this->view = new \App\Lib\View();
	}

	public function index($path = [], $get_query = [], $post_query = [])
    {
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
		
		    if (!$this->auth->auth($inp_password, $inp_login, $password, $login = 'admin')) { //Если логин и пароль введен не правильно
		        $message = '<span style="color:red;">Неправильный логин или пароль.</span>';
		        include APPROOT.DS.'view'.DS.'login.php';
		    }

			if ($this->auth->isAuth()) 
		    {
				$arr = explode('\\', static::class);
				$class = array_pop($arr);
				$full_name_class = '\App\Models\\'.$class;
				$this->model = new $full_name_class($this->table, strtolower($class));//parameters - tables and page for db query
				$data = $this->model->get_data($path, $get_query, $post_query);	
				$this->view->generate(APPROOT.DS.'view/'.mb_strtolower($class).'.php', $data, $this->template);
		    } 
		}
		else 
		{ //Если не авторизован, показываем форму ввода логина и пароля
			$message = "Enter data for log in";
			include APPROOT.DS.'view'.DS.'login.php';
		}
    }

	public function exit() 
	{
		$this->auth->out(); //Выходим
		//header("Location: ".URLROOT."/adm"); //Редирект после выхода
		//header("Location: ".$_SERVER['REQUEST_URI']."");
		//header("Location: ".URLROOT."/");
		//exit;
	}


}