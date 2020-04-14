<?php

class AdminTypeEmployeeController extends AdminController
{
	private $typeEmployee;

	function __construct()
	{
		parent::__construct();
		$this->typeEmployee = new TypeEmployee;
		$this->getView()->setTitle('Тип сотрудника');
	}

	public function actionIndex()
	{
		$this->render('admin_type_employee/index');
	}

	public function actionAdd()
	{
		$this->render('admin_type_employee/add');
	}

	public function actionEdit()
	{
		$this->checkPOST('button_edit');
		$this->typeEmployee->edit();
		$this->render('admin_type_employee/edit', array('typeEmployee'=>$this->typeEmployee));
	}

	public function actionAjaxLoad()
	{
		$this->checkAjax();
		$this->typeEmployee->getTable();
	}

	public function actionInsert()
	{
		$this->checkPOST('admin_add_data_table_form_insert');
		$this->typeEmployee->insert();
		header('Location: /admin-type-employee');
	}

	public function actionUpdate()
	{
		$this->checkPOST('edit_data_table_form_update');
		$this->typeEmployee->update();	
		header('Location: /admin-type-employee');
	}

	public function actionAjaxDelete()
	{
		$this->checkAjax();
		$this->typeEmployee->ajaxDelete();
	}
}

?>