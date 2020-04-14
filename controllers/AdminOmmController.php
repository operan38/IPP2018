<?php

class AdminOmmController extends AdminController
{
	private $organizationalMethodicalMaterials;

	function __construct()
	{
		parent::__construct();
		$this->organizationalMethodicalMaterials = new OrganizationalMethodicalMaterials;
		$this->getView()->setTitle('Организационно-методические материалы');
	}

	public function actionIndex()
	{
		$this->render('admin_omm/index');
	}

	public function actionAdd()
	{
		$this->render('admin_omm/add');
	}

	public function actionEdit()
	{
		$this->checkPOST('button_edit');
		$this->organizationalMethodicalMaterials->edit();
		$this->render('admin_omm/edit', array('organizationalMethodicalMaterials'=>$this->organizationalMethodicalMaterials));
	}

	public function actionAjaxLoad()
	{
		$this->checkAjax();
		$this->organizationalMethodicalMaterials->getTable();
	}

	public function actionInsert()
	{
		$this->checkPOST('admin_add_data_table_form_insert');
		$this->organizationalMethodicalMaterials->insert();
		header('Location: /admin-omm');
	}

	public function actionUpdate()
	{
		$this->checkPOST('edit_data_table_form_update');
		$this->organizationalMethodicalMaterials->update();	
		header('Location: /admin-omm');
	}

	public function actionAjaxDelete()
	{
		$this->checkAjax();
		$this->organizationalMethodicalMaterials->ajaxDelete();
	}
}

?>