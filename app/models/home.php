<?php
namespace App\Models;

use App\Lib\Db_init_sqlite;
use App\Lib\Traits\Css_add;

class Home
{
	use Css_add;

	public array $data;
	protected $db;
	protected string $table;
	protected string $page;

	function __construct($name_of_table_for_db_query, $page_alias)
	{
		$this->db = new Db_init_sqlite;
		$this->table = $name_of_table_for_db_query;
		$this->page = $page_alias;
		$this->data =  [];
	}

	protected function db_query() 
	{
		//add data for head in template
		if ($this->db->db->has($this->table, ["page_alias" => $this->page])) {
		$this->data['page_db_data'] = $this->db->db->select($this->table, "*", ["page_alias" => $this->page]);
		}
	}

	public function get_data($path)
	{	
		$this->db_query();
		//get page list from db
		$this->data['page_list'] = $this->db->db->select($this->table, "*");
		//add css for head in template
		$this->data['css'] = $this->css_add('public'.DS.'css'.DS.'first');

		$i = 0;
		foreach ($this->db->db->select("contacts", ["contacts_type", "contacts_data"]) as $value) {
			if ($value['contacts_type'] === 'tlf' && !empty($value['contacts_data'])) {
				$this->data['tlf'.$i] = $value['contacts_data'];
				$i++;
			} elseif (!empty($value['contacts_data'])) {
				$this->data[$value['contacts_type']] = $value['contacts_data'];
			}
		} 	
/*		
		if (empty($this->data['page_db_data'][0]['page_content'])) {
			$this->data['page_db_data'][0]['page_content'] = (file_exists('README.md')) ? file('README.md') : array(
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
			}
*/			
		return $this->data;
	}
}