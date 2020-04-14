<?php

class AdminTypeUmd2Controller extends AdminController
{
	private $typeUmd2;

	function __construct()
	{
		parent::__construct();
		$this->typeUmd2 = new TypeUmd2;
		$this->getView()->setTitle('Тип УМД');
	}

	public function actionIndex()
	{
		$this->render('admin_type_umd2/index');
	}

	public function actionAdd()
	{
		$this->render('admin_type_umd2/add');
	}

	public function actionEdit()
	{
		$this->checkPOST('button_edit');
		$this->typeUmd2->edit();
		$this->render('admin_type_umd2/edit', array('typeUmd2'=>$this->typeUmd2));
	}

	public function actionAjaxLoad()
	{
		$this->checkAjax();
		$this->typeUmd2->getTable();
	}

	public function actionInsert()
	{
		$this->checkPOST('admin_add_data_table_form_insert');
		$this->typeUmd2->insert();
		header('Location: /admin-type-umd2');
	}

	public function actionUpdate()
	{
		$this->checkPOST('edit_data_table_form_update');
		$this->typeUmd2->update();	
		header('Location: /admin-type-umd2');
	}

	public function actionAjaxDelete()
	{
		$this->checkAjax();
		$this->typeUmd2->ajaxDelete();
	}	
}

?>