<?php

class AdminTypeWorkController extends AdminController
{
	private $typeWork;

	function __construct()
	{
		parent::__construct();
		$this->typeWork = new TypeWork;
		$this->getView()->setTitle('Вид деятельности ОМР');
	}

	public function actionIndex()
	{
		$this->render('admin_type_work/index');
	}

	public function actionAdd()
	{
		$this->render('admin_type_work/add');
	}

	public function actionEdit()
	{
		$this->checkPOST('button_edit');
		$this->typeWork->edit();
		$this->render('admin_type_work/edit', array('typeWork'=>$this->typeWork));
	}

	public function actionAjaxLoad()
	{
		$this->checkAjax();
		$this->typeWork->getTable();
	}

	public function actionInsert()
	{
		$this->checkPOST('admin_add_data_table_form_insert');
		$this->typeWork->insert();
		header('Location: /admin-type-work');
	}

	public function actionUpdate()
	{
		$this->checkPOST('edit_data_table_form_update');
		$this->typeWork->update();	
		header('Location: /admin-type-work');
	}

	public function actionAjaxDelete()
	{
		$this->checkAjax();
		$this->typeWork->ajaxDelete();
	}	
}

?>