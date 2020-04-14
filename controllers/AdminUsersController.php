<?php

class AdminUsersController extends AdminController
{
	private $users;

	function __construct()
	{
		parent::__construct();
		$this->users = new Users;
		$this->getView()->setTitle('Пользователи');
	}

	public function actionIndex()
	{
		$this->render('admin_users/index');
	}

	public function actionEdit()
	{
		$this->checkPOST('button_edit');
		$this->users->edit();
		$this->render('admin_users/edit', array('users'=>$this->users));
	}

	public function actionAjaxLoad()
	{
		$this->checkAjax();
		$this->users->getTable();
	}

	public function actionUpdate()
	{
		$this->checkPOST('edit_data_table_form_update');
		$this->users->update();	
		header('Location: /admin-users');
	}
}

?>