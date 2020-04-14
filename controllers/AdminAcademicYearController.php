<?php

class AdminAcademicYearController extends AdminController
{
	private $academicYear;

	function __construct()
	{
		parent::__construct();
		$this->academicYear = new AcademicYear;
		$this->getView()->setTitle('Академический год');
	}

	public function actionIndex()
	{
		$this->render('admin_academic_year/index');
	}

	public function actionAdd()
	{
		$this->render('admin_academic_year/add');
	}

	public function actionEdit()
	{
		$this->checkPOST('button_edit');
		$this->academicYear->edit();
		$this->render('admin_academic_year/edit', array('academicYear'=>$this->academicYear));
	}

	public function actionAjaxLoad()
	{
		$this->checkAjax();
		$this->academicYear->getTable();
	}

	public function actionInsert()
	{
		$this->checkPOST('admin_add_data_table_form_insert');
		$this->academicYear->insert();
		header('Location: /admin-academic-year');
	}

	public function actionUpdate()
	{
		$this->checkPOST('edit_data_table_form_update');
		$this->academicYear->update();	
		header('Location: /admin-academic-year');
	}

	public function actionAjaxDelete()
	{
		$this->checkAjax();
		$this->academicYear->ajaxDelete();
	}
}

?>