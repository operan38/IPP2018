<?php

class AdminDepartmentController extends AdminController
{
	private $department;

	function __construct()
	{
		parent::__construct();
		$this->department = new Department;
		$this->getView()->setTitle('Отделения');
	}

	public function actionIndex()
	{
		$this->render('admin_department/index');
	}

	public function actionAdd()
	{
		$this->render('admin_department/add');
	}

	public function actionEdit()
	{
		$this->checkPOST('button_edit');
		$this->department->edit();
		$this->render('admin_department/edit', array('department'=>$this->department));
	}

	public function actionAjaxLoad()
	{
		$this->checkAjax();
		$this->department->getTable();
	}

	public function actionInsert()
	{
		$this->checkPOST('admin_add_data_table_form_insert');
		$this->department->insert();
		header('Location: /admin-department');
	}

	public function actionUpdate()
	{
		$this->checkPOST('edit_data_table_form_update');
		$this->department->update();	
		header('Location: /admin-department');
	}

	public function actionAjaxDelete()
	{
		$this->checkAjax();
		$this->department->ajaxDelete();
	}
}

?>