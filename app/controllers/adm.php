<?php
namespace App\Controllers;

class Adm extends Home
{
	protected $table = "adm_pages";
	protected string $template = TEMPLATEROOT.DS.'adm_templ.php';
	protected $auth;
	protected $login;

	public function index($path = [], $get_query = [], $post_query = [])
    {
		$this->login = new \App\Lib\Let_adm_login;
		if ( $this->login->let ) 
		{
			print $this->login->let;
			$arr = explode('\\', static::class);
			$class = array_pop($arr);
			$full_name_class = '\App\Models\\'.$class;
			$this->model = new $full_name_class($this->table, strtolower($class));//parameters - tables and page for db query
			$data = $this->model->get_data($path, $get_query, $post_query);	
			$this->view->generate(APPROOT.DS.'view/'.mb_strtolower($class).'.php', $data, $this->template);
		}
    }

	public function exit() 
	{
		$this->auth = new \App\Lib\Auth;
		$this->auth->out(); //Выходим
		//header("Location: ".URLROOT."/adm"); //Редирект после выхода
	}


}