<?php

namespace App\Models;

use App\Lib\ImageProc;
use App\Lib\SanitizeFileName;
use App\Lib\SqlColNames;
use Fileupload\Upload;

class Createdeletepage extends Adm
{
    use \App\Lib\Traits\DeleteFiles;
    use \App\Lib\Traits\FileFind;
    use \App\Lib\Traits\FilesInDir;
    use \App\Lib\Traits\MbUcfirst;

    protected function dbQuery()
    {
        $this->data['page_db_data'] = [['page_alias' => 'Createdeletepage',
                                                'page_title' => 'Страницы',
                                                'page_meta_description' => 'Создать или удалить страницу',
                                                'page_robots' => 'NOINDEX, NOFOLLOW',
                                                'page_h1' => 'Страницы',
                                                'page_access' => 'admin']];
    }

    public function create()
    {
        // VALIDATE PAGE DATA for SQL DB
        $this->data['name'] = 'Создать';
        if (filter_has_var(INPUT_POST, 'page_alias') && !empty($_POST['page_alias'])) {
            $this->data['res'] = null;

            foreach ($_POST as $key => $value) {
                if ($key === 'page_alias') {
                    if (!preg_match('/^[a-zA-Z0-9-_]{1,100}$/', $value)) {
                        $this->data['res'] .= 'ERROR!<br />Page_alias "'.$value.'" не соответствует шаблону.<br />';
                        $end = true;
                    }
                    $filename = $this->mbUcfirst(SanitizeFileName::run($value));
                    $aliases = $this->db->db->select($this->table, 'page_alias');
                    if (in_array($filename, $aliases)) {
                        $this->data['res'] .= 'ERROR!<br />Page_alias "'.$value.'" уже существует. Придумайте другой.<br />';
                        $end = true;
                    }
                }

                if ($key === 'page_templates' || $key === 'page_title' || $key === 'page_robots' || $key === 'page_h1') {
                    if (strlen($value) > 100) {
                        $this->data['res'] .= 'ERROR!<br />'.$key.' "'.mb_substr($value, 0, 30).'..." слишком длинный (very long).<br />';
                        $end = true;
                    }

                    if (!empty($post[$key]) && filterString($post[$key]) == false) {
                        $this->data['res'] .= 'ERROR!<br />Текст в (Value of) '.$key.' состоит только из запрещенных символов (is not valid).<br />';
                        $end = true;
                    }
                }
                if ($key !== 'MAX_FILE_SIZE') {
                    if ($key === 'page_alias') {
                        $post['page_alias'] = $this->mbUcfirst(SanitizeFileName::run($value));
                    } else {
                        $post[$key] = (!empty($value)) ? $value : null;
                    }
                }
            }

            if (isset($end) && $end === true) {
                goto end;
            }

            if (isset($post)) {
                // INSERT DATA INTO SQL TABLE
                $res = $this->db->db->insert($this->table, $post);
                if ($res->rowCount()) {
                    $this->data['res'] .= 'Page data has been inserted to db.<br />';
                } else {
                    $this->data['res'] .= 'ERROR! <br />Page DATA has NOT been INSERTED to db.<br />';
                    goto end;
                }

                // CREATE CONTROLLER, MODEL, VIEW
                /*
                if (function_exists('mb_ucfirst')){
                    $classname = mb_ucfirst($post['page_alias'], 'UTF-8');
                }
                */

                $classname = $this->mbUcfirst(SanitizeFileName::run($post['page_alias']));
                $filename = $classname.'.php';

                $models = ['<?php'.PHP_EOL, 'namespace App\Models;'.PHP_EOL, 'class '.$classname.' extends Home'.PHP_EOL, '{'.PHP_EOL, '}'.PHP_EOL];
                $view_adm = ['<div class="content">'.PHP_EOL, $filename.PHP_EOL, '</div>'.PHP_EOL];
                $view_user = ['<div class="content">'.PHP_EOL
                                .'<?php'.PHP_EOL
                                .'if (!empty($data[\'res\'])) {'.PHP_EOL
                                    .'print $data[\'res\'];'.PHP_EOL
                                    .'include_once APPROOT.DS."view".DS."js_back.html";'.PHP_EOL
                                .'} else {'.PHP_EOL
                                    .'print \'start\';'.PHP_EOL
                                    .'include_once APPROOT.DS."view".DS."back_home.html";'.PHP_EOL
                                .'}?>'.PHP_EOL
                            .'</div>'.PHP_EOL];
                if (!empty($post['page_admin']) && $post['page_admin'] == 1) {
                    $controllers = ['<?php'.PHP_EOL, 'namespace App\Controllers;'.PHP_EOL, 'class '.$classname.' extends Adm'.PHP_EOL, '{'.PHP_EOL, '}'.PHP_EOL];
                    $view = $view_adm;
                } else {
                    $controllers = ['<?php'.PHP_EOL, 'namespace App\Controllers;'.PHP_EOL, 'class '.$classname.' extends Home'.PHP_EOL, '{'.PHP_EOL, '}'.PHP_EOL];
                    $view = $view_user;
                }
                $file_array = ['controllers', 'models', 'view'];
                foreach ($file_array as $value) {
                    /*
                    if (function_exists('mb_ucfirst')){
                        $name = mb_ucfirst($value, 'UTF-8');
                        if ( substr($name, -1) === 's' ) {
                            $name = substr($name, 0, -1);
                        }
                    }
                    */
                    $name = $this->mbUcfirst($value);
                    if (substr($name, -1) === 's') {
                        $name = substr($name, 0, -1);
                    }
                    if (file_exists(APPROOT.DS.$value.DS.$filename)) {
                        $this->data['res'] .= 'ERROR! <br />Dir or file "'.APPROOT.DS.$value.DS.$filename.'" exists.<br />';
                        goto end;
                    } else {
                        if (is_dir(APPROOT.DS.$value)) {
                            if (!is_writable(APPROOT.DS.$value) && !chmod(APPROOT.DS.$value, 0755)) {
                                $this->data['res'] .= 'ERROR!<br /> Cannot change the mode of dir "'.APPROOT.DS.$value.'".';
                                goto end;
                            } else {
                                if (file_put_contents(APPROOT.DS.$value.DS.$filename, $$value, LOCK_EX) === false) {
                                    $this->data['res'] .= 'ERROR!<br /> Cannot created file "'.APPROOT.DS.$value.DS.$filename.'".<br />';
                                    goto end;
                                } else {
                                    $this->data['res'] .= 'SUCCESS!<br />'.$name.' "'.$filename.'" has been created <br />'; // in dir "'.APPROOT.DS.$value.'".<br />';
                                }
                            }
                        } else {
                            $this->data['res'] .= 'ERROR! <br />"'.APPROOT.DS.$value.DS.$filename.'" is not a directory.<br />';
                            goto end;
                        }
                    }
                }
                // PROCESSING $_FILES
                $new_load = new Upload();

                // set vars for each inputs from form if you need it (array('name_of_input' => [vars]))
                $new_load->config = [
                    'template' => [
                        'dest_dir' => PUBLICROOT.DS.'templates', // where upload file will be saved
                        'file_size' => 3 * 100 * 1024,
                        'file_mimetype' => ['text/php', 'text/html', 'text/x-php', 'application/x-httpd-php', 'application/php', 'application/x-php', 'application/x-httpd-php-source'],
                        'file_ext' => ['.php', 'html'],
                        'new_file_name' => $classname,
                        'replace_old_file' => false,
                    ],
                    'picture' => [
                        'dest_dir' => PUBLICROOT.DS.'tmp',
                        'file_size' => 1 * 1000 * 1024, // 1MB
                        'file_mimetype' => ['image/jpeg', 'image/pjpeg', 'image/png', 'image/webp'],
                        'file_ext' => ['.jpg', '.jpeg', '.png', '.webp'],
                        'new_file_name' => $classname,
                        'replace_old_file' => false,
                    ],
                ];
                if ($new_load->issetData()) {
                    $new_load->upload();
                    // $this->data['res'] .= $new_load->infoToString();

                    // command for processing a image located in a tmp dir
                    $imagefile = self::findByFilename(PUBLICROOT.DS.'tmp', $classname);
                    if ($imagefile !== false) {
                        $this->data['res'] .= (new ImageProc())->imgForPage($imagefile, PUBLICROOT.DS.'imgs'.DS.'pages', 'jpg');
                        // del image file in tmp folder
                        if (self::delFile($imagefile) !== true) {
                            $this->data['res'] .= self::del_file_message;
                        }
                    } else {
                        $this->data['res'] .= 'WARNING! Image witn name "'.$classname.'" was not found in "'.PUBLICROOT.DS.'tmp"<br />';
                    }
                    // CLEAR FOLDER - all files will be deleted in a directory specified by user
                    // print '<br />' . Fileupload\Classes\DelFilesInDir::run('upload_pictures');
                }
            }
            end:
            // OUTPUT MESSAGE if isset error
            if (strpos($this->data['res'], 'RROR') or strpos($this->data['res'], 'rror')) {
                $this->data['res'] .= 'ATTENTION!<br /> If there are errors, delete page and enter all page data again<br />';
            }
            unset($post, $aliases, $value, $key, $end, $res, $filename, $model, $controller, $view);
        } else {
            $colnames = (new SqlColNames($this->db, $this->table))->res;
            $this->data['colname'] = $colnames;
            unset($q, $colnames);
        }

        return $this->data;
    }

    public function delete()
    {
        $this->data['name'] = 'Удалить';
        $this->data['res'] = null;
        if (filter_has_var(INPUT_POST, 'delete_page')) {
            if (is_array($_POST['delete_page'])) {
                foreach ($_POST['delete_page'] as $value) {
                    if (!empty($value)) {
                        $postdel[] = htmlentities($value);
                    }
                }
                if (!empty($postdel)) {
                    // delete data from db
                    $res = $this->db->db->delete($this->table, ['page_alias' => $postdel]);
                    if ($res->rowCount() > 0) {
                        $this->data['res'] .= 'Pages data has been deleted from db table.<br />';
                    } else {
                        $this->data['res'] .= 'ERROR!<br /> The data has NOT been DELETED from database.<br />'.$this->db->db->error;
                    }
                    foreach ($postdel as $val) {
                        // delete controller, model, view (except adm, home)
                        $file_array = ['controllers', 'models', 'view'];
                        foreach ($file_array as $va) {
                            $path = APPROOT.DS.$va.DS.$val.'.php';
                            $name = $this->mbUcfirst($va);
                            if (substr($name, -1) === 's') {
                                $name = substr($name, 0, -1);
                            }
                            if (self::delFile($path) === true) {
                                $this->data['res'] .= $name.' "'.$value.'" has been deleted.<br />';
                            } else {
                                $this->data['res'] .= self::del_file_message.'<br />';
                            }
                        }
                        unset($path);
                        // delete page img
                        $path = PUBLICROOT.DS.'imgs'.DS.'pages';
                        if (self::findByFilename($path, $val) === false) {
                            $this->data['res'] .= 'WARNING! Image named "'.$val.'" was not found for deletion.<br />';
                        } else {
                            $file_for_del = self::findByFilename($path, $val);
                            if (self::delFile($file_for_del)) {
                                $this->data['res'] .= 'Image "'.$val.'" has been deleted.<br />';
                            } else {
                                $this->data['res'] .= 'ERROR!<br />Image "'.$val.'" has not been deleted, because<br />'.self::del_file_message;
                            }
                        }
                        // delete template (except adm_templ.php and templ.php if is the only one)
                        unset($path);
                        // delete page img
                        $path = PUBLICROOT.DS.'templates';
                        if ($val != 'adm_templ' || $val != 'templ') {
                            if (self::findByFilename($path, $val) === false) {
                                $this->data['res'] .= 'WARNING! Template named "'.$val.'" was not found for deletion.<br />';
                            } else {
                                $file_for_del = self::findByFilename($path, $val);
                                if (self::delFile($file_for_del)) {
                                    $this->data['res'] .= 'Template "'.$val.'" has been deleted.<br />';
                                } else {
                                    $this->data['res'] .= 'ERROR!<br />Template "'.$val.'" has not been deleted, because<br />'.self::del_file_message;
                                }
                            }
                        } else {
                            $this->data['res'] .= 'WARNING! We need to replace "adm_teml.php" or "templ.php", otherwise everything will break.<br />';
                        }
                    }
                } else {
                    $this->data['res'] .= 'ERROR!<br /> Empty array of pages for delete.<br />';
                }
            } else {
                $this->data['res'] .= 'ERROR! Data is not array.<br />';
            }
            unset($value, $val, $va, $res, $path, $files, $k, $v);
        }
        // step 1 - list of pages and templates
        else {
            $pagename = $this->db->db->select($this->table, ['page_alias', 'page_title', 'page_admin']);
            // print_r($pagename);
            foreach ($pagename as $page) {
                if ($page['page_alias'] != 'home' && $page['page_alias'] != 'adm') {
                    if (!empty($page['page_admin'])) {
                        $this->data['adm_pagename'][] = $page;
                    } else {
                        $this->data['pagename'][] = $page;
                    }
                }
            }
            // list of files in templates dir
            $this->data['templates_list'] = $this->filesInDirScan(PUBLICROOT.DS.'templates', 'php, html');
        }

        return $this->data;
    }
}
