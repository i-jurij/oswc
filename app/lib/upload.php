<?php
/** 
* The class processes the entire FILES array at once.
*
* Only for single or multiple file uploads in next format
* A) <input type="file" name="name" > or 
* B) <input type="file" name="names[]" >
*
* The input data array must contain the path to the folder for uploading files.
* $input_data_array =   array( 'name' => array( 'destination_dir' => 'required') );
* where required - 
* string 'path/to/dir_for_upload_file' 
* or array ['path2dir', true], path and true|yes|1 for created destination dir (by default $this->create_dir = false)
*
* or full $input_data_array:
* A) single input $input_data_array =   array( 
                                'name' => array( 
                                                    'new_file_name' => '', // if empty = sanitize old filename
                                                    'destination_dir' => 'path_to_dir', // ['path2dir', true]
                                                    'file_size' => '' // integer, default 3072000 Byte
                                                    'file_mimetype' => '' // string or array, 'audio' or ['image', 'audio', 'video']
                                                    'file_ext' => '', // string or array(), 'jpg' or ['php', 'html', 'txt']
                                                    'dir_permissions' => '', // binary, default 0755
                                                    'file_permissions' => '', // binary, default 0644
                                                    'replace_old_file' => '', // yes, no or true, false or 0, 1; if empty - no
                                                    'tmp_dir' => '', // 'path_to_tmp_dir', default /tmp
                                                    'processing' => ['resizeToBestFit' => '300, 200', 'crop' => '200, 200'],  // array where key is method and value is parameters for imageresize class
                                                )
                                );
* A) eg two input $input_data_array =   array( 
                                'name_0' => array( 
                                                    'new_file_name' => '', 
                                                    'destination_dir' => '', 
                                                    'file_size' => ''
                                                    'file_mimetype' => '', 
                                                    'file_ext' => '',
                                                    'dir_permissions' => '', // default 0755
                                                    'replace_old_file' => '' //default no
                                                ),
                                'name_1' => array( 
                                                    'new_file_name' => '', 
                                                    'destination_dir' => '', 
                                                    'file_size' => ''
                                                    'file_mimetype' => '', 
                                                    'file_ext' => '',
                                                    'dir_permissions' => '', // default 0755
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
                                            'dir_permissions' => '', // default 0755
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
* dir_permissions - permissions for destination dir and tmp_dir
*
* file_permissions - permisssions for move_uploaded files
*
* tmp_dir - directory for temporary saved files, eg for postprocessing
*/
namespace App\Lib;

class Upload
{
    use \App\Lib\Traits\Sanitize;
    use \App\Lib\Traits\Mime2ext;
    use \App\Lib\Traits\Count_parametrs_of_method;
    use \App\Lib\Traits\Delete_files;
    use \App\Lib\Traits\Check_create_dir;
    use \App\Lib\Traits\Translit2lat;

    public array $files;
    public array $phpFileUploadErrors;
    public array $errors;
    public string $message;
    protected array $message_value;
    protected $destination_dir;
    protected $create_dir;
    protected $file_size;
    protected $file_mimetype;
    protected $dir_permissions;
    protected $file_permissions;
    protected $tmp_file_name;
    protected $new_file_name;
    protected $name;
    protected $tmp_dir;


    public function __construct($data_array) {
        $this->go($data_array) ;
    }

    protected function init() {
        //declaring variables
        $this->create_dir = false;
        $this->file_size = 3072000; // 3MB
        $this->file_mimetype = '';
        $this->dir_permissions = 0755;
        $this->file_permissions = 0644;
        $this->tmp_dir = PUBLICROOT.DS.'tmp';
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
            $this->message .= 'ERROR! Input data is not an array.';
        }

        if ($data_array_is_array && empty($data_array)) {
            $this->message .= 'Input data array for class upload is empty.<br />If you don\'t upload file - it\'s OK.';
        } else {
            foreach ($data_array as $input_data=> $input_value) {
                $this->message .= 'Input "'.$input_data.'":<br />';

                if (empty($input_value['destination_dir'])) {
                    $this->message .= 'ERROR! Set the destination folder in input data array for class upload.';
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
                                        if (    $this->check_dest_dir($input_value) 
                                                //check file size
                                                && $this->check_file_size($input_data, $input_value, $key, $val)
                                                //check mimetype
                                                && $this->check_mime_type($input_data, $input_value, $key, $val)
                                                //check ext
                                                && $this->check_extension($input_data, $input_value, $key, $val)
                                                //check file_exists file with sanitize filename
                                                && $this->check_new_file_name($input_data, $input_value, $key, $val)
                                            ) {
                                                // move_upload to tmp dir
                                                if ($this->move_upload($input_value, $val)) {
                                                    // file processing (rotate, crop, resize etc) $file = $this->tmp_dir.$this->new_file_name
                                                    if ( $this->check_processing($input_value) ) { 
                                                        $this->img_proc($input_value);
                                                        // clear tmp dir
                                                        if (self::del_files_in_dir($this->tmp_dir, true) === true) {
                                                            $this->message .= 'Tmp dir "'.$this->tmp_dir.'" has been cleared.';
                                                        } else {
                                                            $this->message .= self::del_files_in_dir($this->tmp_dir, true);
                                                        }
                                                    }  else {
                                                        break;
                                                    }
                                                } else {
                                                    break;
                                                }
                                            } else {
                                                break;
                                            }
                                    } else {
                                        if (array_key_exists($val['error'], $this->phpFileUploadErrors)) {
                                            $this->errors[$input_data][$key] = 'ERROR in input "'.$input_data.'['.$key.']" :<br />'.$this->phpFileUploadErrors[$val['error']];
                                        } else {
                                            $this->errors[$input_data][$key] = 'UNKNOWN ERROR! In input "'.$input_data.'['.$key.']".';
                                        }
                                    }
                                }
                            } else {
                                $this->message .= 'ERROR! Processed $_FILES data is not an array.<br />: "'.$input_data.'".';
                            }
                        } else {
                            $this->message .= 'You didn\'t upload the file "'.$input_data.'".';
                        }
                    } else {
                        $this->message .= 'Array $_FILES["'.$input_data.'"] is empty.<br />If you don\'t upload file - it\'s OK.';
                    }
                }
                $this->message .= '<br /><br />';
                //$this->message .= '<hr width="50%" color="SteelBlue" align="center" />';
            }
        }
    }

    protected function img_proc($input_value) {
        $file = $this->tmp_dir.$this->new_file_name;
        try{
            $image = new \App\Lib\Imageresize($file);
            if ( is_array($input_value['processing']) ) {
                foreach ($input_value['processing'] as $key => $value) {
                    if ( method_exists($image,$key) ) {
                        if (is_array($value) && $this->count_parameters_of_method($image, $key) == count($value)) {                            
                            call_user_func_array(array($image, $key), $value); //$value - array of parameters
                        } else {
                            $this->message .= 'ERROR! The "processing" value of "'.$key.'" is not array, or wrong numbers key of array (parameters for method of class App\Lib\Imageresize)';
                            return false;
                        }
                    } else {
                        $this->message .= 'ERROR! Method "'.$key.'" not exists in class App\Lib\Imageresize';
                        return false;
                    }
                }
            } 
            $image->save($this->destination_dir.DIRECTORY_SEPARATOR.$this->new_file_name); 
            $this->message .= 'SUCCES! File has been processed and copied to <br />"'.$this->destination_dir.DIRECTORY_SEPARATOR.$this->new_file_name.'".<br />';
            return true;
        } catch (\App\Lib\Imageresizeexception $e) {
            $this->message .= "ERROR! Something went wrong" . $e->getMessage();
            return false;
        }
    }

    protected function check_processing($input_value) {
        if (empty($input_value['processing'])) {
            return false;
        } else { 
            if ( is_array($input_value['processing']) && !empty($input_value['processing']) ) {
                return true;
            } else {
                return false;
            }
        }
    }

    protected function move_upload($input_value, $val) {
        if ( !empty($input_value['tmp_dir']) ) {
            $this->tmp_dir = $input_value['tmp_dir'].DIRECTORY_SEPARATOR;
        } 
        elseif ( empty($input_value['tmp_dir']) ) {
            if ( !$this->check_processing($input_value) ) { 
                $this->tmp_dir = $this->destination_dir.DIRECTORY_SEPARATOR;
            } 
        } 

        if ( $this->check_or_create_dir($this->tmp_dir, $this->dir_perm($input_value), $this->create_dir) ) {
            if (move_uploaded_file($val['tmp_name'] , $this->tmp_dir.$this->new_file_name)) {
                chmod($this->tmp_dir.$this->new_file_name , $this->file_permissions);
                $this->message .= 'File is uploaded to: "'.$this->tmp_dir.$this->new_file_name.'".<br />'; 
                return true;
            } else {
                $this->message .= 'ERROR! Possible file upload attack: "'.$val['tmp_name'].'".';
                return false;
            }
        } else {
            return false;
        }
    }

    protected function check_new_file_name($input_data, $input_value, $key, $val) {
        if ($this->new_name($input_data, $input_value, $key, $val)) {
            $new_name = $this->name.$this->get_point_ext($val['name']);
            if (file_exists($this->destination_dir.DIRECTORY_SEPARATOR.$new_name)) {
                if ($input_value['replace_old_file'] === 'yes' || $input_value['replace_old_file'] === true || $input_value['replace_old_file'] == 1 ) {
                    $this->new_file_name = $new_name; 
                    return true;
                } else {
                    $this->message .= 'WARNING!<br /> 
                                        A file "'.$new_name.'" exists in "'.$this->destination_dir.'".<br />
                                        Change "new_file_name" in array for upload class in model<br />
                                        or set "replace_old_file" = true or yes or 1.';
                    return false;
                }
            } else {
                $this->new_file_name = $new_name;
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
            $this->message .= 'ERROR! In input "'.$input_data.'['.$key.']": "name" from $_FILES is empty.';
            return false;
        }
        //sanitize filename or create filename from old filename
        if (empty($input_value['new_file_name'])) {
            //create new file name
            if (count($this->files[$input_data]) > 1) {
                $this->name = $key.'_'.$this->sanitize_string($this->translit_cyr_to_lat($path_parts['filename']));
                return true;
            } else {
                $this->name = $this->sanitize_string($this->translit_cyr_to_lat($path_parts['filename']));
                return true;
            }
        } else {
            $this->name = $this->sanitize_string($this->translit_cyr_to_lat($input_value['new_file_name']));
            return true;
        }   
    }

    protected function check_extension($input_data, $input_value, $key, $val) {
        $ext = $this->get_extension($val['name']);
        $pext = $this->get_point_ext($val['name']);
        if (empty($input_value['file_ext'])) {
            return true;
        } else {
            $mt = $this->get_mime_type($val['tmp_name']);
            // crutch for jpg
            if ( ($mt === "image/jpeg" or $mt === "image/pjpeg") && $ext === 'jpg' ) {
                $r = true;
            } else {
                if ($this->mime2ext($mt) === $ext) {
                    $r = true;
                } else {
                    $r = false;
                }
            }
            if ($r) {
                if (is_string($input_value['file_ext']) && ($ext === $input_value['file_ext'] || $pext === $input_value['file_ext'])) {
                    return true;
                } elseif (is_array($input_value['file_ext'])) {
                    if (in_array($ext, $input_value['file_ext']) || in_array($pext, $input_value['file_ext'])) {
                        return true;
                    } else {
                        $this->message .= 'ERROR! In input "'.$input_data.'['.$key.']": wrong extension "'.$ext.'", expected "'.implode('", "', $input_value['file_ext']).'".';
                        return false;
                    }
                } else {
                    $this->message .= 'ERROR! Wrong type in input data "file_ext", must be empty, string or array.';
                    return false;
                }
            } else {
                $this->message .= 'ERROR! In input "'.$input_data.'['.$key.']":<br />
                                    wrong extension "'.$ext.'", because mimetype of file for upload is: "'.$mt.'".';
                    return false;
            }
        }
	}

    public function get_extension($filename) {
		//$ext = strtolower(substr(strrchr($filename, '.'), 1));
        $path_info = pathinfo($filename);
        $ext = strtolower($path_info['extension']);
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
                    $this->message .= 'ERROR! In input "'.$input_data.'['.$key.']": wrong mimetype "'.$mt.'", expected "'.$input_value['file_mimetype'].'".';
			        return false;
                }
            } else {
                if (is_array($input_value['file_mimetype'])) {
                    if ( (!empty($core) && in_array($core, $input_value['file_mimetype'])) || in_array($mt, $input_value['file_mimetype']) ) {
                        return true;
                    } else {
                        $this->message .= 'ERROR! In input "'.$input_data.'['.$key.']": wrong mimetype "'.$mt.'", expected "'.implode('", "', $input_value['file_mimetype']).'".';
			            return false;
                    }
                } else {
                    $this->message .= 'ERROR! Wrong type in input data "file_mimetype", must be empty, string or array.';
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
                $this->message .= 'ERROR! In input "'.$input_data.'['.$key.']": Size "'.$val['name'].'" is too large.';
                return false;
            }
        } else {
            $this->message .= 'ERROR! In input "'.$input_data.'['.$key.']": "size" from $_FILES is empty.';
            return false;
        }
    }
    
    protected function dir_perm($input_value) {
        if (!empty($input_value['dir_permissions'])) {
            $this->dir_permissions = htmlentities($input_value['dir_permissions']);
        } 
    }

    public function check_dest_dir($input_value) {
        $dd = $input_value['destination_dir'];
        if (is_string($dd)) {
            $this->destination_dir = htmlentities($dd);
        } elseif (is_array($dd)) {
            if (!empty($dd[0])) {
                $this->destination_dir = htmlentities($dd[0]);
            } else {
                $this->message .= 'ERROR! Empty key "destination_dir"[0] in data for class Upload.';
                return false;
            }
            if ( !empty($dd[1]) && ($dd[1] === true || $dd[1] == 1 || $dd[1] === 'yes' || $dd[1] === 'true') ) {
                $this->create_dir = $dd[1];
            }
        } else {
            $this->message .= 'ERROR! Wrong type key "destination_dir" in data for class Upload.';
            return false;
        }
        $this->dir_perm($input_value);
        if ( $this->check_or_create_dir($this->destination_dir, $this->dir_permissions, $this->create_dir) === true ) {
            $this->message .= 'Destination dir "'.$this->destination_dir.'" exists.<br />';
            return true;
        } else {
            $this->message .= $this->check_or_create_dir($this->destination_dir, $this->dir_permissions, $this->create_dir);
            return false;
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