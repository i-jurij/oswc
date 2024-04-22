<?php

namespace App\Lib\Traits;

trait DeleteFiles
{
    public static string $del_file_message = '';

    public static function delFilesInDir(string $dir, bool $recursive = true)
    {
        if (!is_readable($dir)) {
            self::$del_file_message .= 'ERROR! Not readable "'.$dir.'".';

            return false;
        }
        $files = array_diff(scandir($dir), ['.', '..']);
        foreach ($files as $file) {
            if (is_dir($dir.DIRECTORY_SEPARATOR.$file) && $recursive === true) {
                self::delFilesInDir($dir.DIRECTORY_SEPARATOR.$file, true);
            } else {
                if (!unlink($dir.DIRECTORY_SEPARATOR.$file)) {
                    self::$del_file_message .= 'ERROR! Not unlink "'.$dir / $file.'".';

                    return false;
                } else {
                    return true;
                }
            }
        }
    }

    /**
     * delete a file or files.
     *
     * @return bool
     */
    public static function delFile(string $path2file)
    {
        if (is_string($path2file)) {
            // $path2file = realpath($path2file);
            if (file_exists($path2file)) {
                if (is_writable($path2file)) {
                    if (unlink($path2file)) {
                        return true;
                    } else {
                        self::$del_file_message .= 'ERROR! Not unlink "'.$path2file.'".';

                        return false;
                    }
                } else {
                    self::$del_file_message .= 'ERROR! File "'.$path2file.'" is not writable.';

                    return false;
                }
            } else {
                self::$del_file_message .= 'WARNING! File "'.$path2file.'" is not exists.';

                return false;
            }
        } else {
            self::$del_file_message .= 'ERROR! Input for $this->del_file($path2file) must be sring.';

            return false;
        }
    }

    public function del_empty_dir($dir)
    {
        if ([] === array_diff(scandir($dir), ['.', '..'])) {
            if (rmdir($dir)) {
                return true;
            } else {
                return false;
            }
        }
    }

    /**
     * delete a file or directory
     * automatically traversing directories if needed.
     * PS: has not been tested with self-referencing symlink shenanigans, that might cause a infinite recursion, i don't know.
     *
     * @throws \RuntimeException if unlink fails
     * @throws \RuntimeException if rmdir fails
     */
    public static function unlinkAllRecursive(string $path, bool $verbose = true): void
    {
        if (!is_readable($path)) {
            return;
        }
        if (is_file($path)) {
            if ($verbose) {
                echo "unlink: {$path}\n";
            }
            if (!unlink($path)) {
                throw new \RuntimeException("Failed to unlink {$path}: ".var_export(error_get_last(), true));
            }

            return;
        }
        $foldersToDelete = [];
        $filesToDelete = [];
        // we should scan the entire directory before traversing deeper, to not have open handles to each directory:
        // on very large director trees you can actually get OS-errors if you have too many open directory handles.
        foreach (new \DirectoryIterator($path) as $fileInfo) {
            if ($fileInfo->isDot()) {
                continue;
            }
            if ($fileInfo->isDir()) {
                $foldersToDelete[] = $fileInfo->getRealPath();
            } else {
                $filesToDelete[] = $fileInfo->getRealPath();
            }
        }
        unset($fileInfo); // free file handle
        foreach ($foldersToDelete as $folder) {
            self::unlinkAllRecursive($folder, $verbose);
        }
        foreach ($filesToDelete as $file) {
            if ($verbose) {
                echo "unlink: {$file}\n";
            }
            if (!unlink($file)) {
                throw new \RuntimeException("Failed to unlink {$file}: ".var_export(error_get_last(), true));
            }
        }
        if ($verbose) {
            echo "rmdir: {$path}\n";
        }
        if (!rmdir($path)) {
            throw new \RuntimeException("Failed to rmdir {$path}: ".var_export(error_get_last(), true));
        }
    }
}
