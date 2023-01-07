<?php
namespace App\Models;

class Create_delete_page extends Adm
{
	protected function db_query() 
	{
		$this->data['page_db_data'] = array(array("page_alias" => "create_delete_page", 
                                                "page_title" => "Создать или удалить страницу",
                                                "page_meta_description" => "Создать или удалить страницу",
                                                "page_robots" => "NOINDEX, NOFOLLOW",
                                                "page_h1" => "Создать или удалить страницу",
                                                "page_access" => "admin"));
	}

    public function create()
	{	
        //  if adm page
        // created controller- extends class \App\Controllers\Adm because this is need authorization
        // but created model extends \App\Models\Home because this is need query db and get page data
        // as \App\Models\Adm not query data from db 
        
	}

    public function delete()
	{	

	}
}
