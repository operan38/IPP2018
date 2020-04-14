<?php

class EducationalWork extends WorkAnalysTable
{
	public $id_ew;
	public $ppccz;
	public $cipher_group;
	public $type_activity;
	public $type_work;
	public $sdate;
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
			$pagination->init("SELECT COUNT(*) FROM educational_work WHERE id_academic_year = :id_academic_year AND id_employee = :id_employee");

			$result = $db->prepare("SELECT id_ew, ppccz.cipher_specialty, spr_cipher_group.gname, spr_t_teach_educational_activity.ename, spr_t_workew.ew_name, 
				sdate, DATE_FORMAT(zdate,'%d.%m.%Y') zdate, report 
				FROM (spr_t_teach_educational_activity INNER JOIN
				(spr_t_workew INNER JOIN
				(ppccz INNER JOIN
				(spr_cipher_group INNER JOIN educational_work
				ON spr_cipher_group.id = educational_work.cipher_group)
				ON ppccz.id_ppccz = educational_work.ppccz)
				ON spr_t_workew.id = educational_work.type_work)
				ON spr_t_teach_educational_activity.id = educational_work.type_activity)
				WHERE id_academic_year = :id_academic_year AND id_employee = :id_employee ORDER BY id_ew LIMIT :page_position,:item_per_page");
			$result->bindParam(':id_academic_year', $this->id_academic_year);
			$result->bindParam(':id_employee', $this->id_employee);
			$result->bindValue(':page_position', $pagination->getPagePosition(), PDO::PARAM_INT);
			$result->bindValue(':item_per_page', $pagination->getItemPerPage(), PDO::PARAM_INT);
			$result->execute();
		}
		else
		{
			$result = $db->prepare("SELECT id_ew, ppccz.cipher_specialty, spr_cipher_group.gname, spr_t_teach_educational_activity.ename, spr_t_workew.ew_name, 
				sdate, DATE_FORMAT(zdate,'%d.%m.%Y') zdate, report 
				FROM (spr_t_teach_educational_activity INNER JOIN
				(spr_t_workew INNER JOIN
				(ppccz INNER JOIN
				(spr_cipher_group INNER JOIN educational_work
				ON spr_cipher_group.id = educational_work.cipher_group)
				ON ppccz.id_ppccz = educational_work.ppccz)
				ON spr_t_workew.id = educational_work.type_work)
				ON spr_t_teach_educational_activity.id = educational_work.type_activity)
				WHERE id_academic_year = :id_academic_year AND id_employee = :id_employee ORDER BY id_ew");
			$result->bindParam(':id_academic_year', $this->id_academic_year);
			$result->bindParam(':id_employee', $this->id_employee);
			$result->execute();
		}

		$this->getWorkList($result, $pagination, array('cipher_specialty', 'gname', 'ew_name', 'ename', 'sdate', 'report'), 'id_ew', '/ew/works-edit-fill', '/ew/works-edit-exec', $isPrint);
	}

	public function worksExportExcel()
	{
		$db = DataBase::getInstance()->getDb();
		$this->id_academic_year = Auth::getInstance()->getAcademicYear();
		$this->id_employee = Auth::getInstance()->getAuth('id');

		$rows = array(
    		array('№', 'Специальность', 'Группа', 'Вид деятельности', 'Вид учебно-воспитательной деятельности', 'Дата', 'Результат'),
    		array('','','','','','','')
		);

		$result = $db->prepare("SELECT id_ew, ppccz.cipher_specialty, spr_cipher_group.gname, spr_t_teach_educational_activity.ename, spr_t_workew.ew_name, 
				sdate, DATE_FORMAT(zdate,'%d.%m.%Y') zdate, report 
				FROM (spr_t_teach_educational_activity INNER JOIN
				(spr_t_workew INNER JOIN
				(ppccz INNER JOIN
				(spr_cipher_group INNER JOIN educational_work
				ON spr_cipher_group.id = educational_work.cipher_group)
				ON ppccz.id_ppccz = educational_work.ppccz)
				ON spr_t_workew.id = educational_work.type_work)
				ON spr_t_teach_educational_activity.id = educational_work.type_activity)
				WHERE id_academic_year = :id_academic_year AND id_employee = :id_employee ORDER BY id_ew");
		$result->bindParam(':id_academic_year', $this->id_academic_year);
		$result->bindParam(':id_employee', $this->id_employee);
		$result->execute();

		$writer = new XLSXWriter();
		$writer->sendHeader('Воспитательная_работа('.Auth::getInstance()->getName().'-'.$this->id_academic_year.').xlsx');
		$writer->setAuthor('User');
		$writer->writeSheetHeader('Лист 1', $header_types = array('string','string','string','string','string','string','string'), $col_options = array('widths'=>[4,20,25,20,40,15,30]));

		$writer->writeSheetRow('Лист 1', array('Воспитательная работа','','','','','',''), $row_options = array('halign'=>'center','valign'=>'center','font-style'=> 'bold','font'=>'Times New Roman','font-size'=>'14'));

		foreach ($rows as $row)
			$writer->writeSheetRow('Лист 1', $row, $row_options = array('halign'=>'center','valign'=>'center','border'=>'left,right,top,bottom','border-style'=>'thin','wrap_text'=>true,'font'=>'Times New Roman','font-size'=>'12'));

		$this->getWorkExcelList($writer, $result, array('cipher_specialty', 'gname', 'ename', 'ew_name', 'sdate', 'report'));

		$writer->markMergedCell('Лист 1', $start_row=1, $start_col=0, $end_row=1, $end_col=6); //

		$writer->markMergedCell('Лист 1', $start_row=2, $start_col=0, $end_row=3, $end_col=0); // Объединение ячеек №
		$writer->markMergedCell('Лист 1', $start_row=2, $start_col=1, $end_row=3, $end_col=1); // Объединение ячеек Специальность
		$writer->markMergedCell('Лист 1', $start_row=2, $start_col=2, $end_row=3, $end_col=2); // Объединение ячеек Группа
		$writer->markMergedCell('Лист 1', $start_row=2, $start_col=3, $end_row=3, $end_col=3); // Объединение ячеек Вид деятельности
		$writer->markMergedCell('Лист 1', $start_row=2, $start_col=4, $end_row=3, $end_col=4); // Объединение ячеек Вид учебно-воспитательной деятельности
		$writer->markMergedCell('Лист 1', $start_row=2, $start_col=5, $end_row=3, $end_col=5); // Объединение ячеек Дата
		$writer->markMergedCell('Лист 1', $start_row=2, $start_col=6, $end_row=3, $end_col=6); // Объединение ячеек Результат

		$writer->writeToStdOut();
	}

	public function getAnalysisTable($isPrint = false)
	{
		$db = DataBase::getInstance()->getDb();
		$filter = $this->getAnalysisAuthRules(array('employee','ppccz','cipher_group','type_work','type_activity'),array('AND educational_work.id_employee', 'AND educational_work.ppccz', 'AND educational_work.cipher_group','AND educational_work.type_work','AND educational_work.type_activity')); // Функция получения прав о текущем пользователе для правильного отображения фильтрации

		$this->id_academic_year = Auth::getInstance()->getAcademicYear();
		$pagination = new Pagination;
		$pagination->setParam([':id_academic_year' => $this->id_academic_year]);
		$pagination->init("SELECT COUNT(*) FROM educational_work INNER JOIN employee ON employee.id = educational_work.id_employee WHERE id_academic_year = :id_academic_year $filter");
		$limit = "";
		if ($isPrint == false)
			$limit = "LIMIT ".$pagination->getPagePosition().", ".$pagination->getItemPerPage();

		$result = $db->prepare("SELECT id_ew, employee.surname AS employee_surname, employee.name AS employee_name, employee.patronymic AS employee_patronymic, ppccz.cipher_specialty, spr_cipher_group.gname, spr_t_teach_educational_activity.ename, spr_t_workew.ew_name, 
			sdate, DATE_FORMAT(zdate,'%d.%m.%Y') zdate, report 
			FROM (spr_t_teach_educational_activity INNER JOIN
			(spr_t_workew INNER JOIN
			(ppccz INNER JOIN
			(spr_cipher_group INNER JOIN 
			(employee INNER JOIN educational_work
			ON employee.id = educational_work.id_employee)
			ON spr_cipher_group.id = educational_work.cipher_group)
			ON ppccz.id_ppccz = educational_work.ppccz)
			ON spr_t_workew.id = educational_work.type_work)
			ON spr_t_teach_educational_activity.id = educational_work.type_activity)
			WHERE id_academic_year = :id_academic_year $filter ORDER BY employee.surname, employee.name, employee.patronymic $limit");
		$result->bindParam(':id_academic_year', $this->id_academic_year);
		$result->execute();

		$this->getAnalysisList($result, $pagination, array('cipher_specialty', 'gname', 'ename', 'ew_name', 'sdate', 'report', 'zdate'), 'id_ew', '/ew/analysis-edit', $isPrint);
	}

	public function analysisExportExcel()
	{
		$db = DataBase::getInstance()->getDb();
		$filter = $this->getAnalysisAuthRules(array('employee','ppccz','cipher_group','type_work','type_activity'),array('AND educational_work.id_employee', 'AND educational_work.ppccz', 'AND educational_work.cipher_group','AND educational_work.type_work','AND educational_work.type_activity')); // Функция получения прав о текущем пользователе для правильного отображения фильтрации

		$this->id_academic_year = Auth::getInstance()->getAcademicYear();

		$rows = array(
    		array('№', 'Специальность', 'Группа', 'Вид деятельности', 'Вид учебно-воспитательной деятельности', 'Дата', 'Результат','Дата записи','Сотрудник'),
    		array('','','','','','','','','')
		);

		$result = $db->prepare("SELECT CONCAT(employee.surname,' ',Left(employee.name,1),'.',Left(employee.patronymic,1),'.') as FIO, employee.surname AS employee_surname, employee.name AS employee_name, employee.patronymic AS employee_patronymic, ppccz.cipher_specialty, spr_cipher_group.gname, spr_t_teach_educational_activity.ename, spr_t_workew.ew_name, 
			sdate, DATE_FORMAT(zdate,'%d.%m.%Y') zdate, report 
			FROM (spr_t_teach_educational_activity INNER JOIN
			(spr_t_workew INNER JOIN
			(ppccz INNER JOIN
			(spr_cipher_group INNER JOIN 
			(employee INNER JOIN educational_work
			ON employee.id = educational_work.id_employee)
			ON spr_cipher_group.id = educational_work.cipher_group)
			ON ppccz.id_ppccz = educational_work.ppccz)
			ON spr_t_workew.id = educational_work.type_work)
			ON spr_t_teach_educational_activity.id = educational_work.type_activity)
			WHERE id_academic_year = :id_academic_year $filter ORDER BY employee.surname, employee.name, employee.patronymic");
		$result->bindParam(':id_academic_year', $this->id_academic_year);
		$result->execute();

		$writer = new XLSXWriter();
		$writer->sendHeader('Воспитательная_работа('.$this->id_academic_year.').xlsx');
		$writer->setAuthor('User');
		$writer->writeSheetHeader('Лист 1', $header_types = array('string','string','string','string','string','string','string','string','string'), $col_options = array('widths'=>[4,20,25,20,40,15,30,15,15]));

		$writer->writeSheetRow('Лист 1', array('Воспитательная работа','','','','','','','',''), $row_options = array('halign'=>'center','valign'=>'center','font-style'=> 'bold','font'=>'Times New Roman','font-size'=>'14'));

		foreach ($rows as $row)
			$writer->writeSheetRow('Лист 1', $row, $row_options = array('halign'=>'center','valign'=>'center','border'=>'left,right,top,bottom','border-style'=>'thin','wrap_text'=>true,'font'=>'Times New Roman','font-size'=>'12'));

		$this->getWorkExcelList($writer, $result, array('cipher_specialty', 'gname', 'ename', 'ew_name', 'sdate', 'report', 'zdate', 'FIO'));

		$writer->markMergedCell('Лист 1', $start_row=1, $start_col=0, $end_row=1, $end_col=8); //

		$writer->markMergedCell('Лист 1', $start_row=2, $start_col=0, $end_row=3, $end_col=0); // Объединение ячеек №
		$writer->markMergedCell('Лист 1', $start_row=2, $start_col=1, $end_row=3, $end_col=1); // Объединение ячеек Специальность
		$writer->markMergedCell('Лист 1', $start_row=2, $start_col=2, $end_row=3, $end_col=2); // Объединение ячеек Группа
		$writer->markMergedCell('Лист 1', $start_row=2, $start_col=3, $end_row=3, $end_col=3); // Объединение ячеек Вид деятельности
		$writer->markMergedCell('Лист 1', $start_row=2, $start_col=4, $end_row=3, $end_col=4); // Объединение ячеек Вид учебно-воспитательной деятельности
		$writer->markMergedCell('Лист 1', $start_row=2, $start_col=5, $end_row=3, $end_col=5); // Объединение ячеек Дата
		$writer->markMergedCell('Лист 1', $start_row=2, $start_col=6, $end_row=3, $end_col=6); // Объединение ячеек Результат
		$writer->markMergedCell('Лист 1', $start_row=2, $start_col=7, $end_row=3, $end_col=7); // Объединение ячеек Дата записи
		$writer->markMergedCell('Лист 1', $start_row=2, $start_col=8, $end_row=3, $end_col=8); // Объединение ячеек Сотрудник

		$writer->writeToStdOut();
	}

	public function worksEditFill()
	{
		$db = DataBase::getInstance()->getDb();
		$button_fill_edit = Helper::clean($_POST['button_fill_edit']);
		$this->id_employee = Auth::getInstance()->getAuth('id');

		if (is_numeric($button_fill_edit))
		{
			$result = $db->prepare("SELECT ppccz.id_ppccz, spr_cipher_group.id AS id_cipher, spr_t_teach_educational_activity.id AS id_ed_activ, spr_t_workew.id AS id_workew, 
				sdate, DATE_FORMAT(zdate,'%d.%m.%Y') zdate, report 
				FROM (spr_t_teach_educational_activity INNER JOIN
				(spr_t_workew INNER JOIN
				(ppccz INNER JOIN
				(spr_cipher_group INNER JOIN educational_work
				ON spr_cipher_group.id = educational_work.cipher_group)
				ON ppccz.id_ppccz = educational_work.ppccz)
				ON spr_t_workew.id = educational_work.type_work)
				ON spr_t_teach_educational_activity.id = educational_work.type_activity)
				WHERE id_ew = :button_fill_edit AND id_employee = :id_employee ORDER BY id_ew");
			$result->bindParam(':button_fill_edit', $button_fill_edit);
			$result->bindParam(':id_employee', $this->id_employee);
			$result->execute();

			$row = $result->fetch();
			$this->ppccz = $row['id_ppccz'];
			$this->cipher_group = $row['id_cipher'];
			$this->type_activity = $row['id_ed_activ'];
			$this->type_work = $row['id_workew'];
			$this->sdate = $row['sdate'];
		}
	}

	public function worksEditExec()
	{
		$db = DataBase::getInstance()->getDb();
		$button_exec_edit = Helper::clean($_POST['button_exec_edit']);
		$this->id_employee = Auth::getInstance()->getAuth('id');

		if (is_numeric($button_exec_edit))
		{
			$result = $db->prepare("SELECT ppccz.cipher_specialty, spr_cipher_group.gname, spr_t_teach_educational_activity.ename, spr_t_workew.ew_name, 
				sdate, DATE_FORMAT(zdate,'%d.%m.%Y') zdate, report 
				FROM (spr_t_teach_educational_activity INNER JOIN
				(spr_t_workew INNER JOIN
				(ppccz INNER JOIN
				(spr_cipher_group INNER JOIN educational_work
				ON spr_cipher_group.id = educational_work.cipher_group)
				ON ppccz.id_ppccz = educational_work.ppccz)
				ON spr_t_workew.id = educational_work.type_work)
				ON spr_t_teach_educational_activity.id = educational_work.type_activity)
				WHERE id_ew = :button_exec_edit AND id_employee = :id_employee ORDER BY id_ew");
			$result->bindParam(':button_exec_edit', $button_exec_edit);
			$result->bindParam(':id_employee', $this->id_employee);
			$result->execute();

			$row = $result->fetch();
			$this->ppccz = $row['cipher_specialty'];
			$this->cipher_group = $row['gname'];
			$this->type_activity = $row['ename'];
			$this->type_work = $row['ew_name'];
			$this->sdate = $row['sdate'];
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
			$this->cipher_group = Helper::clean($_POST['edit_fill_data_table_form_cipher_group']);
			$this->type_activity = Helper::clean($_POST['edit_fill_data_table_form_type_activity']);
			$this->type_work = Helper::clean($_POST['edit_fill_data_table_form_type_work']);
			$this->sdate = Helper::clean($_POST['edit_fill_data_table_form_sdate']);

    		$result = $db->prepare("UPDATE educational_work SET ppccz = :ppccz, cipher_group = :cipher_group, type_activity = :type_activity, type_work = :type_work, sdate = :sdate WHERE id_ew = :edit_fill_data_table_form_update AND id_employee = :id_employee");

    		$result->bindParam(':ppccz', $this->ppccz);
    		$result->bindParam(':cipher_group', $this->cipher_group);
    		$result->bindParam(':type_activity', $this->type_activity);
    		$result->bindParam(':type_work', $this->type_work);
    		$result->bindParam(':sdate', $this->sdate);
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

			$result = $db->prepare("UPDATE educational_work SET report = :report WHERE id_ew = :edit_exec_data_table_form_update AND id_employee = :id_employee");
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

        	$result = $db->prepare("SELECT id,gname FROM spr_cipher_group WHERE ppccz = :ppccz");
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
		$this->cipher_group = Helper::clean($_POST['cipher_group']);
		$this->type_work = Helper::clean($_POST['type_work']);
		$this->type_activity = Helper::clean($_POST['type_activity']);
		$this->sdate = Helper::clean($_POST['sdate']);
		$this->id_academic_year = Auth::getInstance()->getAcademicYear();
		$this->id_employee = Auth::getInstance()->getAuth('id');

    	$result = $db->prepare("INSERT INTO educational_work (ppccz, cipher_group, type_work, type_activity, sdate, zdate, id_employee, id_academic_year)
    		VALUES (:ppccz, :cipher_group, :type_work, :type_activity, :sdate, CURDATE(), :id_employee, :id_academic_year)");
    	$result->bindParam(':ppccz', $this->ppccz);
    	$result->bindParam(':cipher_group', $this->cipher_group);
    	$result->bindParam(':type_work', $this->type_work);
    	$result->bindParam(':type_activity', $this->type_activity);
    	$result->bindParam(':sdate', $this->sdate);
    	$result->bindParam(':id_academic_year', $this->id_academic_year);
    	$result->bindParam(':id_employee', $this->id_employee);
    	$result->execute();

		echo true;
	}

	public function analysisEdit()
	{
		$db = DataBase::getInstance()->getDb();
		$button_edit = Helper::clean($_POST['button_edit']);

		if (is_numeric($button_edit))
		{
			$result = $db->prepare("SELECT ppccz.id_ppccz, spr_cipher_group.id AS id_cipher, spr_t_teach_educational_activity.id AS id_ed_activ, spr_t_workew.id AS id_workew, 
				sdate, DATE_FORMAT(zdate,'%d.%m.%Y') zdate, report 
				FROM (spr_t_teach_educational_activity INNER JOIN
				(spr_t_workew INNER JOIN
				(ppccz INNER JOIN
				(spr_cipher_group INNER JOIN educational_work
				ON spr_cipher_group.id = educational_work.cipher_group)
				ON ppccz.id_ppccz = educational_work.ppccz)
				ON spr_t_workew.id = educational_work.type_work)
				ON spr_t_teach_educational_activity.id = educational_work.type_activity)
				WHERE id_ew = :button_edit");
			$result->bindParam(':button_edit', $button_edit);
			$result->execute();

			$row = $result->fetch();
			$this->ppccz = $row['id_ppccz'];
			$this->cipher_group = $row['id_cipher'];
			$this->type_activity = $row['id_ed_activ'];
			$this->type_work = $row['id_workew'];
			$this->sdate = $row['sdate'];
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
			$this->cipher_group = Helper::clean($_POST['edit_data_table_form_cipher_group']);
			$this->type_activity = Helper::clean($_POST['edit_data_table_form_type_activity']);
			$this->type_work = Helper::clean($_POST['edit_data_table_form_type_work']);
			$this->sdate = Helper::clean($_POST['edit_data_table_form_sdate']);
			$this->report = Helper::clean($_POST['edit_data_table_form_report']);

    		$result = $db->prepare("UPDATE educational_work SET ppccz = :ppccz, cipher_group = :cipher_group, type_activity = :type_activity, type_work = :type_work, sdate = :sdate, report = :report WHERE id_ew = :edit_data_table_form_update");

    		$result->bindParam(':ppccz', $this->ppccz);
    		$result->bindParam(':cipher_group', $this->cipher_group);
    		$result->bindParam(':type_activity', $this->type_activity);
    		$result->bindParam(':type_work', $this->type_work);
    		$result->bindParam(':sdate', $this->sdate);
    		$result->bindParam(':report', $this->report);
    		$result->bindParam(':edit_data_table_form_update', $edit_data_table_form_update);
			$result->execute();
		}
	}

	public function worksAjaxDelete()
	{
		$db = DataBase::getInstance()->getDb();
		$this->id_employee = Auth::getInstance()->getAuth('id');
		$value = Helper::clean($_POST['value']);

		if (is_numeric($value))
		{
			$result = $db->prepare("DELETE FROM educational_work WHERE id_ew = :value AND id_employee = :id_employee");
			$result->bindParam(':value', $value);
			$result->bindParam(':id_employee', $this->id_employee);
			$result->execute();

			echo true;
		}
	}
}

?>