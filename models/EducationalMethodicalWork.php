<?php

class EducationalMethodicalWork extends WorkAnalysTable
{
	public $id_emw;
	public $ppccz;
	public $discipline;
	public $type_activities;
	public $type_umd;
	public $type_umd2;
	public $date_performance;
	public $report;
	public $id_employee;
	public $id_academic_year;

	function __construct(){}

	public function getTable($isPrint = false)
	{
		$db = DataBase::getInstance()->getDb();
		$this->id_academic_year = Auth::getInstance()->getAcademicYear();
		$this->id_employee = Auth::getInstance()->getAuth('id');

		$pagination = new Pagination;
		$result = "";

		if (!$isPrint)
		{
			$pagination->setParam([':id_academic_year' => $this->id_academic_year, ':id_employee' => $this->id_employee]);
			$pagination->init("SELECT COUNT(*) FROM educational_methodical_work WHERE id_academic_year = :id_academic_year AND id_employee = :id_employee");

			$result = $db->prepare("SELECT id_emw, ppccz.cipher_specialty,ppccz.name_ppccz,spr_index_disciplines.dindex, disciplines.dname, spr_t_activities.name, spr_t_umd.uname, spr_t_umd2.name_umd2, date_performance, DATE_FORMAT(zdate,'%d.%m.%Y') zdate, report
			          FROM (spr_index_disciplines INNER JOIN
			          (ppccz INNER JOIN 
			          (disciplines INNER JOIN
			          (spr_t_activities INNER JOIN
			          (spr_t_umd INNER JOIN
					  (spr_t_umd2 INNER JOIN educational_methodical_work
					   ON spr_t_umd2.id_umd2 = educational_methodical_work.type_umd2)
					   ON spr_t_umd.id = educational_methodical_work.type_umd)
			           ON spr_t_activities.id = educational_methodical_work.type_activities) 
			           ON disciplines.id = educational_methodical_work.discipline)
			           ON ppccz.id_ppccz = educational_methodical_work.ppccz)
			           ON spr_index_disciplines.id_ind = disciplines.dindex)
			           WHERE id_academic_year = :id_academic_year AND id_employee = :id_employee ORDER BY id_emw LIMIT :page_position,:item_per_page");
			$result->bindParam(':id_academic_year', $this->id_academic_year);
			$result->bindParam(':id_employee', $this->id_employee);
			$result->bindValue(':page_position', $pagination->getPagePosition(), PDO::PARAM_INT);
			$result->bindValue(':item_per_page', $pagination->getItemPerPage(), PDO::PARAM_INT);
			$result->execute();
		}
		else
		{
			$result = $db->prepare("SELECT id_emw, ppccz.cipher_specialty,ppccz.name_ppccz,spr_index_disciplines.dindex, disciplines.dname, spr_t_activities.name, spr_t_umd.uname, spr_t_umd2.name_umd2, date_performance, DATE_FORMAT(zdate,'%d.%m.%Y') zdate, report
			          FROM (spr_index_disciplines INNER JOIN
			          (ppccz INNER JOIN 
			          (disciplines INNER JOIN
			          (spr_t_activities INNER JOIN
			          (spr_t_umd INNER JOIN
					  (spr_t_umd2 INNER JOIN educational_methodical_work
					   ON spr_t_umd2.id_umd2 = educational_methodical_work.type_umd2)
					   ON spr_t_umd.id = educational_methodical_work.type_umd)
			           ON spr_t_activities.id = educational_methodical_work.type_activities) 
			           ON disciplines.id = educational_methodical_work.discipline)
			           ON ppccz.id_ppccz = educational_methodical_work.ppccz)
			           ON spr_index_disciplines.id_ind = disciplines.dindex)
			           WHERE id_academic_year = :id_academic_year AND id_employee = :id_employee ORDER BY id_emw");
			$result->bindParam(':id_academic_year', $this->id_academic_year);
			$result->bindParam(':id_employee', $this->id_employee);
			$result->execute();
		}

		$this->getWorkList($result, $pagination, array('cipher_specialty', 'name_ppccz', 'dindex', 'dname', 'name', 'uname', 'name_umd2', 'date_performance', 'report'), 'id_emw', '/emw/works-edit-fill', '/emw/works-edit-exec', $isPrint);
	}

	public function worksExportExcel()
	{
		$db = DataBase::getInstance()->getDb();
		$this->id_academic_year = Auth::getInstance()->getAcademicYear();
		$this->id_employee = Auth::getInstance()->getAuth('id');

		$rows = array(
    		array('№', 'Специальность', 'Наименование специальности', 'Уч. дисциплина (ПМ,МДК)', 'Наименование уч. дисциплины (ПМ,МДК)', 'Вид деятельности', 'Вид УМД', 'Тип УМД', 'Срок исполнения', 'Краткий отчет о выполнении'),
    		array('','','','','','','','','','')
		);

		$result = $db->prepare("SELECT id_emw, ppccz.cipher_specialty,ppccz.name_ppccz,spr_index_disciplines.dindex, disciplines.dname, spr_t_activities.name, spr_t_umd.uname, spr_t_umd2.name_umd2, date_performance, DATE_FORMAT(zdate,'%d.%m.%Y') zdate, report
			          FROM (spr_index_disciplines INNER JOIN
			          (ppccz INNER JOIN 
			          (disciplines INNER JOIN
			          (spr_t_activities INNER JOIN
			          (spr_t_umd INNER JOIN
					  (spr_t_umd2 INNER JOIN educational_methodical_work
					   ON spr_t_umd2.id_umd2 = educational_methodical_work.type_umd2)
					   ON spr_t_umd.id = educational_methodical_work.type_umd)
			           ON spr_t_activities.id = educational_methodical_work.type_activities) 
			           ON disciplines.id = educational_methodical_work.discipline)
			           ON ppccz.id_ppccz = educational_methodical_work.ppccz)
			           ON spr_index_disciplines.id_ind = disciplines.dindex)
			           WHERE id_academic_year = :id_academic_year AND id_employee = :id_employee ORDER BY id_emw");
		$result->bindParam(':id_academic_year', $this->id_academic_year);
		$result->bindParam(':id_employee', $this->id_employee);
		$result->execute();

		$writer = new XLSXWriter();
		$writer->sendHeader('Учебно-методическая_работа('.Auth::getInstance()->getName().'-'.$this->id_academic_year.').xlsx');
		$writer->setAuthor('User');
		$writer->writeSheetHeader('Лист 1', $header_types = array('string','string','string','string','string','string','string','string','string','string'), $col_options = array('widths'=>[4,20,40,20,40,15,25,25,25,35]));

		$writer->writeSheetRow('Лист 1', array('Учебно-методическая работа','','','','','','','','',''), $row_options = array('halign'=>'center','valign'=>'center','font-style'=> 'bold','font'=>'Times New Roman','font-size'=>'14'));

		foreach ($rows as $row)
			$writer->writeSheetRow('Лист 1', $row, $row_options = array('halign'=>'center','valign'=>'center','border'=>'left,right,top,bottom','border-style'=>'thin','wrap_text'=>true,'font'=>'Times New Roman','font-size'=>'12'));

		$this->getWorkExcelList($writer, $result, array('cipher_specialty', 'name_ppccz', 'dindex', 'dname', 'name', 'uname', 'name_umd2', 'date_performance', 'report'));

		$writer->markMergedCell('Лист 1', $start_row=1, $start_col=0, $end_row=1, $end_col=9); //

		$writer->markMergedCell('Лист 1', $start_row=2, $start_col=0, $end_row=3, $end_col=0); // Объединение ячеек №
		$writer->markMergedCell('Лист 1', $start_row=2, $start_col=1, $end_row=3, $end_col=1); // Объединение ячеек Специальность
		$writer->markMergedCell('Лист 1', $start_row=2, $start_col=2, $end_row=3, $end_col=2); // Объединение ячеек Наименование специальности
		$writer->markMergedCell('Лист 1', $start_row=2, $start_col=3, $end_row=3, $end_col=3); // Объединение ячеек Уч. дисциплина (ПМ,МДК)
		$writer->markMergedCell('Лист 1', $start_row=2, $start_col=4, $end_row=3, $end_col=4); // Объединение ячеек Наименование уч. дисциплины (ПМ,МДК)
		$writer->markMergedCell('Лист 1', $start_row=2, $start_col=5, $end_row=3, $end_col=5); // Объединение ячеек Вид деятельности
		$writer->markMergedCell('Лист 1', $start_row=2, $start_col=6, $end_row=3, $end_col=6); // Объединение ячеек Вид УМД
		$writer->markMergedCell('Лист 1', $start_row=2, $start_col=7, $end_row=3, $end_col=7); // Объединение ячеек Тип УМД
		$writer->markMergedCell('Лист 1', $start_row=2, $start_col=8, $end_row=3, $end_col=8); // Объединение ячеек Срок исполнения
		$writer->markMergedCell('Лист 1', $start_row=2, $start_col=9, $end_row=3, $end_col=9); // Объединение ячеек Краткий отчет о выполнении

		$writer->writeToStdOut();
	}

	public function getAnalysisTable($isPrint = false)
	{
		$db = DataBase::getInstance()->getDb();
		$filter = $this->getAnalysisAuthRules(array('employee','ppccz','discipline','type_activities','type_umd','type_umd2'),array('AND educational_methodical_work.id_employee', 'AND educational_methodical_work.ppccz', 'AND educational_methodical_work.discipline','AND educational_methodical_work.type_activities','AND educational_methodical_work.type_umd', 'AND educational_methodical_work.type_umd2')); // Функция получения прав о текущем пользователе для правильного отображения фильтрации

		$this->id_academic_year = Auth::getInstance()->getAcademicYear();
		$pagination = new Pagination;
		$pagination->setParam([':id_academic_year' => $this->id_academic_year]);
		$pagination->init("SELECT COUNT(*) FROM educational_methodical_work INNER JOIN employee ON employee.id = educational_methodical_work.id_employee WHERE id_academic_year = :id_academic_year $filter");
		$limit = "";
		if ($isPrint == false)
			$limit = "LIMIT ".$pagination->getPagePosition().", ".$pagination->getItemPerPage();

		$result = $db->prepare("SELECT id_emw, employee.surname AS employee_surname, employee.name AS employee_name, employee.patronymic AS employee_patronymic, ppccz.cipher_specialty, ppccz.name_ppccz, spr_index_disciplines.dindex, disciplines.dname, spr_t_activities.name, spr_t_umd.uname, spr_t_umd2.name_umd2, date_performance, DATE_FORMAT(zdate,'%d.%m.%Y') zdate, report
			          FROM (spr_index_disciplines INNER JOIN
			          (ppccz INNER JOIN 
			          (disciplines INNER JOIN
			          (spr_t_activities INNER JOIN
			          (spr_t_umd INNER JOIN
					  (spr_t_umd2 INNER JOIN
					  (employee INNER JOIN educational_methodical_work
			           ON employee.id = educational_methodical_work.id_employee)
					   ON spr_t_umd2.id_umd2 = educational_methodical_work.type_umd2)
					   ON spr_t_umd.id = educational_methodical_work.type_umd)
			           ON spr_t_activities.id = educational_methodical_work.type_activities) 
			           ON disciplines.id = educational_methodical_work.discipline)
			           ON ppccz.id_ppccz = educational_methodical_work.ppccz)
			           ON spr_index_disciplines.id_ind = disciplines.dindex)
			           WHERE id_academic_year = :id_academic_year $filter ORDER BY employee.surname, employee.name, employee.patronymic $limit");

		$result->bindParam(':id_academic_year', $this->id_academic_year);
		$result->execute();

		$this->getAnalysisList($result, $pagination, array('cipher_specialty', 'name_ppccz', 'dindex', 'dname', 'name', 'uname', 'name_umd2', 'date_performance', 'report', 'zdate'), 'id_emw', '/emw/analysis-edit', $isPrint);
	}

	public function analysisExportExcel()
	{
		$db = DataBase::getInstance()->getDb();
		$filter = $this->getAnalysisAuthRules(array('employee','ppccz','discipline','type_activities','type_umd','type_umd2'),array('AND educational_methodical_work.id_employee', 'AND educational_methodical_work.ppccz', 'AND educational_methodical_work.discipline','AND educational_methodical_work.type_activities','AND educational_methodical_work.type_umd', 'AND educational_methodical_work.type_umd2')); // Функция получения прав о текущем пользователе для правильного отображения фильтрации

		$this->id_academic_year = Auth::getInstance()->getAcademicYear();

		$rows = array(
    		array('№', 'Специальность', 'Наименование специальности', 'Уч. дисциплина (ПМ,МДК)', 'Наименование уч. дисциплины (ПМ,МДК)', 'Вид деятельности', 'Вид УМД', 'Тип УМД', 'Срок исполнения', 'Краткий отчет о выполнении', 'Дата записи', 'Сотрудник'),
    		array('','','','','','','','','','','','')
		);

		$result = $db->prepare("SELECT CONCAT(employee.surname,' ',Left(employee.name,1),'.',Left(employee.patronymic,1),'.') as FIO, employee.surname AS employee_surname, employee.name AS employee_name, employee.patronymic AS employee_patronymic, ppccz.cipher_specialty, ppccz.name_ppccz, spr_index_disciplines.dindex, disciplines.dname, spr_t_activities.name, spr_t_umd.uname, spr_t_umd2.name_umd2, date_performance, DATE_FORMAT(zdate,'%d.%m.%Y') zdate, report
			          FROM (spr_index_disciplines INNER JOIN
			          (ppccz INNER JOIN 
			          (disciplines INNER JOIN
			          (spr_t_activities INNER JOIN
			          (spr_t_umd INNER JOIN
					  (spr_t_umd2 INNER JOIN
					  (employee INNER JOIN educational_methodical_work
			           ON employee.id = educational_methodical_work.id_employee)
					   ON spr_t_umd2.id_umd2 = educational_methodical_work.type_umd2)
					   ON spr_t_umd.id = educational_methodical_work.type_umd)
			           ON spr_t_activities.id = educational_methodical_work.type_activities) 
			           ON disciplines.id = educational_methodical_work.discipline)
			           ON ppccz.id_ppccz = educational_methodical_work.ppccz)
			           ON spr_index_disciplines.id_ind = disciplines.dindex)
			           WHERE id_academic_year = :id_academic_year $filter ORDER BY employee.surname, employee.name, employee.patronymic");

		$result->bindParam(':id_academic_year', $this->id_academic_year);
		$result->execute();

		$writer = new XLSXWriter();
		$writer->sendHeader('Учебно-методическая_работа('.$this->id_academic_year.').xlsx');
		$writer->setAuthor('User');
		$writer->writeSheetHeader('Лист 1', $header_types = array('string','string','string','string','string','string','string','string','string','string','string','string'), $col_options = array('widths'=>[4,20,40,20,40,15,25,25,25,35,15,15]));

		$writer->writeSheetRow('Лист 1', array('Учебно-методическая работа','','','','','','','','','','',''), $row_options = array('halign'=>'center','valign'=>'center','font-style'=> 'bold','font'=>'Times New Roman','font-size'=>'14'));

		foreach ($rows as $row)
			$writer->writeSheetRow('Лист 1', $row, $row_options = array('halign'=>'center','valign'=>'center','border'=>'left,right,top,bottom','border-style'=>'thin','wrap_text'=>true,'font'=>'Times New Roman','font-size'=>'12'));

		$this->getAnalysisExcelList($writer, $result, array('cipher_specialty', 'name_ppccz', 'dindex', 'dname', 'name', 'uname', 'name_umd2', 'date_performance', 'report', 'zdate', 'FIO'), 'Лист 1');

		$writer->markMergedCell('Лист 1', $start_row=1, $start_col=0, $end_row=1, $end_col=11); //

		$writer->markMergedCell('Лист 1', $start_row=2, $start_col=0, $end_row=3, $end_col=0); // Объединение ячеек №
		$writer->markMergedCell('Лист 1', $start_row=2, $start_col=1, $end_row=3, $end_col=1); // Объединение ячеек Специальность
		$writer->markMergedCell('Лист 1', $start_row=2, $start_col=2, $end_row=3, $end_col=2); // Объединение ячеек Наименование специальности
		$writer->markMergedCell('Лист 1', $start_row=2, $start_col=3, $end_row=3, $end_col=3); // Объединение ячеек Уч. дисциплина (ПМ,МДК)
		$writer->markMergedCell('Лист 1', $start_row=2, $start_col=4, $end_row=3, $end_col=4); // Объединение ячеек Наименование уч. дисциплины (ПМ,МДК)
		$writer->markMergedCell('Лист 1', $start_row=2, $start_col=5, $end_row=3, $end_col=5); // Объединение ячеек Вид деятельности
		$writer->markMergedCell('Лист 1', $start_row=2, $start_col=6, $end_row=3, $end_col=6); // Объединение ячеек Вид УМД
		$writer->markMergedCell('Лист 1', $start_row=2, $start_col=7, $end_row=3, $end_col=7); // Объединение ячеек Тип УМД
		$writer->markMergedCell('Лист 1', $start_row=2, $start_col=8, $end_row=3, $end_col=8); // Объединение ячеек Срок исполнения
		$writer->markMergedCell('Лист 1', $start_row=2, $start_col=9, $end_row=3, $end_col=9); // Объединение ячеек Краткий отчет о выполнении
		$writer->markMergedCell('Лист 1', $start_row=2, $start_col=10, $end_row=3, $end_col=10); // Объединение ячеек Дата записи
		$writer->markMergedCell('Лист 1', $start_row=2, $start_col=11, $end_row=3, $end_col=11); // Объединение ячеек Сотрудник

		$writer->writeToStdOut();
	}

	public function worksEditFill()
	{
		$db = DataBase::getInstance()->getDb();
		$button_fill_edit = Helper::clean($_POST['button_fill_edit']);
		$this->id_employee = Auth::getInstance()->getAuth('id');

		if (is_numeric($button_fill_edit))
		{
			$result = $db->prepare("SELECT ppccz.id_ppccz, disciplines.id AS id_disp, spr_t_activities.id AS id_activ, spr_t_umd.id AS id_umd, spr_t_umd2.id_umd2, date_performance
			          FROM (spr_index_disciplines INNER JOIN
			          (ppccz INNER JOIN 
			          (disciplines INNER JOIN
			          (spr_t_activities INNER JOIN
			          (spr_t_umd INNER JOIN
					  (spr_t_umd2 INNER JOIN educational_methodical_work
					   ON spr_t_umd2.id_umd2 = educational_methodical_work.type_umd2)
					   ON spr_t_umd.id = educational_methodical_work.type_umd)
			           ON spr_t_activities.id = educational_methodical_work.type_activities) 
			           ON disciplines.id = educational_methodical_work.discipline)
			           ON ppccz.id_ppccz = educational_methodical_work.ppccz)
			           ON spr_index_disciplines.id_ind = disciplines.dindex) WHERE id_emw = :button_fill_edit AND id_employee = :id_employee");

			$result->bindParam(':button_fill_edit', $button_fill_edit);
			$result->bindParam(':id_employee', $this->id_employee);
			$result->execute();

			$row = $result->fetch();
			$this->ppccz = $row['id_ppccz'];
			$this->discipline = $row['id_disp'];
			$this->type_activities = $row['id_activ'];
			$this->type_umd = $row['id_umd'];
			$this->type_umd2 = $row['id_umd2'];
			$this->date_performance = $row['date_performance'];
		}
	}

	public function worksEditExec()
	{
		$db = DataBase::getInstance()->getDb();
		$button_exec_edit = Helper::clean($_POST['button_exec_edit']);
		$this->id_employee = Auth::getInstance()->getAuth('id');

		if (is_numeric($button_exec_edit))
		{
			$result = $db->prepare("SELECT ppccz.cipher_specialty, spr_index_disciplines.dindex, disciplines.dname, spr_t_activities.name, spr_t_umd.uname, spr_t_umd2.name_umd2, date_performance
			          FROM (spr_index_disciplines INNER JOIN
			          (ppccz INNER JOIN 
			          (disciplines INNER JOIN
			          (spr_t_activities INNER JOIN
			          (spr_t_umd INNER JOIN
					  (spr_t_umd2 INNER JOIN educational_methodical_work
					   ON spr_t_umd2.id_umd2 = educational_methodical_work.type_umd2)
					   ON spr_t_umd.id = educational_methodical_work.type_umd)
			           ON spr_t_activities.id = educational_methodical_work.type_activities) 
			           ON disciplines.id = educational_methodical_work.discipline)
			           ON ppccz.id_ppccz = educational_methodical_work.ppccz)
			           ON spr_index_disciplines.id_ind = disciplines.dindex) WHERE id_emw = :button_exec_edit AND id_employee = :id_employee");
			$result->bindParam(':button_exec_edit', $button_exec_edit);
			$result->bindParam(':id_employee', $this->id_employee);
			$result->execute();

			$row = $result->fetch();
			$this->ppccz = $row['cipher_specialty'];
			$this->discipline = $row['dindex'].' '.$row['dname'];
			$this->type_activities = $row['name'];
			$this->type_umd = $row['uname'];
			$this->type_umd2 = $row['name_umd2'];
			$this->date_performance = $row['date_performance'];
		}
	}

	public function worksUpdateFill()
	{
		$db = DataBase::getInstance()->getDb();
		$edit_fill_data_table_form_update = Helper::clean($_POST['edit_fill_data_table_form_update']);
		$this->id_employee = Auth::getInstance()->getAuth('id');

		if (is_numeric($edit_fill_data_table_form_update))
		{
			$this->ppccz = Helper::clean($_POST['edit_fill_data_table_form_ppccz']);
			$this->discipline = Helper::clean($_POST['edit_fill_data_table_form_discipline']);
			$this->type_activities =  Helper::clean($_POST['edit_fill_data_table_form_type_activities']);
			$this->type_umd = Helper::clean($_POST['edit_fill_data_table_form_type_umd']);
			$this->type_umd2 = Helper::clean($_POST['edit_fill_data_table_form_type_umd2']);
			$this->date_performance = Helper::clean($_POST['edit_fill_data_table_form_date_performance']);

			$result = $db->prepare("UPDATE educational_methodical_work SET ppccz = :ppccz, discipline = :discipline, type_activities = :type_activities, type_umd = :type_umd, type_umd2 = :type_umd2, date_performance = :date_performance WHERE id_emw = :edit_fill_data_table_form_update AND id_employee = :id_employee");

			$result->bindParam(':ppccz', $this->ppccz);
    		$result->bindParam(':discipline', $this->discipline);
    		$result->bindParam(':type_activities', $this->type_activities);
    		$result->bindParam(':type_umd', $this->type_umd);
    		$result->bindParam(':type_umd2', $this->type_umd2);
    		$result->bindParam(':date_performance', $this->date_performance);
    		$result->bindParam(':edit_fill_data_table_form_update', $edit_fill_data_table_form_update);
    		$result->bindParam(':id_employee', $this->id_employee);
			$result->execute();
		}
	}

	public function worksUpdateExec()
	{
		$db = DataBase::getInstance()->getDb();
		$edit_exec_data_table_form_update = Helper::clean($_POST['edit_exec_data_table_form_update']);
		$edit_exec_data_table_form_check = Helper::clean($_POST['edit_exec_data_table_form_check']);
		$this->id_employee = Auth::getInstance()->getAuth('id');

		if (is_numeric($edit_exec_data_table_form_update))
		{
			if ($edit_exec_data_table_form_check == '1')
				$this->report = 'Выполнено: '.Helper::clean($_POST['edit_exec_data_table_form_report']);
			else if ($edit_exec_data_table_form_check == '0')
				$this->report = 'Не выполнено: '.Helper::clean($_POST['edit_exec_data_table_form_report']);

			$result = $db->prepare("UPDATE educational_methodical_work SET report = :report WHERE id_emw = :edit_exec_data_table_form_update AND id_employee = :id_employee");
			$result->bindParam(':edit_exec_data_table_form_update', $edit_exec_data_table_form_update);
			$result->bindParam(':id_employee', $this->id_employee);
    		$result->bindParam(':report', $this->report);
    		$result->execute();
		}

	}

	public function getAjaxDataTable()
	{
		$db = DataBase::getInstance()->getDb();
		$result = "";

		if (isset($_POST['ppccz']))
		{
			$this->ppccz = Helper::clean($_POST['ppccz']);

			$result = $db->prepare("SELECT id,spr_index_disciplines.dindex,dname FROM disciplines INNER JOIN spr_index_disciplines ON disciplines.dindex = spr_index_disciplines.id_ind WHERE ppccz = :ppccz ORDER BY spr_index_disciplines.dindex");
        	$result->bindParam(':ppccz', $this->ppccz);
        	$result->execute();

        	$i = 0;
			$arr = array();
			while ($row = $result->fetch())
			{
				$arr[$i] = $row;
    			$i++;
			}

			echo json_encode($arr);
		}
	}

	public function worksAjaxInsert()
	{
		$db = DataBase::getInstance()->getDb();
		$this->ppccz = Helper::clean($_POST['ppccz']);
		$this->discipline = Helper::clean($_POST['discipline']);
		$this->type_activities = Helper::clean($_POST['type_activities']);
		$this->type_umd = Helper::clean($_POST['type_umd']);
		$this->type_umd2 = Helper::clean($_POST['type_umd2']);
		$this->date_performance = Helper::clean($_POST['date_performance']);
		$this->id_academic_year = Auth::getInstance()->getAcademicYear();
		$this->id_employee = Auth::getInstance()->getAuth('id');

		$result = $db->prepare("INSERT INTO educational_methodical_work (ppccz, discipline, type_activities, type_umd, type_umd2, date_performance, zdate, id_employee, id_academic_year) 
		VALUES(:ppccz, :discipline, :type_activities, :type_umd, :type_umd2, :date_performance, CURDATE(), :id_employee, :id_academic_year)");

		$result->bindParam(':ppccz', $this->ppccz);
		$result->bindParam(':discipline', $this->discipline);
		$result->bindParam(':type_activities', $this->type_activities);
		$result->bindParam(':type_umd', $this->type_umd);
		$result->bindParam(':type_umd2', $this->type_umd2);
    	$result->bindParam(':date_performance', $this->date_performance);
		$result->bindParam(':id_academic_year', $this->id_academic_year);
    	$result->bindParam(':id_employee', $this->id_employee);
    	$result->execute();

    	echo true;
	}

	public function worksAjaxDelete()
	{
		$db = DataBase::getInstance()->getDb();
		$this->id_employee = Auth::getInstance()->getAuth('id');
		$value = Helper::clean($_POST['value']);

		if (is_numeric($value))
		{
			$result = $db->prepare("DELETE FROM educational_methodical_work WHERE id_emw = :value AND id_employee = :id_employee");
			$result->bindParam(':value', $value);
			$result->bindParam(':id_employee', $this->id_employee);
			$result->execute();

			echo true;
		}
	}

	public function analysisEdit()
	{
		$db = DataBase::getInstance()->getDb();
		$button_edit = Helper::clean($_POST['button_edit']);

		if (is_numeric($button_edit))
		{
			$result = $db->prepare("SELECT ppccz.id_ppccz, disciplines.id AS id_disp, spr_t_activities.id AS id_activ, spr_t_umd.id AS id_umd, spr_t_umd2.id_umd2, date_performance, report
			          FROM (spr_index_disciplines INNER JOIN
			          (ppccz INNER JOIN 
			          (disciplines INNER JOIN
			          (spr_t_activities INNER JOIN
			          (spr_t_umd INNER JOIN
					  (spr_t_umd2 INNER JOIN educational_methodical_work
					   ON spr_t_umd2.id_umd2 = educational_methodical_work.type_umd2)
					   ON spr_t_umd.id = educational_methodical_work.type_umd)
			           ON spr_t_activities.id = educational_methodical_work.type_activities) 
			           ON disciplines.id = educational_methodical_work.discipline)
			           ON ppccz.id_ppccz = educational_methodical_work.ppccz)
			           ON spr_index_disciplines.id_ind = disciplines.dindex) WHERE id_emw = :button_edit");

			$result->bindParam(':button_edit', $button_edit);
			$result->execute();

			$row = $result->fetch();
			$this->ppccz = $row['id_ppccz'];
			$this->discipline = $row['id_disp'];
			$this->type_activities = $row['id_activ'];
			$this->type_umd = $row['id_umd'];
			$this->type_umd2 = $row['id_umd2'];
			$this->date_performance = $row['date_performance'];
			$this->report = $row['report'];
		}
	}

	public function analysisUpdate()
	{
		$db = DataBase::getInstance()->getDb();
		$edit_data_table_form_update = Helper::clean($_POST['edit_data_table_form_update']);

		if (is_numeric($edit_data_table_form_update))
		{
			$this->ppccz = Helper::clean($_POST['edit_data_table_form_ppccz']);
			$this->discipline = Helper::clean($_POST['edit_data_table_form_discipline']);
			$this->type_activities =  Helper::clean($_POST['edit_data_table_form_type_activities']);
			$this->type_umd = Helper::clean($_POST['edit_data_table_form_type_umd']);
			$this->type_umd2 = Helper::clean($_POST['edit_data_table_form_type_umd2']);
			$this->date_performance = Helper::clean($_POST['edit_data_table_form_date_performance']);
			$this->report = Helper::clean($_POST['edit_data_table_form_report']);

			$result = $db->prepare("UPDATE educational_methodical_work SET ppccz = :ppccz, discipline = :discipline, type_activities = :type_activities, type_umd = :type_umd, type_umd2 = :type_umd2, date_performance = :date_performance, report = :report WHERE id_emw = :edit_data_table_form_update");

			$result->bindParam(':ppccz', $this->ppccz);
    		$result->bindParam(':discipline', $this->discipline);
    		$result->bindParam(':type_activities', $this->type_activities);
    		$result->bindParam(':type_umd', $this->type_umd);
    		$result->bindParam(':type_umd2', $this->type_umd2);
    		$result->bindParam(':date_performance', $this->date_performance);
    		$result->bindParam(':report', $this->report);
    		$result->bindParam(':edit_data_table_form_update', $edit_data_table_form_update);
			$result->execute();
		}		
	}

	public function analysisAjaxDelete()
	{
		$db = DataBase::getInstance()->getDb();
		$value = Helper::clean($_POST['value']);

		if (is_numeric($value))
		{
			$result = $db->prepare("DELETE FROM educational_methodical_work WHERE id_emw = :value");
			$result->bindParam(':value', $value);
			$result->execute();

			echo true;
	}	}
}

?>