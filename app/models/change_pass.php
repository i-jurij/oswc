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

    public function get_data($path)
	{	
        $this->data['path'] = $path;
		$this->db_query();
        $this->data['users'] = $this->db->db->select("users", "username");
		//add css for head in template
		$this->data['css'] = $this->css_add('public'.DS.'css'.DS.'first');		
		return $this->data;
	}

    public function add($path)
	{	
        $this->data['test'] = 'rrr';
        return $this->data;
	} 

    public function delete($path)
	{	
        
	} 

    public function change($path)
	{	
        
	}
}