<?php
namespace App\Models;

class Masters extends Adm
{
    public function get_data($path, $get_query, $post_query)
	{	
		$this->db_query();
		//add css for head in template
		$this->data['css'] = $this->css_add('public'.DS.'css'.DS.'first');
		
		return $this->data;
	}
}