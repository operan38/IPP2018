<?php

class AdminLevelEventsController extends AdminController
{
	private $levelEvents;

	function __construct()
	{
		parent::__construct();
		$this->levelEvents = new LevelEvents;
		$this->getView()->setTitle('Уровень мероприятий');
	}

	public function actionIndex()
	{
		$this->render('admin_level_events/index');
	}

	public function actionAdd()
	{
		$this->render('admin_level_events/add');
	}

	public function actionEdit()
	{
		$this->checkPOST('button_edit');
		$this->levelEvents->edit();
		$this->render('admin_level_events/edit', array('levelEvents'=>$this->levelEvents));
	}

	public function actionAjaxLoad()
	{
		$this->checkAjax();
		$this->levelEvents->getTable();
	}

	public function actionInsert()
	{
		$this->checkPOST('admin_add_data_table_form_insert');
		$this->levelEvents->insert();
		header('Location: /admin-level-events');
	}

	public function actionUpdate()
	{
		$this->checkPOST('edit_data_table_form_update');
		$this->levelEvents->update();	
		header('Location: /admin-level-events');
	}

	public function actionAjaxDelete()
	{
		$this->checkAjax();
		$this->levelEvents->ajaxDelete();
	}
}

?>