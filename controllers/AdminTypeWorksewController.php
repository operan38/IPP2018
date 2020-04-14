<?php

class AdminTypeWorksewController extends AdminController
{
	private $typeWorksew;

	function __construct()
	{
		parent::__construct();
		$this->typeWorksew = new TypeWorksew;
		$this->getView()->setTitle('Форма повышения квалификации');
	}

	public function actionIndex()
	{
		$this->render('admin_type_worksew/index');
	}

	public function actionAdd()
	{
		$this->render('admin_type_worksew/add');
	}

	public function actionEdit()
	{
		$this->checkPOST('button_edit');
		$this->typeWorksew->edit();
		$this->render('admin_type_worksew/edit', array('typeWorksew'=>$this->typeWorksew));
	}

	public function actionAjaxLoad()
	{
		$this->checkAjax();
		$this->typeWorksew->getTable();
	}

	public function actionInsert()
	{
		$this->checkPOST('admin_add_data_table_form_insert');
		$this->typeWorksew->insert();
		header('Location: /admin-type-worksew');
	}

	public function actionUpdate()
	{
		$this->checkPOST('edit_data_table_form_update');
		$this->typeWorksew->update();	
		header('Location: /admin-type-worksew');
	}

	public function actionAjaxDelete()
	{
		$this->checkAjax();
		$this->typeWorksew->ajaxDelete();
	}
}

?>