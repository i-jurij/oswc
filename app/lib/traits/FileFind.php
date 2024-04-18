<?php
/**
 * Find file by filename without extension.
 *
 * @param string $dir for find
 * @param string filename
 *
 * @return string or false
 */

namespace App\Lib\Traits;

trait FileFind
{
    /**
     * function find file by name without extension.
     *
     * @param $path     - directory
     * @param $filename - name without extension
     *
     * @return string path to file or boolean false
     */
    public static function findByFilename($path, $filename)
    {
        $files = scandir($path);
        foreach ($files as $k => $v) {
            $fname = pathinfo($v, PATHINFO_FILENAME);
            $only_name[$k] = $fname;
        }
        $name_key_name = array_search($filename, $only_name);
        if (!empty($name_key_name)) {
            return $path.DS.$files[$name_key_name];
        } else {
            return false;
        }
    }
}
