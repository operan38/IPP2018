<?php

class TrainingProductionWork extends WorkAnalysTable
{
	public $id_tpw;
	public $ppccz;
	//public $id_foundation;
	public $placement;
	public $type_activities;
	public $type_upd;
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
			$pagination->init("SELECT COUNT(*) FROM training_production_work WHERE id_academic_year = :id_academic_year AND id_employee = :id_employee");

			$result = $db->prepare("SELECT id_tpw, ppccz.cipher_specialty, spr_placement.pname, spr_t_activities.name, spr_t_upd.uname, sdate, DATE_FORMAT(zdate,'%d.%m.%Y') zdate, report
			          FROM (ppccz INNER JOIN 
			          (spr_t_activities INNER JOIN
			          (spr_t_upd INNER JOIN
			          (spr_placement INNER JOIN training_production_work
			           ON spr_placement.id = training_production_work.placement)
					   ON spr_t_upd.id = training_production_work.type_upd)
			           ON spr_t_activities.id = training_production_work.type_activities) 
			           ON ppccz.id_ppccz = training_production_work.ppccz)
			           WHERE id_employee = :id_employee AND id_academic_year = :id_academic_year ORDER BY id_tpw LIMIT :page_position,:item_per_page");

			$result->bindParam(':id_academic_year', $this->id_academic_year);
			$result->bindParam(':id_employee', $this->id_employee);
			$result->bindValue(':page_position', $pagination->getPagePosition(), PDO::PARAM_INT);
			$result->bindValue(':item_per_page', $pagination->getItemPerPage(), PDO::PARAM_INT);
			$result->execute();
		}
		else
		{
			$result = $db->prepare("SELECT id_tpw, ppccz.cipher_specialty, spr_placement.pname, spr_t_activities.name, spr_t_upd.uname, sdate, DATE_FORMAT(zdate,'%d.%m.%Y') zdate, report
			          FROM (ppccz INNER JOIN 
			          (spr_t_activities INNER JOIN
			          (spr_t_upd INNER JOIN
			          (spr_placement INNER JOIN training_production_work
			           ON spr_placement.id = training_production_work.placement)
					   ON spr_t_upd.id = training_production_work.type_upd)
			           ON spr_t_activities.id = training_production_work.type_activities) 
			           ON ppccz.id_ppccz = training_production_work.ppccz)
			           WHERE id_employee = :id_employee AND id_academic_year = :id_academic_year ORDER BY id_tpw");

			$result->bindParam(':id_academic_year', $this->id_academic_year);
			$result->bindParam(':id_employee', $this->id_employee);
			$result->execute();	
		}

		$this->getWorkList($result, $pagination, array('cipher_specialty', 'pname', 'name', 'uname', 'sdate', 'report'), 'id_tpw', '/tpw/works-edit-fill', '/tpw/works-edit-exec', $isPrint);
	}

	public function worksExportExcel()
	{
		$db = DataBase::getInstance()->getDb();
		$this->id_academic_year = Auth::getInstance()->getAcademicYear();;
		$this->id_employee = Auth::getInstance()->getAuth('id');

		$rows = array(
    		array('№', 'Специальность', 'Место размещения', 'Вид деятельности', 'Вид УПД', 'Дата', 'Результат'),
    		array('','','','','','','')
		);

		$result = $db->prepare("SELECT id_tpw, ppccz.cipher_specialty, spr_placement.pname, spr_t_activities.name, spr_t_upd.uname, sdate, DATE_FORMAT(zdate,'%d.%m.%Y') zdate, report
			          FROM (ppccz INNER JOIN 
			          (spr_t_activities INNER JOIN
			          (spr_t_upd INNER JOIN
			          (spr_placement INNER JOIN training_production_work
			           ON spr_placement.id = training_production_work.placement)
					   ON spr_t_upd.id = training_production_work.type_upd)
			           ON spr_t_activities.id = training_production_work.type_activities) 
			           ON ppccz.id_ppccz = training_production_work.ppccz)
			           WHERE id_employee = :id_employee AND id_academic_year = :id_academic_year ORDER BY id_tpw");

		$result->bindParam(':id_academic_year', $this->id_academic_year);
		$result->bindParam(':id_employee', $this->id_employee);
		$result->execute();	

		$writer = new XLSXWriter();
		$writer->sendHeader('Учебно-производственная_работа('.Auth::getInstance()->getName().'-'.$this->id_academic_year.').xlsx');
		$writer->setAuthor('User');
		$writer->writeSheetHeader('Лист 1', $header_types = array('string','string','string','string','string','string','string'), $col_options = array('widths'=>[4,16,15,25,25,25,30]));

		$writer->writeSheetRow('Лист 1', array('Учебно-производственная работа','','','','','',''), $row_options = array('halign'=>'center','valign'=>'center','font-style'=> 'bold','font'=>'Times New Roman','font-size'=>'14'));

		foreach ($rows as $row)
			$writer->writeSheetRow('Лист 1', $row, $row_options = array('halign'=>'center','valign'=>'center','border'=>'left,right,top,bottom','border-style'=>'thin','wrap_text'=>true,'font'=>'Times New Roman','font-size'=>'12'));

		$this->getWorkExcelList($writer, $result, array('cipher_specialty', 'pname', 'name', 'uname', 'sdate', 'report'));

		$writer->markMergedCell('Лист 1', $start_row=1, $start_col=0, $end_row=1, $end_col=6); //

		$writer->markMergedCell('Лист 1', $start_row=2, $start_col=0, $end_row=3, $end_col=0); // Объединение ячеек №
		$writer->markMergedCell('Лист 1', $start_row=2, $start_col=1, $end_row=3, $end_col=1); // Объединение ячеек Cпециальность
		$writer->markMergedCell('Лист 1', $start_row=2, $start_col=2, $end_row=3, $end_col=2); // Объединение ячеек Место размещения
		$writer->markMergedCell('Лист 1', $start_row=2, $start_col=3, $end_row=3, $end_col=3); // Объединение ячеек Вид деятельности
		$writer->markMergedCell('Лист 1', $start_row=2, $start_col=4, $end_row=3, $end_col=4); // Объединение ячеек Вид УПД
		$writer->markMergedCell('Лист 1', $start_row=2, $start_col=5, $end_row=3, $end_col=5); // Объединение ячеек Дата
		$writer->markMergedCell('Лист 1', $start_row=2, $start_col=6, $end_row=3, $end_col=6); // Объединение ячеек Результат

		$writer->writeToStdOut();
	}

	public function getAnalysisTable($isPrint = false)
	{
		$db = DataBase::getInstance()->getDb();
		$filter = $this->getAnalysisAuthRules(array('employee','ppccz','placement','type_activities','type_upd'),array('AND training_production_work.id_employee','AND training_production_work.ppccz','AND training_production_work.placement','AND training_production_work.type_activities','AND training_production_work.type_upd')); // Функция получения прав о текущем пользователе для правильного отображения фильтрации

		$this->id_academic_year = Auth::getInstance()->getAcademicYear();
		$pagination = new Pagination;
		$pagination->setParam([':id_academic_year' => $this->id_academic_year]);
		$pagination->init("SELECT COUNT(*) FROM training_production_work INNER JOIN employee ON employee.id = training_production_work.id_employee WHERE id_academic_year = :id_academic_year $filter");
		$limit = "";
		if ($isPrint == false)
			$limit = "LIMIT ".$pagination->getPagePosition().", ".$pagination->getItemPerPage();

		$result = $db->prepare("SELECT id_tpw, employee.surname AS employee_surname, employee.name AS employee_name, employee.patronymic AS employee_patronymic, ppccz.cipher_specialty, spr_placement.pname, spr_t_activities.name, spr_t_upd.uname, sdate, DATE_FORMAT(zdate,'%d.%m.%Y') zdate, report
			          FROM (ppccz INNER JOIN 
			          (spr_t_activities INNER JOIN
			          (spr_t_upd INNER JOIN
			          (employee INNER JOIN
			          (spr_placement INNER JOIN training_production_work
			           ON spr_placement.id = training_production_work.placement)
			           ON employee.id = training_production_work.id_employee)
					   ON spr_t_upd.id = training_production_work.type_upd)
			           ON spr_t_activities.id = training_production_work.type_activities) 
			           ON ppccz.id_ppccz = training_production_work.ppccz)
			           WHERE id_academic_year = :id_academic_year $filter ORDER BY employee.surname, employee.name, employee.patronymic $limit");

		$result->bindParam(':id_academic_year', $this->id_academic_year);
		$result->execute();

		$this->getAnalysisList($result, $pagination, array('cipher_specialty', 'pname', 'name', 'uname', 'sdate', 'report', 'zdate'), 'id_tpw', '/tpw/analysis-edit', $isPrint);
	}

	public function analysisExportExcel()
	{
		$db = DataBase::getInstance()->getDb();
		$filter = $this->getAnalysisAuthRules(array('employee','ppccz','placement','type_activities','type_upd'),array('AND training_production_work.id_employee','AND training_production_work.ppccz','AND training_production_work.placement','AND training_production_work.type_activities','AND training_production_work.type_upd')); // Функция получения прав о текущем пользователе для правильного отображения фильтрации

		$this->id_academic_year = Auth::getInstance()->getAcademicYear();

		$rows = array(
    		array('№', 'Специальность', 'Место размещения', 'Вид деятельности', 'Вид УПД', 'Дата', 'Результат', 'Дата записи', 'Сотрудник'),
    		array('','','','','','','','','')
		);

		$result = $db->prepare("SELECT CONCAT(employee.surname,' ',Left(employee.name,1),'.',Left(employee.patronymic,1),'.') as FIO, employee.surname AS employee_surname, employee.name AS employee_name, employee.patronymic AS employee_patronymic, ppccz.cipher_specialty, spr_placement.pname, spr_t_activities.name, spr_t_upd.uname, sdate, DATE_FORMAT(zdate,'%d.%m.%Y') zdate, report
			          FROM (ppccz INNER JOIN 
			          (spr_t_activities INNER JOIN
			          (spr_t_upd INNER JOIN
			          (employee INNER JOIN
			          (spr_placement INNER JOIN training_production_work
			           ON spr_placement.id = training_production_work.placement)
			           ON employee.id = training_production_work.id_employee)
					   ON spr_t_upd.id = training_production_work.type_upd)
			           ON spr_t_activities.id = training_production_work.type_activities) 
			           ON ppccz.id_ppccz = training_production_work.ppccz)
			           WHERE id_academic_year = :id_academic_year $filter ORDER BY employee.surname, employee.name, employee.patronymic");

		$result->bindParam(':id_academic_year', $this->id_academic_year);
		$result->execute();

		$writer = new XLSXWriter();
		$writer->sendHeader('Учебно-производственная_работа('.$this->id_academic_year.').xlsx');
		$writer->setAuthor('User');
		$writer->writeSheetHeader('Лист 1', $header_types = array('string','string','string','string','string','string','string','string','string'), $col_options = array('widths'=>[4,16,15,25,25,25,30,15,15]));

		$writer->writeSheetRow('Лист 1', array('Учебно-производственная работа','','','','','','','',''), $row_options = array('halign'=>'center','valign'=>'center','font-style'=> 'bold','font'=>'Times New Roman','font-size'=>'14'));

		foreach ($rows as $row)
			$writer->writeSheetRow('Лист 1', $row, $row_options = array('halign'=>'center','valign'=>'center','border'=>'left,right,top,bottom','border-style'=>'thin','wrap_text'=>true,'font'=>'Times New Roman','font-size'=>'12'));

		$this->getAnalysisExcelList($writer, $result, array('cipher_specialty', 'pname', 'name', 'uname', 'sdate', 'report', 'zdate', 'FIO'), 'Лист 1');

		$writer->markMergedCell('Лист 1', $start_row=1, $start_col=0, $end_row=1, $end_col=8); //

		$writer->markMergedCell('Лист 1', $start_row=2, $start_col=0, $end_row=3, $end_col=0); // Объединение ячеек №
		$writer->markMergedCell('Лист 1', $start_row=2, $start_col=1, $end_row=3, $end_col=1); // Объединение ячеек Cпециальность
		$writer->markMergedCell('Лист 1', $start_row=2, $start_col=2, $end_row=3, $end_col=2); // Объединение ячеек Место размещения
		$writer->markMergedCell('Лист 1', $start_row=2, $start_col=3, $end_row=3, $end_col=3); // Объединение ячеек Вид деятельности
		$writer->markMergedCell('Лист 1', $start_row=2, $start_col=4, $end_row=3, $end_col=4); // Объединение ячеек Вид УПД
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
			$result = $db->prepare("SELECT ppccz.id_ppccz, spr_placement.id AS id_placement, spr_t_activities.id AS id_activ, spr_t_upd.id AS id_upd, sdate
			        FROM (ppccz INNER JOIN 
			        (spr_t_activities INNER JOIN
			        (spr_t_upd INNER JOIN
			        (spr_placement INNER JOIN training_production_work
			        ON spr_placement.id = training_production_work.placement)
					ON spr_t_upd.id = training_production_work.type_upd)
			        ON spr_t_activities.id = training_production_work.type_activities) 
			        ON ppccz.id_ppccz = training_production_work.ppccz) WHERE id_tpw = :button_fill_edit AND id_employee = :id_employee");

			$result->bindParam(':button_fill_edit', $button_fill_edit);
			$result->bindParam(':id_employee', $this->id_employee);
			$result->execute();

			$row = $result->fetch();
			$this->ppccz = $row['id_ppccz'];
			$this->placement = $row['id_placement'];
			$this->type_activities = $row['id_activ'];
			$this->type_upd = $row['id_upd'];
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
			$result = $db->prepare("SELECT ppccz.cipher_specialty, spr_placement.pname, spr_t_activities.name, spr_t_upd.uname, sdate
			        FROM (ppccz INNER JOIN 
			        (spr_t_activities INNER JOIN
			        (spr_t_upd INNER JOIN
			        (spr_placement INNER JOIN training_production_work
			        ON spr_placement.id = training_production_work.placement)
					ON spr_t_upd.id = training_production_work.type_upd)
			        ON spr_t_activities.id = training_production_work.type_activities) 
			        ON ppccz.id_ppccz = training_production_work.ppccz) WHERE id_tpw = :button_exec_edit AND id_employee = :id_employee");

			$result->bindParam(':button_exec_edit', $button_exec_edit);
			$result->bindParam(':id_employee', $this->id_employee);
			$result->execute();

			$row = $result->fetch();
			$this->ppccz = $row['cipher_specialty'];
			$this->placement = $row['pname'];
			$this->type_activities = $row['name'];
			$this->type_upd = $row['uname'];
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
			$this->placement = Helper::clean($_POST['edit_fill_data_table_form_placement']);
			$this->type_activities =  Helper::clean($_POST['edit_fill_data_table_form_type_activities']);
			$this->type_upd = Helper::clean($_POST['edit_fill_data_table_form_type_upd']);
			$this->sdate = Helper::clean($_POST['edit_fill_data_table_form_sdate']);


			$result = $db->prepare("UPDATE training_production_work SET ppccz = :ppccz, placement = :placement, type_activities = :type_activities, type_upd = :type_upd, sdate = :sdate WHERE id_tpw = :edit_fill_data_table_form_update AND id_employee = :id_employee");

			$result->bindParam(':ppccz', $this->ppccz);
    		$result->bindParam(':placement', $this->placement);
    		$result->bindParam(':type_activities', $this->type_activities);
    		$result->bindParam(':type_upd', $this->type_upd);
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

			$result = $db->prepare("UPDATE training_production_work SET report = :report WHERE id_tpw = :edit_exec_data_table_form_update AND id_employee = :id_employee");
			$result->bindParam(':edit_exec_data_table_form_update', $edit_exec_data_table_form_update);
			$result->bindParam(':id_employee', $this->id_employee);
    		$result->bindParam(':report', $this->report);
    		$result->execute();
		}
	}

	/*public function getAjaxDataTable()
	{
		$db = DataBase::getInstance()->getDb();
		$result = "";

		if (isset($_POST['ppccz']))
		{
			$this->ppccz = Helper::clean($_POST['ppccz']);

			$result = $db->prepare("SELECT foundation_offices.id,spr_t_cabinet.cname,foundation_offices.oname FROM foundation_offices INNER JOIN spr_t_cabinet ON foundation_offices.id_t_cabinet = spr_t_cabinet.id WHERE ppccz = :ppccz");
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
	}*/

	public function worksAjaxInsert()
	{
		$db = DataBase::getInstance()->getDb();
		$this->ppccz = Helper::clean($_POST['ppccz']);
		$this->placement = Helper::clean($_POST['placement']);
		$this->type_activities = Helper::clean($_POST['type_activities']);
		$this->type_upd = Helper::clean($_POST['type_upd']);
		$this->sdate = Helper::clean($_POST['sdate']);
		$this->id_academic_year = Auth::getInstance()->getAcademicYear();
		$this->id_employee = Auth::getInstance()->getAuth('id');

		$result = $db->prepare("INSERT INTO training_production_work (ppccz, placement, type_activities, type_upd, sdate, zdate, id_employee, id_academic_year) 
		VALUES(:ppccz, :placement, :type_activities, :type_upd, :sdate, CURDATE(), :id_employee, :id_academic_year)");

		$result->bindParam(':ppccz', $this->ppccz);
		$result->bindParam(':placement', $this->placement);
		$result->bindParam(':type_activities', $this->type_activities);
		$result->bindParam(':type_upd', $this->type_upd);
		$result->bindParam(':sdate', $this->sdate);
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
			$result = $db->prepare("DELETE FROM training_production_work WHERE id_tpw = :value AND id_employee = :id_employee");
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
			$result = $db->prepare("SELECT ppccz.id_ppccz, spr_placement.pname, spr_t_activities.id AS id_activ, spr_t_upd.id AS id_upd, sdate, report
			        FROM (ppccz INNER JOIN 
			        (spr_t_activities INNER JOIN
			        (spr_t_upd INNER JOIN
			        (spr_placement INNER JOIN training_production_work
			        ON spr_placement.id = training_production_work.placement)
					ON spr_t_upd.id = training_production_work.type_upd)
			        ON spr_t_activities.id = training_production_work.type_activities) 
			        ON ppccz.id_ppccz = training_production_work.ppccz) WHERE id_tpw = :button_edit");

			$result->bindParam(':button_edit', $button_edit);
			$result->execute();

			$row = $result->fetch();
			$this->ppccz = $row['id_ppccz'];
			$this->placement = $row['pname'];
			$this->type_activities = $row['id_activ'];
			$this->type_upd = $row['id_upd'];
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
			$this->placement = Helper::clean($_POST['edit_data_table_form_placement']);
			$this->type_activities = Helper::clean($_POST['edit_data_table_form_type_activities']);
			$this->type_upd = Helper::clean($_POST['edit_data_table_form_type_upd']);
			$this->sdate = Helper::clean($_POST['edit_data_table_form_sdate']);
			$this->report = Helper::clean($_POST['edit_data_table_form_report']);

			$result = $db->prepare("UPDATE training_production_work SET ppccz = :ppccz, placement = :placement, type_activities = :type_activities, type_upd = :type_upd, sdate = :sdate, report = :report WHERE id_tpw = :edit_data_table_form_update");

			$result->bindParam(':ppccz', $this->ppccz);
    		$result->bindParam(':placement', $this->placement);
    		$result->bindParam(':type_activities', $this->type_activities);
    		$result->bindParam(':type_upd', $this->type_upd);
    		$result->bindParam(':sdate', $this->sdate);
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
			$result = $db->prepare("DELETE FROM training_production_work WHERE id_tpw = :value");
			$result->bindParam(':value', $value);
			$result->execute();

			echo true;
		}
	}
}

?>