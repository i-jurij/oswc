<?php

require_once __DIR__ . '/vendor/autoload.php';

$root = __DIR__;

$init = new App\Lib\Rout($root);

////////////////////////////////////////////
// print page
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
