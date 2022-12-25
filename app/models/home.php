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
				'Controller' => 'Home',
				'Description' => 'Home page.'
			),
			array(
				'N' => '2',
				'Controller' => 'Adm',
				'Description' => 'Page for site admins.'
			),
			array(
				'N' => '3',
				'Controller' => 'Adm login',
				'Description' => 'user'
			),
			array(
				'N' => '4',
				'Controller' => 'Adm pass',
				'Description' => 'pass'
			),
			array(
				'N' => '5',
				'Controller' => 'Change login and pass in',
				'Description' => 'app/config/config.php'
			),
		);
	}

}