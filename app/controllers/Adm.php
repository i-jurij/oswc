<?php

namespace App\Controllers;

use App\Lib\Auth;
use App\Lib\Registry;
use App\Lib\View;

class Adm extends Home
{
    // protected $table = "adm_pages";
    protected string $template = TEMPLATEROOT.DS.'adm_templ.php';
    protected $auth;
    protected $login;

    public function __construct()
    {
        $this->auth = new Auth();
        $this->view = new View();
    }

    public function index($path = [])
    {
        if ($this->auth->check_auth()) {
            $arr = explode('\\', static::class);
            $class = array_pop($arr);
            unset($arr);
            $full_name_class = '\App\Models\\'.$class;
            if (class_exists($full_name_class)) {
                $this->model = new $full_name_class($this->table, $class); // parameters - tables and page for db query
                if (!empty($path[0])) {
                    if (method_exists($this->model, $path[0])) {
                        $method = $path[0];
                        $nav = Registry::get('nav');
                        array_push($nav, array_shift($path));
                        Registry::set('nav', $nav);
                        // $data = $this->model->get_data($path) + $this->model->$method($path);
                        $data = array_merge($this->model->get_data($path), $this->model->$method($path));
                    } else {
                        header('HTTP/1.0 404 Not Found');
                        $data = $this->model->get_data($path);
                        $data['page_db_data'][0]['page_title'] = 'Страница не найдена (Page not found)';
                        $data['page_db_data'][0]['page_content'] = 'Страница, которую Вы запросили, не найдена.';
                    }
                } else {
                    $data = $this->model->get_data($path);
                }
            }
            $this->view->generate(APPROOT.DS.'view/'.$class.'.php', $data, $this->template);
        } else {
            $this->auth->login();
        }
    }

    public function exit()
    {
        $this->auth->out();
        header_remove();
        header('Location: '.URLROOT.'/');
        exit;
    }
}
