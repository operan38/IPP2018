<?php

class AdminTable
{
	function __construct(){}

	protected function getAdminList($result, $pagination, $fields, $id, $urlEdit)
	{
		$workList = array();

		$i = 0; // Счетчик записей
		$j = 0; // Счетчик полей
		$countFields = count($fields) + 2; // Количество полей с учетом порядкового номера и поля редактирования и удаления

		while ($row = $result->fetch()) 
		{
			$workList[$i] = '<tr><td>'.($pagination->getPagePosition()+$i+1).'</td>'; // Порядковый номер
			while ($j < count($fields))
			{
				$workList[$i] .= '<td>'.$row[$fields[$j]].'</td>'; // Поля на вывод
				$j++;
			}

			$workList[$i] .= '<td><form style="display: inline-block" method="POST" action="'.$urlEdit.'"><button value="'.$row[$id].'" class="table_button_edit" name="button_edit" title="Редактировать"><img src="/web/images/table_button_edit.png"></button></form><button value="'.$row[$id].'" class="table_button_delete" name="button_delete"><img src="/web/images/table_button_delete.png" title="Удалить"></button></td></tr>';

			echo $workList[$i];
			$j = 0;
			$i++;
		}

		echo $pagination->render($countFields);
	}

	protected function getAdminVarList($result, $pagination, $fieldsQuery, $fieldsVar, $idVar, $id, $urlEdit, $isDel = true, $isEdit = true)
	{
		$workList = array();

		$i = 0; // Счетчик записей
		$j = 0; // Счетчик полей запроса
		$l = 0; // Счетчик полей переменной
		$countFields = count($fieldsQuery) + count($idVar) + 2; // Количество полей с учетом порядкового номера и поля редактирования и удаления

		while ($row = $result->fetch()) 
		{
			$workList[$i] = '<tr><td>'.($pagination->getPagePosition()+$i+1).'</td>'; // Порядковый номер
			while ($j < count($fieldsQuery))
			{
				$workList[$i] .= '<td>'.$row[$fieldsQuery[$j]].'</td>'; // Поля на вывод
				$j++;
			}
			while ($l < count($idVar))
			{
				$workList[$i] .= '<td>'.$fieldsVar[$row[$idVar]].'</td>'; // Поля на вывод
				$l++;
			}

			$workList[$i] .= '<td>';

			if ($isEdit)
				$workList[$i] .= '<form style="display: inline-block" method="POST" action="'.$urlEdit.'"><button value="'.$row[$id].'" class="table_button_edit" name="button_edit" title="Редактировать"><img src="/web/images/table_button_edit.png"></button></form>';

			if ($isDel)
				$workList[$i] .= '<button value="'.$row[$id].'" class="table_button_delete" name="button_delete"><img src="/web/images/table_button_delete.png" title="Удалить"></button>';

			$workList[$i] .= '</td></tr>';

			echo $workList[$i];
			$j = 0;
			$l = 0;
			$i++;
		}

		echo $pagination->render($countFields);
	}
}

?>