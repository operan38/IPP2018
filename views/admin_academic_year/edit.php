<div id="edit_data_table_form">
	<form id="edit_form" method='POST' action='/admin-academic-year/update'>
		<p><span>Год</span><br>
    		<input type='text' id='edit_data_table_form_id' name='edit_data_table_form_id' value='<?php echo $academicYear->id ?>'>
		</p>
		<p><span>Режим</span><br>
			<?php Widgets::dropDownSimple(array('Заполнение ИП','Заполнение ИП(редактирование), Выполнение ИП', 'Корректировка ИП', 'Закрыт'),'edit_data_table_form_locking',$academicYear->locking) ?>
		</p><br>
		<button type='button' id='edit_data_table_form_cancel' name='edit_data_table_form_cancel'>Отменить</button>
		<button type='submit' id='edit_data_table_form_update' name='edit_data_table_form_update' value='<?php if (isset($_POST["button_edit"])) echo $_POST["button_edit"] ?>'>Редактировать</button>		
	</form>
</div>