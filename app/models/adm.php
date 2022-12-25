<?php
namespace App\Models;

class Adm
{
    protected $path;
    protected $get_query;
    protected $post_query;

	public function get_data($path = [], $get_query = [], $post_query = [])
	{	
        $db = new \App\Lib\Db_init();
		// get vars for adm page from database
	}

}
