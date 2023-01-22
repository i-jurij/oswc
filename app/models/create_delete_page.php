<?php
namespace App\Models;

use App\Lib\Sql_col_names;
use App\Lib\Upload;

class Create_delete_page extends Adm
{
	protected function db_query() 
	{
		$this->data['page_db_data'] = array(array("page_alias" => "create_delete_page", 
                                                "page_title" => "Страницы",
                                                "page_meta_description" => "Создать или удалить страницу",
                                                "page_robots" => "NOINDEX, NOFOLLOW",
                                                "page_h1" => "Страницы",
                                                "page_access" => "admin"));
	}

    public function create()
	{	
        //VALIDATE PAGE DATA for SQL DB
        $this->data['name'] = 'Создать';
        if (filter_has_var(INPUT_POST, 'page_alias') && !empty($_POST['page_alias'])) {
            $this->data['res'] = null;
            $table = (!empty($_POST['page_access'])) ? 'adm_pages' : 'pages';

            foreach ($_POST as $key => $value) {
                if ( $key === 'page_alias') {
                    if (!preg_match("/^[a-zA-Zа-яА-ЯёЁ0-9-_]{1,100}$/", $value)) {
                        $this->data['res'] .= 'ERROR!<br />Page_alias "'.$value.'" не соответствует шаблону.<br />';
                        $end = true;
                    }
                    $aliases = $this->db->db->select($table, "page_alias");
                    if (in_array($value, $aliases)) {
                        $this->data['res'] .= 'ERROR!<br />Page_alias "'.$value.'" уже существует. Придумайте другой.<br />';
                        $end = true;
                    } 
                }

                if (($key === 'page_templates' || $key === 'page_title' || $key === 'page_robots' || $key === 'page_h1' ) 
                    && strlen($value) > 100 ) 
                {
                    $this->data['res'] .= 'ERROR!<br />'.$key.' "'.mb_substr($value, 0, 30).'..." слишком длинный.<br />';
                    $end = true;
                }
                if ($key !== 'MAX_FILE_SIZE') {
                    $post[$key] = (!empty($value)) ? $value: null;  
                }
            }

            if ( isset($end) && $end === true ) {
                goto end;
            }
            //INSERT DATA INTO SQL TABLE
            if ( isset($post) ) {
                /*
                $res = $this->db->db->insert($table, $post);
                if ($res->rowCount()) {
                    $this->data['res'] .= 'Page data has been inserted to db.<br />';
                } else {
                    $this->data['res'] .= 'ERROR! <br />Page DATA has NOT been INSERTED to db.<br />';
                }
                */
            }

            //UPLOAD FILES "TEMPLATE", "PICTURE"
            $input_data_array = [   'template' => array( 
                                        'new_file_name' => '', 
                                        'destination_dir' => PUBLICROOT.DS.'templates', 
                                        'file_size' => '', 
                                        'file_mimetype' => ['text/php', 'text/html', 'text/x-php', 'application/x-httpd-php', 'application/php', 'application/x-php', 'application/x-httpd-php-source' ],
                                        'file_ext' => ['php', '.html'],
                                        'dir_permissions' => '', // default 0700
                                        'replace_old_file' => '', //default false
                                        'tmp_dir' => ''
                                    ),
                                    'picture' => array( 
                                        'new_file_name' => '', 
                                        'destination_dir' => PUBLICROOT.DS.'imgs/pages', 
                                        'file_size' => 1024000, //1MB
                                        'file_mimetype' => 'image',
                                        'file_ext' => ['jpg', 'png', 'webp', 'jpeg', 'image'],
                                        'dir_permissions' => '', // default 0700
                                        'replace_old_file' => '', //default false
                                        'tmp_dir' => ''
                                    )
                                ];
            $files = new Upload($input_data_array);
            $this->data['res'] .= $files->message;

            print '<pre>';
            if (isset($files->files)) {
                print_r($files->files);
                if (isset($files->errors)) {
                    print_r ($files->errors);
                }
            }
            print '</pre>';


            //CREATE CONTROLLER, MODEL, VIEW
            if ( isset($post) ) {
               $filename = $post['page_alias'];
            }

            //OUTPUT MESSAGE if isset error
            if (strpos($this->data['res'], 'RROR') or strpos($this->data['res'], 'rror')) {
                $this->data['res'] .= 'ATTENTION! If there are errors, delete page and enter all page data again<br />';
            } 

            unset($post,$table, $aliases, $value, $key, $end, $res, $filename );
            end:
        } else {
            //print_r($_GET);
            $q = $_SERVER['QUERY_STRING'];
            if (is_string($q) && strlen(htmlentities(trim($q))) === 1 ) {
                if ($q === 'a') { $table = 'adm_pages'; $this->data['name'] .= '&nbsp;в adm_pages';}
                elseif ($q === 'b') { $table = 'pages'; $this->data['name'] .= '&nbsp;в pages';}
                if (!empty($table)) {
                    $colnames = (new Sql_col_names($this->db, $table))->res;
                    $this->data['colname'] = $colnames;
                }
            }
            unset($q,$table, $colnames );
        }
        return $this->data;
	}

    public function delete()
	{	
        $this->data['name'] = 'Удалить';
        $this->data['res'] = null;
        $q = $_SERVER['QUERY_STRING'];
        if (is_string($q) && strlen(htmlentities(trim($q))) === 1 ) {
            if ($q === 'a') { $table = 'adm_pages'; $this->data['name'] .= '&nbsp;в adm_pages';}
            elseif ($q === 'b') { $table = 'pages'; $this->data['name'] .= '&nbsp;в pages';}
        }
        if (filter_has_var(INPUT_POST, 'delete_page')) {
            //delete controller, model, view (except adm, home)

            //delete template (except adm_templ.php and templ.php if is the only one)

            //delete page img

            //delete data from db
            if (is_array($_POST['delete_page'])) {
                foreach ($_POST['delete_page'] as $value) {
                    if (!empty($value)) {
                        $postdel[] = htmlentities($value);
                    }
                }
                $res = $this->db->db->delete($table, ["page_alias" => $postdel]);
                if ($res->rowCount() > 0) {
                    $this->data['res'] .= 'Pages data has been deleted from db table';
                } else {
                    $this->data['res'] .= 'ERROR!<br /> The data has NOT been DELETED from database.<br />'.$this->db->db->error;
                }
            } else { 
                $this->data['res'] .= 'ERROR! Data is not array.';
            }
            unset($value, $res);
        } else {
            if ($table === 'pages') {
                $this->data['pagename'] = $this->db->db->select($table, ["page_alias", "page_title", "page_templates", "page_img"]);
            } else {
                $this->data['pagename'] = $this->db->db->select($table, ["page_alias", "page_title", "page_templates"]);
            }
            //list of files in templates dir
            $this->data['templates_list'] = files_in_dir(PUBLICROOT.DS.'templates', 'php');
        }
        return $this->data;
	}
}
