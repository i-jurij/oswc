<?php
namespace App\Lib\Traits;

trait Css_add
{
    public function css_add($path_to_css  = 'public/css/first') 
    {
        $pattern = '/*.{php,css}';
        $css_files = array();
        foreach(glob($path_to_css . $pattern , GLOB_BRACE) as $file) {
            if (strpos($file, 'normalize')) {
                if (!empty($css_files[0])) {
                    $css_files[] = $css_files[0];
                    $css_files[0] = '<link rel="stylesheet" type="text/css" href="'.$file.'" />';
                } else {
                    $css_files[0] = '<link rel="stylesheet" type="text/css" href="'.$file.'" />';
                }   
            } else {
                $css_files[] = '<link rel="stylesheet" type="text/css" href="'.$file.'" />';
            }
        }
        $res_string = implode("\n", $css_files);
        return $res_string;
    }
}
?>