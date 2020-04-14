<?php

class AdminPlacementController extends AdminController
{
	private $placement;

	function __construct()
	{
		parent::__construct();
		$this->placement = new Placement;
		$this->getView()->setTitle('Место размещения');
	}

	public function actionIndex()
	{
		$this->render('admin_placement/index');
	}

	public function actionAdd()
	{
		$this->render('admin_placement/add');
	}

	public function actionEdit()
	{
		$this->checkPOST('button_edit');
		$this->placement->edit();
		$this->render('admin_placement/edit', array('placement'=>$this->placement));
	}

	public function actionAjaxLoad()
	{
		$this->checkAjax();
		$this->placement->getTable();
	}

	public function actionInsert()
	{
		$this->checkPOST('admin_add_data_table_form_insert');
		$this->placement->insert();
		header('Location: /admin-placement');
	}

	public function actionUpdate()
	{
		$this->checkPOST('edit_data_table_form_update');
		$this->placement->update();	
		header('Location: /admin-placements');
	}

	public function actionAjaxDelete()
	{
		$this->checkAjax();
		$this->placement->ajaxDelete();
	}
}

?>