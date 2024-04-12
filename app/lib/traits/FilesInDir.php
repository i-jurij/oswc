<?php

namespace App\Lib\Traits;

trait FilesInDir
{
  /**
 * @param string $path - dir for scan
 * @param string $ext  - extension of files eg 'png' or 'png, webp, jpg'
 *
 * @return array path to files
 */
function filesInDirScan($path, $ext = '')
{
    $files = [];
    if (file_exists($path)) {
        $f = scandir($path);
        foreach ($f as $file) {
            if (is_dir($file)) {
                continue;
            }
            if (empty($ext)) {
                $files[] = $file;
            } else {
                $arr = explode(',', $ext);
                foreach ($arr as $value) {
                    $extt = mb_strtolower(trim($value));
                    // $extt = mb_strtolower(ltrim(trim($value), '.'));
                    /*
                    if(preg_match("/\.($extt)/", $file)) {
                      $files[] = $file;
                    }
                    */
                    if ($extt === mb_strtolower(pathinfo($file, PATHINFO_EXTENSION))) {
                        $files[] = $file;
                    }
                }
            }
        }
    }

    return $files;
}

  /**
   * @param string $dir - dir for scan
   * @param string $ext - extension of files eg 'png' or 'png, webp, jpg'
   * @return array basename  of files or false
   */
  function filesInDirIter($dir, $ext = '')
  {
    if (file_exists($dir) && is_dir($dir) && is_readable($dir)) {
      foreach (new \DirectoryIterator($dir) as $fileInfo) {
        if ($fileInfo->isDot()) continue;
        if (empty($ext)) {
          $files[] = $fileInfo->getBasename();
        } else {
          $arr = explode(',', $ext);
          foreach ($arr as $value) {
            $extt = mb_strtolower(ltrim(trim($value), '.'));
            if ($extt === $fileInfo->getExtension()) {
              $files[] = $fileInfo->getBasename();
            }
          }
        }
      }
    } else {
      return false;
    }
    return $files;
  }
}
