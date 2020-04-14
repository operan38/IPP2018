<?php

class AdminTypeCabinetController extends AdminController
{
	private $typeCabinet;

	function __construct()
	{
		parent::__construct();
		$this->typeCabinet = new TypeCabinet;
		$this->getView()->setTitle('Тип кабинета');
	}

	public function actionIndex()
	{
		$this->render('admin_type_cabinet/index');
	}

	public function actionAdd()
	{
		$this->render('admin_type_cabinet/add');
	}

	public function actionEdit()
	{
		$this->checkPOST('button_edit');
		$this->typeCabinet->edit();
		$this->render('admin_type_cabinet/edit', array('typeCabinet'=>$this->typeCabinet));
	}

	public function actionAjaxLoad()
	{
		$this->checkAjax();
		$this->typeCabinet->getTable();
	}

	public function actionInsert()
	{
		$this->checkPOST('admin_add_data_table_form_insert');
		$this->typeCabinet->insert();
		header('Location: /admin-type-cabinet');
	}

	public function actionUpdate()
	{
		$this->checkPOST('edit_data_table_form_update');
		$this->typeCabinet->update();	
		header('Location: /admin-type-cabinet');
	}

	public function actionAjaxDelete()
	{
		$this->checkAjax();
		$this->typeCabinet->ajaxDelete();
	}	
}

?>