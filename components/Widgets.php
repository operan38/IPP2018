<?php

class Widgets 
{
	// Выпадающий список с массивом
	public static function dropDownSimple($data,$name,$select = -1,$offset = 0)
	{
		echo "<select id='".$name."' name='".$name."'>
			  <option value=''></option>";

		for ($i = 0+$offset; $i < count($data)+$offset; $i++)
		{
			if ($select == $i)
				echo "<option value='".$i."' selected>".$data[$i]."</option>";
			else
				echo "<option value='".$i."'>".$data[$i]."</option>";
		}

		echo "</select>";
	}

	// Выпадающий список с использованием базы данных
	public static function dropDownList($sql,$fieldId,$fieldData,$name,$fieldForeignData = array())
	{
		$db = DataBase::getInstance()->getDb();
		$result = $db->prepare($sql);

		if (!empty($fieldForeignData)) // Внешние параметры
			$result->execute($fieldForeignData);
		else
			$result->execute();

		$result->execute();

		if ($result->rowCount() > 0)
			echo "<select id='".$name."' name='".$name."'>
			  <option value=''></option>";
		else
			echo "<select id='".$name."' name='".$name."' disabled>";

		while ($row = $result->fetch())
		{
			if (is_array($fieldData)) // Если массив с данными
			{
				$data = "";
				for ($i = 0; $i < count($fieldData); $i++)
					$data .= $row[$fieldData[$i]] . ' ';

				echo "<option value='".$row[$fieldId]."'>".$data."</option>";
			}
			else
			{
				echo "<option value='".$row[$fieldId]."'>".$row[$fieldData]."</option>";
			}
		}

		echo "</select>";		
	}

	// Выпадающий список с использованием базы данных и зарнее выбранный ID
	public static function dropDownSelectIdList($sql,$fieldId,$fieldData,$fieldSelectData,$name,$isEmpty = false,$fieldForeignData = array())
	{
		$db = DataBase::getInstance()->getDb();
		$result = $db->prepare($sql);

		if (!empty($fieldForeignData)) // Внешние параметры
			$result->execute($fieldForeignData);
		else
			$result->execute();

		echo "<select id='".$name."' name='".$name."'>";

		if ($isEmpty)
			echo "<option value=''></option>";

		while ($row = $result->fetch())
		{
			if (is_array($fieldData)) // Если массив с данными
			{
				$data = "";
				for ($i = 0; $i < count($fieldData); $i++)
					$data .= $row[$fieldData[$i]] . ' ';

				if ($fieldSelectData == $row[$fieldId])
					echo "<option value='".$row[$fieldId]."' selected>".$data."</option>";
				else
					echo "<option value='".$row[$fieldId]."'>".$data."</option>";
			}
			else
			{
				if ($fieldSelectData == $row[$fieldId])
					echo "<option value='".$row[$fieldId]."' selected>".$row[$fieldData]."</option>";
				else
					echo "<option value='".$row[$fieldId]."'>".$row[$fieldData]."</option>";
			}
		}

		echo "</select>";
	}

	// Форма удаления записи
	public static function dialogBoxConfirmDelete()
	{
		echo '<div id="back_dialog"></div>
		<div id="dialog_box_confirm">
		<div class="dialog_box_confirm_right_line"></div>
		<div class="dialog_box_confirm_left_line"></div>
    	<div class="dialog_box_confirm_title">
        	<span>Удаление записи</span>
    	</div>
		<div class="dialog_box_confirm_content">
			Вы уверены что хотите удалить выбранную запись?
		</div>
	
		<div class="dialog_box_confirm_footer">
			<button type="button" id="dialog_box_confirm_ok" name="dialog_box_confirm_ok">Да</button>
			<button type="button" id="dialog_box_confirm_cancel" name="dialog_box_confirm_cancel">Нет</button>
		</div>
		</div>';
	}

	// Форма выбора акдемического года на Титульном листе
	public static function academicYearTitleForm()
	{
		$db = DataBase::getInstance()->getDb();
		$result = $db->query('SELECT * FROM academic_year ORDER BY id DESC');
		echo '<div>
		<form method="POST" action="/user/academic-year-submit">
    	<select onchange="this.form.submit()" name="id_academic_year" id="id_academic_year" style="width: 80%; display: block; margin: 10px auto">
		<option value="">';
    	while ($row = $result->fetch()) 
    	{
			if ($row['id'] == Auth::getInstance()->getAcademicYear())
			{
				$selected = $row;
				echo '<option value="'.$row['id'].'" selected>'.$row['id'].'</option>';
			}
			else
			{
				echo '<option value="'.$row['id'].'">'.$row['id'].'</option>';
			}
    	}
    	echo '</select></form></div>';
	}

	// Форма выбора акдемического года на видах работ
	public static function academicYearForm($class)
	{
		$db = DataBase::getInstance()->getDb();
		$result = $db->query('SELECT * FROM academic_year ORDER BY id DESC');
		$selected = '';
		echo '<div id="academic_year_form" class="'.$class.'_academic_year_form">
		<p>Академический год</p><br>
		<form method="POST" action="/user/academic-year-submit">
    	<select onchange="this.form.submit()" name="id_academic_year" id="id_academic_year">
		<option value="">';
    	while ($row = $result->fetch()) 
    	{
			if ($row['id'] == Auth::getInstance()->getAcademicYear())
			{
				$selected = $row;
				echo '<option value="'.$row['id'].'" selected>'.$row['id'].'</option>';
			}
			else
			{
				echo '<option value="'.$row['id'].'">'.$row['id'].'</option>';
			}
    	}
    	echo '</select></form>';
    	if ($class == 'works')
    	{
    		if (!empty($selected)) // Если выбран
    		{
    			Auth::getInstance()->setAcademicYearLocking($selected['locking']);

    			if (Auth::getInstance()->compareAcademicYearLocking(Auth::ACADEMIC_YEAR_FILL)) // Заполнение
    			{
					Auth::getInstance()->setTableWorksMode(Auth::TABLE_WORKS_MODE_FILL);
    			}
    			else if (Auth::getInstance()->compareAcademicYearLocking(Auth::ACADEMIC_YEAR_FILL_EDIT_EXEC)) // Заполнение(Редактирование) и Выполнение
    			{
    				echo '<form method="POST" action="/user/table-mode-fill-edit"><input type="submit" value="Заполнение ИП" name="academic_year_fill_edit" id="academic_year_fill_edit"></form>
					<form method="POST" action="/user/table-mode-exec"><input type="submit" value="Выполнение ИП" name="academic_year_exec" id="academic_year_exec"></form>';

					if (Auth::getInstance()->getTableWorksMode() != Auth::TABLE_WORKS_MODE_FILL_EDIT)
						Auth::getInstance()->setTableWorksMode(Auth::TABLE_WORKS_MODE_EXEC);
    			}
    			else if (Auth::getInstance()->compareAcademicYearLocking(Auth::ACADEMIC_YEAR_ADJUSTMENT)) // Корректировка ИП
    			{
					Auth::getInstance()->setTableWorksMode(Auth::TABLE_WORKS_MODE_ADJUSTMENT);
    			}
    			else // Чтение
    			{
					Auth::getInstance()->setTableWorksMode(Auth::TABLE_WORKS_MODE_READ);
    			}
    		}
    		else // Не выбран академический год
    		{
				Auth::getInstance()->setTableWorksMode(Auth::TABLE_WORKS_MODE_READ);
    		}
    	}
    	echo '</div>';
	}

	// Админ меню с списком категорий
	public static function adminMenu()
	{
		echo'
		<div id="admin_menu">
			<p>Список категорий</p>
		<ul>
			<li class="admin_submenu" data-admin-submenu="1"><a><img src="/web/images/admin_menu_category.png">Общие справочники</a>
				<ul>
					<li><a href="/admin-academic-year"><img src="/web/images/table.png">Академический год</a></li>
					<li><a href="/admin-type-activities"><img src="/web/images/table.png">Вид деятельности УМР/УПР</a></li>
					<li><a href="/admin-level-events"><img src="/web/images/table.png">Уровень мероприятий</a></li>
					<li><a href="/admin-type-worksmw"><img src="/web/images/table.png">Вид участия</a></li>
					<li><a href="/admin-omm"><img src="/web/images/table.png">Организационно-методические материалы</a></li>
					<li><a href="/admin-users"><img src="/web/images/table.png">Пользователи</a></li>
				</ul>
			</li>
			<li class="admin_submenu" data-admin-submenu="2"><a><img src="/web/images/admin_menu_category.png">Педагогические работники</a>
				<ul>
					<li><a href="/admin-employee"><img src="/web/images/table.png">Педагогические работники</a></li>
					<li><a href="/admin-type-employee"><img src="/web/images/table.png">Тип сотрудника</a></li>
					<li><a href="/admin-posts"><img src="/web/images/table.png">Должности педагогического работника</a></li>
					<li><a href="/admin-category"><img src="/web/images/table.png">Категория педагогического работника</a></li>
				</ul>
			</li>
			<li class="admin_submenu" data-admin-submenu="3"><a><img src="/web/images/admin_menu_category.png">Организационная структура</a>
				<ul>
					<li><a href="/admin-department"><img src="/web/images/table.png">Отделения</a></li>
					<li><a href="/admin-pk-pks"><img src="/web/images/table.png">ПК/ПЦК</a></li>
					<li><a href="/admin-ppccz"><img src="/web/images/table.png">Перечень специальностей</a></li>
					<li><a href="/admin-cipher-group"><img src="/web/images/table.png">Группы</a></li>
					<li><a href="/admin-index-disciplines"><img src="/web/images/table.png">Индекс дисциплин</a></li>
					<li><a href="/admin-disciplines"><img src="/web/images/table.png">Наименование дисциплин</a></li>
				</ul>
			</li>
			<li class="admin_submenu" data-admin-submenu="4"><a><img src="/web/images/admin_menu_category.png">Учебно-методическая работа</a>
				<ul>
					<li><a href="/admin-type-umd"><img src="/web/images/table.png">Вид УМД</a></li>
					<li><a href="/admin-type-umd2"><img src="/web/images/table.png">Тип УМД</a></li>
				</ul>
			</li>
			<li class="admin_submenu" data-admin-submenu="5"><a><img src="/web/images/admin_menu_category.png">Организационно-методическая работа</a>
				<ul>
					<li><a href="/admin-type-work"><img src="/web/images/table.png">Вид деятельности ОМР</a></li>
					<li><a href="/admin-type-event"><img src="/web/images/table.png">Вид мероприятия</a></li>
				</ul>
			</li>
			<li class="admin_submenu" data-admin-submenu="6"><a><img src="/web/images/admin_menu_category.png">Научно-методическая работа</a>
				<ul>
					<li><a href="/admin-type-participation"><img src="/web/images/table.png">Вид участия</a></li>
					<li><a href="/admin-event-nmp"><img src="/web/images/table.png">Вид мероприятий</a></li>
				</ul>
			</li>
			<li class="admin_submenu" data-admin-submenu="7"><a><img src="/web/images/admin_menu_category.png">Учебно-производственная работа</a>
				<ul>
					<!--<li><a href="/admin-type-cabinet"><img src="/web/images/table.png">Тип кабинета</a></li>
					<li><a href="/admin-foundation-offices"><img src="/web/images/table.png">Наименование кабинета</a></li>-->
					<li><a href="/admin-type-upd"><img src="/web/images/table.png">Вид УПД</a></li>
					<li><a href="/admin-placement"><img src="/web/images/table.png">Место размещения</a></li>
				</ul>
			</li>
			<li class="admin_submenu" data-admin-submenu="8"><a><img src="/web/images/admin_menu_category.png">Воспитательная работа</a>
				<ul>
					<li><a href="/admin-type-workew"><img src="/web/images/table.png">Вид деятельности ВР</a></li>
					<li><a href="/admin-type-teach-educational-activity"><img src="/web/images/table.png">Вид учебно-воспитательной деятельности</a></li>
				</ul>
			</li>
			<li class="admin_submenu" data-admin-submenu="9"><a><img src="/web/images/admin_menu_category.png">Повышение уровня квалификации</a>
				<ul>
					<li><a href="/admin-type-worksew"><img src="/web/images/table.png">Форма повышения квалификации</a></li>
				</ul>
			</li>
		</ul>
		</div>';	
	}
}

?>