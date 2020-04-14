<?php

class EwController extends Controller
{
	private $educationalWork;

	function __construct()
	{
		parent::__construct();
		$this->educationalWork = new EducationalWork;
		$this->getView()->setTitle('Воспитательная работа');
		$this->getView()->setLayoutFooter('work_analysis');
	}

	public function actionIndex()
	{
		if (Auth::getInstance()->compareRules(Auth::CHAIR_PK_PKS) ||
			Auth::getInstance()->compareRules(Auth::HEAD_DEPARTMENT) ||
			Auth::getInstance()->compareRules(Auth::STANDARD))
		{
			if (Auth::getInstance()->getTableModule() == Auth::TABLE_MODULE_WORKS)
				header('Location: /ew/works');
			else if (Auth::getInstance()->getTableModule() == Auth::TABLE_MODULE_ANALYSIS)
				header('Location: /ew/analysis');
		}
		else
		{
			header('Location: /ew/analysis');
		}
	}

	// Works

	public function actionWorks()
	{
		if (Auth::getInstance()->compareRules(Auth::CHAIR_PK_PKS) ||
			Auth::getInstance()->compareRules(Auth::HEAD_DEPARTMENT) ||
			Auth::getInstance()->compareRules(Auth::STANDARD)){}
		else
		{
			header('Location: /');
			exit;
		}

		if (Auth::getInstance()->getTableId() != Auth::TABLE_ID_EW)
		{
			Auth::getInstance()->setTableId(Auth::TABLE_ID_EW);
			Auth::getInstance()->setPaginationPage(1);
		}
		if (Auth::getInstance()->getTableModule() != Auth::TABLE_MODULE_WORKS)
		{
			Auth::getInstance()->setTableModule(Auth::TABLE_MODULE_WORKS);
			Auth::getInstance()->setPaginationPage(1);
		}
		$this->render('educational_work/works', array('educationalWork' => $this->educationalWork));
	}

	public function actionWorksAjaxLoad()
	{
		$this->checkAjax();
		$this->educationalWork->getTable();
	}

	public function actionWorksAjaxPrint()
	{
		$this->checkAjax();
		$this->educationalWork->getTable(true);
	}

	public function actionWorksExportExcel()
	{
		$this->checkPOST('export_excel_submit');
		$this->educationalWork->worksExportExcel();
	}

	public function actionAjaxDataTable()
	{
		$this->checkAjax();
		$this->educationalWork->getAjaxDataTable();
	}

	public function actionWorksAjaxInsert()
	{
		$this->checkAjax();
		$this->educationalWork->worksAjaxInsert();
	}

	public function actionWorksAjaxDelete()
	{
		$this->checkAjax();
		$this->educationalWork->worksAjaxDelete();
	}

	public function actionWorksEditFill()
	{
		$this->checkPOST('button_fill_edit');
		$this->educationalWork->worksEditFill();

		$this->render('educational_work/works_edit_fill', array('educationalWork' => $this->educationalWork));
	}

	public function actionWorksEditExec()
	{
		$this->checkPOST('button_exec_edit');
		$this->educationalWork->worksEditExec();

		$this->render('educational_work/works_edit_exec', array('educationalWork' => $this->educationalWork));
	}

	public function actionWorksUpdateFill()
	{
		$this->checkPOST('edit_fill_data_table_form_update');
		$this->educationalWork->worksUpdateFill();
		header('Location: /ew/works');
	}

	public function actionWorksUpdateExec()
	{
		$this->checkPOST('edit_exec_data_table_form_update');
		$this->educationalWork->worksUpdateExec();
		header('Location: /ew/works');
	}

	// Analysis - Анализ и фильтрация

	public function actionAnalysis()
	{
		if (Auth::getInstance()->compareRules(Auth::CHAIR_PK_PKS) ||
			Auth::getInstance()->compareRules(Auth::HEAD_DEPARTMENT) ||
			Auth::getInstance()->compareRules(Auth::DIRECTOR) ||
			Auth::getInstance()->compareRules(Auth::DEP_DIRECTOR) || 
			Auth::getInstance()->compareRules(Auth::DEP_UPR) ||
			Auth::getInstance()->compareRules(Auth::HEAD_UMC)){}
		else
		{
			header('Location: /');
			exit;
		}

		if (Auth::getInstance()->getTableId() != Auth::TABLE_ID_EW)
		{
			Auth::getInstance()->setTableId(Auth::TABLE_ID_EW);
			Auth::getInstance()->setPaginationPage(1);
			Auth::getInstance()->resetTableAllAnalysisFilter();
		}
		if (Auth::getInstance()->getTableModule() != Auth::TABLE_MODULE_ANALYSIS)
		{
			Auth::getInstance()->setTableModule(Auth::TABLE_MODULE_ANALYSIS);
			Auth::getInstance()->setPaginationPage(1);
			//Auth::getInstance()->resetTableAllAnalysisFilter();
		}
		$this->render('educational_work/analysis', array('educationalWork' => $this->educationalWork));
	}

	public function actionAnalysisAjaxLoad()
	{
		$this->checkAjax();
		$this->educationalWork->getAnalysisTable();
	}

	public function actionAnalysisAjaxPrint()
	{
		$this->checkAjax();
		$this->educationalWork->getAnalysisTable(true);
	}

	public function actionAnalysisExportExcel()
	{
		$this->checkPOST('export_excel_submit');
		$this->educationalWork->analysisExportExcel();
	}

	public function actionAnalysisEdit()
	{
		$this->checkPOST('button_edit');
		$this->educationalWork->analysisEdit();
		$this->render('educational_work/analysis_edit', array('educationalWork' => $this->educationalWork));
	}

	public function actionAnalysisUpdate()
	{
		$this->checkPOST('edit_data_table_form_update');
		$this->educationalWork->analysisUpdate();
		header('Location: /ew/analysis');
	}

	public function actionAnalysisAjaxDelete()
	{
		$this->educationalWork->analysisAjaxDelete();
	}
}

?>