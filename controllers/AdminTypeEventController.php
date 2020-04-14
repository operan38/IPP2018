<?php

class AdminTypeEventController extends AdminController
{
	private $typeEvent;

	function __construct()
	{
		parent::__construct();
		$this->typeEvent = new TypeEvent;
		$this->getView()->setTitle('Вид мероприятия');
	}

	public function actionIndex()
	{
		$this->render('admin_type_event/index');
	}

	public function actionAdd()
	{
		$this->render('admin_type_event/add');
	}

	public function actionEdit()
	{
		$this->checkPOST('button_edit');
		$this->typeEvent->edit();
		$this->render('admin_type_event/edit', array('typeEvent'=>$this->typeEvent));
	}

	public function actionAjaxLoad()
	{
		$this->checkAjax();
		$this->typeEvent->getTable();
	}

	public function actionInsert()
	{
		$this->checkPOST('admin_add_data_table_form_insert');
		$this->typeEvent->insert();
		header('Location: /admin-type-event');
	}

	public function actionUpdate()
	{
		$this->checkPOST('edit_data_table_form_update');
		$this->typeEvent->update();	
		header('Location: /admin-type-event');
	}

	public function actionAjaxDelete()
	{
		$this->checkAjax();
		$this->typeEvent->ajaxDelete();
	}	
}

?>