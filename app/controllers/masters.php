<?php
namespace App\Controllers;

class Masters extends Adm
{
    public function index($path = [], $get_query = [], $post_query = [])
    {
        $this->auth = new \App\Lib\Auth;
		if ( $this->auth->isAuth() ) 
		{
			print '<div style="margin: 0 2rem;">
                        <span style="float: left;">Здравствуйте, ' . $this->auth->getLogin() . '</span>
                        <a href="'.URLROOT.'/adm/exit" style="float: right;">Выйти</a>
                    </div>
                    <div style="clear: both;"></div>';
			$arr = explode('\\', static::class);
			$class = array_pop($arr);
			$full_name_class = '\App\Models\\'.$class;
			$this->model = new $full_name_class($this->table, strtolower($class));//parameters - tables and page for db query
			$data = $this->model->get_data($path, $get_query, $post_query);	
			$this->view->generate(APPROOT.DS.'view/'.mb_strtolower($class).'.php', $data, $this->template);
		}
        else {
            $this->login = new \App\Lib\Let_adm_login;
        }
    }

}