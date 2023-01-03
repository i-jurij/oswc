<?php
namespace App\Lib\Traits;

trait Css_add
{
    public function css_add($path_to_css = DS.'public'.DS.'css'.DS.'first') 
    {
        $pattern = '/*.{php,css}';
        $css_files = array();
        foreach(glob($path_to_css . $pattern , GLOB_BRACE) as $file) {
            if (strpos($file, 'normalize')) {
                if (!empty($css_files[0])) {
                    $css_files[] = $css_files[0];
                    $css_files[0] = $file;
                } else {
                    $css_files[0] = $file;
                }   
            } else {
                $css_files[] = $file;
            }
        }
        return $css_files;
    }
}
?>