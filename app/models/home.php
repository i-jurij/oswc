<?php
namespace App\Models;

use App\Lib\Db_init_sqlite;
use App\Lib\Traits\Css_add;

class Home
{
	use Css_add;

	public function get_data()
	{	
		// get vars for home page from database
		$data = [];
		//$css = $this->css_add();
		$db = new Db_init_sqlite;
		if ($db->db->has("pages", ["page_alias" => "home"])) {
			$data = $db->db->get("pages", ["page_title", "page_meta_description", "page_meta_keywords", "page_h1"], ["page_alias" => "home"]);
		}
		
		$data['content'] = (file_exists('README.md')) ? file('README.md') : array(
			"<table>Home<tr><td>N</td><td>Controller</td><td>Desc</td></tr>",
			array(
				'N' => '1',
				'Controller' => 'Home',
				'Desc' => 'Home page.'
			),
			array(
				'N' => '2',
				'Controller' => 'Adm',
				'Desc' => 'Page for site admins.'
			),
			array(
				'N' => '3',
				'Controller' => 'Adm login',
				'Desc' => 'admin'
			),
			array(
				'N' => '4',
				'Controller' => 'Adm pass',
				'Desc' => 'passw'
			),
			array(
				'N' => '5',
				'Controller' => 'Change login and pass in',
				'Desc' => 'table "users" in database'
			),
			"</table>"
		);
		return $data;
	}

}