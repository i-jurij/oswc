<?php
namespace App\Models;

class Home
{
	public function get_data()
	{	
		// get vars for home page from database
		return array(
			array(
				'N' => '1',
				'Controller' => 'Main',
				'Description' => 'Home page only.'
			),
			array(
				'N' => '2',
				'Controller' => 'Pages',
				'Description' => 'Other page for clients.'
			),
			array(
				'N' => '3',
				'Controller' => 'Adm',
				'Description' => 'Page for site admins.'
			),
		);
	}

}