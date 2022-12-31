<?php
namespace App\Models;

use App\Lib\Db_init_sqlite;
use App\Lib\Traits\Css_add;

class Adm
{
	use Css_add;

    protected $path;
    protected $get_query;
    protected $post_query;

	public function get_data($path = [], $get_query = [], $post_query = [])
	{	
		// get vars for home page from database
		$data = [];
		
		$db = new Db_init_sqlite;
		//add data for head in template
		if ($db->db->has("pages", ["page_alias" => "adm"])) {
			$data = $db->db->get("pages", ["page_title", "page_meta_description", "page_meta_keywords", "page_h1"], ["page_alias" => "home"]);
		}
		//add css for head in template
		$data['css'] = $this->css_add();

	}

}
