<div id="edit_data_table_form">
<form id="edit_form_exec" method='POST' action='/se/works-update-exec'>
	<p><span>Форма повышения</span><br>
		<input type='text' id='edit_exec_data_table_form_type_work' name='edit_exec_data_table_form_type_work' value='<?php echo $skillsEnchancement->type_work ?>' disabled>
	</p>
	<p><span>Дата</span><br>
		<input type='text' id='edit_exec_data_table_form_sdate' name='edit_exec_data_table_form_sdate' value='<?php echo $skillsEnchancement->sdate ?>' disabled>
	</p>
	<p><span>Место</span><br>
		<input type='text' id='edit_exec_data_table_form_place' name='edit_exec_data_table_form_place' value='<?php echo $skillsEnchancement->place ?>' disabled>
	</p>
	<p><span>Тема</span><br>
		<input type='text' id='edit_exec_data_table_form_theme' name='edit_exec_data_table_form_theme' value='<?php echo $skillsEnchancement->theme ?>' disabled>
	</p>
	<p><input type='radio' id='edit_exec_data_table_form_check1' name="edit_exec_data_table_form_check" value="1"><label for="edit_exec_data_table_form_check1">Выполнено</label>
		<input type='radio' id='edit_exec_data_table_form_check2' name="edit_exec_data_table_form_check" value="0"><label for="edit_exec_data_table_form_check2">Не выполнено</label></p>
	<p><span>Документ/Результат</span><br>
		<input type='text' id='edit_exec_data_table_form_result_executing' name='edit_exec_data_table_form_result_executing' value='<?php echo $skillsEnchancement->result_executing ?>'>
	</p><br>
	<button type='button' id='edit_exec_data_table_form_cancel' name='edit_exec_data_table_form_cancel'>Отменить</button>
	<button type='submit' id='edit_exec_data_table_form_update' name='edit_exec_data_table_form_update' value='<?php if (isset($_POST["button_exec_edit"])) echo $_POST["button_exec_edit"] ?>'>Обновить</button>
</form>
</div>