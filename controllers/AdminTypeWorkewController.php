<?php

class AdminTypeWorkewController extends AdminController
{
	private $typeWorkew;

	function __construct()
	{
		parent::__construct();
		$this->typeWorkew = new TypeWorkew;
		$this->getView()->setTitle('Вид деятельности ВР');
	}

	public function actionIndex()
	{
		$this->render('admin_type_workew/index');
	}

	public function actionAdd()
	{
		$this->render('admin_type_workew/add');
	}

	public function actionEdit()
	{
		$this->checkPOST('button_edit');
		$this->typeWorkew->edit();
		$this->render('admin_type_workew/edit', array('typeWorkew'=>$this->typeWorkew));
	}

	public function actionAjaxLoad()
	{
		$this->checkAjax();
		$this->typeWorkew->getTable();
	}

	public function actionInsert()
	{
		$this->checkPOST('admin_add_data_table_form_insert');
		$this->typeWorkew->insert();
		header('Location: /admin-type-workew');
	}

	public function actionUpdate()
	{
		$this->checkPOST('edit_data_table_form_update');
		$this->typeWorkew->update();	
		header('Location: /admin-type-workew');
	}

	public function actionAjaxDelete()
	{
		$this->checkAjax();
		$this->typeWorkew->ajaxDelete();
	}	
}

?>