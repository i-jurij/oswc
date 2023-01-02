<?php
//DB Params
define('DBINITNAME', 'db_init_sqlite');
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'oswc');
define('DS', DIRECTORY_SEPARATOR);
define('APPROOT', dirname(dirname(__FILE__)));
define('PUBLICROOT', dirname(dirname(dirname(__FILE__))).DS.'public');
//site name
define('SITENAME', 'oswc');
define('URLROOT', 'http://localhost/'.SITENAME);
//define('URLROOT', 'http://'.SITENAME.'.net');
/* login and pass for basic auth */
//define('ADMUSER', 'admin');
//define('ADMPASS', 'passw');
/*
define('ADMLOGPAS', [   "admin" => ['login' => 'admin', 'password' => 'passw'],
                        "moderator" =>  ['login' => 'moder', 'password' => 'moder'],
                        "user" =>  ['login' => 'user', 'password' => 'user']
                    ] );
*/
define('TEMPLATEROOT', PUBLICROOT.DS.'templates');
define('IMGDIR', PUBLICROOT.DS.'imgs');