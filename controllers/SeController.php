<?php

class SeController extends Controller
{
	private $skillsEnchancement;

	function __construct()
	{
		parent::__construct();
		$this->skillsEnchancement = new SkillsEnchancement;
		$this->getView()->setTitle('Повышение уровня квалификации');
		$this->getView()->setLayoutFooter('work_analysis');
	}

	public function actionIndex()
	{
		if (Auth::getInstance()->compareRules(Auth::CHAIR_PK_PKS) ||
			Auth::getInstance()->compareRules(Auth::HEAD_DEPARTMENT) ||
			Auth::getInstance()->compareRules(Auth::STANDARD))
		{
			if (Auth::getInstance()->getTableModule() == Auth::TABLE_MODULE_WORKS)
				header('Location: /se/works');
			else if (Auth::getInstance()->getTableModule() == Auth::TABLE_MODULE_ANALYSIS)
				header('Location: /se/analysis');
		}
		else
		{
			header('Location: /se/analysis');
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

		if (Auth::getInstance()->getTableId() != Auth::TABLE_ID_SE)
		{
			Auth::getInstance()->setTableId(Auth::TABLE_ID_SE);
			Auth::getInstance()->setPaginationPage(1);
		}
		if (Auth::getInstance()->getTableModule() != Auth::TABLE_MODULE_WORKS)
		{
			Auth::getInstance()->setTableModule(Auth::TABLE_MODULE_WORKS);
			Auth::getInstance()->setPaginationPage(1);
		}
		$this->render('skills_enchancement/works', array('skillsEnchancement' => $this->skillsEnchancement));
	}

	public function actionWorksAjaxLoad()
	{
		$this->checkAjax();
		$this->skillsEnchancement->getTable();
	}

	public function actionWorksAjaxPrint()
	{
		$this->checkAjax();
		$this->skillsEnchancement->getTable(true);
	}

	public function actionWorksExportExcel()
	{
		$this->checkPOST('export_excel_submit');
		$this->skillsEnchancement->worksExportExcel();
	}

	public function actionWorksAjaxInsert()
	{
		$this->checkAjax();
		$this->skillsEnchancement->worksAjaxInsert();
	}

	public function actionWorksAjaxDelete()
	{
		$this->checkAjax();
		$this->skillsEnchancement->worksAjaxDelete();
	}

	public function actionWorksEditFill()
	{
		$this->checkPOST('button_fill_edit');
		$this->skillsEnchancement->worksEditFill();

		$this->render('skills_enchancement/works_edit_fill', array('skillsEnchancement' => $this->skillsEnchancement));
	}

	public function actionWorksEditExec()
	{
		$this->checkPOST('button_exec_edit');
		$this->skillsEnchancement->worksEditExec();

		$this->render('skills_enchancement/works_edit_exec', array('skillsEnchancement' => $this->skillsEnchancement));
	}

	public function actionWorksUpdateFill()
	{
		$this->checkPOST('edit_fill_data_table_form_update');
		$this->skillsEnchancement->worksUpdateFill();
		header('Location: /se/works');
	}

	public function actionWorksUpdateExec()
	{
		$this->checkPOST('edit_exec_data_table_form_update');
		$this->skillsEnchancement->worksUpdateExec();
		header('Location: /se/works');
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

		if (Auth::getInstance()->getTableId() != Auth::TABLE_ID_SE)
		{
			Auth::getInstance()->setTableId(Auth::TABLE_ID_SE);
			Auth::getInstance()->setPaginationPage(1);
			Auth::getInstance()->resetTableAllAnalysisFilter();
		}
		if (Auth::getInstance()->getTableModule() != Auth::TABLE_MODULE_ANALYSIS)
		{
			Auth::getInstance()->setTableModule(Auth::TABLE_MODULE_ANALYSIS);
			Auth::getInstance()->setPaginationPage(1);
			//Auth::getInstance()->resetTableAllAnalysisFilter();
		}
		$this->render('skills_enchancement/analysis', array('skillsEnchancement' => $this->skillsEnchancement));
	}

	public function actionAnalysisAjaxLoad()
	{
		$this->checkAjax();
		$this->skillsEnchancement->getAnalysisTable();
	}

	public function actionAnalysisAjaxPrint()
	{
		$this->checkAjax();
		$this->skillsEnchancement->getAnalysisTable(true);
	}

	public function actionAnalysisExportExcel()
	{
		$this->checkPOST('export_excel_submit');
		$this->skillsEnchancement->analysisExportExcel();
	}

	public function actionAnalysisEdit()
	{
		$this->checkPOST('button_edit');
		$this->skillsEnchancement->analysisEdit();
		$this->render('skills_enchancement/analysis_edit', array('skillsEnchancement' => $this->skillsEnchancement));
	}

	public function actionAnalysisUpdate()
	{
		$this->checkPOST('edit_data_table_form_update');
		$this->skillsEnchancement->analysisUpdate();
		header('Location: /se/analysis');
	}

	public function actionAnalysisAjaxDelete()
	{
		$this->skillsEnchancement->analysisAjaxDelete();
	}
}

?>