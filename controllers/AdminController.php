<?php

class AdminController extends Controller
{
	function __construct()
	{
		parent::__construct();
		if (!Auth::getInstance()->compareRules(Auth::ADMIN)) // Если пользователь не администратор
		{
			header('Location: /');
			exit;
		}

		$this->getView()->setLayoutHeader('admin');
		$this->getView()->setLayoutFooter('admin');
	}

	public function actionIndex()
	{
		$this->getView()->setTitle('Заполнение разделов индивидуального плана');
		$this->render('admin/index');
	}
}

?>