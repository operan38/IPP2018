<?php

class AdminTypeActivitiesController extends AdminController
{
	private $typeActivities;

	function __construct()
	{
		parent::__construct();
		$this->typeActivities = new TypeActivities;
		$this->getView()->setTitle('Вид деятельности УМР/УПР');
	}

	public function actionIndex()
	{
		$this->render('admin_type_activities/index');
	}

	public function actionAdd()
	{
		$this->render('admin_type_activities/add');
	}

	public function actionEdit()
	{
		$this->checkPOST('button_edit');
		$this->typeActivities->edit();
		$this->render('admin_type_activities/edit', array('typeActivities'=>$this->typeActivities));
	}

	public function actionAjaxLoad()
	{
		$this->checkAjax();
		$this->typeActivities->getTable();
	}

	public function actionInsert()
	{
		$this->checkPOST('admin_add_data_table_form_insert');
		$this->typeActivities->insert();
		header('Location: /admin-type-activities');
	}

	public function actionUpdate()
	{
		$this->checkPOST('edit_data_table_form_update');
		$this->typeActivities->update();	
		header('Location: /admin-type-activities');
	}

	public function actionAjaxDelete()
	{
		$this->checkAjax();
		$this->typeActivities->ajaxDelete();
	}
}

?>