<?php
namespace App\Models;

class Change_pass extends Adm
{
    function __construct($name_of_table_for_db_query, $page_alias)
	{
		$this->db = new \App\Lib\Db_init_sqlite;
		$this->table = $name_of_table_for_db_query;
		$this->page = $page_alias;
		$this->data =  [];
	}

	protected function db_query() 
	{
		$this->data['page_db_data'] = array(array("page_alias" => "change_pass", 
                                                "page_title" => "Изменить логин и(или) пароль",
                                                "page_meta_description" => "Изменить явки и пароли",
                                                "page_robots" => "NOINDEX, NOFOLLOW",
                                                "page_h1" => "Изменить логин и(или) пароль",
                                                "page_access" => "admin"));
	}

    public function get_data($path)
	{	
		$this->db_query();
        $this->data['users'] = $this->db->db->select("users", "username");
		//add css for head in template
		$this->data['css'] = $this->css_add('public'.DS.'css'.DS.'first');		
		return $this->data;
	}

    public function change()
	{	
        
	}
}