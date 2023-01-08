<?php
namespace App\Models;

class Change_pass extends Adm
{
	protected function db_query() 
	{
		$this->data['page_db_data'] = array(array("page_alias" => "change_pass", 
                                                "page_title" => "Изменить данные пользователей",
                                                "page_meta_description" => "Изменить явки и пароли",
                                                "page_robots" => "NOINDEX, NOFOLLOW",
                                                "page_h1" => "Изменить данные пользователей",
                                                "page_access" => "admin"));
	}

    public function add($path)
	{	
        if (strlen(filter_has_var( INPUT_POST, "login" )) < 256 
            && filter_has_var( INPUT_POST, "password" )  
            && filter_has_var( INPUT_POST, "status" ) ) 
        {
            $this->data['reg'] = 'DATA is INSERT to TABLE';
        } else {
            $file = APPROOT.DS.'view'.DS.'registration.php';
            if (is_readable($file)) {
                $this->data['reg'] = file_get_contents($file);
            }else {
                $this->data['reg'] = 'Проверьте наличие файла '.$file.' и права доступа к нему.';
            }             
        }
        return $this->data;
	} 

    public function delete($path)
	{	
        $this->data['users'] = $this->db->db->select("users", "username");
	} 

    public function change($path)
	{	
        $this->data['users'] = $this->db->db->select("users", "username");
	}
}