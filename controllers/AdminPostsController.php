<?php

class AdminPostsController extends AdminController
{
	private $posts;

	function __construct()
	{
		parent::__construct();
		$this->posts = new Posts;
		$this->getView()->setTitle('Должности педагогического работника');
	}

	public function actionIndex()
	{
		$this->render('admin_posts/index');
	}

	public function actionAdd()
	{
		$this->render('admin_posts/add');
	}

	public function actionEdit()
	{
		$this->checkPOST('button_edit');
		$this->posts->edit();
		$this->render('admin_posts/edit', array('posts'=>$this->posts));
	}

	public function actionAjaxLoad()
	{
		$this->checkAjax();
		$this->posts->getTable();
	}

	public function actionInsert()
	{
		$this->checkPOST('admin_add_data_table_form_insert');
		$this->posts->insert();
		header('Location: /admin-posts');
	}

	public function actionUpdate()
	{
		$this->checkPOST('edit_data_table_form_update');
		$this->posts->update();	
		header('Location: /admin-posts');
	}

	public function actionAjaxDelete()
	{
		$this->checkAjax();
		$this->posts->ajaxDelete();
	}
}

?>