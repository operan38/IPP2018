<?php

class AdminTypeUpdController extends AdminController
{
	private $typeUpd;

	function __construct()
	{
		parent::__construct();
		$this->typeUpd = new TypeUpd;
		$this->getView()->setTitle('Вид УПД');
	}

	public function actionIndex()
	{
		$this->render('admin_type_upd/index');
	}

	public function actionAdd()
	{
		$this->render('admin_type_upd/add');
	}

	public function actionEdit()
	{
		$this->checkPOST('button_edit');
		$this->typeUpd->edit();
		$this->render('admin_type_upd/edit', array('typeUpd'=>$this->typeUpd));
	}

	public function actionAjaxLoad()
	{
		$this->checkAjax();
		$this->typeUpd->getTable();
	}

	public function actionInsert()
	{
		$this->checkPOST('admin_add_data_table_form_insert');
		$this->typeUpd->insert();
		header('Location: /admin-type-upd');
	}

	public function actionUpdate()
	{
		$this->checkPOST('edit_data_table_form_update');
		$this->typeUpd->update();	
		header('Location: /admin-type-upd');
	}

	public function actionAjaxDelete()
	{
		$this->checkAjax();
		$this->typeUpd->ajaxDelete();
	}	
}

?>