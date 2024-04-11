<?php
// Load Config
require_once $root.DIRECTORY_SEPARATOR.'app/configs/config.php';
require_once $root.DIRECTORY_SEPARATOR.'app/lib/function/func.php';
//Loading Libraries
/*
spl_autoload_register(function($className){
    if (file_exists(APPLICATION_ROOT.DIRECTORY_SEPARATOR.'lib/'.mb_strtolower($className).'.php')) {
        require_once APPLICATION_ROOT.DIRECTORY_SEPARATOR.'lib/'.mb_strtolower($className).'.php';
    }
    elseif (file_exists(APPLICATION_ROOT.DIRECTORY_SEPARATOR.'services/'.mb_strtolower($className).'.php')) {
        require_once APPLICATION_ROOT.DIRECTORY_SEPARATOR.'services/'.mb_strtolower($className).'.php';
    } 
});
*/
spl_autoload_register();
