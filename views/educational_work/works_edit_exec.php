<div id="edit_data_table_form">
<form id="edit_form_exec" method='POST' action='/ew/works-update-exec'>
	<p><span>Специальность</span><br>
		<input type='text' id='edit_exec_data_table_form_ppccz' name='edit_exec_data_table_form_ppccz' value='<?php echo $educationalWork->ppccz ?>' disabled>
	</p>
	<p><span>Учебная группа</span><br>
		<input type='text' id='edit_exec_data_table_form_cipher_group' name='edit_exec_data_table_form_cipher_group' value='<?php echo $educationalWork->cipher_group ?>' disabled>
	</p>
	<p><span>Вид деятельности</span><br>
		<input type='text' id='edit_exec_data_table_form_type_activities' name='edit_exec_data_table_form_type_work' value='<?php echo $educationalWork->type_work ?>' disabled>
	</p>
	<p><span>Вид УВД</span><br>
		<input type='text' id='edit_exec_data_table_form_type_activity' name='edit_exec_data_table_form_type_umd' value='<?php echo $educationalWork->type_activity ?>' disabled>
	</p>
	<p><span>Дата</span><br>
		<input type='text' id='edit_exec_data_table_form_sdate' name='edit_exec_data_table_form_sdate' value='<?php echo $educationalWork->sdate ?>' disabled>
	</p>
	<p><input type='radio' id='edit_exec_data_table_form_check1' name="edit_exec_data_table_form_check" value="1"><label for="edit_exec_data_table_form_check1">Выполнено</label>
		<input type='radio' id='edit_exec_data_table_form_check2' name="edit_exec_data_table_form_check" value="0"><label for="edit_exec_data_table_form_check2">Не выполнено</label></p>
	<p><span>Результат</span><br>
		<input type='text' id='edit_exec_data_table_form_report' name='edit_exec_data_table_form_report' value='<?php echo $educationalWork->report ?>'>
	</p><br>
	<button type='button' id='edit_exec_data_table_form_cancel' name='edit_exec_data_table_form_cancel'>Отменить</button>
	<button type='submit' id='edit_exec_data_table_form_update' name='edit_exec_data_table_form_update' value='<?php if (isset($_POST["button_exec_edit"])) echo $_POST["button_exec_edit"] ?>'>Обновить</button>
</form>
</div>