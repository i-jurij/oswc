<?php
$root = __DIR__;

require_once $root.DIRECTORY_SEPARATOR.'app'.DIRECTORY_SEPARATOR.'lib'.DIRECTORY_SEPARATOR.'load.php';

// Init Core Library
$init  = new App\Lib\Rout($root);
/*
use App\Lib\Registry;
$content_view = Registry::get("content_view");
$data = Registry::get("data");
$template_view = Registry::get("template_view");
if (!empty($template_view)) {
    include $template_view;
}
Registry::clean();
*/
?>
