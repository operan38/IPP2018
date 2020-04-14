<?php

class AdminTypeTeachEducationalActivityController extends AdminController
{
	private $typeTeachEducationalActivity;

	function __construct()
	{
		parent::__construct();
		$this->typeTeachEducationalActivity = new TypeTeachEducationalActivity;
		$this->getView()->setTitle('Вид учебно-воспитательной деятельности');
	}

	public function actionIndex()
	{
		$this->render('admin_type_teach_educational_activity/index');
	}

	public function actionAdd()
	{
		$this->render('admin_type_teach_educational_activity/add');
	}

	public function actionEdit()
	{
		$this->checkPOST('button_edit');
		$this->typeTeachEducationalActivity->edit();
		$this->render('admin_type_teach_educational_activity/edit', array('typeTeachEducationalActivity'=>$this->typeTeachEducationalActivity));
	}

	public function actionAjaxLoad()
	{
		$this->checkAjax();
		$this->typeTeachEducationalActivity->getTable();
	}

	public function actionInsert()
	{
		$this->checkPOST('admin_add_data_table_form_insert');
		$this->typeTeachEducationalActivity->insert();
		header('Location: /admin-type-teach-educational-activity');
	}

	public function actionUpdate()
	{
		$this->checkPOST('edit_data_table_form_update');
		$this->typeTeachEducationalActivity->update();	
		header('Location: /admin-type-teach-educational-activity');
	}

	public function actionAjaxDelete()
	{
		$this->checkAjax();
		$this->typeTeachEducationalActivity->ajaxDelete();
	}	
}

?>