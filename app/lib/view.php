<?php
namespace App\Lib;

class View
{
	protected $data = [];

	public $template_view = APPROOT.DS.'templates/templ.php'; 
	/*
	$content_view - виды отображающие контент страниц;
	$template_view - общий для всех страниц шаблон;
	$data - массив, содержащий элементы контента страницы. Обычно заполняется в модели.
	*/
	public function generate($content_view, $template_view, $data)
	//	public function generate($content_view, $data = null)
	{
		/*
		if (array($data) && !empty($data)) {
			extract($data);
		}
		*/
		/*
		динамически подключаем общий шаблон (вид),
		внутри которого будет встраиваться вид
		для отображения контента конкретной страницы.
		*/
		include $this->template_view;
	}
}