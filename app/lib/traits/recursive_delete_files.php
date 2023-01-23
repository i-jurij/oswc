<?php
namespace App\Lib\Traits;

trait Recursive_delete_files 
{
  public static function delTree($dir) {
    $mes = '';
    $files = array_diff(scandir($dir), array('.','..'));
    foreach ($files as $file) {
      if (is_dir("$dir/$file")) {
        self::delTree("$dir/$file");
      } else {
        if (!unlink("$dir/$file")) {
          $mes .= 'ERROR! Not unlink "'.$dir/$file.'".';
        }
      }
    }
    //return rmdir($dir);
    if (!empty($mes)) {
      return $mes;
    } else {
      return true;
    }
  }
}
?>