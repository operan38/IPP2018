<?php

class AdminPkPksController extends AdminController
{
	private $pkPks;

	function __construct()
	{
		parent::__construct();
		$this->pkPks = new PkPks;
		$this->getView()->setTitle('ПК/ПЦК');
	}

	public function actionIndex()
	{
		$this->render('admin_pk_pks/index');
	}

	public function actionAdd()
	{
		$this->render('admin_pk_pks/add');
	}

	public function actionEdit()
	{
		$this->checkPOST('button_edit');
		$this->pkPks->edit();
		$this->render('admin_pk_pks/edit', array('pkPks'=>$this->pkPks));
	}

	public function actionAjaxLoad()
	{
		$this->checkAjax();
		$this->pkPks->getTable();
	}

	public function actionInsert()
	{
		$this->checkPOST('admin_add_data_table_form_insert');
		$this->pkPks->insert();
		header('Location: /admin-pk-pks');
	}

	public function actionUpdate()
	{
		$this->checkPOST('edit_data_table_form_update');
		$this->pkPks->update();	
		header('Location: /admin-pk-pks');
	}

	public function actionAjaxDelete()
	{
		$this->checkAjax();
		$this->pkPks->ajaxDelete();
	}	
}

?>