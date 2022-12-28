<?php
namespace App\Lib;

class View
{
	protected array $data = [];
	/*
	$content_view - виды отображающие контент страниц;
	$template_view - общий для всех страниц шаблон;
	$data - массив, содержащий элементы контента страницы. Обычно заполняется в модели.
	*/
	public function generate($content_view, $data, $template_view = TEMPLATEROOT.DS.'first/templ.php')
	//	public function generate($content_view, $data = null)
	{
		/*
		if (array($data) && !empty($data)) {
			extract($data);
		}
		*/
		/*
		динамически подключаем общий шаблон (вид), внутри которого будет встраиваться вид
		для отображения контента конкретной страницы.
		*/
		if (isset($data) && is_array($data) && !empty($data['templates'])) {
			include $data['templates'];
		} else {
			include $template_view;
		}
	}
}