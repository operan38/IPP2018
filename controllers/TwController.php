<?php

class TwController extends Controller
{
	private $trainingWork;

	function __construct()
	{
		parent::__construct();
		$this->trainingWork = new TrainingWork;
		$this->getView()->setTitle('Учебная работа');
		$this->getView()->setLayoutFooter('work_analysis');
	}

	public function actionIndex()
	{
		if (Auth::getInstance()->compareRules(Auth::CHAIR_PK_PKS) ||
			Auth::getInstance()->compareRules(Auth::HEAD_DEPARTMENT) ||
			Auth::getInstance()->compareRules(Auth::STANDARD))
		{
			if (Auth::getInstance()->getTableModule() == Auth::TABLE_MODULE_WORKS)
				header('Location: /tw/works');
			else if (Auth::getInstance()->getTableModule() == Auth::TABLE_MODULE_ANALYSIS)
				header('Location: /tw/analysis');
		}
		else
		{
			header('Location: /tw/analysis');
		}
	}

	// Works - Заполнение и выполнение ИП

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

		if (Auth::getInstance()->getTableId() != Auth::TABLE_ID_TW)
		{
			Auth::getInstance()->setTableId(Auth::TABLE_ID_TW);
			Auth::getInstance()->setPaginationPage(1);
		}
		if (Auth::getInstance()->getTableModule() != Auth::TABLE_MODULE_WORKS)
		{
			Auth::getInstance()->setTableModule(Auth::TABLE_MODULE_WORKS);
			Auth::getInstance()->setPaginationPage(1);
		}
		$this->render('training_work/works', array('trainingWork' => $this->trainingWork));
	}

	public function actionWorksAjaxLoad()
	{
		$this->checkAjax();
		$this->trainingWork->getTable();
	}

	public function actionWorksAjaxPrint()
	{
		$this->checkAjax();
		$this->trainingWork->getTable(true);
	}

	public function actionWorksExportExcel()
	{
		$this->checkPOST('export_excel_submit');
		$this->trainingWork->worksExportExcel();
	}

	public function actionAjaxDataTable()
	{
		$this->checkAjax();
		$this->trainingWork->getAjaxDataTable();
	}

	public function actionWorksAjaxInsert()
	{
		$this->checkAjax();
		$this->trainingWork->worksAjaxInsert();
	}

	public function actionWorksAjaxDelete()
	{
		$this->checkAjax();
		$this->trainingWork->worksAjaxDelete();
	}

	public function actionWorksEditFill()
	{
		$this->checkPOST('button_fill_edit');
		$this->trainingWork->worksEditFill();

		$this->render('training_work/works_edit_fill', array('trainingWork' => $this->trainingWork));
	}

	public function actionWorksEditExec()
	{
		$this->checkPOST('button_exec_edit');
		$this->trainingWork->worksEditExec();

		$this->render('training_work/works_edit_exec', array('trainingWork' => $this->trainingWork));
	}

	public function actionWorksUpdateFill()
	{
		$this->checkPOST('edit_fill_data_table_form_update');
		$this->trainingWork->worksUpdateFill();
		header('Location: /tw/works');
	}

	public function actionWorksUpdateExec()
	{
		$this->checkPOST('edit_exec_data_table_form_update');
		$this->trainingWork->worksUpdateExec();
		header('Location: /tw/works');
	}

	// Analysis - Анализ и фильтрация

	public function actionAnalysis()
	{
		if (Auth::getInstance()->compareRules(Auth::CHAIR_PK_PKS) ||
			Auth::getInstance()->compareRules(Auth::HEAD_DEPARTMENT) ||
			Auth::getInstance()->compareRules(Auth::DEP_DIRECTOR) || 
			Auth::getInstance()->compareRules(Auth::DIRECTOR) || 
			Auth::getInstance()->compareRules(Auth::DEP_UPR) ||
			Auth::getInstance()->compareRules(Auth::HEAD_UMC)){}
		else
		{
			header('Location: /');
			exit;
		}


		if (Auth::getInstance()->getTableId() != Auth::TABLE_ID_TW)
		{
			Auth::getInstance()->setTableId(Auth::TABLE_ID_TW);
			Auth::getInstance()->setPaginationPage(1);
			Auth::getInstance()->resetTableAllAnalysisFilter();
		}
		if (Auth::getInstance()->getTableModule() != Auth::TABLE_MODULE_ANALYSIS)
		{
			Auth::getInstance()->setTableModule(Auth::TABLE_MODULE_ANALYSIS);
			Auth::getInstance()->setPaginationPage(1);
			//Auth::getInstance()->resetTableAllAnalysisFilter();
		}
		$this->render('training_work/analysis', array('trainingWork' => $this->trainingWork));
	}

	public function actionAnalysisAjaxLoad()
	{
		$this->checkAjax();
		$this->trainingWork->getAnalysisTable();
	}

	public function actionAnalysisAjaxPrint()
	{
		$this->checkAjax();
		$this->trainingWork->getAnalysisTable(true);
	}

	public function actionAnalysisExportExcel()
	{
		$this->checkPOST('export_excel_submit');
		$this->trainingWork->analysisExportExcel();
	}

	public function actionAnalysisEdit()
	{
		$this->checkPOST('button_edit');
		$this->trainingWork->analysisEdit();
		$this->render('training_work/analysis_edit', array('trainingWork' => $this->trainingWork));
	}

	public function actionAnalysisUpdate()
	{
		$this->checkPOST('edit_data_table_form_update');
		$this->trainingWork->analysisUpdate();
		header('Location: /tw/analysis');
	}

	public function actionAnalysisAjaxDelete()
	{
		$this->trainingWork->analysisAjaxDelete();
	}
}

?>