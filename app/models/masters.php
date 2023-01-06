<?php
namespace App\Models;

class Masters extends Adm
{
    public function get_data($path)
	{	
		$this->db_query();//get vars for template
		//add css for head in template
		$this->data['css'] = $this->css_add('public'.DS.'css'.DS.'first');
		//get data for masters from db
		$this->data['masters'] = $this->db->db->select("masters", "*");
		
		return $this->data;
	}
}