<?php

class AdminTypeUmdController extends AdminController
{
	private $typeUmd;

	function __construct()
	{
		parent::__construct();
		$this->typeUmd = new TypeUmd;
		$this->getView()->setTitle('Вид УМД');
	}

	public function actionIndex()
	{
		$this->render('admin_type_umd/index');
	}

	public function actionAdd()
	{
		$this->render('admin_type_umd/add');
	}

	public function actionEdit()
	{
		$this->checkPOST('button_edit');
		$this->typeUmd->edit();
		$this->render('admin_type_umd/edit', array('typeUmd'=>$this->typeUmd));
	}

	public function actionAjaxLoad()
	{
		$this->checkAjax();
		$this->typeUmd->getTable();
	}

	public function actionInsert()
	{
		$this->checkPOST('admin_add_data_table_form_insert');
		$this->typeUmd->insert();
		header('Location: /admin-type-umd');
	}

	public function actionUpdate()
	{
		$this->checkPOST('edit_data_table_form_update');
		$this->typeUmd->update();	
		header('Location: /admin-type-umd');
	}

	public function actionAjaxDelete()
	{
		$this->checkAjax();
		$this->typeUmd->ajaxDelete();
	}	
}

?>