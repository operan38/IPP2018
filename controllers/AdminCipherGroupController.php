<?php 

class AdminCipherGroupController extends AdminController
{
	private $cipherGroup;

	function __construct()
	{
		parent::__construct();
		$this->cipherGroup = new CipherGroup;
		$this->getView()->setTitle('Группы');
	}

	public function actionIndex()
	{
		$this->render('admin_cipher_group/index');
	}

	public function actionAdd()
	{
		$this->checkPOST('admin_key_ppccz');
		$this->render('admin_cipher_group/add');
	}

	public function actionEdit()
	{
		$this->checkPOST('button_edit');
		$this->cipherGroup->edit();
		$this->render('admin_cipher_group/edit', array('cipherGroup'=>$this->cipherGroup));
	}

	public function actionAjaxLoad()
	{
		$this->checkAjax();
		$this->cipherGroup->getTable();
	}

	public function actionInsert()
	{
		$this->checkPOST('admin_add_data_table_form_insert');
		$this->cipherGroup->insert();
		header('Location: /admin-cipher-group');
	}

	public function actionUpdate()
	{
		$this->checkPOST('edit_data_table_form_update');
		$this->cipherGroup->update();	
		header('Location: /admin-cipher-group');
	}

	public function actionAjaxDelete()
	{
		$this->checkAjax();
		$this->cipherGroup->ajaxDelete();
	}
}

?>