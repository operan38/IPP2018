<div id="edit_data_table_form">
<form id="edit_form_exec" method='POST' action='/tpw/works-update-exec'>
	<p><span>Специальность</span><br>
		<input type='text' id='edit_exec_data_table_form_ppccz' name='edit_exec_data_table_form_ppccz' value='<?php echo $trainingProductionWork->ppccz ?>' disabled>
	</p>
	<!--<p><span>Наименование кабинета</span><br>
		<input type='text' id='edit_exec_data_table_form_id_foundation' name='edit_exec_data_table_form_id_foundation' value='<?php echo $trainingProductionWork->id_foundation ?>' disabled>
	</p>-->
	<p><span>Место размещения</span><br>
		<input type='text' id='edit_exec_data_table_form_placement' name='edit_exec_data_table_form_placement' value='<?php echo $trainingProductionWork->placement ?>' disabled>
	</p>
	<p><span>Вид деятельности</span><br>
		<input type='text' id='edit_exec_data_table_form_type_activities' name='edit_exec_data_table_form_type_activities' value='<?php echo $trainingProductionWork->type_activities ?>' disabled>
	</p>
	<p><span>Вид УПД</span><br>
		<input type='text' id='edit_exec_data_table_form_type_upd' name='edit_exec_data_table_form_type_upd' value='<?php echo $trainingProductionWork->type_upd ?>' disabled>
	</p>
	<p><span>Дата</span><br>
		<input type='text' id='edit_exec_data_table_form_sdate' name='edit_exec_data_table_form_sdate' value='<?php echo $trainingProductionWork->sdate ?>' disabled>
	</p>
	<p><input type='radio' id='edit_exec_data_table_form_check1' name="edit_exec_data_table_form_check" value="1"><label for="edit_exec_data_table_form_check1">Выполнено</label>
		<input type='radio' id='edit_exec_data_table_form_check2' name="edit_exec_data_table_form_check" value="0"><label for="edit_exec_data_table_form_check2">Не выполнено</label></p>
	<p><span>Результат</span><br>
		<input type='text' id='edit_exec_data_table_form_report' name='edit_exec_data_table_form_report' value='<?php echo $trainingProductionWork->report ?>'>
	</p><br>
	<button type='button' id='edit_exec_data_table_form_cancel' name='edit_exec_data_table_form_cancel'>Отменить</button>
	<button type='submit' id='edit_exec_data_table_form_update' name='edit_exec_data_table_form_update' value='<?php if (isset($_POST["button_exec_edit"])) echo $_POST["button_exec_edit"] ?>'>Обновить</button>
</form>
</div>