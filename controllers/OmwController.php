<?php

class OmwController extends Controller
{
	private $organizationalMethodicalWork;

	function __construct()
	{
		parent::__construct();
		$this->organizationalMethodicalWork = new OrganizationalMethodicalWork;
		$this->getView()->setTitle('Организационно-методическая работа');
		$this->getView()->setLayoutFooter('work_analysis');
	}

	public function actionIndex()
	{
		if (Auth::getInstance()->compareRules(Auth::CHAIR_PK_PKS) ||
			Auth::getInstance()->compareRules(Auth::HEAD_DEPARTMENT) ||
			Auth::getInstance()->compareRules(Auth::STANDARD))
		{
			if (Auth::getInstance()->getTableModule() == Auth::TABLE_MODULE_WORKS)
				header('Location: /omw/works');
			else if (Auth::getInstance()->getTableModule() == Auth::TABLE_MODULE_ANALYSIS)
				header('Location: /omw/analysis');
		}
		else
		{
			header('Location: /omw/analysis');
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


		if (Auth::getInstance()->getTableId() != Auth::TABLE_ID_OMW)
		{
			Auth::getInstance()->setTableId(Auth::TABLE_ID_OMW);
			Auth::getInstance()->setPaginationPage(1);
		}
		if (Auth::getInstance()->getTableModule() != Auth::TABLE_MODULE_WORKS)
		{
			Auth::getInstance()->setTableModule(Auth::TABLE_MODULE_WORKS);
			Auth::getInstance()->setPaginationPage(1);
		}
		$this->render('organizational_methodical_work/works', array('organizationalMethodicalWork' => $this->organizationalMethodicalWork));
	}

	public function actionWorksAjaxLoad()
	{
		$this->checkAjax();
		$this->organizationalMethodicalWork->getTable();
	}

	public function actionWorksAjaxPrint()
	{
		$this->checkAjax();
		$this->organizationalMethodicalWork->getTable(true);
	}

	public function actionWorksExportExcel()
	{
		$this->checkPOST('export_excel_submit');
		$this->organizationalMethodicalWork->worksExportExcel();
	}

	public function actionWorksAjaxInsert()
	{
		$this->checkAjax();
		$this->organizationalMethodicalWork->worksAjaxInsert();
	}

	public function actionWorksAjaxDelete()
	{
		$this->checkAjax();
		$this->organizationalMethodicalWork->worksAjaxDelete();
	}

	public function actionWorksEditFill()
	{
		$this->checkPOST('button_fill_edit');
		$this->organizationalMethodicalWork->worksEditFill();

		$this->render('organizational_methodical_work/works_edit_fill', array('organizationalMethodicalWork' => $this->organizationalMethodicalWork));
	}

	public function actionWorksEditExec()
	{
		$this->checkPOST('button_exec_edit');
		$this->organizationalMethodicalWork->worksEditExec();

		$this->render('organizational_methodical_work/works_edit_exec', array('organizationalMethodicalWork' => $this->organizationalMethodicalWork));
	}

	public function actionWorksUpdateFill()
	{
		$this->checkPOST('edit_fill_data_table_form_update');
		$this->organizationalMethodicalWork->worksUpdateFill();
		header('Location: /omw/works');
	}

	public function actionWorksUpdateExec()
	{
		$this->checkPOST('edit_exec_data_table_form_update');
		$this->organizationalMethodicalWork->worksUpdateExec();
		header('Location: /omw/works');
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

		if (Auth::getInstance()->getTableId() != Auth::TABLE_ID_OMW)
		{
			Auth::getInstance()->setTableId(Auth::TABLE_ID_OMW);
			Auth::getInstance()->setPaginationPage(1);
			Auth::getInstance()->resetTableAllAnalysisFilter();
		}
		if (Auth::getInstance()->getTableModule() != Auth::TABLE_MODULE_ANALYSIS)
		{
			Auth::getInstance()->setTableModule(Auth::TABLE_MODULE_ANALYSIS);
			Auth::getInstance()->setPaginationPage(1);
			//Auth::getInstance()->resetTableAllAnalysisFilter();
		}
		$this->render('organizational_methodical_work/analysis', array('organizationalMethodicalWork' => $this->organizationalMethodicalWork));
	}

	public function actionAnalysisAjaxLoad()
	{
		$this->checkAjax();
		$this->organizationalMethodicalWork->getAnalysisTable();
	}

	public function actionAnalysisAjaxPrint()
	{
		$this->checkAjax();
		$this->organizationalMethodicalWork->getAnalysisTable(true);
	}

	public function actionAnalysisExportExcel()
	{
		$this->checkPOST('export_excel_submit');
		$this->organizationalMethodicalWork->analysisExportExcel();
	}

	public function actionAnalysisEdit()
	{
		$this->checkPOST('button_edit');
		$this->organizationalMethodicalWork->analysisEdit();
		$this->render('organizational_methodical_work/analysis_edit', array('organizationalMethodicalWork' => $this->organizationalMethodicalWork));
	}

	public function actionAnalysisUpdate()
	{
		$this->checkPOST('edit_data_table_form_update');
		$this->organizationalMethodicalWork->analysisUpdate();
		header('Location: /omw/analysis');
	}

	public function actionAnalysisAjaxDelete()
	{
		$this->organizationalMethodicalWork->analysisAjaxDelete();
	}
}

?>