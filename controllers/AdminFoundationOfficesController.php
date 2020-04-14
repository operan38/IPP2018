<?php 

class AdminFoundationOfficesController extends AdminController
{
	private $foundationOffices;

	function __construct()
	{
		parent::__construct();
		$this->foundationOffices = new FoundationOffices;
		$this->getView()->setTitle('Наименование кабинета');
	}

	public function actionIndex()
	{
		$this->render('admin_foundation_offices/index');
	}

	public function actionAdd()
	{
		$this->checkPOST('admin_key_ppccz');
		$this->render('admin_foundation_offices/add');
	}

	public function actionEdit()
	{
		$this->checkPOST('button_edit');
		$this->foundationOffices->edit();
		$this->render('admin_foundation_offices/edit', array('foundationOffices'=>$this->foundationOffices));
	}

	public function actionAjaxLoad()
	{
		$this->checkAjax();
		$this->foundationOffices->getTable();
	}

	public function actionInsert()
	{
		$this->checkPOST('admin_add_data_table_form_insert');
		$this->foundationOffices->insert();
		header('Location: /admin-foundation-offices');
	}

	public function actionUpdate()
	{
		$this->checkPOST('edit_data_table_form_update');
		$this->foundationOffices->update();	
		header('Location: /admin-foundation-offices');
	}

	public function actionAjaxDelete()
	{
		$this->checkAjax();
		$this->foundationOffices->ajaxDelete();
	}
}

?>