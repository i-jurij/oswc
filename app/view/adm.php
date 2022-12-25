<?php
session_start();
$login = 'login';
$pass = 'pass';

if( ($_SERVER['PHP_AUTH_PW'] != $pass || $_SERVER['PHP_AUTH_USER'] != $login) || $_SERVER['PHP_AUTH_USER'] = '' )
{
    header('WWW-Authenticate: Basic realm="Auth"');
    header('HTTP/1.0 401 Unauthorized');
    echo 'Авторизуйтесь для доступа к этому разделу.';
    exit;
}
?>
Admin page
