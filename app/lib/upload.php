<?php
/*
* Only for single or multiple file uploads in next format
* A) <input type="file" name="name" > or 
* B) <input type="file" name="names[]" >
* the input data array for the class should be of this type
* A) single input $input_data_array =   array( 
                                'name' => array( 
                                                    'new_file_name' => 'if empty = sanitize old filename', 
                                                    'destination_dir' => 'required', 
                                                    'file_size' => '', 
                                                    'file_type' => string or array()
                                                )
                                );
* A) eg two input $input_data_array =   array( 
                                'name0' => array( 
                                                    'new_file_name' => 'if empty = sanitize old filename', 
                                                    'destination_dir' => 'required', 
                                                    'file_size' => '', 
                                                    'file_type' => string or array()
                                                ),
                                'name1' => array( 
                                                    'new_file_name' => '', 
                                                    'destination_dir' => 'required', 
                                                    'file_size' => '', 
                                                    'file_type' => string or array()
                                                )    
                                );
* B) $input_data_array = ['names' => 
                                        [
                                            'new_file_name' => 'if empty = sanitize old filename', 
                                            'destination_dir' => 'required', 
                                            'file_size' => '', 
                                            'file_type' => string or array()
                                        ] 
                        ];
* B) the name of each file will be "sanitize_new_file_name_Inputs_index" or "sanitize_old_name_index"
* dest_dir, file_size, File_type - will be the same for all files
*/
namespace App\Lib;

class Upload
{
    public array $files;
    public array $phpFileUploadErrors;
    public array $errors;
    public string $message;
    protected array $message_value;

    public function __construct($data_array) {
        $this->validate($data_array) ;
    }

    protected function init() {
        //declaring variables
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

    public function validate($data_array) { 
        $this->init();
        if (is_array($data_array)) {
            if (empty($data_array)) {
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
                                            //check other 

                                            
                                            $this->message .= 'OK';
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
                            $this->message .= 'Array $_FILES is empty.<br />If you don\'t upload file - it\'s OK.<br />';
                        }
                    }
                }
            }
        } else {
            $this->message .= 'ERROR! Input data is not an array<br />';
        }
    }

    public function run() {
# this->run:
                    //check size
                    //check type
                    //move to tmp dir
                    //sanitize name (oldname)
                    //move to dir
                    //clean tmp dir
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