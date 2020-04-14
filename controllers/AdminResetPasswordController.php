<?php

class AdminResetPasswordController extends Controller
{
	function __construct()
	{
		parent::__construct();

		if (!Auth::getInstance()->isGuest())
			header('Location: /');

		$this->getView()->setLayoutHeader('simple');
		$this->getView()->setLayoutFooter('simple');
	}

	public function actionIndex()
	{
		$this->getView()->setTitle('Сброс пароля администратора');
		$adminResetPasswordForm = new AdminResetPasswordForm;

		if (isset($_POST['admin_add_data_table_form_submit']))
			$adminResetPasswordForm->submit();

		$this->render('admin_reset_password/index', array('adminResetPasswordForm' => $adminResetPasswordForm));
	}
}

?>