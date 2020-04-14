<?php

class View
{
	private $title = ''; // Заголовок страницы по умолчанию
	private $layoutHeader = 'main'; // Шаблоны по умолчанию
	private $layoutFooter = 'main'; //

	function __construct(){}
	
	public function setTitle($title)
	{
		$this->title = $title;
	}

	public function setLayoutHeader($layout)
	{
		$this->layoutHeader = $layout;
	}

	public function setLayoutFooter($layout)
	{
		$this->layoutFooter = $layout;
	}

	public function render($view, $params = array())
	{
		if (!empty($params)) // Если пристутвуют параметры которые нужно передать на страницу
			extract($params);

		require_once(ROOT.'/views/layouts/'.$this->layoutHeader.'/header.php');
		require_once(ROOT.'/views/'.$view.'.php');
		require_once(ROOT.'/views/layouts/'.$this->layoutFooter.'/footer.php');
	}
}

?>