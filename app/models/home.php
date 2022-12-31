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
		
		$db = new Db_init_sqlite;
		//add data for head in template
		if ($db->db->has("pages", ["page_alias" => "home"])) {
			$data = $db->db->get("pages", ["page_title", "page_meta_description", "page_meta_keywords", "page_h1"], ["page_alias" => "home"]);
		}
		//add css for head in template
		$data['css'] = $this->css_add();

		$i = 0;
		foreach ($db->db->select("contacts", ["contacts_type", "contacts_data"]) as $value) {
			if ($value['contacts_type'] === 'tlf' && !empty($value['contacts_data'])) {
				$data['tlf'.$i] = $value['contacts_data'];
				$i++;
			} elseif (!empty($value['contacts_data'])) {
				$data[$value['contacts_type']] = $value['contacts_data'];
			}
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