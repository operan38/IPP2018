<?php 

class AdminDisciplinesController extends AdminController
{
	private $disciplines;

	function __construct()
	{
		parent::__construct();
		$this->disciplines = new Disciplines;
		$this->getView()->setTitle('Наименование дисциплин');
	}

	public function actionIndex()
	{
		$this->render('admin_disciplines/index');
	}

	public function actionAdd()
	{
		$this->checkPOST('admin_key_ppccz');
		$this->render('admin_disciplines/add');
	}

	public function actionEdit()
	{
		$this->checkPOST('button_edit');
		$this->disciplines->edit();
		$this->render('admin_disciplines/edit', array('disciplines'=>$this->disciplines));
	}

	public function actionAjaxLoad()
	{
		$this->checkAjax();
		$this->disciplines->getTable();
	}

	public function actionInsert()
	{
		$this->checkPOST('admin_add_data_table_form_insert');
		$this->disciplines->insert();
		header('Location: /admin-disciplines');
	}

	public function actionUpdate()
	{
		$this->checkPOST('edit_data_table_form_update');
		$this->disciplines->update();	
		header('Location: /admin-disciplines');
	}

	public function actionAjaxDelete()
	{
		$this->checkAjax();
		$this->disciplines->ajaxDelete();
	}
}

?>