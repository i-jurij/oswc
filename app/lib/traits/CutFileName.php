<?php

namespace App\Lib\Traits;

trait CutFileName
{
    /**
     * cuts out middle part of name so that total length of name is equal to specified value
     * @param string $only_file_name_without_path, eg pathinfo($filename, PATHINFO_FILENAME)
     * @param int $name_length - the number of characters in the name
     * @param string $encoding
     */
    public function cutFileName($only_file_name_without_path, $name_length = 101, $encoding = 'UTF-8')
    {
        if (!empty($only_file_name_without_path)) {
            if (mb_strlen($only_file_name_without_path, $encoding) < $name_length) {
                $name = $only_file_name_without_path;
            } else {
                $first = mb_strimwidth($only_file_name_without_path, 0, $name_length / 2, "...");
                $last = mb_substr($only_file_name_without_path, -($name_length / 2), null, $encoding);
                $name =  $first . $last;
            }
        }
        return $name;
    }
}
