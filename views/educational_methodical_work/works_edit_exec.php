<div id="edit_data_table_form">
<form id="edit_form_exec" method='POST' action='/emw/works-update-exec'>
	<p><span>Специальность</span><br>
		<input type='text' id='edit_exec_data_table_form_ppccz' name='edit_exec_data_table_form_ppccz' value='<?php echo $educationalMethodicalWork->ppccz ?>' disabled>
	</p>
	<p><span>Учебная дисциплина</span><br>
		<input type='text' id='edit_exec_data_table_form_discipline' name='edit_exec_data_table_form_discipline' value='<?php echo $educationalMethodicalWork->discipline ?>' disabled>
	</p>
	<p><span>Вид деятельности</span><br>
		<input type='text' id='edit_exec_data_table_form_type_activities' name='edit_exec_data_table_form_type_activities' value='<?php echo $educationalMethodicalWork->type_activities ?>' disabled>
	</p>
	<p><span>Вид УМД</span><br>
		<input type='text' id='edit_exec_data_table_form_type_umd' name='edit_exec_data_table_form_type_umd' value='<?php echo $educationalMethodicalWork->type_umd ?>' disabled>
	</p>
	<p><span>Тип УМД</span><br>
		<input type='text' id='edit_exec_data_table_form_type_umd2' name='edit_exec_data_table_form_type_umd2' value='<?php echo $educationalMethodicalWork->type_umd2 ?>' disabled>
	</p>
	<p><span>Срок исполнения</span><br>
		<input type='text' id='edit_exec_data_table_form_date_performance' name='edit_exec_data_table_form_date_performance' value='<?php echo $educationalMethodicalWork->date_performance ?>' disabled>
	</p>
	<p><input type='radio' id='edit_exec_data_table_form_check1' name="edit_exec_data_table_form_check" value="1"><label for="edit_exec_data_table_form_check1">Выполнено</label>
		<input type='radio' id='edit_exec_data_table_form_check2' name="edit_exec_data_table_form_check" value="0"><label for="edit_exec_data_table_form_check2">Не выполнено</label></p>
	<p><span>Краткий отчет о выполнении</span><br>
		<input type='text' id='edit_exec_data_table_form_report' name='edit_exec_data_table_form_report' value='<?php echo $educationalMethodicalWork->report ?>'>
	</p><br>
	<button type='button' id='edit_exec_data_table_form_cancel' name='edit_exec_data_table_form_cancel'>Отменить</button>
	<button type='submit' id='edit_exec_data_table_form_update' name='edit_exec_data_table_form_update' value='<?php if (isset($_POST["button_exec_edit"])) echo $_POST["button_exec_edit"] ?>'>Обновить</button>
</form>
</div>