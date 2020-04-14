<?php

class EmwController extends Controller
{
	private $educationalMethodicalWork;

	function __construct()
	{
		parent::__construct();
		$this->educationalMethodicalWork = new EducationalMethodicalWork;
		$this->getView()->setTitle('Учебно-методическая работа');
		$this->getView()->setLayoutFooter('work_analysis');
	}

	public function actionIndex()
	{
		if (Auth::getInstance()->compareRules(Auth::CHAIR_PK_PKS) ||
			Auth::getInstance()->compareRules(Auth::HEAD_DEPARTMENT) ||
			Auth::getInstance()->compareRules(Auth::STANDARD))
		{
			if (Auth::getInstance()->getTableModule() == Auth::TABLE_MODULE_WORKS)
				header('Location: /emw/works');
			else if (Auth::getInstance()->getTableModule() == Auth::TABLE_MODULE_ANALYSIS)
				header('Location: /emw/analysis');
		}
		else
		{
			header('Location: /emw/analysis');
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

		if (Auth::getInstance()->getTableId() != Auth::TABLE_ID_EMW)
		{
			Auth::getInstance()->setTableId(Auth::TABLE_ID_EMW);
			Auth::getInstance()->setPaginationPage(1);
		}
		if (Auth::getInstance()->getTableModule() != Auth::TABLE_MODULE_WORKS)
		{
			Auth::getInstance()->setTableModule(Auth::TABLE_MODULE_WORKS);
			Auth::getInstance()->setPaginationPage(1);
		}
		$this->render('educational_methodical_work/works', array('educationalMethodicalWork' => $this->educationalMethodicalWork));
	}

	public function actionWorksAjaxLoad()
	{
		$this->checkAjax();
		$this->educationalMethodicalWork->getTable();
	}

	public function actionWorksAjaxPrint()
	{
		$this->checkAjax();
		$this->educationalMethodicalWork->getTable(true);
	}

	public function actionWorksExportExcel()
	{
		$this->checkPOST('export_excel_submit');
		$this->educationalMethodicalWork->worksExportExcel();
	}

	public function actionAjaxDataTable()
	{
		$this->checkAjax();
		$this->educationalMethodicalWork->getAjaxDataTable();
	}

	public function actionWorksAjaxInsert()
	{
		$this->checkAjax();
		$this->educationalMethodicalWork->worksAjaxInsert();
	}

	public function actionWorksAjaxDelete()
	{
		$this->checkAjax();
		$this->educationalMethodicalWork->worksAjaxDelete();
	}

	public function actionWorksEditFill()
	{
		$this->checkPOST('button_fill_edit');
		$this->educationalMethodicalWork->worksEditFill();

		$this->render('educational_methodical_work/works_edit_fill', array('educationalMethodicalWork' => $this->educationalMethodicalWork));
	}

	public function actionWorksEditExec()
	{
		$this->checkPOST('button_exec_edit');
		$this->educationalMethodicalWork->worksEditExec();

		$this->render('educational_methodical_work/works_edit_exec', array('educationalMethodicalWork' => $this->educationalMethodicalWork));
	}

	public function actionWorksUpdateFill()
	{
		$this->checkPOST('edit_fill_data_table_form_update');
		$this->educationalMethodicalWork->worksUpdateFill();
		header('Location: /emw/works');
	}

	public function actionWorksUpdateExec()
	{
		$this->checkPOST('edit_exec_data_table_form_update');
		$this->educationalMethodicalWork->worksUpdateExec();
		header('Location: /emw/works');
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

		if (Auth::getInstance()->getTableId() != Auth::TABLE_ID_EMW)
		{
			Auth::getInstance()->setTableId(Auth::TABLE_ID_EMW);
			Auth::getInstance()->setPaginationPage(1);
			Auth::getInstance()->resetTableAllAnalysisFilter();
		}
		if (Auth::getInstance()->getTableModule() != Auth::TABLE_MODULE_ANALYSIS)
		{
			Auth::getInstance()->setTableModule(Auth::TABLE_MODULE_ANALYSIS);
			Auth::getInstance()->setPaginationPage(1);
			//Auth::getInstance()->resetTableAllAnalysisFilter();
		}
		$this->render('educational_methodical_work/analysis', array('educationalMethodicalWork' => $this->educationalMethodicalWork));
	}

	public function actionAnalysisAjaxLoad()
	{
		$this->checkAjax();
		$this->educationalMethodicalWork->getAnalysisTable();
	}

	public function actionAnalysisAjaxPrint()
	{
		$this->checkAjax();
		$this->educationalMethodicalWork->getAnalysisTable(true);
	}

	public function actionAnalysisExportExcel()
	{
		$this->checkPOST('export_excel_submit');
		$this->educationalMethodicalWork->analysisExportExcel();
	}

	public function actionAnalysisEdit()
	{
		$this->checkPOST('button_edit');
		$this->educationalMethodicalWork->analysisEdit();
		$this->render('educational_methodical_work/analysis_edit', array('educationalMethodicalWork' => $this->educationalMethodicalWork));
	}

	public function actionAnalysisUpdate()
	{
		$this->checkPOST('edit_data_table_form_update');
		$this->educationalMethodicalWork->analysisUpdate();
		header('Location: /emw/analysis');
	}

	public function actionAnalysisAjaxDelete()
	{
		$this->educationalMethodicalWork->analysisAjaxDelete();
	}
}

?>