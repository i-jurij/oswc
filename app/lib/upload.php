<?php
/*
*
* Only for single or multiple file uploads in next format
* A) <input type="file" name="name" > or 
* B) <input type="file" name="names[]" >
* the input data array for the class should be of this type
* A) single input $input_data_array =   array( 
                                'name' => array( 
                                                    'new_file_name' => '', //if empty = sanitize old filename, 
                                                    'destination_dir' => 'required', 
                                                    'file_size' => ''
                                                    'file_mimetype' => '' //string or array, audio or ['image', 'audio', 'video'], 
                                                    'file_ext' => '', //string or array(), jpg or ['php', 'html', 'txt']
                                                )
                                );
* A) eg two input $input_data_array =   array( 
                                'name_0' => array( 
                                                    'new_file_name_0' => '', 
                                                    'destination_dir_0' => '', 
                                                    'file_size_0' => ''
                                                    'file_mimetype' => '', 
                                                    'file_ext_0' => '',
                                                ),
                                'name_1' => array( 
                                                    'new_file_name_1' => '', 
                                                    'destination_dir_1' => '', 
                                                    'file_size_1' => ''
                                                    'file_mimetype' => '', 
                                                    'file_ext_1' => '',
                                                )    
                                );
* B) $input_data_array = ['names' => 
                                        [
                                            'new_file_name' => 'if empty = sanitize old filename', 
                                            'destination_dir' => 'required', 
                                            'file_size' => ''
                                            'file_mimetype' => '' //string or array, audio or ['image', 'audio', 'video'], 
                                            'file_ext' => '', //string or array(), jpg or ['php', 'html', 'txt']
                                        ] 
                        ];
* B) the name of each file will be "sanitize_new_file_name_Inputs_index" or "sanitize_old_name_index"
* dest_dir, file_size, File_type - will be the same for all files
*
* in function init: $create_dest_dir = false - default
*
* file_size - default 3MB, can be changed in function init 
*
* file_mimetype and file_ext - default empty (all files can be uploaded), can be changed in function init 
* the extension refines the mimetype. mimetype is checked first, then extension is checked, 
* if the extension of the downloaded file is suitable for mimetype, 
* but does not match the extension specified by the user - the file will not be uploaded.
*
*/
namespace App\Lib;

class Upload
{
    use \App\Lib\Traits\Sanitize;

    public array $files;
    public array $phpFileUploadErrors;
    public array $errors;
    public string $message;
    protected array $message_value;
    protected $create_dest_dir;
    protected $file_size;
    protected $file_mimetype; 
    protected $permissions;

    public function __construct($data_array) {
        $this->go($data_array) ;
    }

    protected function init() {
        //declaring variables
        $this->create_dest_dir = false;
        $this->file_size = 3072000; // 3MB
        $this->file_mimetype = '';
        $this->permissions = 0700;
        $this->message = '';
        $this->errors = [];
        $this->phpFileUploadErrors = array(
            0 => 'There is no error, the file uploaded with success',
            1 => 'The uploaded file exceeds the upload_max_filesize directive in php.ini',
            2 => 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form',
            3 => 'The uploaded file was only partially uploaded',
            4 => 'No file was uploaded',
            6 => 'Missing a temporary folder',
            7 => 'Failed to write file to disk.',
            8 => 'A PHP extension stopped the file upload.',
        );
    }

    public function go($data_array) { 
        $this->init();
        if (is_array($data_array)) {
            $data_array_is_array = true;
        } else {
            $this->message .= 'ERROR! Input data is not an array<br />';
        }

        if ($data_array_is_array && empty($data_array)) {
            $this->message .= 'Input data array for class upload is empty.<br />If you don\'t upload file - it\'s OK.<br />';
        } else {
            foreach ($data_array as $input_data=> $input_value) {
                if (empty($input_value['destination_dir'])) {
                    $this->message .= 'ERROR! Set the destination folder in input data array for class upload.<br />';
                } else {
                    if (!empty(self::normalize_files_array($_FILES))) {
                        $this->files = self::normalize_files_array($_FILES);
                        //$this->message = 'Array $_FILES normalized<br />';
                        if (isset($this->files[$input_data])) {
                            if ( is_array($this->files[$input_data])) {
                                foreach ($this->files[$input_data] as $key => $val) {
                                    if ($val['error'] === 0) {
                                        //sanitize filename or create filename from old filename
                                        if (empty($input_value['new_file_name'])) {
                                            //create new file name
                                            $path_parts = pathinfo($val['name']);
                                            $ext = $path_parts['extension'];
                                            if (count($this->files[$input_data]) > 1) {
                                                $name = $this->sanitize_string($path_parts['filename']);
                                                $new_name = $key.'_'.$name.'.'.$ext;
                                            } else {
                                                $name = $this->sanitize_string($path_parts['filename']);
                                                $new_name = $name.'.'.$ext;
                                            }
                                        } else {
                                            $new_name = $this->sanitize_string($input_value['new_file_name']);
                                        }
                                        //check file_exists file with sanitize filename

                                        //check filesize

                                        //check mimetype and ext

                                        // check dest dir
                                        if ($this->check_dest_dir($input_value['destination_dir'], $input_data)) {
                                            // move_upload to tmp dir
                                            // file processing (rotate, crop, resize etc)
                                            // copy or move to dest_dir
                                            // empty or unlink tmp dir
                                            $this->message .= 'OK';
                                        } else {
                                            break;
                                        }
                                        

                                            
                                        
                                    } else {
                                        if (array_key_exists($val['error'], $this->phpFileUploadErrors)) {
                                            $this->errors[$input_data][$key] = 'ERROR in input "'.$input_data.'['.$key.']" :<br />'.$this->phpFileUploadErrors[$val['error']];
                                        }
                                    }
                                }
                            } else {
                                $this->message .= 'ERROR! Processed $_FILES data is not an array.<br />: "'.$input_data.'".<br />';
                            }
                        } else {
                            $this->message .= 'Name of input not isset in $_FILES. <br />Maybe it\'s a mistake in the name of input '.'"'.$input_data.'" for class Upload.';
                        }
                    } else {
                        $this->message .= 'Array $_FILES["'.$input_data.'"] is empty.<br />If you don\'t upload file - it\'s OK.<br />';
                    }
                }
            }
        }
    }

    public function check_dest_dir($dest_dir, $input_data) {
        if (file_exists($dest_dir)) {
            if (is_dir($dest_dir)) {
                if (!is_writable($dest_dir) && !chmod($dest_dir, 0755)) {
                    $this->message .= 'Cannot change the mode of dir "'.$dest_dir.'" for input "'.$input_data.'"';
                    return false;
                } else {
                    return true;
                }
            } else {
                $this->message .= 'ERROR! "'.$dest_dir.'" for input "'.$input_data.'" is file.<br />';
                return false;
            }
        } else {
            # create dir if $create_dest_dir = true or message - dir not exists
            if ($this->create_dest_dir) {
                if (mkdir($dest_dir, $this->permissions, true)) {
                    return true;
                } else {
                    $this->message .= 'ERROR! Failed to create directory "'.$dest_dir.'" for input "'.$input_data.'".<br />';
                    return false;
                }
            } else {
                $this->message .= 'ERROR! "'.$dest_dir.'" for input "'.$input_data.'" NOT EXISTS.<br />';
                return false;
            }
        }
    }

    public static function normalize_files_array() {
        $normalized_array = [];
        if (isset($_FILES)) {
            foreach($_FILES as $index => $file) {
                if (!is_array($file['name'])) {
                    if (!empty($file['name'])) {
                        $normalized_array[$index][] = $file;
                        continue;
                    }
                }
                if (!empty($file['name'])) {
                    foreach($file['name'] as $idx => $name) {
                        if (!empty($name)) {
                            $normalized_array[$index][$idx] = [
                                'name' => $name,
                                'type' => $file['type'][$idx],
                                'tmp_name' => $file['tmp_name'][$idx],
                                'error' => $file['error'][$idx],
                                'size' => $file['size'][$idx]
                            ];
                        }
                    }
                }
            }
        }
        return $normalized_array;
    }
}

?>