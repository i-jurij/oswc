<?php
namespace App\Controllers;

class Home
{
	protected $param = [];
    protected $model;
	protected $view;
	
	function __construct()
	{
		$this->view = new \App\Lib\View();
	}

	public function index($path = [], $get_query = [], $post_query = [])
    {		
		$arr = explode('\\', static::class);
		$class = array_pop($arr);
		$full_name_class = '\App\Models\\'.$class;
		$this->model = new $full_name_class;
        $data = $this->model->get_data($path, $get_query, $post_query);	
		$this->view->generate(APPROOT.DS.'view/'.mb_strtolower($class).'.php', $data);
    }

}