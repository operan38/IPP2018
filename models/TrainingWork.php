<?php

class TrainingWork extends WorkAnalysTable
{
	public $id_tw;
	public $ppccz;
	public $cipher_group;
	public $discipline;
	public $plan_1;
	public $fact_1;
	public $plan_2;
	public $fact_2;
	public $plan_3;
	public $fact_3;
	public $reason_failure;
	public $quality_progress;
	public $absolute_progress;
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
			$pagination->init("SELECT COUNT(*) FROM training_work WHERE id_academic_year = :id_academic_year AND id_employee = :id_employee");
			$result = $db->prepare("SELECT id_tw, ppccz.cipher_specialty, spr_index_disciplines.dindex, disciplines.dname, spr_cipher_group.gname, plan_1, fact_1, plan_2, fact_2, plan_3, fact_3, reason_failure, DATE_FORMAT(zdate,'%d.%m.%Y') zdate, absolute_progress, quality_progress
                      FROM (spr_index_disciplines INNER JOIN
			          (ppccz INNER JOIN 
			          (disciplines INNER JOIN
			          (spr_cipher_group INNER JOIN training_work
			           ON spr_cipher_group.id = training_work.cipher_group)
			           ON disciplines.id = training_work.discipline)
			           ON ppccz.id_ppccz = training_work.ppccz)
			           ON spr_index_disciplines.id_ind = disciplines.dindex) WHERE id_academic_year = :id_academic_year AND id_employee = :id_employee ORDER BY id_tw LIMIT :page_position,:item_per_page");
			$result->bindParam(':id_academic_year', $this->id_academic_year);
			$result->bindParam(':id_employee', $this->id_employee);
			$result->bindValue(':page_position', $pagination->getPagePosition(), PDO::PARAM_INT);
			$result->bindValue(':item_per_page', $pagination->getItemPerPage(), PDO::PARAM_INT);
			$result->execute();
		}
		else
		{
			$result = $db->prepare("SELECT id_tw, ppccz.cipher_specialty, spr_index_disciplines.dindex, disciplines.dname, spr_cipher_group.gname, plan_1, fact_1, plan_2, fact_2, plan_3, fact_3, reason_failure, DATE_FORMAT(zdate,'%d.%m.%Y') zdate, absolute_progress, quality_progress
                      FROM (spr_index_disciplines INNER JOIN
			          (ppccz INNER JOIN 
			          (disciplines INNER JOIN
			          (spr_cipher_group INNER JOIN training_work
			           ON spr_cipher_group.id = training_work.cipher_group)
			           ON disciplines.id = training_work.discipline)
			           ON ppccz.id_ppccz = training_work.ppccz)
			           ON spr_index_disciplines.id_ind = disciplines.dindex) WHERE id_academic_year = :id_academic_year AND id_employee = :id_employee ORDER BY id_tw");
			$result->bindParam(':id_academic_year', $this->id_academic_year);
			$result->bindParam(':id_employee', $this->id_employee);
			$result->execute();			
		}

		$this->getWorkList($result, $pagination, array('cipher_specialty', 'dindex', 'dname', 'gname', 'plan_1', 'fact_1', 'plan_2', 'fact_2', 'plan_3', 'fact_3', 'reason_failure', 'absolute_progress', 'quality_progress'), 'id_tw', '/tw/works-edit-fill', '/tw/works-edit-exec', $isPrint);
	}

	public function worksExportExcel()
	{
		$db = DataBase::getInstance()->getDb();
		$this->id_academic_year = Auth::getInstance()->getAcademicYear();
		$this->id_employee = Auth::getInstance()->getAuth('id');

		$rows = array(
    		array('№', 'Специальность', 'Уч. дисциплина (ПМ,МДК)', 'Наименование уч. дисциплины (ПМ,МДК)', 'Группа', 'Первый семестр', '', 'Второй семестр', '', 'Учебный год', '', 'Причина невыполнения', 'Абсолютная успеваемость','Качественная успеваемость'),
    		array('','','','','','План','Факт','План','Факт','План','Факт','','',''),
		);

		$result = $db->prepare("SELECT ppccz.cipher_specialty, spr_index_disciplines.dindex, disciplines.dname, spr_cipher_group.gname, plan_1, fact_1, plan_2, fact_2, plan_3, fact_3, reason_failure, DATE_FORMAT(zdate,'%d.%m.%Y') zdate, absolute_progress, quality_progress
                      FROM (spr_index_disciplines INNER JOIN
			          (ppccz INNER JOIN 
			          (disciplines INNER JOIN
			          (spr_cipher_group INNER JOIN training_work
			           ON spr_cipher_group.id = training_work.cipher_group)
			           ON disciplines.id = training_work.discipline)
			           ON ppccz.id_ppccz = training_work.ppccz)
			           ON spr_index_disciplines.id_ind = disciplines.dindex) WHERE id_academic_year = :id_academic_year AND id_employee = :id_employee ORDER BY id_tw");
		$result->bindParam(':id_academic_year', $this->id_academic_year);
		$result->bindParam(':id_employee', $this->id_employee);
		$result->execute();	

		$writer = new XLSXWriter();
		$writer->sendHeader('Учебная работа('.Auth::getInstance()->getName().'-'.$this->id_academic_year.').xlsx');
		$writer->setAuthor('User');
		$writer->writeSheetHeader('Лист 1', $header_types = array('string','string','string','string','string','string','string','string','string','string','string','string','string','string'), $col_options = array('widths'=>[4,20,20,40,15,9,9,9,9,9,9,25,25,25]));

		$writer->writeSheetRow('Лист 1', array('Учебная работа','','','','','','','','','','','','',''), $row_options = array('halign'=>'center','valign'=>'center','font-style'=> 'bold','font'=>'Times New Roman','font-size'=>'14'));

		foreach ($rows as $row)
			$writer->writeSheetRow('Лист 1', $row, $row_options = array('halign'=>'center','valign'=>'center','border'=>'left,right,top,bottom','border-style'=>'thin','wrap_text'=>true,'font'=>'Times New Roman','font-size'=>'12'));

		$this->getWorkExcelList($writer, $result, array('cipher_specialty', 'dindex', 'dname', 'gname', 'plan_1', 'fact_1', 'plan_2', 'fact_2', 'plan_3', 'fact_3', 'reason_failure', 'absolute_progress', 'quality_progress'));

		$writer->markMergedCell('Лист 1', $start_row=1, $start_col=0, $end_row=1, $end_col=13); //

		$writer->markMergedCell('Лист 1', $start_row=2, $start_col=5, $end_row=2, $end_col=6); // Объединение ячеек первый семсетр
		$writer->markMergedCell('Лист 1', $start_row=2, $start_col=7, $end_row=2, $end_col=8); // Объединение ячеек второй семсетр
		$writer->markMergedCell('Лист 1', $start_row=2, $start_col=9, $end_row=2, $end_col=10); // Объединение ячеек учебный год

		$writer->markMergedCell('Лист 1', $start_row=2, $start_col=0, $end_row=3, $end_col=0); // Объединение ячеек №
		$writer->markMergedCell('Лист 1', $start_row=2, $start_col=1, $end_row=3, $end_col=1); // Объединение ячеек Специальность
		$writer->markMergedCell('Лист 1', $start_row=2, $start_col=2, $end_row=3, $end_col=2); // Объединение ячеек Уч. дисциплина (ПМ,МДК)
		$writer->markMergedCell('Лист 1', $start_row=2, $start_col=3, $end_row=3, $end_col=3); // Объединение ячеек Наименование уч. дисциплины (ПМ,МДК)
		$writer->markMergedCell('Лист 1', $start_row=2, $start_col=4, $end_row=3, $end_col=4); // Объединение ячеек Группа

		$writer->markMergedCell('Лист 1', $start_row=2, $start_col=11, $end_row=3, $end_col=11); // Объединение ячеек Причина невыполнения
		$writer->markMergedCell('Лист 1', $start_row=2, $start_col=12, $end_row=3, $end_col=12); // Объединение ячеек Абсолютная успеваемость
		$writer->markMergedCell('Лист 1', $start_row=2, $start_col=13, $end_row=3, $end_col=13); // Объединение ячеек Качественная успеваемость

		$writer->writeToStdOut();
	}

	public function getAnalysisTable($isPrint = false)
	{
		$db = DataBase::getInstance()->getDb();
		$filter = $this->getAnalysisAuthRules(array('employee','ppccz','discipline','cipher_group'),array('AND training_work.id_employee','AND training_work.ppccz','AND training_work.discipline','AND training_work.cipher_group')); // Получить выбранные фильтры с учетом прав

		$this->id_academic_year = Auth::getInstance()->getAcademicYear();

		$pagination = new Pagination;
		$pagination->setParam([':id_academic_year' => $this->id_academic_year]);
		$pagination->init("SELECT COUNT(*) FROM training_work INNER JOIN employee ON employee.id = training_work.id_employee WHERE id_academic_year = :id_academic_year $filter");

		$limit = "";
		if ($isPrint == false)
			$limit = "LIMIT ".$pagination->getPagePosition().", ".$pagination->getItemPerPage();

		/*$pagePosition = $pagination->getPagePosition();
		$itemPerPage = $pagination->getItemPerPage();*/

		$result = $db->prepare("SELECT id_tw, employee.surname AS employee_surname, employee.name AS employee_name, employee.patronymic AS employee_patronymic, ppccz.cipher_specialty, spr_index_disciplines.dindex, disciplines.dname, spr_cipher_group.gname, plan_1, fact_1, plan_2, fact_2, plan_3, fact_3, reason_failure, DATE_FORMAT(zdate,'%d.%m.%Y') zdate ,absolute_progress, quality_progress
                      FROM (spr_index_disciplines INNER JOIN
			          (ppccz INNER JOIN 
			          (disciplines INNER JOIN
			          (spr_cipher_group INNER JOIN
			          (employee INNER JOIN training_work
			           ON employee.id = training_work.id_employee)
			           ON spr_cipher_group.id = training_work.cipher_group)
			           ON disciplines.id = training_work.discipline)
			           ON ppccz.id_ppccz = training_work.ppccz)
			           ON spr_index_disciplines.id_ind = disciplines.dindex) WHERE id_academic_year = :id_academic_year $filter ORDER BY employee.surname, employee.name, employee.patronymic $limit");

		$result->bindParam(':id_academic_year', $this->id_academic_year);
		$result->execute();

		//isset($_SESSION['table_analysis_filter']) ? var_dump($_SESSION['table_analysis_filter']) : 0;

		$this->getAnalysisList($result, $pagination, array('cipher_specialty', 'dindex', 'dname', 'gname', 'plan_1', 'fact_1', 'plan_2', 'fact_2', 'plan_3', 'fact_3', 'reason_failure', 'absolute_progress', 'quality_progress', 'zdate'), 'id_tw', '/tw/analysis-edit', $isPrint);
	}

	public function analysisExportExcel()
	{
		$db = DataBase::getInstance()->getDb();
		$filter = $this->getAnalysisAuthRules(array('employee','ppccz','discipline','cipher_group'),array('AND training_work.id_employee','AND training_work.ppccz','AND training_work.discipline','AND training_work.cipher_group')); // Получить выбранные фильтры с учетом прав

		$this->id_academic_year = Auth::getInstance()->getAcademicYear();
		
		$rows = array(
    		array('№', 'Специальность', 'Уч. дисциплина (ПМ,МДК)', 'Наименование уч. дисциплины (ПМ,МДК)', 'Группа', 'Первый семестр', '', 'Второй семестр', '', 'Учебный год', '', 'Причина невыполнения', 'Абсолютная успеваемость','Качественная успеваемость', 'Дата записи','Сотрудник'),
    		array('','','','','','План','Факт','План','Факт','План','Факт','','','','',''),
		);

		$result = $db->prepare("SELECT CONCAT(employee.surname,' ',Left(employee.name,1),'.',Left(employee.patronymic,1),'.') as FIO, employee.surname AS employee_surname, employee.name AS employee_name, employee.patronymic AS employee_patronymic, ppccz.cipher_specialty, spr_index_disciplines.dindex, disciplines.dname, spr_cipher_group.gname, plan_1, fact_1, plan_2, fact_2, plan_3, fact_3, reason_failure, DATE_FORMAT(zdate,'%d.%m.%Y') zdate ,absolute_progress, quality_progress
                      FROM (spr_index_disciplines INNER JOIN
			          (ppccz INNER JOIN 
			          (disciplines INNER JOIN
			          (spr_cipher_group INNER JOIN
			          (employee INNER JOIN training_work
			           ON employee.id = training_work.id_employee)
			           ON spr_cipher_group.id = training_work.cipher_group)
			           ON disciplines.id = training_work.discipline)
			           ON ppccz.id_ppccz = training_work.ppccz)
			           ON spr_index_disciplines.id_ind = disciplines.dindex) WHERE id_academic_year = :id_academic_year $filter ORDER BY employee.surname, employee.name, employee.patronymic");

		$result->bindParam(':id_academic_year', $this->id_academic_year);
		$result->execute();

		$writer = new XLSXWriter();
		$writer->sendHeader('Учебная работа('.$this->id_academic_year.').xlsx');
		$writer->setAuthor('User');
		$writer->writeSheetHeader('Лист 1', $header_types = array('string','string','string','string','string','string','string','string','string','string','string','string','string','string','string'), $col_options = array('widths'=>[4,20,20,40,15,9,9,9,9,9,9,25,25,25,15,15]));

		$writer->writeSheetRow('Лист 1', array('Учебная работа','','','','','','','','','','','','','','',''), $row_options = array('halign'=>'center','valign'=>'center','font-style'=> 'bold','font'=>'Times New Roman','font-size'=>'14'));

		foreach ($rows as $row)
			$writer->writeSheetRow('Лист 1', $row, $row_options = array('halign'=>'center','valign'=>'center','border'=>'left,right,top,bottom','border-style'=>'thin','wrap_text'=>true,'font'=>'Times New Roman','font-size'=>'12'));

		$this->getAnalysisExcelList($writer, $result, array('cipher_specialty', 'dindex', 'dname', 'gname', 'plan_1', 'fact_1', 'plan_2', 'fact_2', 'plan_3', 'fact_3', 'reason_failure', 'absolute_progress', 'quality_progress', 'zdate', 'FIO'), 'Лист 1');

		$writer->markMergedCell('Лист 1', $start_row=1, $start_col=0, $end_row=1, $end_col=15); //

		$writer->markMergedCell('Лист 1', $start_row=2, $start_col=5, $end_row=2, $end_col=6); // Объединение ячеек первый семсетр
		$writer->markMergedCell('Лист 1', $start_row=2, $start_col=7, $end_row=2, $end_col=8); // Объединение ячеек второй семсетр
		$writer->markMergedCell('Лист 1', $start_row=2, $start_col=9, $end_row=2, $end_col=10); // Объединение ячеек учебный год

		$writer->markMergedCell('Лист 1', $start_row=2, $start_col=0, $end_row=3, $end_col=0); // Объединение ячеек №
		$writer->markMergedCell('Лист 1', $start_row=2, $start_col=1, $end_row=3, $end_col=1); // Объединение ячеек Специальность
		$writer->markMergedCell('Лист 1', $start_row=2, $start_col=2, $end_row=3, $end_col=2); // Объединение ячеек Уч. дисциплина (ПМ,МДК)
		$writer->markMergedCell('Лист 1', $start_row=2, $start_col=3, $end_row=3, $end_col=3); // Объединение ячеек Наименование уч. дисциплины (ПМ,МДК)
		$writer->markMergedCell('Лист 1', $start_row=2, $start_col=4, $end_row=3, $end_col=4); // Объединение ячеек Группа

		$writer->markMergedCell('Лист 1', $start_row=2, $start_col=11, $end_row=3, $end_col=11); // Объединение ячеек Причина невыполнения
		$writer->markMergedCell('Лист 1', $start_row=2, $start_col=12, $end_row=3, $end_col=12); // Объединение ячеек Абсолютная успеваемость
		$writer->markMergedCell('Лист 1', $start_row=2, $start_col=13, $end_row=3, $end_col=13); // Объединение ячеек Качественная успеваемость
		$writer->markMergedCell('Лист 1', $start_row=2, $start_col=14, $end_row=3, $end_col=14); // Объединение ячеек Дата записи
		$writer->markMergedCell('Лист 1', $start_row=2, $start_col=15, $end_row=3, $end_col=15); // Объединение ячеек Сотрудник

		$writer->writeToStdOut();
	}

	public function worksEditFill()
	{
		$db = DataBase::getInstance()->getDb();
		$button_fill_edit = Helper::clean($_POST['button_fill_edit']);
		$this->id_employee = Auth::getInstance()->getAuth('id');

		if (is_numeric($button_fill_edit))
		{
			$result = $db->prepare("SELECT ppccz.id_ppccz, disciplines.id AS id_disp, spr_cipher_group.id AS id_cipher, plan_1, plan_2
                    FROM (spr_index_disciplines INNER JOIN
			        (ppccz INNER JOIN 
			        (disciplines INNER JOIN
			        (spr_cipher_group INNER JOIN training_work
			        ON spr_cipher_group.id = training_work.cipher_group)
			        ON disciplines.id = training_work.discipline)
			        ON ppccz.id_ppccz = training_work.ppccz)
			        ON spr_index_disciplines.id_ind = disciplines.dindex) WHERE id_tw = :button_fill_edit AND id_employee = :id_employee");
			$result->bindParam(':button_fill_edit', $button_fill_edit);
			$result->bindParam(':id_employee', $this->id_employee);
			$result->execute();

			$row = $result->fetch();
			$this->ppccz = $row['id_ppccz'];
			$this->discipline = $row['id_disp'];
			$this->cipher_group = $row['id_cipher'];
			$this->plan_1 = $row['plan_1'];
			$this->plan_2 = $row['plan_2'];
		}
	}

	public function worksEditExec()
	{
		$db = DataBase::getInstance()->getDb();
		$button_exec_edit = Helper::clean($_POST['button_exec_edit']);
		$this->id_employee = Auth::getInstance()->getAuth('id');

		if (is_numeric($button_exec_edit))
		{
			$result = $db->prepare("SELECT ppccz.cipher_specialty, spr_index_disciplines.dindex, disciplines.dname, spr_cipher_group.gname, plan_1, fact_1, plan_2, fact_2, plan_3, fact_3, reason_failure, absolute_progress, quality_progress
                    FROM (spr_index_disciplines INNER JOIN
			        (ppccz INNER JOIN 
			        (disciplines INNER JOIN
			        (spr_cipher_group INNER JOIN training_work
			        ON spr_cipher_group.id = training_work.cipher_group)
			        ON disciplines.id = training_work.discipline)
			        ON ppccz.id_ppccz = training_work.ppccz)
			        ON spr_index_disciplines.id_ind = disciplines.dindex) WHERE id_tw = :button_exec_edit AND id_employee = :id_employee");
			$result->bindParam(':button_exec_edit', $button_exec_edit);
			$result->bindParam(':id_employee', $this->id_employee);
			$result->execute();

			$row = $result->fetch();
			$this->ppccz = $row['cipher_specialty'];
			$this->discipline = $row['dindex'].' '.$row['dname'];
			$this->cipher_group = $row['gname'];
			$this->plan_1 = $row['plan_1'];
			$this->fact_1 = $row['fact_1'];
			$this->plan_2 = $row['plan_2'];
			$this->fact_2 = $row['fact_2'];
			$this->plan_3 = $row['plan_3'];
			$this->fact_3 = $row['fact_3'];
			$this->reason_failure = $row['reason_failure'];
			$this->absolute_progress = $row['absolute_progress'];
			$this->quality_progress = $row['quality_progress'];
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
			$this->cipher_group = Helper::clean($_POST['edit_fill_data_table_form_cipher_group']);
			$this->plan_1 = Helper::clean($_POST['edit_fill_data_table_form_plan_1']);
			$this->plan_2 = Helper::clean($_POST['edit_fill_data_table_form_plan_2']);
			$this->plan_3 = $this->plan_1 + $this->plan_2;

			if (is_numeric($this->plan_3))
			{
    			$result = $db->prepare("UPDATE training_work SET ppccz = :ppccz, discipline = :discipline, cipher_group = :cipher_group, plan_1 = :plan_1, plan_2 = :plan_2, plan_3 = :plan_3 WHERE id_tw = :edit_fill_data_table_form_update AND id_employee = :id_employee");
    			$result->bindParam(':ppccz', $this->ppccz);
    			$result->bindParam(':discipline', $this->discipline);
    			$result->bindParam(':cipher_group', $this->cipher_group);
    			$result->bindParam(':plan_1', $this->plan_1);
    			$result->bindParam(':plan_2', $this->plan_2);
    			$result->bindParam(':plan_3', $this->plan_3);
    			$result->bindParam(':edit_fill_data_table_form_update', $edit_fill_data_table_form_update);
    			$result->bindParam(':id_employee', $this->id_employee);
				$result->execute();
			}
		}
	}

	public function worksUpdateExec()
	{
		$db = DataBase::getInstance()->getDb();
		$edit_exec_data_table_form_update = Helper::clean($_POST['edit_exec_data_table_form_update']);
		$this->id_employee = Auth::getInstance()->getAuth('id');

		if (is_numeric($edit_exec_data_table_form_update))
		{
			$this->fact_1 = Helper::clean($_POST['edit_exec_data_table_form_fact_1']);
			$this->fact_2 = Helper::clean($_POST['edit_exec_data_table_form_fact_2']);
			$this->reason_failure = Helper::clean($_POST['edit_exec_data_table_form_reason_failure']);
			$this->absolute_progress = Helper::clean($_POST['edit_exec_data_table_form_absolute_progress']);
			$this->quality_progress = Helper::clean($_POST['edit_exec_data_table_form_quality_progress']);

			if (empty($this->fact_1))
				$this->fact_1 = 0;
			if (empty($this->fact_2))
				$this->fact_2 = 0;

			$this->fact_3 = $this->fact_1 + $this->fact_2;

			if (is_numeric($this->fact_3))
			{
				$result = $db->prepare("UPDATE training_work SET fact_1 = :fact_1, fact_2 = :fact_2, fact_3 = :fact_3, reason_failure = :reason_failure, absolute_progress = :absolute_progress, quality_progress = :quality_progress WHERE id_tw = :edit_exec_data_table_form_update AND id_employee = :id_employee");
				$result->bindParam(':fact_1', $this->fact_1);
				$result->bindParam(':fact_2', $this->fact_2);
				$result->bindParam(':fact_3', $this->fact_3);
				$result->bindParam(':reason_failure', $this->reason_failure);
				$result->bindParam(':absolute_progress', $this->absolute_progress);
				$result->bindParam(':quality_progress', $this->quality_progress);
				$result->bindParam(':edit_exec_data_table_form_update', $edit_exec_data_table_form_update);
				$result->bindParam(':id_employee', $this->id_employee);
				$result->execute();
			}
		}
	}

	public function getAjaxDataTable()
	{
		$db = DataBase::getInstance()->getDb();
		$result = "";

		if (isset($_POST['ppccz']))
		{
    		$this->ppccz = Helper::clean($_POST['ppccz']);

    		if (isset($_POST['discipline']))
    		{
        		$result = $db->prepare("SELECT id,spr_index_disciplines.dindex,dname FROM disciplines INNER JOIN spr_index_disciplines ON disciplines.dindex = spr_index_disciplines.id_ind WHERE ppccz = :ppccz ORDER BY spr_index_disciplines.dindex");
        		$result->bindParam(':ppccz', $this->ppccz);
        		$result->execute();
    		}
    		else if(isset($_POST['cipher_group']))
    		{
        		$result = $db->prepare("SELECT id,gname FROM spr_cipher_group WHERE ppccz = :ppccz");
        		$result->bindParam(':ppccz', $this->ppccz);
        		$result->execute();
    		}

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
		$this->cipher_group = Helper::clean($_POST['cipher_group']);
		$this->discipline = Helper::clean($_POST['discipline']);
		$this->plan_1 = Helper::clean($_POST['plan_1']);
		$this->plan_2 = Helper::clean($_POST['plan_2']);
		$this->plan_3 = Helper::clean($_POST['plan_1'] + $_POST['plan_2']);
		$this->id_academic_year = Auth::getInstance()->getAcademicYear();
		$this->id_employee = Auth::getInstance()->getAuth('id');

		if (is_numeric($this->plan_3))
		{
    		$result = $db->prepare("INSERT INTO training_work (ppccz, cipher_group, discipline, plan_1, plan_2, plan_3, zdate, id_employee, id_academic_year)
    		VALUES (:ppccz, :cipher_group, :discipline, :plan_1, :plan_2, :plan_3, CURDATE(), :id_employee, :id_academic_year)");
    		$result->bindParam(':ppccz', $this->ppccz);
    		$result->bindParam(':cipher_group', $this->cipher_group);
    		$result->bindParam(':discipline', $this->discipline);
    		$result->bindParam(':plan_1', $this->plan_1);
    		$result->bindParam(':plan_2', $this->plan_2);
    		$result->bindParam(':plan_3', $this->plan_3);
    		$result->bindParam(':id_academic_year', $this->id_academic_year);
    		$result->bindParam(':id_employee', $this->id_employee);
    		$result->execute();

			echo true;
		}
	}

	public function worksAjaxDelete()
	{
		$db = DataBase::getInstance()->getDb();
		$this->id_employee = Auth::getInstance()->getAuth('id');
		$value = Helper::clean($_POST['value']);

		if (is_numeric($value))
		{
			$result = $db->prepare("DELETE FROM training_work WHERE id_tw = :value AND id_employee = :id_employee");
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
			$result = $db->prepare("SELECT ppccz.id_ppccz, disciplines.id AS id_disp, spr_cipher_group.id AS id_cipher, plan_1, fact_1, plan_2, fact_2, plan_3, fact_3, reason_failure, absolute_progress, quality_progress
                    FROM (spr_index_disciplines INNER JOIN
			        (ppccz INNER JOIN 
			        (disciplines INNER JOIN
			        (spr_cipher_group INNER JOIN training_work
			        ON spr_cipher_group.id = training_work.cipher_group)
			        ON disciplines.id = training_work.discipline)
			        ON ppccz.id_ppccz = training_work.ppccz)
			        ON spr_index_disciplines.id_ind = disciplines.dindex) WHERE id_tw = :button_edit");
			$result->bindParam(':button_edit', $button_edit);
			$result->execute();

			$row = $result->fetch();
			$this->ppccz = $row['id_ppccz'];
			$this->discipline = $row['id_disp'];
			$this->cipher_group = $row['id_cipher'];
			$this->plan_1 = $row['plan_1'];
			$this->fact_1 = $row['fact_1'];
			$this->plan_2 = $row['plan_2'];
			$this->fact_2 = $row['fact_2'];
			$this->plan_3 = $row['plan_3'];
			$this->fact_3 = $row['fact_3'];
			$this->reason_failure = $row['reason_failure'];
			$this->absolute_progress = $row['absolute_progress'];
			$this->quality_progress = $row['quality_progress'];
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
			$this->cipher_group = Helper::clean($_POST['edit_data_table_form_cipher_group']);
			$this->plan_1 = Helper::clean($_POST['edit_data_table_form_plan_1']);
			$this->fact_1 = Helper::clean($_POST['edit_data_table_form_fact_1']);
			$this->plan_2 = Helper::clean($_POST['edit_data_table_form_plan_2']);
			$this->fact_2 = Helper::clean($_POST['edit_data_table_form_fact_2']);
			$this->plan_3 = $this->plan_1 + $this->plan_2;
			$this->fact_3 = $this->fact_1 + $this->fact_2;
			$this->reason_failure = Helper::clean($_POST['edit_data_table_form_reason_failure']);
			$this->absolute_progress = Helper::clean($_POST['edit_data_table_form_absolute_progress']);
			$this->quality_progress = Helper::clean($_POST['edit_data_table_form_quality_progress']);

			if (empty($this->plan_3))
    		{
    			$this->plan_1 = 0;
    			$this->plan_2 = 0;
    			$this->plan_3 = 0;
    		}

			if (empty($this->fact_3))
    		{
    			$this->fact_1 = 0;
    			$this->fact_2 = 0;
    			$this->fact_3 = 0;
    		}

			if (is_numeric($this->plan_3) && is_numeric($this->fact_3))
			{
    			$result = $db->prepare("UPDATE training_work SET ppccz = :ppccz, discipline = :discipline, cipher_group = :cipher_group, plan_1 = :plan_1, fact_1 = :fact_1, plan_2 = :plan_2, fact_2 = :fact_2, plan_3 = :plan_3, fact_3 = :fact_3, reason_failure = :reason_failure, absolute_progress = :absolute_progress, quality_progress = :quality_progress WHERE id_tw = :edit_data_table_form_update");
    			$result->bindParam(':ppccz', $this->ppccz);
    			$result->bindParam(':discipline', $this->discipline);
    			$result->bindParam(':cipher_group', $this->cipher_group);
    			$result->bindParam(':plan_1', $this->plan_1);
    			$result->bindParam(':fact_1', $this->fact_1);
    			$result->bindParam(':plan_2', $this->plan_2);
    			$result->bindParam(':fact_2', $this->fact_2);
    			$result->bindParam(':plan_3', $this->plan_3);
    			$result->bindParam(':fact_3', $this->fact_3);
    			$result->bindParam(':reason_failure', $this->reason_failure);
    			$result->bindParam(':absolute_progress', $this->absolute_progress);
    			$result->bindParam(':quality_progress', $this->quality_progress);
    			$result->bindParam(':edit_data_table_form_update', $edit_data_table_form_update);
				$result->execute();
			}
		}
	}

	public function analysisAjaxDelete()
	{
		$db = DataBase::getInstance()->getDb();
		$value = Helper::clean($_POST['value']);

		if (is_numeric($value))
		{
			$result = $db->prepare("DELETE FROM training_work WHERE id_tw = :value");
			$result->bindParam(':value', $value);
			$result->execute();

			echo true;
		}
	}
}

?>