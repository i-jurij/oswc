<?php
function getOutput ($file) {
  ob_start();
  include $file;
  $output = ob_get_contents();
  ob_end_clean();
  return $output;
}

function files_in_dir($path, $ext) 
{
  $files = array();
  $f = scandir($path);
  foreach ($f as $file){
    if(preg_match("/\.($ext)/", $file)) {
      $files[] = $file;
    }
  }
  return $files;
}
?>