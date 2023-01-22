<?php
/*
*
* Only for single or multiple file uploads in next format
* A) <input type="file" name="name" > or 
* B) <input type="file" name="names[]" >
* the input data array for the class should be of this type
* A) single input $input_data_array =   array( 
                                'name' => array( 
                                                    'new_file_name' => '', // if empty = sanitize old filename
                                                    'destination_dir' => 'required' // path to destination dir
                                                    'file_size' => '' // integer, default 3072000 Byte
                                                    'file_mimetype' => '' // string or array, 'audio' or ['image', 'audio', 'video']
                                                    'file_ext' => '', // string or array(), 'jpg' or ['php', 'html', 'txt']
                                                    'permissions' => '', // binary, default 0700
                                                    'replace_old_file' => '', // yes, no or true, false or 0, 1; if empty - no
                                                    'rotate' => '',
                                                    'crop' => '',
                                                    'resize' => ''
                                                )
                                );
* A) eg two input $input_data_array =   array( 
                                'name_0' => array( 
                                                    'new_file_name' => '', 
                                                    'destination_dir' => '', 
                                                    'file_size' => ''
                                                    'file_mimetype' => '', 
                                                    'file_ext' => '',
                                                    'permissions' => '', // default 0700
                                                    'replace_old_file' => '' //default no
                                                ),
                                'name_1' => array( 
                                                    'new_file_name' => '', 
                                                    'destination_dir' => '', 
                                                    'file_size' => ''
                                                    'file_mimetype' => '', 
                                                    'file_ext' => '',
                                                    'permissions' => '', // default 0700
                                                    'replace_old_file' => '' //default no
                                                )    
                                );
* B) $input_data_array = ['names' => 
                                        [
                                            'new_file_name' => 'if empty = sanitize old filename', 
                                            'destination_dir' => 'required', 
                                            'file_size' => ''
                                            'file_mimetype' => '' //string or array, audio or ['image', 'audio', 'video'], 
                                            'file_ext' => '', //string or array(), jpg or ['php', 'html', 'txt']
                                            'permissions' => '', // default 0700
                                            'replace_old_file' => '' //default no
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
                        //$this->message .= 'Array $_FILES normalized<br />';
                        if (isset($this->files[$input_data])) {
                            //$this->message .= 'Messages for input "'.$input_data.'":<br />';
                            if ( is_array($this->files[$input_data])) {
                                foreach ($this->files[$input_data] as $key => $val) {
                                    if ($val['error'] === 0) {
                                        // check dest dir
                                        if (    $this->check_dest_dir($input_data, $input_value) 
                                                //check file size
                                                && $this->check_file_size($input_data, $input_value, $key, $val)
                                                //check mimetype
                                                && $this->check_mime_type($input_data, $input_value, $key, $val)
                                                //check ext
                                                && $this->check_extension($input_data, $input_value, $key, $val)
                                                //check file_exists file with sanitize filename
                                                && $this->check_new_file_name($input_data, $input_value, $key, $val)
                                            ) {
                                                $this->message .= 'Ok';
                                                // move_upload to tmp dir
                                                // file processing (rotate, crop, resize etc)
                                                // copy or move to dest_dir
                                                // empty or unlink tmp dir
                                            } else {
                                                break;
                                            }
                                    } else {
                                        if (array_key_exists($val['error'], $this->phpFileUploadErrors)) {
                                            $this->errors[$input_data][$key] = 'ERROR in input "'.$input_data.'['.$key.']" :<br />'.$this->phpFileUploadErrors[$val['error']];
                                        } else {
                                            $this->errors[$input_data][$key] = 'UNKNOWN ERROR! In input "'.$input_data.'['.$key.']".<br />';
                                        }
                                    }
                                }
                            } else {
                                $this->message .= 'ERROR! Processed $_FILES data is not an array.<br />: "'.$input_data.'".<br />';
                            }
                            $this->message .= '<br />';
                        } else {
                            $this->message .= 'Name of input "'.$input_data.'" not isset in $_FILES. <br />
                                                Reasons:    1) error in the name of input '.'"'.$input_data.'" in model,<br />
                                                            2) or you didn\'t upload the file.<br />';
                        }
                    } else {
                        $this->message .= 'Array $_FILES["'.$input_data.'"] is empty.<br />If you don\'t upload file - it\'s OK.<br />';
                    }
                }
            }
        }
    }

    protected function check_new_file_name($input_data, $input_value, $key, $val) {
        if ($this->new_name($input_data, $input_value, $key, $val)) {
            $new_name = $this->new_name($input_data, $input_value, $key, $val);
            if (file_exists($input_value['destination_dir'].DIRECTORY_SEPARATOR.$new_name)) {
                if ($input_value['replace_old_file'] === 'yes' || $input_value['replace_old_file'] === true || $input_value['replace_old_file'] == 1 ) {
                    return true;
                } else {
                    $this->message .= 'ERROR in data from input "'.$input_data.'"!<br /> 
                                        A file "'.$new_name.'"exists in "'.$input_value['destination_dir'].'".<br />
                                        Change "new_file_name" in array for upload class in model<br />
                                        or set "replace_old_file" = true or yes or 1.<br />';
                    return false;
                }
            } else {
                return true;
            }
        } else {
            return false;
        }
    }

    protected function new_name($input_data, $input_value, $key, $val) {
        //get patrs of files name 
        if (!empty($val['name'])) {
            $path_parts = pathinfo($val['name']);
        } else {
            $this->message .= 'ERROR! In input "'.$input_data.'['.$key.']": "name" from $_FILES is empty.<br />';
            return false;
        }
        //sanitize filename or create filename from old filename
        if (empty($input_value['new_file_name'])) {
            //create new file name
            if (count($this->files[$input_data]) > 1) {
                $name = $key.'_'.$this->sanitize_string($path_parts['filename']);
                return $name;
            } else {
                $name = $this->sanitize_string($path_parts['filename']);
                return $name;
            }
        } else {
            $name = $this->sanitize_string($input_value['new_file_name']);
            return $name;
        }   
    }

    protected function check_extension($input_data, $input_value, $key, $val) {
        $ext = $this->get_extension($val['name']);
        $pext = $this->get_point_ext($val['name']);
        if (empty($input_value['file_ext'])) {
            return true;
        } else {
            if (is_string($input_value['file_ext']) && ($ext === $input_value['file_ext'] || $pext === $input_value['file_ext'])) {
                return true;
            } elseif (is_array($input_value['file_ext'])) {
                if (in_array($ext, $input_value['file_ext']) || in_array($pext, $input_value['file_ext'])) {
                    return true;
                } else {
                    $this->message .= 'ERROR! In input "'.$input_data.'['.$key.']": wrong extension "'.$ext.'", expected "'.implode('", "', $input_value['file_ext']).'".<br />';
                    return false;
                }
            } else {
                $this->message .= 'ERROR! Wrong type in input data "file_ext", must be empty, string or array.<br />';
                return false;
            }
            
        }
	}

    public function get_extension($filename) {
		//$ext = strtolower(substr(strrchr($filename, '.'), 1));
        $path_info = pathinfo($filename);
        $ext = $path_info['extension'];
		return $ext;
	}

    public function get_point_ext($filename) {
		$ext = strtolower(strrchr($filename, '.'));
		return $ext;
	}

    public function get_only_file_name($filename) {
		//$ext = strtolower(substr(strrchr($filename, '.'), 1));
        $path_info = pathinfo($filename);
        $name = $path_info['filename'];
		return $name;
	}

    public function get_basename($filename) {
		//$ext = strtolower(substr(strrchr($filename, '.'), 1));
        $path_info = pathinfo($filename);
        $basename = $path_info['basename'];
		return $basename;
	}

    protected function check_mime_type($input_data, $input_value, $key, $val) {
        $mt = $this->get_mime_type($val['tmp_name']);
        list($core, $type) = explode('/', $mt);
        if ( empty($input_value['file_mimetype']) ) {
            return true;
        } else {
            if (is_string($input_value['file_mimetype'])) {
                if ((!empty($core) && $input_value['file_mimetype'] === $core) || $input_value['file_mimetype'] === $mt) {
                    return true;
                } else {
                    $this->message .= 'ERROR! In input "'.$input_data.'['.$key.']": wrong mimetype "'.$mt.'", expected "'.$input_value['file_mimetype'].'".<br />';
			        return false;
                }
            } else {
                if (is_array($input_value['file_mimetype'])) {
                    if ( (!empty($core) && in_array($core, $input_value['file_mimetype'])) || in_array($mt, $input_value['file_mimetype']) ) {
                        return true;
                    } else {
                        $this->message .= 'ERROR! In input "'.$input_data.'['.$key.']": wrong mimetype "'.$mt.'", expected "'.implode('", "', $input_value['file_mimetype']).'".<br />';
			            return false;
                    }
                } else {
                    $this->message .= 'ERROR! Wrong type in input data "file_mimetype", must be empty, string or array.<br />';
                    return false;
                }
            }
        }
	}

    function get_mime_type($file) {
		$mtype = false;
		if (function_exists('finfo_open')) {
			$finfo = finfo_open(FILEINFO_MIME_TYPE);
			$mtype = finfo_file($finfo, $file);
			finfo_close($finfo);
		} elseif (function_exists('mime_content_type')) {
			$mtype = mime_content_type($file);
		} 
		return $mtype;
	}

    protected function check_file_size($input_data, $input_value, $key, $val) {
        if (!empty($val['size'])) {
            $size = (!empty($input_value['file_size']) && is_numeric($input_value['file_size'])) ? $input_value['file_size'] : $this->file_size;
            if ( $val['size'] <= $size) {
               return true;
            } else {
                $this->message .= 'ERROR! In input "'.$input_data.'['.$key.']": Size "'.$val['name'].'" is too large.<br />';
                return false;
            }
        } else {
            $this->message .= 'ERROR! In input "'.$input_data.'['.$key.']": "size" from $_FILES is empty.<br />';
            return false;
        }
    }
    
    public function check_dest_dir($input_data, $input_value) {
        $dest_dir = htmlentities($input_value['destination_dir']);
        if (!empty($input_value['permissions'])) {
            $this->permissions = htmlentities($input_value['permissions']);
        } 
        if (file_exists($dest_dir)) {
            if (is_dir($dest_dir)) {
                if (!is_writable($dest_dir) && !chmod($dest_dir, $this->permissions)) {
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