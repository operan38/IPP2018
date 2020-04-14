<?php

class AdminIndexDisciplinesController extends AdminController
{
	private $indexDisciplines;

	function __construct()
	{
		parent::__construct();
		$this->indexDisciplines = new IndexDisciplines;
		$this->getView()->setTitle('Индекс дисциплин');
	}

	public function actionIndex()
	{
		$this->render('admin_index_disciplines/index');
	}

	public function actionAdd()
	{
		$this->render('admin_index_disciplines/add');
	}

	public function actionEdit()
	{
		$this->checkPOST('button_edit');
		$this->indexDisciplines->edit();
		$this->render('admin_index_disciplines/edit', array('indexDisciplines'=>$this->indexDisciplines));
	}

	public function actionAjaxLoad()
	{
		$this->checkAjax();
		$this->indexDisciplines->getTable();
	}

	public function actionInsert()
	{
		$this->checkPOST('admin_add_data_table_form_insert');
		$this->indexDisciplines->insert();
		header('Location: /admin-index-disciplines');
	}

	public function actionUpdate()
	{
		$this->checkPOST('edit_data_table_form_update');
		$this->indexDisciplines->update();	
		header('Location: /admin-index-disciplines');
	}

	public function actionAjaxDelete()
	{
		$this->checkAjax();
		$this->indexDisciplines->ajaxDelete();
	}
}

?>