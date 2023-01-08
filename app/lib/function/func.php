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

function menu($data) 
{
  $res = array_column($data['page_list'], 'page_alias', 'page_h1');
  if(!empty($data['nav'])){
    if (is_array($data['nav'])) {
      foreach ($data['nav'] as $value) {
        $ress[$value] = array_search($value, $res);
      }
    } 			
  }
  if(!empty($data['page_db_data'][0])){
    $ress[$data['page_db_data'][0]['page_alias']] = $data['page_db_data'][0]['page_h1'];
  }

  if ($data['page_db_data'][0]['page_alias'] == 'home' or $data['page_db_data'][0]['page_alias'] == 'adm') {
    $nav = '';
  } else {
    $nav = '<a href="'.URLROOT.'/adm">Главная</a>';
  }
  if (!empty($ress)) {
    $prevk = ''; $prevv = '';
    foreach ($ress as $key => $value) {
      if (empty($value)) {
        $value = $key;
      }
      if (!empty($prevk)) {
        $nav .= ' / <a href="'.URLROOT.$prevk.DS.$key.'">'.$value.'</a>';
        $prevk .= DS.$key;
      } else {
        if (empty($nav)) {
          $nav = '<a href="'.URLROOT.DS.$key.'">'.$value.'</a>';
        } else {
          $nav .= ' / <a href="'.URLROOT.DS.$key.'">'.$value.'</a>';
        }
        $prevk .= DS.$key;
      }
    }
  }
  print_r($nav);
}
?>