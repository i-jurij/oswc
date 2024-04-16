<?php

namespace App\Lib\Traits;

trait CutFileName
{
    /**
     * cuts out middle part of name so that total length of name is equal to specified value.
     *
     * @param int $name_length - the number of characters in the name
     */
    public function cutFileName($only_file_name_without_path, $name_length = 101)
    {
        if (!empty($only_file_name_without_path)) {
            if (mb_strlen($only_file_name_without_path, $encoding) < $name_length) {
                $name = $only_file_name_without_path;
            } else {
                $first = mb_strimwidth($only_file_name_without_path, 0, $name_length / 2, '...', mb_detect_encoding($only_file_name_without_path));
                $last = mb_substr($only_file_name_without_path, -($name_length / 2), null, mb_detect_encoding($only_file_name_without_path));
                $name = $first.$last;
            }
        }

        return $name;
    }
}
