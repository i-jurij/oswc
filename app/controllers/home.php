<?php
namespace App\Controllers;

class Home
{
	protected $param = [];
    protected $model;
	protected $view;
	protected $table = "pages";
	
	function __construct()
	{
		$this->view = new \App\Lib\View();
	}

	public function index($path = [])
    {		
		$arr = explode('\\', static::class);
		$class = array_pop($arr);
		$full_name_class = '\App\Models\\'.$class;
		if (class_exists($full_name_class)) {
			$this->model = new $full_name_class($this->table, strtolower($class));//parameters - tables and page for db query
			if (!empty($path[0]) && method_exists($this->model, $path[0])) {
				$method = $path[0];
				array_shift($path);
				$data = ($this->model)->$method($path);
			} else {
				$data = $this->model->get_data($path);
			}
		}
		$this->view->generate(APPROOT.DS.'view/'.mb_strtolower($class).'.php', $data);
    }
}