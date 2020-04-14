<?php

class AdminTypeParticipationController extends AdminController
{
	private $typeParticipation;

	function __construct()
	{
		parent::__construct();
		$this->typeParticipation = new TypeParticipation;
		$this->getView()->setTitle('Вид участия');
	}

	public function actionIndex()
	{
		$this->render('admin_type_participation/index');
	}

	public function actionAdd()
	{
		$this->render('admin_type_participation/add');
	}

	public function actionEdit()
	{
		$this->checkPOST('button_edit');
		$this->typeParticipation->edit();
		$this->render('admin_type_participation/edit', array('typeParticipation'=>$this->typeParticipation));
	}

	public function actionAjaxLoad()
	{
		$this->checkAjax();
		$this->typeParticipation->getTable();
	}

	public function actionInsert()
	{
		$this->checkPOST('admin_add_data_table_form_insert');
		$this->typeParticipation->insert();
		header('Location: /admin-type-participation');
	}

	public function actionUpdate()
	{
		$this->checkPOST('edit_data_table_form_update');
		$this->typeParticipation->update();	
		header('Location: /admin-type-participation');
	}

	public function actionAjaxDelete()
	{
		$this->checkAjax();
		$this->typeParticipation->ajaxDelete();
	}	
}

?>