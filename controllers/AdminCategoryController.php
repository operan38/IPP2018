<?php

class AdminCategoryController extends AdminController
{
	private $category;

	function __construct()
	{
		parent::__construct();
		$this->category = new Category;
		$this->getView()->setTitle('Категория педагогического работника');
	}

	public function actionIndex()
	{
		$this->render('admin_category/index');
	}

	public function actionAdd()
	{
		$this->render('admin_category/add');
	}

	public function actionEdit()
	{
		$this->checkPOST('button_edit');
		$this->category->edit();
		$this->render('admin_category/edit', array('category'=>$this->category));
	}

	public function actionAjaxLoad()
	{
		$this->checkAjax();
		$this->category->getTable();
	}

	public function actionInsert()
	{
		$this->checkPOST('admin_add_data_table_form_insert');
		$this->category->insert();
		header('Location: /admin-category');
	}

	public function actionUpdate()
	{
		$this->checkPOST('edit_data_table_form_update');
		$this->category->update();	
		header('Location: /admin-category');
	}

	public function actionAjaxDelete()
	{
		$this->checkAjax();
		$this->category->ajaxDelete();
	}
}

?>