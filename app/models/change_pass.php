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
        if (isset($_SERVER['HTTP_REFERER']) && strpos($_SERVER['HTTP_REFERER'], $_SERVER['HTTP_HOST'])) {
            # code...
        } else {
            # code...
        }
        
        $this->data['test'] = 'rrr';
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