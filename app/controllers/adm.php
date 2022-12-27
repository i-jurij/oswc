<?php
namespace App\Controllers;

class Adm extends Home
{
	protected $param = [];
    public $model;
	public $view;

	public function index($path = [], $get_query = [], $post_query = [])
    {		
		$letlogin = new \App\Lib\Let_adm_login;
		if ( $letlogin->let ) 
		{
			print $letlogin->let;
			$arr = explode('\\', static::class);
			$class = array_pop($arr);
			$full_name_class = '\App\Models\\'.$class;
			$this->model = new $full_name_class;
			$data = $this->model->get_data($path, $get_query, $post_query);	
			$this->view->generate(APPROOT.DS.'view/'.mb_strtolower($class).'.php', APPROOT.DS.'templates/templ.php', $data);
		}
    }

	public function exit() 
	{
		$auth = new \App\Lib\Auth;
		$auth->out(); //Выходим
		header("Location: ".URLROOT); //Редирект после выхода
	}
}