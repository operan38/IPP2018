<div id="edit_data_table_form">
<form id="edit_form_exec" method='POST' action='/smw/works-update-exec'>
	<p><span>Вид деятельности</span><br>
		<input type='text' id='edit_exec_data_table_form_type_work' name='edit_exec_data_table_form_type_work' value='<?php echo $scientificMethodicalWork->type_work ?>' disabled>
	</p>
	<p><span>Вид мероприятия</span><br>
		<input type='text' id='edit_exec_data_table_form_type_event' name='edit_exec_data_table_form_type_event' value='<?php echo $scientificMethodicalWork->type_event ?>' disabled>
	</p>
	<p><span>Название мероприятия</span><br>
		<input type='text' id='edit_exec_data_table_form_name_event' name='edit_exec_data_table_form_name_event' value='<?php echo $scientificMethodicalWork->name_event ?>' disabled>
	</p>
	<p><span>Уровень мероприятия</span><br>
		<input type='text' id='edit_exec_data_table_form_level_event' name='edit_exec_data_table_form_level_event' value='<?php echo $scientificMethodicalWork->level_event ?>' disabled>
	</p>
	<p><span>Дата</span><br>
		<input type='text' id='edit_exec_data_table_form_sdate' name='edit_exec_data_table_form_sdate' value='<?php echo $scientificMethodicalWork->sdate ?>' disabled>
	</p>
	<p><span>Место</span><br>
		<input type='text' id='edit_exec_data_table_form_place' name='edit_exec_data_table_form_place' value='<?php echo $scientificMethodicalWork->place ?>' disabled>
	</p>
	<p><span>Вид участия</span><br>
		<input type='text' id='edit_exec_data_table_form_type_of_participation' name='edit_exec_data_table_form_type_of_participation' value='<?php echo $scientificMethodicalWork->type_of_participation ?>' disabled>
	</p>
	<p><input type='radio' id='edit_exec_data_table_form_check1' name="edit_exec_data_table_form_check" value="1"><label for="edit_exec_data_table_form_check1">Выполнено</label>
		<input type='radio' id='edit_exec_data_table_form_check2' name="edit_exec_data_table_form_check" value="0"><label for="edit_exec_data_table_form_check2">Не выполнено</label></p>
	<p><span>Результат</span><br>
		<input type='text' id='edit_exec_data_table_form_result_executing' name='edit_exec_data_table_form_result_executing' value='<?php echo $scientificMethodicalWork->result_executing ?>'>
	</p><br>
	<button type='button' id='edit_exec_data_table_form_cancel' name='edit_exec_data_table_form_cancel'>Отменить</button>
	<button type='submit' id='edit_exec_data_table_form_update' name='edit_exec_data_table_form_update' value='<?php if (isset($_POST["button_exec_edit"])) echo $_POST["button_exec_edit"] ?>'>Обновить</button>
</form>
</div>