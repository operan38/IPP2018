<?php

class OrganizationalMethodicalWork extends WorkAnalysTable
{
	public $id_omw;
	public $type_work;
	public $type_event;
	public $name_event;
	public $lev_event;
	public $sdate;
	public $information_students;
	public $result_executing;
	public $id_employee;
	public $id_academic_year;

	function __construct(){}

	public function getTable($isPrint = false)
	{
		$db = DataBase::getInstance()->getDb();
		$this->id_academic_year = Auth::getInstance()->getAcademicYear();;
		$this->id_employee = Auth::getInstance()->getAuth('id');

		$pagination = new Pagination;
		$result = "";

		if (!$isPrint)
		{
			$pagination->setParam([':id_academic_year' => $this->id_academic_year, ':id_employee' => $this->id_employee]);
			$pagination->init("SELECT COUNT(*) FROM organizational_methodological_work WHERE id_academic_year = :id_academic_year AND id_employee = :id_employee");
			$result = $db->prepare("SELECT id_omw, spr_t_work.name, spr_t_event.evname, name_event, spr_level_events.name_level, sdate, information_students, DATE_FORMAT(zdate,'%d.%m.%Y') zdate, result_executing
			          FROM (spr_t_work INNER JOIN 
			          (spr_level_events INNER JOIN
			          (spr_t_event INNER JOIN organizational_methodological_work 
			           ON spr_t_event.id = organizational_methodological_work.type_event)
			           ON spr_level_events.id_level = organizational_methodological_work.lev_event)
			           ON spr_t_work.id = organizational_methodological_work.type_work) WHERE id_employee = :id_employee AND id_academic_year = :id_academic_year ORDER BY id_omw LIMIT :page_position,:item_per_page");
			$result->bindParam(':id_academic_year', $this->id_academic_year);
			$result->bindParam(':id_employee', $this->id_employee);
			$result->bindValue(':page_position', $pagination->getPagePosition(), PDO::PARAM_INT);
			$result->bindValue(':item_per_page', $pagination->getItemPerPage(), PDO::PARAM_INT);
			$result->execute();
		}
		else
		{
			$result = $db->prepare("SELECT id_omw, spr_t_work.name, spr_t_event.evname, name_event, spr_level_events.name_level, sdate, information_students, DATE_FORMAT(zdate,'%d.%m.%Y') zdate, result_executing
			          FROM (spr_t_work INNER JOIN 
			          (spr_level_events INNER JOIN
			          (spr_t_event INNER JOIN organizational_methodological_work 
			           ON spr_t_event.id = organizational_methodological_work.type_event)
			           ON spr_level_events.id_level = organizational_methodological_work.lev_event)
			           ON spr_t_work.id = organizational_methodological_work.type_work) WHERE id_employee = :id_employee AND id_academic_year = :id_academic_year ORDER BY id_omw");
			$result->bindParam(':id_academic_year', $this->id_academic_year);
			$result->bindParam(':id_employee', $this->id_employee);
			$result->execute();
		}

		$this->getWorkList($result, $pagination, array('name', 'evname', 'name_event', 'name_level', 'sdate', 'information_students', 'result_executing'), 'id_omw', '/omw/works-edit-fill', '/omw/works-edit-exec', $isPrint);
	}

	public function worksExportExcel()
	{
		$db = DataBase::getInstance()->getDb();
		$this->id_academic_year = Auth::getInstance()->getAcademicYear();;
		$this->id_employee = Auth::getInstance()->getAuth('id');

		$rows = array(
    		array('№', 'Вид деятельности', 'Вид мероприятия', 'Название мероприятия', 'Уровень мероприятия', 'Дата', 'Информация о студентах', 'Результат'),
    		array('','','','','','','','')
		);

		$result = $db->prepare("SELECT id_omw, spr_t_work.name, spr_t_event.evname, name_event, spr_level_events.name_level, sdate, information_students, DATE_FORMAT(zdate,'%d.%m.%Y') zdate, result_executing
			          FROM (spr_t_work INNER JOIN 
			          (spr_level_events INNER JOIN
			          (spr_t_event INNER JOIN organizational_methodological_work 
			           ON spr_t_event.id = organizational_methodological_work.type_event)
			           ON spr_level_events.id_level = organizational_methodological_work.lev_event)
			           ON spr_t_work.id = organizational_methodological_work.type_work) WHERE id_employee = :id_employee AND id_academic_year = :id_academic_year ORDER BY id_omw");
		$result->bindParam(':id_academic_year', $this->id_academic_year);
		$result->bindParam(':id_employee', $this->id_employee);
		$result->execute();

		$writer = new XLSXWriter();
		$writer->sendHeader('Организационно-методическая_работа('.Auth::getInstance()->getName().'-'.$this->id_academic_year.').xlsx');
		$writer->setAuthor('User');
		$writer->writeSheetHeader('Лист 1', $header_types = array('string','string','string','string','string','string','string','string'), $col_options = array('widths'=>[4,15,25,25,20,25,25,25]));

		$writer->writeSheetRow('Лист 1', array('Организационно-методическая работа','','','','','','',''), $row_options = array('halign'=>'center','valign'=>'center','font-style'=> 'bold','font'=>'Times New Roman','font-size'=>'14'));

		foreach ($rows as $row)
			$writer->writeSheetRow('Лист 1', $row, $row_options = array('halign'=>'center','valign'=>'center','border'=>'left,right,top,bottom','border-style'=>'thin','wrap_text'=>true,'font'=>'Times New Roman','font-size'=>'12'));

		$this->getWorkExcelList($writer, $result, array('name', 'evname', 'name_event', 'name_level', 'sdate', 'information_students', 'result_executing'));

		$writer->markMergedCell('Лист 1', $start_row=1, $start_col=0, $end_row=1, $end_col=8); //

		$writer->markMergedCell('Лист 1', $start_row=2, $start_col=0, $end_row=3, $end_col=0); // Объединение ячеек №
		$writer->markMergedCell('Лист 1', $start_row=2, $start_col=1, $end_row=3, $end_col=1); // Объединение ячеек Вид деятельности
		$writer->markMergedCell('Лист 1', $start_row=2, $start_col=2, $end_row=3, $end_col=2); // Объединение ячеек Вид мероприятия
		$writer->markMergedCell('Лист 1', $start_row=2, $start_col=3, $end_row=3, $end_col=3); // Объединение ячеек Название мероприятия
		$writer->markMergedCell('Лист 1', $start_row=2, $start_col=4, $end_row=3, $end_col=4); // Объединение ячеек Уровень мероприятия
		$writer->markMergedCell('Лист 1', $start_row=2, $start_col=5, $end_row=3, $end_col=5); // Объединение ячеек Дата
		$writer->markMergedCell('Лист 1', $start_row=2, $start_col=6, $end_row=3, $end_col=6); // Объединение ячеек Информация о студентах
		$writer->markMergedCell('Лист 1', $start_row=2, $start_col=7, $end_row=3, $end_col=7); // Объединение ячеек Результат

		$writer->writeToStdOut();
	}

	public function getAnalysisTable($isPrint = false)
	{
		$db = DataBase::getInstance()->getDb();
		$filter = $this->getAnalysisAuthRules(array('employee','type_work','type_event','lev_event'),array('AND organizational_methodological_work.id_employee','AND organizational_methodological_work.type_work','AND organizational_methodological_work.type_event','AND organizational_methodological_work.lev_event')); // Функция получения прав о текущем пользователе для правильного отображения фильтрации

		$this->id_academic_year = Auth::getInstance()->getAcademicYear();
		$pagination = new Pagination;
		$pagination->setParam([':id_academic_year' => $this->id_academic_year]);
		$pagination->init("SELECT COUNT(*) FROM organizational_methodological_work INNER JOIN employee ON employee.id = organizational_methodological_work.id_employee WHERE id_academic_year = :id_academic_year $filter");
		$limit = "";
		if ($isPrint == false)
			$limit = "LIMIT ".$pagination->getPagePosition().", ".$pagination->getItemPerPage();

		$result = $db->prepare("SELECT id_omw, employee.surname AS employee_surname, employee.name AS employee_name, employee.patronymic AS employee_patronymic, spr_t_work.name, spr_t_event.evname, name_event, spr_level_events.name_level, sdate, information_students, DATE_FORMAT(zdate,'%d.%m.%Y') zdate, result_executing
			          FROM (spr_t_work INNER JOIN 
			          (spr_level_events INNER JOIN
			          (spr_t_event INNER JOIN
			          (employee INNER JOIN organizational_methodological_work
			           ON employee.id = organizational_methodological_work.id_employee)
			           ON spr_t_event.id = organizational_methodological_work.type_event)
			           ON spr_level_events.id_level = organizational_methodological_work.lev_event)
			           ON spr_t_work.id = organizational_methodological_work.type_work) 
			           WHERE id_academic_year = :id_academic_year $filter ORDER BY employee.surname, employee.name, employee.patronymic $limit");
		$result->bindParam(':id_academic_year', $this->id_academic_year);
		$result->execute();

		$this->getAnalysisList($result, $pagination, array('name', 'evname', 'name_event', 'name_level', 'sdate', 'information_students', 'result_executing', 'zdate'), 'id_omw', '/omw/analysis-edit', $isPrint);
	}

	public function analysisExportExcel()
	{
		$db = DataBase::getInstance()->getDb();
		$filter = $this->getAnalysisAuthRules(array('employee','type_work','type_event','lev_event'),array('AND organizational_methodological_work.id_employee','AND organizational_methodological_work.type_work','AND organizational_methodological_work.type_event','AND organizational_methodological_work.lev_event')); // Функция получения прав о текущем пользователе для правильного отображения фильтрации

		$this->id_academic_year = Auth::getInstance()->getAcademicYear();

		$rows = array(
    		array('№', 'Вид деятельности', 'Вид мероприятия', 'Название мероприятия', 'Уровень мероприятия', 'Дата', 'Информация о студентах', 'Результат', 'Дата записи', 'Сотрудник'),
    		array('','','','','','','','','','')
		);

		$result = $db->prepare("SELECT CONCAT(employee.surname,' ',Left(employee.name,1),'.',Left(employee.patronymic,1),'.') as FIO, employee.surname AS employee_surname, employee.name AS employee_name, employee.patronymic AS employee_patronymic, spr_t_work.name, spr_t_event.evname, name_event, spr_level_events.name_level, sdate, information_students, DATE_FORMAT(zdate,'%d.%m.%Y') zdate, result_executing
			          FROM (spr_t_work INNER JOIN 
			          (spr_level_events INNER JOIN
			          (spr_t_event INNER JOIN
			          (employee INNER JOIN organizational_methodological_work
			           ON employee.id = organizational_methodological_work.id_employee)
			           ON spr_t_event.id = organizational_methodological_work.type_event)
			           ON spr_level_events.id_level = organizational_methodological_work.lev_event)
			           ON spr_t_work.id = organizational_methodological_work.type_work) 
			           WHERE id_academic_year = :id_academic_year $filter ORDER BY employee.surname, employee.name, employee.patronymic");
		$result->bindParam(':id_academic_year', $this->id_academic_year);
		$result->execute();

		$writer = new XLSXWriter();
		$writer->sendHeader('Организационно-методическая_работа('.$this->id_academic_year.').xlsx');
		$writer->setAuthor('User');
		$writer->writeSheetHeader('Лист 1', $header_types = array('string','string','string','string','string','string','string','string','string','string'), $col_options = array('widths'=>[4,15,25,25,20,25,25,25,15,15,15]));

		$writer->writeSheetRow('Лист 1', array('Организационно-методическая работа','','','','','','','','',''), $row_options = array('halign'=>'center','valign'=>'center','font-style'=> 'bold','font'=>'Times New Roman','font-size'=>'14'));

		foreach ($rows as $row)
			$writer->writeSheetRow('Лист 1', $row, $row_options = array('halign'=>'center','valign'=>'center','border'=>'left,right,top,bottom','border-style'=>'thin','wrap_text'=>true,'font'=>'Times New Roman','font-size'=>'12'));

		$this->getAnalysisExcelList($writer, $result, array('name', 'evname', 'name_event', 'name_level', 'sdate', 'information_students', 'result_executing', 'zdate', 'FIO'), 'Лист 1');

		$writer->markMergedCell('Лист 1', $start_row=1, $start_col=0, $end_row=1, $end_col=9); //

		$writer->markMergedCell('Лист 1', $start_row=2, $start_col=0, $end_row=3, $end_col=0); // Объединение ячеек №
		$writer->markMergedCell('Лист 1', $start_row=2, $start_col=1, $end_row=3, $end_col=1); // Объединение ячеек Вид деятельности
		$writer->markMergedCell('Лист 1', $start_row=2, $start_col=2, $end_row=3, $end_col=2); // Объединение ячеек Вид мероприятия
		$writer->markMergedCell('Лист 1', $start_row=2, $start_col=3, $end_row=3, $end_col=3); // Объединение ячеек Название мероприятия
		$writer->markMergedCell('Лист 1', $start_row=2, $start_col=4, $end_row=3, $end_col=4); // Объединение ячеек Уровень мероприятия
		$writer->markMergedCell('Лист 1', $start_row=2, $start_col=5, $end_row=3, $end_col=5); // Объединение ячеек Дата
		$writer->markMergedCell('Лист 1', $start_row=2, $start_col=6, $end_row=3, $end_col=6); // Объединение ячеек Информация о студентах
		$writer->markMergedCell('Лист 1', $start_row=2, $start_col=7, $end_row=3, $end_col=7); // Объединение ячеек Результат
		$writer->markMergedCell('Лист 1', $start_row=2, $start_col=8, $end_row=3, $end_col=8); // Объединение ячеек Дата записи
		$writer->markMergedCell('Лист 1', $start_row=2, $start_col=9, $end_row=3, $end_col=9); // Объединение ячеек Сотрудник

		$writer->writeToStdOut();
	}

	public function worksEditFill()
	{
		$db = DataBase::getInstance()->getDb();
		$button_fill_edit = Helper::clean($_POST['button_fill_edit']);
		$this->id_employee = Auth::getInstance()->getAuth('id');

		if (is_numeric($button_fill_edit))
		{
			$result = $db->prepare("SELECT spr_t_work.id AS id_work, spr_t_event.id AS id_event, name_event, spr_level_events.id_level, sdate, information_students
			          FROM (spr_t_work INNER JOIN 
			          (spr_level_events INNER JOIN
			          (spr_t_event INNER JOIN organizational_methodological_work 
			           ON spr_t_event.id = organizational_methodological_work.type_event)
			           ON spr_level_events.id_level = organizational_methodological_work.lev_event)
			           ON spr_t_work.id = organizational_methodological_work.type_work) WHERE id_omw = :button_fill_edit AND id_employee = :id_employee");

			$result->bindParam(':button_fill_edit', $button_fill_edit);
			$result->bindParam(':id_employee', $this->id_employee);
			$result->execute();

			$row = $result->fetch();
			$this->type_work = $row['id_work'];
			$this->type_event = $row['id_event'];
			$this->name_event = $row['name_event'];
			$this->lev_event = $row['id_level'];
			$this->sdate = $row['sdate'];
			$this->information_students = $row['information_students'];
		}
	}

	public function worksEditExec()
	{
		$db = DataBase::getInstance()->getDb();
		$button_exec_edit = Helper::clean($_POST['button_exec_edit']);
		$this->id_employee = Auth::getInstance()->getAuth('id');

		if (is_numeric($button_exec_edit))
		{
			$result = $db->prepare("SELECT spr_t_work.name, spr_t_event.evname, name_event, spr_level_events.name_level, sdate, information_students
			          FROM (spr_t_work INNER JOIN 
			          (spr_level_events INNER JOIN
			          (spr_t_event INNER JOIN organizational_methodological_work 
			           ON spr_t_event.id = organizational_methodological_work.type_event)
			           ON spr_level_events.id_level = organizational_methodological_work.lev_event)
			           ON spr_t_work.id = organizational_methodological_work.type_work) WHERE id_omw = :button_exec_edit AND id_employee = :id_employee");

			$result->bindParam(':button_exec_edit', $button_exec_edit);
			$result->bindParam(':id_employee', $this->id_employee);
			$result->execute();

			$row = $result->fetch();
			$this->type_work = $row['name'];
			$this->type_event = $row['evname'];
			$this->name_event = $row['name_event'];
			$this->lev_event = $row['name_level'];
			$this->sdate = $row['sdate'];
			$this->information_students = $row['information_students'];
		}
	}

	public function worksUpdateFill()
	{
		$db = DataBase::getInstance()->getDb();
		$edit_fill_data_table_form_update = Helper::clean($_POST['edit_fill_data_table_form_update']);
		$this->id_employee = Auth::getInstance()->getAuth('id');

		if (is_numeric($edit_fill_data_table_form_update))
		{
			$this->type_work = Helper::clean($_POST['edit_fill_data_table_form_type_work']);
			$this->type_event = Helper::clean($_POST['edit_fill_data_table_form_type_event']);
			$this->name_event = Helper::clean($_POST['edit_fill_data_table_form_name_event']);
			$this->lev_event = Helper::clean($_POST['edit_fill_data_table_form_lev_event']);
			$this->sdate = Helper::clean($_POST['edit_fill_data_table_form_sdate']);
			$this->information_students = Helper::clean($_POST['edit_fill_data_table_form_information_students']);

			$result = $db->prepare("UPDATE organizational_methodological_work SET type_work = :type_work, type_event = :type_event, name_event = :name_event, lev_event = :lev_event, sdate = :sdate, information_students = :information_students WHERE id_omw = :edit_fill_data_table_form_update AND id_employee = :id_employee");

			$result->bindParam(':type_work', $this->type_work);
    		$result->bindParam(':type_event', $this->type_event);
    		$result->bindParam(':name_event', $this->name_event);
    		$result->bindParam(':lev_event', $this->lev_event);
    		$result->bindParam(':sdate', $this->sdate);
    		$result->bindParam(':information_students', $this->information_students);
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
				$this->result_executing = 'Выполнено: '.Helper::clean($_POST['edit_exec_data_table_form_result_executing']);
			else if ($edit_exec_data_table_form_check == '0')
				$this->result_executing = 'Не выполнено: '.Helper::clean($_POST['edit_exec_data_table_form_result_executing']);

			$result = $db->prepare("UPDATE organizational_methodological_work SET result_executing = :result_executing WHERE id_omw = :edit_exec_data_table_form_update AND id_employee = :id_employee");
			$result->bindParam(':edit_exec_data_table_form_update', $edit_exec_data_table_form_update);
			$result->bindParam(':id_employee', $this->id_employee);
    		$result->bindParam(':result_executing', $this->result_executing);
    		$result->execute();
		}
	}

	public function worksAjaxInsert()
	{
		$db = DataBase::getInstance()->getDb();
		$this->type_work = Helper::clean($_POST['type_work']);
		$this->type_event = Helper::clean($_POST['type_event']);
		$this->name_event = Helper::clean($_POST['name_event']);
		$this->lev_event = Helper::clean($_POST['lev_event']);
		$this->sdate = Helper::clean($_POST['sdate']);
		$this->information_students = Helper::clean($_POST['information_students']);
		$this->id_academic_year = Auth::getInstance()->getAcademicYear();
		$this->id_employee = Auth::getInstance()->getAuth('id');

		$result = $db->prepare("INSERT INTO organizational_methodological_work (type_work, type_event, name_event, lev_event, sdate, information_students, zdate, id_employee, id_academic_year) 
		VALUES(:type_work, :type_event, :name_event, :lev_event, :sdate, :information_students, CURDATE(), :id_employee, :id_academic_year)");

		$result->bindParam(':type_work', $this->type_work);
		$result->bindParam(':type_event', $this->type_event);
		$result->bindParam(':name_event', $this->name_event);
		$result->bindParam(':lev_event', $this->lev_event);
		$result->bindParam(':sdate', $this->sdate);
		$result->bindParam(':information_students', $this->information_students);
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
			$result = $db->prepare("DELETE FROM organizational_methodological_work WHERE id_omw = :value AND id_employee = :id_employee");
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
			$result = $db->prepare("SELECT spr_t_work.id AS id_work, spr_t_event.id AS id_event, name_event, spr_level_events.id_level, sdate, information_students, result_executing
			          FROM (spr_t_work INNER JOIN 
			          (spr_level_events INNER JOIN
			          (spr_t_event INNER JOIN organizational_methodological_work 
			           ON spr_t_event.id = organizational_methodological_work.type_event)
			           ON spr_level_events.id_level = organizational_methodological_work.lev_event)
			           ON spr_t_work.id = organizational_methodological_work.type_work) WHERE id_omw = :button_edit");

			$result->bindParam(':button_edit', $button_edit);
			$result->execute();

			$row = $result->fetch();
			$this->type_work = $row['id_work'];
			$this->type_event = $row['id_event'];
			$this->name_event = $row['name_event'];
			$this->lev_event = $row['id_level'];
			$this->sdate = $row['sdate'];
			$this->information_students = $row['information_students'];
			$this->result_executing = $row['result_executing'];
		}
	}

	public function analysisUpdate()
	{
		$db = DataBase::getInstance()->getDb();
		$edit_data_table_form_update = Helper::clean($_POST['edit_data_table_form_update']);

		if (is_numeric($edit_data_table_form_update))
		{
			$this->type_work = Helper::clean($_POST['edit_data_table_form_type_work']);
			$this->type_event = Helper::clean($_POST['edit_data_table_form_type_event']);
			$this->name_event = Helper::clean($_POST['edit_data_table_form_name_event']);
			$this->lev_event = Helper::clean($_POST['edit_data_table_form_lev_event']);
			$this->sdate = Helper::clean($_POST['edit_data_table_form_sdate']);
			$this->information_students = Helper::clean($_POST['edit_data_table_form_information_students']);
			$this->result_executing = Helper::clean($_POST['edit_data_table_form_result_executing']);

			$result = $db->prepare("UPDATE organizational_methodological_work SET type_work = :type_work, type_event = :type_event, name_event = :name_event, lev_event = :lev_event, sdate = :sdate, information_students = :information_students, result_executing = :result_executing WHERE id_omw = :edit_data_table_form_update");

			$result->bindParam(':type_work', $this->type_work);
    		$result->bindParam(':type_event', $this->type_event);
    		$result->bindParam(':name_event', $this->name_event);
    		$result->bindParam(':lev_event', $this->lev_event);
    		$result->bindParam(':sdate', $this->sdate);
    		$result->bindParam(':information_students', $this->information_students);
    		$result->bindParam(':result_executing', $this->result_executing);
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
			$result = $db->prepare("DELETE FROM organizational_methodological_work WHERE id_omw = :value");
			$result->bindParam(':value', $value);
			$result->execute();

			echo true;
		}
	}
}

?>