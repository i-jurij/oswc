<?php

// DB Params
define('DBINITNAME', 'DbInitSqlite');
// define('DBINITNAME', 'DbInitMysql'); //mysql, mariadb
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'oswc');
define('DS', DIRECTORY_SEPARATOR);
define('ROOT', dirname(dirname(dirname(__FILE__))));
define('APPROOT', dirname(dirname(__FILE__)));
define('PUBLICROOT', dirname(dirname(dirname(__FILE__))) . DS . 'public');
// site name
//define('SITENAME', 'oswc'); 
if (PHP_OS_FAMILY === "Windows") {
    // for OSPanel on Windows
    define('SITENAME', 'oswc');
} elseif (PHP_OS_FAMILY === "Linux") {
    // for XAMPP on Linux
    define('SITENAME', 'localhost/oswc');
}
// define('URLROOT', 'http'.((isset($_SERVER['HTTPS']) and $_SERVER['HTTPS']=='on') ? 's': '').'://'.SITENAME.'.net');
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? 'https://' : 'http://';
define('URLROOT', $protocol . SITENAME);
define('CURRENT_PAGE_LOCATION', $protocol . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
/*
define('ADMLOGPAS', [   "admin" => ['login' => 'admin', 'password' => 'passw'],
                        "moderator" =>  ['login' => 'moder', 'password' => 'moder'],
                        "user" =>  ['login' => 'user', 'password' => 'user']
                    ] );
*/
define('TEMPLATEROOT', PUBLICROOT . DS . 'templates');
define('IMGDIR', PUBLICROOT . DS . 'imgs');
