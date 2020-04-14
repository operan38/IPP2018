<?php

class AdminPpcczController extends AdminController
{
	private $ppccz;

	function __construct()
	{
		parent::__construct();
		$this->ppccz = new Ppccz;
		$this->getView()->setTitle('Перечень специальностей');
	}

	public function actionIndex()
	{
		$this->render('admin_ppccz/index');
	}

	public function actionAdd()
	{
		$this->checkPOST('admin_key_id_department');
		$this->render('admin_ppccz/add');
	}

	public function actionEdit()
	{
		$this->checkPOST('button_edit');
		$this->ppccz->edit();
		$this->render('admin_ppccz/edit', array('ppccz'=>$this->ppccz));
	}

	public function actionAjaxLoad()
	{
		$this->checkAjax();
		$this->ppccz->getTable();
	}

	public function actionInsert()
	{
		$this->checkPOST('admin_add_data_table_form_insert');
		$this->ppccz->insert();
		header('Location: /admin-ppccz');
	}

	public function actionUpdate()
	{
		$this->checkPOST('edit_data_table_form_update');
		$this->ppccz->update();	
		header('Location: /admin-ppccz');
	}

	public function actionAjaxDelete()
	{
		$this->checkAjax();
		$this->ppccz->ajaxDelete();
	}
}

?>