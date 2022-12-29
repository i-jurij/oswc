<?php
namespace App\Models;
use App\Lib\Traits\Css_add;

class Home
{
	use Css_add;

	public function get_data()
	{	
		//$css = $this->css_add();
		// get vars for home page from database
		$data = (file_exists('README.md')) ? file('README.md') : array(
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