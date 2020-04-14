<?php

class Controller
{
	private $view;
	private $errors = array(); // Ошибки

	function __construct()
	{
		$this->view = new View;
	}

	public function checkAjax()
	{
		if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && !empty($_SERVER['HTTP_X_REQUESTED_WITH']) 
		&& strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){}
		else
		{
			header('Location: /');
			exit;
		}
	}

	public function checkPOST($post)
	{
		if (isset($_POST[$post])){}
		else
		{
			header('Location: /');
			exit;
		}
	}

	public function setRouterError($errors)
	{
		$this->errors = $errors;
	}

	public function getRouterError()
	{
		return array_shift($this->errors);
	}

	public function getView()
	{
		return $this->view;
	}

	public function render($view, $params = array())
	{
		$this->view->render($view, $params);
	}
}

?>