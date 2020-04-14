<?php

class SmwController extends Controller
{
	private $scientificMethodicalWork;

	function __construct()
	{
		parent::__construct();
		$this->scientificMethodicalWork = new ScientificMethodicalWork;
		$this->getView()->setTitle('Научно-методическая работа');
		$this->getView()->setLayoutFooter('work_analysis');
	}

	public function actionIndex()
	{
		if (Auth::getInstance()->compareRules(Auth::CHAIR_PK_PKS) ||
			Auth::getInstance()->compareRules(Auth::HEAD_DEPARTMENT) ||
			Auth::getInstance()->compareRules(Auth::STANDARD))
		{
			if (Auth::getInstance()->getTableModule() == Auth::TABLE_MODULE_WORKS)
				header('Location: /smw/works');
			else if (Auth::getInstance()->getTableModule() == Auth::TABLE_MODULE_ANALYSIS)
				header('Location: /smw/analysis');
		}
		else
		{
			header('Location: /smw/analysis');
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

		if (Auth::getInstance()->getTableId() != Auth::TABLE_ID_SMW)
		{
			Auth::getInstance()->setTableId(Auth::TABLE_ID_SMW);
			Auth::getInstance()->setPaginationPage(1);
		}
		if (Auth::getInstance()->getTableModule() != Auth::TABLE_MODULE_WORKS)
		{
			Auth::getInstance()->setTableModule(Auth::TABLE_MODULE_WORKS);
			Auth::getInstance()->setPaginationPage(1);
		}
		$this->render('scientific_methodical_work/works', array('scientificMethodicalWork' => $this->scientificMethodicalWork));
	}

	public function actionWorksAjaxLoad()
	{
		$this->checkAjax();
		$this->scientificMethodicalWork->getTable();
	}

	public function actionWorksAjaxPrint()
	{
		$this->checkAjax();
		$this->scientificMethodicalWork->getTable(true);
	}

	public function actionWorksExportExcel()
	{
		$this->checkPOST('export_excel_submit');
		$this->scientificMethodicalWork->worksExportExcel();
	}

	public function actionWorksAjaxInsert()
	{
		$this->checkAjax();
		$this->scientificMethodicalWork->worksAjaxInsert();
	}

	public function actionWorksAjaxDelete()
	{
		$this->checkAjax();
		$this->scientificMethodicalWork->worksAjaxDelete();
	}

	public function actionWorksEditFill()
	{
		$this->checkPOST('button_fill_edit');
		$this->scientificMethodicalWork->worksEditFill();

		$this->render('scientific_methodical_work/works_edit_fill', array('scientificMethodicalWork' => $this->scientificMethodicalWork));
	}

	public function actionWorksEditExec()
	{
		$this->checkPOST('button_exec_edit');
		$this->scientificMethodicalWork->worksEditExec();

		$this->render('scientific_methodical_work/works_edit_exec', array('scientificMethodicalWork' => $this->scientificMethodicalWork));
	}

	public function actionWorksUpdateFill()
	{
		$this->checkPOST('edit_fill_data_table_form_update');
		$this->scientificMethodicalWork->worksUpdateFill();
		header('Location: /smw/works');
	}

	public function actionWorksUpdateExec()
	{
		$this->checkPOST('edit_exec_data_table_form_update');
		$this->scientificMethodicalWork->worksUpdateExec();
		header('Location: /smw/works');
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

		if (Auth::getInstance()->getTableId() != Auth::TABLE_ID_SMW)
		{
			Auth::getInstance()->setTableId(Auth::TABLE_ID_SMW);
			Auth::getInstance()->setPaginationPage(1);
			Auth::getInstance()->resetTableAllAnalysisFilter();
		}
		if (Auth::getInstance()->getTableModule() != Auth::TABLE_MODULE_ANALYSIS)
		{
			Auth::getInstance()->setTableModule(Auth::TABLE_MODULE_ANALYSIS);
			Auth::getInstance()->setPaginationPage(1);
			//Auth::getInstance()->resetTableAllAnalysisFilter();
		}
		$this->render('scientific_methodical_work/analysis', array('scientificMethodicalWork' => $this->scientificMethodicalWork));
	}

	public function actionAnalysisAjaxLoad()
	{
		$this->checkAjax();
		$this->scientificMethodicalWork->getAnalysisTable();
	}

	public function actionAnalysisAjaxPrint()
	{
		$this->checkAjax();
		$this->scientificMethodicalWork->getAnalysisTable(true);
	}

	public function actionAnalysisExportExcel()
	{
		$this->checkPOST('export_excel_submit');
		$this->scientificMethodicalWork->analysisExportExcel();
	}

	public function actionAnalysisEdit()
	{
		$this->checkPOST('button_edit');
		$this->scientificMethodicalWork->analysisEdit();
		$this->render('scientific_methodical_work/analysis_edit', array('scientificMethodicalWork' => $this->scientificMethodicalWork));
	}

	public function actionAnalysisUpdate()
	{
		$this->checkPOST('edit_data_table_form_update');
		$this->scientificMethodicalWork->analysisUpdate();
		header('Location: /smw/analysis');
	}

	public function actionAnalysisAjaxDelete()
	{
		$this->scientificMethodicalWork->analysisAjaxDelete();
	}
}

?>