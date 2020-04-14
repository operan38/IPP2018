<?php

class SkillsEnchancement extends WorkAnalysTable
{
	public $id_se;
	public $type_work;
	public $sdate;
	public $place;
	public $theme;
	public $result_executing;

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
			$pagination->init("SELECT COUNT(*) FROM skills_enchancement WHERE id_academic_year = :id_academic_year AND id_employee = :id_employee");

			$result = $db->prepare("SELECT id_se, spr_t_worksew.sew_name, sdate, place, theme, DATE_FORMAT(zdate,'%d.%m.%Y') zdate, result_executing
			          FROM (spr_t_worksew INNER JOIN 
					  skills_enchancement
				      ON spr_t_worksew.id_sework = skills_enchancement.type_work) WHERE id_employee = :id_employee AND id_academic_year = :id_academic_year ORDER BY id_se LIMIT :page_position,:item_per_page");

			$result->bindParam(':id_academic_year', $this->id_academic_year);
			$result->bindParam(':id_employee', $this->id_employee);
			$result->bindValue(':page_position', $pagination->getPagePosition(), PDO::PARAM_INT);
			$result->bindValue(':item_per_page', $pagination->getItemPerPage(), PDO::PARAM_INT);
			$result->execute();
		}
		else
		{
			$result = $db->prepare("SELECT id_se, spr_t_worksew.sew_name, sdate, place, theme, DATE_FORMAT(zdate,'%d.%m.%Y') zdate, result_executing
			          FROM (spr_t_worksew INNER JOIN 
					  skills_enchancement
				      ON spr_t_worksew.id_sework = skills_enchancement.type_work) WHERE id_employee = :id_employee AND id_academic_year = :id_academic_year ORDER BY id_se");

			$result->bindParam(':id_academic_year', $this->id_academic_year);
			$result->bindParam(':id_employee', $this->id_employee);
			$result->execute();
		}

		$this->getWorkList($result, $pagination, array('sew_name', 'sdate', 'place', 'theme', 'result_executing'), 'id_se', '/se/works-edit-fill', '/se/works-edit-exec', $isPrint);
	}

	public function worksExportExcel()
	{
		$db = DataBase::getInstance()->getDb();
		$this->id_academic_year = Auth::getInstance()->getAcademicYear();;
		$this->id_employee = Auth::getInstance()->getAuth('id');

		$rows = array(
    		array('№', 'Форма повышения', 'Дата', 'Место', 'Тема', 'Документ/Результат'),
    		array('','','','','','')
		);

		$result = $db->prepare("SELECT id_se, spr_t_worksew.sew_name, sdate, place, theme, DATE_FORMAT(zdate,'%d.%m.%Y') zdate, result_executing
			          FROM (spr_t_worksew INNER JOIN 
					  skills_enchancement
				      ON spr_t_worksew.id_sework = skills_enchancement.type_work) WHERE id_employee = :id_employee AND id_academic_year = :id_academic_year ORDER BY id_se");

		$result->bindParam(':id_academic_year', $this->id_academic_year);
		$result->bindParam(':id_employee', $this->id_employee);
		$result->execute();

		$writer = new XLSXWriter();
		$writer->sendHeader('Повышение_уровня_квалификации('.Auth::getInstance()->getName().'-'.$this->id_academic_year.').xlsx');
		$writer->setAuthor('User');
		$writer->writeSheetHeader('Лист 1', $header_types = array('string','string','string','string','string','string'), $col_options = array('widths'=>[4,25,20,20,25,30]));

		$writer->writeSheetRow('Лист 1', array('Повышение уровня квалификации','','','','',''), $row_options = array('halign'=>'center','valign'=>'center','font-style'=> 'bold','wrap_text'=>true,'font'=>'Times New Roman','font-size'=>'14'));

		foreach ($rows as $row)
			$writer->writeSheetRow('Лист 1', $row, $row_options = array('halign'=>'center','valign'=>'center','border'=>'left,right,top,bottom','border-style'=>'thin','font'=>'Times New Roman','font-size'=>'12'));

		$this->getWorkExcelList($writer, $result, array('sew_name', 'sdate', 'place', 'theme', 'result_executing'));

		$writer->markMergedCell('Лист 1', $start_row=1, $start_col=0, $end_row=1, $end_col=5); //

		$writer->markMergedCell('Лист 1', $start_row=2, $start_col=0, $end_row=3, $end_col=0); // Объединение ячеек №
		$writer->markMergedCell('Лист 1', $start_row=2, $start_col=1, $end_row=3, $end_col=1); // Объединение ячеек Форма повышения
		$writer->markMergedCell('Лист 1', $start_row=2, $start_col=2, $end_row=3, $end_col=2); // Объединение ячеек Дата
		$writer->markMergedCell('Лист 1', $start_row=2, $start_col=3, $end_row=3, $end_col=3); // Объединение ячеек Место
		$writer->markMergedCell('Лист 1', $start_row=2, $start_col=4, $end_row=3, $end_col=4); // Объединение ячеек Тема
		$writer->markMergedCell('Лист 1', $start_row=2, $start_col=5, $end_row=3, $end_col=5); // Объединение ячеек Документ/Результат
		$writer->markMergedCell('Лист 1', $start_row=2, $start_col=6, $end_row=3, $end_col=6); // Объединение ячеек Дата записи

		$writer->writeToStdOut();		
	}

	public function getAnalysisTable($isPrint = false)
	{
		$db = DataBase::getInstance()->getDb();
		$filter = $this->getAnalysisAuthRules(array('employee','type_work'),array('AND skills_enchancement.id_employee','AND skills_enchancement.type_work')); // Функция получения прав о текущем пользователе для правильного отображения фильтрации

		$this->id_academic_year = Auth::getInstance()->getAcademicYear();
		$pagination = new Pagination;
		$pagination->setParam([':id_academic_year' => $this->id_academic_year]);
		$pagination->init("SELECT COUNT(*) FROM skills_enchancement INNER JOIN employee ON employee.id = skills_enchancement.id_employee WHERE id_academic_year = :id_academic_year $filter");
		$limit = "";
		if ($isPrint == false)
			$limit = "LIMIT ".$pagination->getPagePosition().", ".$pagination->getItemPerPage();

		$result = $db->prepare("SELECT id_se, employee.surname AS employee_surname, employee.name AS employee_name, employee.patronymic AS employee_patronymic, spr_t_worksew.sew_name, sdate, place, theme, DATE_FORMAT(zdate,'%d.%m.%Y') zdate, result_executing
			          FROM (spr_t_worksew INNER JOIN 
					  	   (employee INNER JOIN skills_enchancement
					  ON employee.id = skills_enchancement.id_employee)
				      ON spr_t_worksew.id_sework = skills_enchancement.type_work)
				      WHERE id_academic_year = :id_academic_year $filter ORDER BY employee.surname, employee.name, employee.patronymic $limit");

		$result->bindParam(':id_academic_year', $this->id_academic_year);
		$result->execute();

		$this->getAnalysisList($result, $pagination, array('sew_name', 'sdate', 'place', 'theme', 'result_executing', 'zdate'), 'id_se', '/se/analysis-edit', $isPrint);
	}

	public function analysisExportExcel()
	{
		$db = DataBase::getInstance()->getDb();
		$filter = $this->getAnalysisAuthRules(array('employee','type_work'),array('AND skills_enchancement.id_employee','AND skills_enchancement.type_work')); // Функция получения прав о текущем пользователе для правильного отображения фильтрации

		$this->id_academic_year = Auth::getInstance()->getAcademicYear();

		$rows = array(
    		array('№', 'Форма повышения', 'Дата', 'Место', 'Тема', 'Документ/Результат', 'Дата записи', 'Сотрудник'),
    		array('','','','','','','','')
		);

		$result = $db->prepare("SELECT CONCAT(employee.surname,' ',Left(employee.name,1),'.',Left(employee.patronymic,1),'.') as FIO, employee.surname AS employee_surname, employee.name AS employee_name, employee.patronymic AS employee_patronymic, spr_t_worksew.sew_name, sdate, place, theme, DATE_FORMAT(zdate,'%d.%m.%Y') zdate, result_executing
			          FROM (spr_t_worksew INNER JOIN 
					  	   (employee INNER JOIN skills_enchancement
					  ON employee.id = skills_enchancement.id_employee)
				      ON spr_t_worksew.id_sework = skills_enchancement.type_work)
				      WHERE id_academic_year = :id_academic_year $filter ORDER BY employee.surname, employee.name, employee.patronymic");

		$result->bindParam(':id_academic_year', $this->id_academic_year);
		$result->execute();

		$writer = new XLSXWriter();
		$writer->sendHeader('Повышение_уровня_квалификации('.$this->id_academic_year.').xlsx');
		$writer->setAuthor('User');
		$writer->writeSheetHeader('Лист 1', $header_types = array('string','string','string','string','string','string','string','string'), $col_options = array('widths'=>[4,25,20,20,25,30,15,15]));

		$writer->writeSheetRow('Лист 1', array('Повышение уровня квалификации','','','','','','',''), $row_options = array('halign'=>'center','valign'=>'center','font-style'=> 'bold','wrap_text'=>true,'font'=>'Times New Roman','font-size'=>'14'));

		foreach ($rows as $row)
			$writer->writeSheetRow('Лист 1', $row, $row_options = array('halign'=>'center','valign'=>'center','border'=>'left,right,top,bottom','border-style'=>'thin','font'=>'Times New Roman','font-size'=>'12'));

		$this->getAnalysisExcelList($writer, $result, array('sew_name', 'sdate', 'place', 'theme', 'result_executing', 'zdate', 'FIO'), 'Лист 1');

		$writer->markMergedCell('Лист 1', $start_row=1, $start_col=0, $end_row=1, $end_col=7); //

		$writer->markMergedCell('Лист 1', $start_row=2, $start_col=0, $end_row=3, $end_col=0); // Объединение ячеек №
		$writer->markMergedCell('Лист 1', $start_row=2, $start_col=1, $end_row=3, $end_col=1); // Объединение ячеек Форма повышения
		$writer->markMergedCell('Лист 1', $start_row=2, $start_col=2, $end_row=3, $end_col=2); // Объединение ячеек Дата
		$writer->markMergedCell('Лист 1', $start_row=2, $start_col=3, $end_row=3, $end_col=3); // Объединение ячеек Место
		$writer->markMergedCell('Лист 1', $start_row=2, $start_col=4, $end_row=3, $end_col=4); // Объединение ячеек Тема
		$writer->markMergedCell('Лист 1', $start_row=2, $start_col=5, $end_row=3, $end_col=5); // Объединение ячеек Документ/Результат
		$writer->markMergedCell('Лист 1', $start_row=2, $start_col=6, $end_row=3, $end_col=6); // Объединение ячеек Дата записи
		$writer->markMergedCell('Лист 1', $start_row=2, $start_col=7, $end_row=3, $end_col=7); // Объединение ячеек Сотрудник

		$writer->writeToStdOut();
	}

	public function worksEditFill()
	{
		$db = DataBase::getInstance()->getDb();
		$button_fill_edit = Helper::clean($_POST['button_fill_edit']);
		$this->id_employee = Auth::getInstance()->getAuth('id');

		if (is_numeric($button_fill_edit))
		{
			$result = $db->prepare("SELECT spr_t_worksew.id_sework, sdate, place, theme
			          FROM (spr_t_worksew INNER JOIN 
					  skills_enchancement
				      ON spr_t_worksew.id_sework = skills_enchancement.type_work) WHERE id_se = :button_fill_edit AND id_employee = :id_employee");

			$result->bindParam(':button_fill_edit', $button_fill_edit);
			$result->bindParam(':id_employee', $this->id_employee);
			$result->execute();

			$row = $result->fetch();
			$this->type_work = $row['id_sework'];
			$this->sdate = $row['sdate'];
			$this->place = $row['place'];
			$this->theme = $row['theme'];
		}
	}

	public function worksEditExec()
	{
		$db = DataBase::getInstance()->getDb();
		$button_exec_edit = Helper::clean($_POST['button_exec_edit']);
		$this->id_employee = Auth::getInstance()->getAuth('id');

		if (is_numeric($button_exec_edit))
		{
			$result = $db->prepare("SELECT spr_t_worksew.sew_name, sdate, place, theme
			          FROM (spr_t_worksew INNER JOIN 
					  skills_enchancement
				      ON spr_t_worksew.id_sework = skills_enchancement.type_work) WHERE id_se = :button_exec_edit AND id_employee = :id_employee");

			$result->bindParam(':button_exec_edit', $button_exec_edit);
			$result->bindParam(':id_employee', $this->id_employee);
			$result->execute();

			$row = $result->fetch();
			$this->type_work = $row['sew_name'];
			$this->sdate = $row['sdate'];
			$this->place = $row['place'];
			$this->theme = $row['theme'];
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
			$this->sdate = Helper::clean($_POST['edit_fill_data_table_form_sdate']);
			$this->place = Helper::clean($_POST['edit_fill_data_table_form_place']);
			$this->theme = Helper::clean($_POST['edit_fill_data_table_form_theme']);

			$result = $db->prepare("UPDATE skills_enchancement SET type_work = :type_work, sdate = :sdate, place = :place, theme = :theme WHERE id_se = :edit_fill_data_table_form_update AND id_employee = :id_employee");

			$result->bindParam(':type_work', $this->type_work);
    		$result->bindParam(':sdate', $this->sdate);
    		$result->bindParam(':place', $this->place);
    		$result->bindParam(':theme', $this->theme);
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

			$result = $db->prepare("UPDATE skills_enchancement SET result_executing = :result_executing WHERE id_se = :edit_exec_data_table_form_update AND id_employee = :id_employee");
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
		$this->sdate = Helper::clean($_POST['sdate']);
		$this->place = Helper::clean($_POST['place']);
		$this->theme = Helper::clean($_POST['theme']);
		$this->id_academic_year = Auth::getInstance()->getAcademicYear();
		$this->id_employee = Auth::getInstance()->getAuth('id');

		$result = $db->prepare("INSERT INTO skills_enchancement (type_work, sdate, place, theme, zdate, id_employee, id_academic_year) 
		VALUES(:type_work, :sdate, :place, :theme, CURDATE(), :id_employee, :id_academic_year)");

		$result->bindParam(':type_work', $this->type_work);
		$result->bindParam(':sdate', $this->sdate);
		$result->bindParam(':place', $this->place);
		$result->bindParam(':theme', $this->theme);
		$result->bindParam(':id_employee', $this->id_employee);
		$result->bindParam(':id_academic_year', $this->id_academic_year);
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
			$result = $db->prepare("DELETE FROM skills_enchancement WHERE id_se = :value AND id_employee = :id_employee");
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
			$result = $db->prepare("SELECT spr_t_worksew.id_sework, sdate, place, theme, result_executing
			          FROM (spr_t_worksew INNER JOIN 
					  skills_enchancement
				      ON spr_t_worksew.id_sework = skills_enchancement.type_work) WHERE id_se = :button_edit");

			$result->bindParam(':button_edit', $button_edit);
			$result->execute();

			$row = $result->fetch();
			$this->type_work = $row['id_sework'];
			$this->sdate = $row['sdate'];
			$this->place = $row['place'];
			$this->theme = $row['theme'];
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
			$this->sdate = Helper::clean($_POST['edit_data_table_form_sdate']);
			$this->place = Helper::clean($_POST['edit_data_table_form_place']);
			$this->theme = Helper::clean($_POST['edit_data_table_form_theme']);
			$this->result_executing = Helper::clean($_POST['edit_data_table_form_result_executing']);

			$result = $db->prepare("UPDATE skills_enchancement SET type_work = :type_work, sdate = :sdate, place = :place, theme = :theme, result_executing = :result_executing WHERE id_se = :edit_data_table_form_update");

			$result->bindParam(':type_work', $this->type_work);
    		$result->bindParam(':sdate', $this->sdate);
    		$result->bindParam(':place', $this->place);
    		$result->bindParam(':theme', $this->theme);
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
			$result = $db->prepare("DELETE FROM skills_enchancement WHERE id_se = :value");
			$result->bindParam(':value', $value);
			$result->execute();

			echo true;
		}
	}
}

?>