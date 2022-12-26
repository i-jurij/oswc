<?php
namespace App\Lib;
/**
*  App Load class
* Parse URL and loads controller from app/pages
* URL FORMAT may be /controller/method_for_controller_or_model/params/...?name=string&name2=string2
* if method not exists for controller - he may used as model method
* rout loaded controller and put other data from url to controllers input datas array
* where 'path' - a piece of url after /method/... before ? if exists method for controller
* or where 'path' - a piece of url after /controller/... before ? if method for controller not exists
* 'get_query' - after ?
* and may be 'post_query' - from html POST
* For adm page load simple authenticate class. Login and pass is in app/config/config.php
*/
class Rout
{
    protected $controller;
    protected $method = 'index';
    protected $param = [];

    public function __construct($siterootpath)
    {
        $dbinit = '\App\Lib\\'.DBINITNAME;
        $db_create = new $dbinit;
        $db_create->create_tables();
        $url_arr = $this->url_to_arr($siterootpath);
        //if (empty($url_arr['path']) or !file_exists(APPROOT.DS.'controllers'.DS.$url_arr['path'][0].'.php')) 
        if (empty($url_arr['path']) or !class_exists("App\\Controllers\\".ucwords($url_arr['path'][0]))) 
        {
            $contr = 'App\Controllers\Home';
            $this->controller = new $contr;
            $url_arr = [];
        }
        else 
        {
            /*
            if ( strpos('adm',$url_arr['path'][0]) !== false )
            {
                $session = new Session();
                $session->start();
                //load login form, get post with login and pass
                new \App\Lib\Auth;
            }
            */
            //print_r($url_arr['path'][0]);
            $contr = "App\\Controllers\\".ucwords($url_arr['path'][0]);
            //$this->param = $url_arr;
            $this->controller = new $contr;
            unset($url_arr['path'][0]);
            
            if (isset($url_arr['path'][1]) && method_exists($contr, $url_arr['path'][1])) 
            {
                $this->method = $url_arr['path'][1];
                unset($url_arr['path'][1]);
            }

            if (!empty($url_arr['path']) && array_filter($url_arr['path'], 'is_string') === $url_arr['path'])
            {
                $this->param['path'] = $url_arr['path'];
            }
            if (!empty($url_arr['get_query']) && array_filter($url_arr['get_query'], 'is_string') === $url_arr['get_query']) 
            {
                $params['get_query'] = $url_arr['get_query'];
            }
            if (!empty($url_arr['post_query']) && array_filter($url_arr['post_query'], 'is_string') === $url_arr['post_query']) 
            {
                $params['post_query'] = $url_arr['post_query'];
            }
            if (isset($params) && !empty($params)) 
            {
                $this->param = $params;
            }
        }
        call_user_func_array([$this->controller,$this->method], $this->param);
        //print_r($this->param);
    }

    public function url_to_arr($siterootpath)
    {
        if (isset($_SERVER['REQUEST_URI'])) 
        {
            $request_url = rtrim($_SERVER['REQUEST_URI'], '/');
            $request_url = filter_var($_SERVER['REQUEST_URI'], FILTER_SANITIZE_URL);
            $request_url = strtok($request_url, '?');
            $request_url = trim($request_url, " \/");
            $res_path = explode('/', $request_url);
            //если на сервере многосайтовость - уберем из пути корневую папку сайта и переиндексируем
            $root = explode('/', $siterootpath);
            $res_path = array_values(array_diff($res_path, array(array_pop($root))));
            $res = array('path' => $res_path);
            
            if ($_SERVER['REQUEST_METHOD'] === "GET") 
            {
                if (!empty($_GET)) 
                {
                    $res['get_query'] = $_GET;
                } 
            }

            if ($_SERVER['REQUEST_METHOD'] === "POST") 
            {
                foreach ($_POST as $key => $value) {
                    $res['post_query'][$key] = $value;
                }
            }
            return $res;
        }
    }
}
