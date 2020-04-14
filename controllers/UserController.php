<?php

class UserController extends Controller
{
	function __construct()
	{
		parent::__construct();
	}

	public function actionTitle()
	{
		if (Auth::getInstance()->compareRules(Auth::STANDARD) || Auth::getInstance()->compareRules(Auth::CHAIR_PK_PKS)
			|| Auth::getInstance()->compareRules(Auth::HEAD_DEPARTMENT)){}
		else
		{
			header('Location: /');
			exit;
		}

		$this->getView()->setTitle('Титульный лист');
		$this->getView()->setLayoutFooter('work_analysis');
		$titleForm = new TitleForm;

		if (isset($_POST['title_submit']))
		{
			$titleForm->surname = $_POST['title_surname'];
			$titleForm->name = $_POST['title_name'];
			$titleForm->patronymic = $_POST['title_patronymic'];
			$titleForm->id_pk_pkc = $_POST['title_id_pk_pkc'];
			$titleForm->id_type_employee = $_POST['title_id_type_employee'];
			$titleForm->id_department = $_POST['title_id_department'];
			$titleForm->id_posts = $_POST['title_id_posts'];
			$titleForm->category = $_POST['title_category'];
			$titleForm->date_certification = $_POST['title_date_certification'];

			$titleForm->edit();
		}

		$this->render('user/title', array('titleForm' => $titleForm));
	}

	public function actionAjaxTitlePrint()
	{
		$this->checkAjax();
		$titleForm = new TitleForm;
		$titleForm->ajaxPrint();
	}

	public function actionAcademicYearSubmit()
	{
		$this->checkPOST('id_academic_year');
		Auth::getInstance()->setAcademicYear(Helper::clean($_POST['id_academic_year']));
		Auth::getInstance()->setPaginationPage(1);
		header('Location: '.$_SERVER['HTTP_REFERER']);
	}

	/*public function actionTableModeFill()
	{
		if (Auth::getInstance()->compareAcademicYearLocking(Auth::ACADEMIC_YEAR_FILL))
		{
			$this->checkPOST('academic_year_fill');
			Auth::getInstance()->setTableWorksMode(Auth::TABLE_WORKS_MODE_FILL);
		}
		header('Location: '.$_SERVER['HTTP_REFERER']);
	}*/

	public function actionTableModeFillEdit()
	{
		$this->checkPOST('academic_year_fill_edit');
		if (Auth::getInstance()->compareAcademicYearLocking(Auth::ACADEMIC_YEAR_FILL_EDIT_EXEC))
		{
			Auth::getInstance()->setTableWorksMode(Auth::TABLE_WORKS_MODE_FILL_EDIT);
			header('Location: '.$_SERVER['HTTP_REFERER']);
		}
	}

	public function actionTableModeExec()
	{
		$this->checkPOST('academic_year_exec');
		if (Auth::getInstance()->compareAcademicYearLocking(Auth::ACADEMIC_YEAR_FILL_EDIT_EXEC))
		{
			Auth::getInstance()->setTableWorksMode(Auth::TABLE_WORKS_MODE_EXEC);
			header('Location: '.$_SERVER['HTTP_REFERER']);
		}
	}

	public function actionAjaxSetPaginationPage()
	{
		$this->checkAjax();
		Auth::getInstance()->setPaginationPage(Helper::clean($_POST['pagination_page']));
	}

	public function actionAjaxSetPaginationItemPerPage()
	{
		$this->checkAjax();
		Auth::getInstance()->setPaginationItemPerPage(Helper::clean($_POST['pagination_item_per_page']));
	}

	public function actionAjaxGetPaginationPage()
	{
		$this->checkAjax();
		$arr = array();
		$arr['pagination_item_per_page'] = Auth::getInstance()->getPaginationItemPerPage();
		$arr['pagination_page'] = Auth::getInstance()->getPaginationPage();
		echo json_encode($arr);
	}

	public function actionLogout()
	{
		Auth::getInstance()->logout();
		header('Location: /');
	}
}

?>