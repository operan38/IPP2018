<?php

class AdminEmployeeController extends AdminController
{
	private $employee;

	function __construct()
	{
		parent::__construct();
		$this->employee =  new Employee;
		$this->getView()->setTitle('Педагогические работники');
	}

	public function actionIndex()
	{
		$this->render('admin_employee/index');
	}

	public function actionEdit()
	{
		$this->checkPOST('button_edit');
		$this->employee->edit();
		$this->render('admin_employee/edit', array('employee'=>$this->employee));
	}

	public function actionAjaxLoad()
	{
		$this->checkAjax();
		$this->employee->getTable();
	}

	public function actionUpdate()
	{
		$this->checkPOST('edit_data_table_form_update');
		$this->employee->update();
		header('Location: /admin-employee');
	}

	public function actionAjaxDelete()
	{
		$this->checkAjax();
		$this->employee->ajaxDelete();
	}	
}

?>