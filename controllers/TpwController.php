<?php

class TpwController extends Controller
{
	private $trainingProductionWork;

	function __construct()
	{
		parent::__construct();
		$this->trainingProductionWork = new TrainingProductionWork;
		$this->getView()->setTitle('Учебно-производственная работа');
		$this->getView()->setLayoutFooter('work_analysis');
	}

	public function actionIndex()
	{
		if (Auth::getInstance()->compareRules(Auth::CHAIR_PK_PKS) ||
			Auth::getInstance()->compareRules(Auth::HEAD_DEPARTMENT) ||
			Auth::getInstance()->compareRules(Auth::STANDARD))
		{
			if (Auth::getInstance()->getTableModule() == Auth::TABLE_MODULE_WORKS)
				header('Location: /tpw/works');
			else if (Auth::getInstance()->getTableModule() == Auth::TABLE_MODULE_ANALYSIS)
				header('Location: /tpw/analysis');
		}
		else
		{
			header('Location: /tpw/analysis');
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

		if (Auth::getInstance()->getTableId() != Auth::TABLE_ID_TPW)
		{
			Auth::getInstance()->setTableId(Auth::TABLE_ID_TPW);
			Auth::getInstance()->setPaginationPage(1);
		}
		if (Auth::getInstance()->getTableModule() != Auth::TABLE_MODULE_WORKS)
		{
			Auth::getInstance()->setTableModule(Auth::TABLE_MODULE_WORKS);
			Auth::getInstance()->setPaginationPage(1);
		}
		$this->render('training_production_work/works', array('trainingProductionWork' => $this->trainingProductionWork));
	}

	public function actionWorksAjaxLoad()
	{
		$this->checkAjax();
		$this->trainingProductionWork->getTable();
	}

	public function actionWorksAjaxPrint()
	{
		$this->checkAjax();
		$this->trainingProductionWork->getTable(true);
	}

	public function actionWorksExportExcel()
	{
		$this->checkPOST('export_excel_submit');
		$this->trainingProductionWork->worksExportExcel();
	}

	public function actionAjaxDataTable()
	{
		$this->checkAjax();
		$this->trainingProductionWork->getAjaxDataTable();
	}

	public function actionWorksAjaxInsert()
	{
		$this->checkAjax();
		$this->trainingProductionWork->worksAjaxInsert();
	}

	public function actionWorksAjaxDelete()
	{
		$this->checkAjax();
		$this->trainingProductionWork->worksAjaxDelete();
	}

	public function actionWorksEditFill()
	{
		$this->checkPOST('button_fill_edit');
		$this->trainingProductionWork->worksEditFill();

		$this->render('training_production_work/works_edit_fill', array('trainingProductionWork' => $this->trainingProductionWork));
	}

	public function actionWorksEditExec()
	{
		$this->checkPOST('button_exec_edit');
		$this->trainingProductionWork->worksEditExec();

		$this->render('training_production_work/works_edit_exec', array('trainingProductionWork' => $this->trainingProductionWork));
	}

	public function actionWorksUpdateFill()
	{
		$this->checkPOST('edit_fill_data_table_form_update');
		$this->trainingProductionWork->worksUpdateFill();
		header('Location: /tpw/works');
	}

	public function actionWorksUpdateExec()
	{
		$this->checkPOST('edit_exec_data_table_form_update');
		$this->trainingProductionWork->worksUpdateExec();
		header('Location: /tpw/works');
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

		if (Auth::getInstance()->getTableId() != Auth::TABLE_ID_TPW)
		{
			Auth::getInstance()->setTableId(Auth::TABLE_ID_TPW);
			Auth::getInstance()->setPaginationPage(1);
			Auth::getInstance()->resetTableAllAnalysisFilter();
		}
		if (Auth::getInstance()->getTableModule() != Auth::TABLE_MODULE_ANALYSIS)
		{
			Auth::getInstance()->setTableModule(Auth::TABLE_MODULE_ANALYSIS);
			Auth::getInstance()->setPaginationPage(1);
			//Auth::getInstance()->resetTableAllAnalysisFilter();
		}
		$this->render('training_production_work/analysis', array('trainingProductionWork' => $this->trainingProductionWork));
	}

	public function actionAnalysisAjaxLoad()
	{
		$this->checkAjax();
		$this->trainingProductionWork->getAnalysisTable();
	}

	public function actionAnalysisAjaxPrint()
	{
		$this->checkAjax();
		$this->trainingProductionWork->getAnalysisTable(true);
	}

	public function actionAnalysisExportExcel()
	{
		$this->checkPOST('export_excel_submit');
		$this->trainingProductionWork->analysisExportExcel();
	}

	public function actionAnalysisEdit()
	{
		$this->checkPOST('button_edit');
		$this->trainingProductionWork->analysisEdit();
		$this->render('training_production_work/analysis_edit', array('trainingProductionWork' => $this->trainingProductionWork));
	}

	public function actionAnalysisUpdate()
	{
		$this->checkPOST('edit_data_table_form_update');
		$this->trainingProductionWork->analysisUpdate();
		header('Location: /tpw/analysis');
	}

	public function actionAnalysisAjaxDelete()
	{
		$this->trainingProductionWork->analysisAjaxDelete();
	}
}

?>