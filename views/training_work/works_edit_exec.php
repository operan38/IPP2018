<div id="edit_data_table_form">
<form id="edit_form_exec" method='POST' action='/tw/works-update-exec'>
	<p><span>Специальность</span><br>
		<input type='text' id='edit_exec_data_table_form_ppccz' name='edit_exec_data_table_form_ppccz' value='<?php echo $trainingWork->ppccz ?>' disabled>
	</p>
	<p><span>Учебная дисциплина</span><br>
		<input type='text' id='edit_exec_data_table_form_discipline' name='edit_exec_data_table_form_discipline' value='<?php echo $trainingWork->discipline ?>' disabled>
	</p>
	<p><span>Учебная группа</span><br>
		<input type='text' id='edit_exec_data_table_form_cipher_group' name='edit_exec_data_table_form_cipher_group' value='<?php echo $trainingWork->cipher_group ?>' disabled>
	</p>
	<p><span><b>Первый семестр</b></span><br>
		<span>План</span><br>
		<input type='text' id='edit_exec_data_table_form_plan_1' name='edit_exec_data_table_form_plan_1' value='<?php echo $trainingWork->plan_1 ?>' disabled><br>
		<span>Факт</span><br>
		<input type='text' id='edit_exec_data_table_form_fact_1' name='edit_exec_data_table_form_fact_1' maxlength='4' value='<?php echo $trainingWork->fact_1 ?>'>
	</p>
	<p><span><b>Второй семестр</b></span><br>
		<span>План</span><br>
		<input type='text' id='edit_exec_data_table_form_plan_2' name='edit_exec_data_table_form_plan_2' value='<?php echo $trainingWork->plan_2 ?>' disabled><br>
		<span>Факт</span><br>
		<input type='text' id='edit_exec_data_table_form_fact_2' name='edit_exec_data_table_form_fact_2' maxlength='4' value='<?php echo $trainingWork->fact_2 ?>'>
	</p>
	<p><span><b>Учебный год</b></span><br>
		<span>План</span><br>
		<input type='text' id='edit_exec_data_table_form_plan_3' name='edit_exec_data_table_form_plan_3' value='<?php echo $trainingWork->plan_3 ?>' disabled><br>
		<span>Факт</span><br>
		<input type='text' id='edit_exec_data_table_form_fact_3' name='edit_exec_data_table_form_fact_3' value='<?php echo $trainingWork->fact_3 ?>' disabled>
	</p>
	<p><span>Причина невыполнения</span><br>
		<input type='text' id='edit_exec_data_table_form_reason_failure' name='edit_exec_data_table_form_reason_failure' value='<?php echo $trainingWork->reason_failure ?>' maxlength='100'>
	</p>
	<p><span>Абсолютная успеваемость</span><br>
		<input type='text' id='edit_exec_data_table_form_absolute_progress' name='edit_exec_data_table_form_absolute_progress' value='<?php echo $trainingWork->absolute_progress ?>' maxlength='100'>
	</p>
	<p><span>Качественная успеваемость</span><br>
		<input type='text' id='edit_exec_data_table_form_quality_progress' name='edit_exec_data_table_form_quality_progress' value='<?php echo $trainingWork->quality_progress ?>' maxlength='100'>
	</p><br>
	<button type='button' id='edit_exec_data_table_form_cancel' name='edit_exec_data_table_form_cancel'>Отменить</button>
	<button type='submit' id='edit_exec_data_table_form_update' name='edit_exec_data_table_form_update' value='<?php if (isset($_POST["button_exec_edit"])) echo $_POST["button_exec_edit"] ?>'>Обновить</button>
</form>
</div>