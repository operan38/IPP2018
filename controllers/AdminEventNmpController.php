<?php

class AdminEventNmpController extends AdminController
{
	private $eventNmp;

	function __construct()
	{
		parent::__construct();
		$this->eventNmp = new EventNmp;
		$this->getView()->setTitle('Вид мероприятий');
	}

	public function actionIndex()
	{
		$this->render('admin_event_nmp/index');
	}

	public function actionAdd()
	{
		$this->render('admin_event_nmp/add');
	}

	public function actionEdit()
	{
		$this->checkPOST('button_edit');
		$this->eventNmp->edit();
		$this->render('admin_event_nmp/edit', array('eventNmp'=>$this->eventNmp));
	}

	public function actionAjaxLoad()
	{
		$this->checkAjax();
		$this->eventNmp->getTable();
	}

	public function actionInsert()
	{
		$this->checkPOST('admin_add_data_table_form_insert');
		$this->eventNmp->insert();
		header('Location: /admin-event-nmp');
	}

	public function actionUpdate()
	{
		$this->checkPOST('edit_data_table_form_update');
		$this->eventNmp->update();	
		header('Location: /admin-event-nmp');
	}

	public function actionAjaxDelete()
	{
		$this->checkAjax();
		$this->eventNmp->ajaxDelete();
	}	
}

?>