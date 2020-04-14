<?php

class WorkAnalysTable
{
	function __construct(){}

	protected function getWorkList($result, $pagination, $fields, $id, $urlEditFill, $urlEditExec, $isPrint = false)
	{
		$workList = array();

		$i = 0; // Счетчик записей
		$countFields = count($fields) + 2; // Количество полей с учетом порядкового номера и поля редактирования и удаления

		if (Auth::getInstance()->getTableWorksMode() == Auth::TABLE_WORKS_MODE_READ) // Если таблица в режмие чтения
			$countFields = count($fields) + 1; // Количество полей с учетом порядкового номера

		while ($row = $result->fetch()) 
		{
			$workList[$i] = '<tr><td>'.($pagination->getPagePosition()+$i+1).'</td>'; // Порядковый номер

			for ($j = 0; $j < count($fields); $j++) // Счетчик полей
			{
				$workList[$i] .= '<td>'.$row[$fields[$j]].'</td>'; // Поля на вывод
			}

			if (Auth::getInstance()->getTableWorksMode() == Auth::TABLE_WORKS_MODE_FILL && $isPrint == false) // Типы кнопок (завист от режима таблицы)
				$workList[$i] .= '<td><form style="display: inline-block" method="POST" action="'.$urlEditFill.'"><button value="'.$row[$id].'" class="table_button_edit" name="button_fill_edit"><img src="/web/images/table_button_edit.png" title="Редактировать"></button></form><button value="'.$row[$id].'" class="table_button_delete" name="button_delete"><img src="/web/images/table_button_delete.png" title="Удалить"></button></td></tr>';
			else if (Auth::getInstance()->getTableWorksMode() == Auth::TABLE_WORKS_MODE_FILL_EDIT && $isPrint == false)
				$workList[$i] .= '<td><form style="display: inline-block" method="POST" action="'.$urlEditFill.'"><button value="'.$row[$id].'" class="table_button_edit" name="button_fill_edit"><img src="/web/images/table_button_edit.png" title="Редактировать"></button></form></td></tr>';
			else if (Auth::getInstance()->getTableWorksMode() == Auth::TABLE_WORKS_MODE_EXEC && $isPrint == false)
				$workList[$i] .= '<td><form method="POST" action="'.$urlEditExec.'"><button value="'.$row[$id].'" class="table_button_edit" name="button_exec_edit"><img src="/web/images/table_button_exec.png" title="Выполнить"></button></form></td></tr>';
			else if (Auth::getInstance()->getTableWorksMode() == Auth::TABLE_WORKS_MODE_ADJUSTMENT && $isPrint == false)
			{
				if ($row['zdate'] == date("d.m.Y"))
				{
					$workList[$i] .= '<td><form style="display: inline-block" method="POST" action="'.$urlEditFill.'"><button value="'.$row[$id].'" class="table_button_edit" name="button_fill_edit"><img src="/web/images/table_button_edit.png" title="Редактировать"></button></form></td></tr>';
				}
				else
				{
					$workList[$i] .= '<td></td></tr>';
				}
			}
			else if (Auth::getInstance()->getTableWorksMode() == Auth::TABLE_WORKS_MODE_READ && $isPrint == false)
				$workList[$i] .= '</tr>';
			else if ($isPrint == true)
				$workList[$i] .= '</tr>';

			echo $workList[$i];
			$i++;
		}

		if ($isPrint == false)
			echo $pagination->render($countFields);
	}
	protected function getWorkExcelList($writer, $result, $fields)
	{
		$workList = array();
		$i = 0; // Счетчик записей
		$ordinal = 0;

		array_unshift($fields,'ordinal');

		while ($row = $result->fetch()) 
		{
			$ordinal++;

			for ($j = 0; $j < count($fields); $j++)
			{
				if ($fields[$j] == 'ordinal')
					$workList[$i][$j] = $ordinal;
				else
					$workList[$i][$j] = $row[$fields[$j]];
			}

			$writer->writeSheetRow('Лист 1', $workList[$i], $row_options = array('halign'=>'center','valign'=>'center','border'=>'left,right,top,bottom','border-style'=>'thin','wrap_text'=>true,'font'=>'Times New Roman','font-size'=>'12'));

			$i++;
		}
	}
    // Функция получения прав о текущем пользователе для правильного отображения фильтрации
	protected function getAnalysisAuthRules($id,$sql)
	{
		$db = DataBase::getInstance()->getDb();
		$filter = "";

		if (Auth::getInstance()->compareRules(Auth::CHAIR_PK_PKS))
		{
			$pk_pkc = Auth::getInstance()->getAuth('id_pk_pkc');
			$filter .= "AND employee.id_pk_pkc = '$pk_pkc' ";
		}
		else if (Auth::getInstance()->compareRules(Auth::HEAD_DEPARTMENT))
		{
			$id_department = Auth::getInstance()->getAuth('id_department');
			$filter .= "AND employee.id_department = '$id_department' ";
		}
		else if (Auth::getInstance()->compareRules(Auth::DEP_UPR))
		{
			$result = $db->prepare("SELECT id FROM spr_posts WHERE post_name = 'мастер производственного обучения'");
			$result->execute();
			$row = $result->fetch();
			$filter .= "AND employee.id_posts = $row[id] ";
		}

		return $this->getAnalysisCollectRules($filter,$id,$sql);
	}
	// Сбор правил фильтрации
	private function getAnalysisCollectRules($filter,$id = array(),$sql = array())
	{
		for ($i = 0; $i < count($id); $i++)
		{
			if (isset($_POST[$id[$i]]))
			{
				$this->value = Helper::clean($_POST[$id[$i]]);

				if ($this->value == '')
				{
					Auth::getInstance()->resetTableAnalysisFilter($id[$i]);
				}
				else
				{
					Auth::getInstance()->setTableAnalysisFilter($id[$i], $this->value);
					$filter .= $sql[$i]." = '$this->value' ";
				}
			}
			else
			{
				if (Auth::getInstance()->isExsistsTableAnalysisFilter($id[$i])) // Если существует сохран фильтр
				{
					$this->value = Auth::getInstance()->getTableAnalysisFilter($id[$i]);
					$filter .= $sql[$i]." = '$this->value' ";
				}
			}
		}

		return $filter;
	}

	public function getWorkTableMode()
	{
		if (Auth::getInstance()->getTableWorksMode() == Auth::TABLE_WORKS_MODE_FILL)
		{
			echo ' - Заполнение индивидуального плана';
		}
		else if (Auth::getInstance()->getTableWorksMode() == Auth::TABLE_WORKS_MODE_FILL_EDIT)
		{
			echo ' - Заполнение индивидуального плана (только редактирование)';
		}
		else if(Auth::getInstance()->getTableWorksMode() == Auth::TABLE_WORKS_MODE_EXEC)
		{
			echo ' - Выполнение индивидуального плана';
		}
		else if (Auth::getInstance()->getTableWorksMode() == Auth::TABLE_WORKS_MODE_ADJUSTMENT)
		{
			echo ' - Корректировка индивидуального плана';
		}
		else
		{
			echo '';
		}
	}
	// Выпадающий список преподавателей для фильтрации с учетом прав
	public function dropDownEmployee()
	{
		$db = DataBase::getInstance()->getDb();
		$result = "";

		if (Auth::getInstance()->compareRules(Auth::DEP_DIRECTOR) || 
			Auth::getInstance()->compareRules(Auth::DIRECTOR) || 
			Auth::getInstance()->compareRules(Auth::HEAD_UMC))
		{
			$result = $db->prepare("SELECT id,surname,name,patronymic FROM employee ORDER BY surname");
			$result->execute();
		}
		else if (Auth::getInstance()->compareRules(Auth::DEP_UPR))
		{
			$result1 = $db->prepare("SELECT id FROM spr_posts WHERE post_name = 'мастер производственного обучения'");
			$result1->execute();
			$row = $result1->fetch();

			$result = $db->prepare("SELECT id,surname,name,patronymic FROM employee WHERE id_posts = :id_posts ORDER BY surname");
			$result->bindParam(':id_posts', $row['id']);
			$result->execute();
		}
		else if (Auth::getInstance()->compareRules(Auth::HEAD_DEPARTMENT))
		{
			$id_department = Auth::getInstance()->getAuth('id_department');
			$result = $db->prepare("SELECT id,surname,name,patronymic FROM employee WHERE id_department = :id_department ORDER BY surname");
			$result->bindParam(':id_department', $id_department);
			$result->execute();
		}
		else if (Auth::getInstance()->compareRules(Auth::CHAIR_PK_PKS))
		{
			$id_pk_pkc = Auth::getInstance()->getAuth('id_pk_pkc');
			$result = $db->prepare("SELECT id,surname,name,patronymic FROM employee WHERE id_pk_pkc = :id_pk_pkc ORDER BY surname");
			$result->bindParam(':id_pk_pkc', $id_pk_pkc);
			$result->execute();			
		}

		echo "<select id='filtration_form_employee' name='filtration_form_employee'>
			  <option value=''></option>";

		while ($row = $result->fetch())
		{
			if (Auth::getInstance()->getTableAnalysisFilter('employee') == $row['id'])
				echo "<option value='".$row['id']."' selected>".$row['surname'].' '.$row['name'].' '.$row['patronymic']."</option>";
			else
				echo "<option value='".$row['id']."'>".$row['surname'].' '.$row['name'].' '.$row['patronymic']."</option>";
		}

		echo "</select>";
	}
	// Формаирование таблицы для Фильтрации
	protected function getAnalysisList($result, $pagination, $fields, $id, $urlEdit, $isPrint = false)
	{
		$workList = array();

		$i = 0; // Счетчик записей

		$ordinal = 0; // Порядковый номер
		$curSurname = '';
		$curName = '';
		$curPatronymic = '';
		$curFIO = '';
		$countFields = 0;

		if (Auth::getInstance()->compareRules(Auth::HEAD_UMC) && $isPrint == false) // Если таблица отображется заведующему УМЧ
			$countFields = count($fields) + 2; // Количество полей с учетом порядкового номера и поля редактирования и удаления
		else
			$countFields = count($fields) + 1; // Количество полей с учетом порядкового номера

		while ($row = $result->fetch())
		{
			if ($curSurname != $row['employee_surname'] || $curName != $row['employee_name'] || $curPatronymic != $row['employee_patronymic'])
			{
				$curSurname = $row['employee_surname'];
				$curName = $row['employee_name'];
				$curPatronymic = $row['employee_patronymic'];
				$curFIO = $curSurname.' '.$curName.' '.$curPatronymic;
				$ordinal = $pagination->getPagePosition()+$i+1;
				$workList[$i] = '<tr><td colspan="'.$countFields.'"><b>'.$curFIO.'</b></td></tr>
				<tr><td>'.$ordinal.'</td>';
			}
			else if ($curSurname == $row['employee_surname'] && $curName == $row['employee_name'] && $curPatronymic == $row['employee_patronymic'])
			{
				$workList[$i] = '<tr><td>'.($pagination->getPagePosition()+$i+1).'</td>';
			}

			for ($j = 0; $j < count($fields); $j++) // Счетчик полей
			{
				$workList[$i] .= '<td>'.$row[$fields[$j]].'</td>'; // Поля на вывод
			}

			if (Auth::getInstance()->compareRules(Auth::HEAD_UMC) && $isPrint == false) // Если заведующий УМЧ (Редактирование и удаление)
			{
				$workList[$i] .= '<td><form style="display: inline-block" method="POST" action="'.$urlEdit.'"><button value="'.$row[$id].'" class="table_button_edit" name="button_edit"><img src="/web/images/table_button_edit.png" title="Редактировать"></button></form><button value="'.$row[$id].'" class="table_button_delete" name="button_delete"><img src="/web/images/table_button_delete.png" title="Удалить"></button></td></tr>';
			}
			else
			{
				$workList[$i] .= '</tr>';
			}

			echo $workList[$i];
			$i++;
		}

		if ($isPrint == false)
			echo $pagination->render($countFields);
	}
	// Формаирование таблицы для Excel
	protected function getAnalysisExcelList($writer, $result, $fields, $tableName)
	{
		$workList = array();
		$i = 0; // Счетчик записей
		$l = 4; // Индекс объединения имени сотрудника
		$ordinal = 0;
		$curSurname = '';
		$curName = '';
		$curPatronymic = '';
		$curFIO = '';

		array_unshift($fields,'ordinal');

		while ($row = $result->fetch())
		{
			$ordinal++;

			if ($curSurname != $row['employee_surname'] || $curName != $row['employee_name'] || $curPatronymic != $row['employee_patronymic'])
			{
				$curSurname = $row['employee_surname'];
				$curName = $row['employee_name'];
				$curPatronymic = $row['employee_patronymic'];
				$curFIO = $curSurname.' '.$curName.' '.$curPatronymic;
				$curFIO = array($curFIO);

				$writer->writeSheetRow($tableName, $curFIO, $row_options = array('halign'=>'center','valign'=>'center','border'=>'left,right,top,bottom','border-style'=>'thin', 'font-style'=> 'bold','font'=>'Times New Roman','font-size'=>'12'));
				$writer->markMergedCell($tableName, $start_row=$l, $start_col=0, $end_row=$l, $end_col=(count($fields)-1));
				$l++;
				$ordinal = 1;
			}

			for ($j = 0; $j < count($fields); $j++)
			{
				if ($fields[$j] == 'ordinal')
					$workList[$i][$j] = $ordinal;
				else
					$workList[$i][$j] = $row[$fields[$j]];
			}

			$writer->writeSheetRow($tableName, $workList[$i], $row_options = array('halign'=>'center','valign'=>'center','border'=>'left,right,top,bottom','border-style'=>'thin','wrap_text'=>true,'font'=>'Times New Roman','font-size'=>'12'));

			$i++;
			$l++;
		}
	}
}

?>