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

	public function index($path = [])
    {
		if ($this->auth->check_auth()) 
		{
			$str = '<div style="margin: 0 1rem 1rem 1rem; width: 100%;">
						<div style="float: right;">
							<span style="margin: 0 1rem 0 0; color: blanchedalmond;">Здравствуйте, <b>' . $_SESSION['user_name'] . '</b></span>
							<a class="buttons" href="'.URLROOT.'/adm/exit">Выход</a>
						</div>
					</div>
					<div style="clear: both;"></div>';
			\App\Lib\Registry::set("exit_from_adm", $str);
			$arr = explode('\\', static::class);
			$class = array_pop($arr);
			$full_name_class = '\App\Models\\'.$class;
			$this->model = new $full_name_class($this->table, strtolower($class));//parameters - tables and page for db query
			$data = $this->model->get_data($path);	
			$this->view->generate(APPROOT.DS.'view/'.mb_strtolower($class).'.php', $data, $this->template);
		}
		else 
		{
			$this->auth->login();
		}
    }

	public function exit() 
	{
		$this->auth->out();
		header_remove();
		header("Location: ".URLROOT."/");
		exit;
	}
}