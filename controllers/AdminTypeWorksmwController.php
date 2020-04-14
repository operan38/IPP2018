<?php

class AdminTypeWorksmwController extends AdminController
{
	private $typeWorksmw;

	function __construct()
	{
		parent::__construct();
		$this->typeWorksmw = new TypeWorksmw;
		$this->getView()->setTitle('Вид участия');
	}

	public function actionIndex()
	{
		$this->render('admin_type_worksmw/index');
	}

	public function actionAdd()
	{
		$this->render('admin_type_worksmw/add');
	}

	public function actionEdit()
	{
		$this->checkPOST('button_edit');
		$this->typeWorksmw->edit();
		$this->render('admin_type_worksmw/edit', array('typeWorksmw'=>$this->typeWorksmw));
	}

	public function actionAjaxLoad()
	{
		$this->checkAjax();
		$this->typeWorksmw->getTable();
	}

	public function actionInsert()
	{
		$this->checkPOST('admin_add_data_table_form_insert');
		$this->typeWorksmw->insert();
		header('Location: /admin-type-worksmw');
	}

	public function actionUpdate()
	{
		$this->checkPOST('edit_data_table_form_update');
		$this->typeWorksmw->update();	
		header('Location: /admin-type-worksmw');
	}

	public function actionAjaxDelete()
	{
		$this->checkAjax();
		$this->typeWorksmw->ajaxDelete();
	}
}

?>