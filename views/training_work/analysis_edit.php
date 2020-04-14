<div id="edit_data_table_form">
<form id="edit_form" method='POST' action='/tw/analysis-update'>
	<p><span>Специальность</span><br>
		<?php Widgets::dropDownSelectIdList('SELECT * FROM ppccz ORDER BY cipher_specialty','id_ppccz','cipher_specialty',$trainingWork->ppccz,'edit_data_table_form_ppccz'); ?>
	</p>
	<p><span>Учебная дисциплина</span><br>
		<?php Widgets::dropDownSelectIdList('SELECT disciplines.id,spr_index_disciplines.dindex,disciplines.dname FROM disciplines INNER JOIN spr_index_disciplines ON disciplines.dindex = spr_index_disciplines.id_ind WHERE ppccz = :ppccz ORDER BY spr_index_disciplines.dindex', 'id', array('dindex','dname'), $trainingWork->discipline, 'edit_data_table_form_discipline', false, array(':ppccz' => $trainingWork->ppccz)); ?>
	</p>
	<p><span>Учебная группа</span><br>
		<?php Widgets::dropDownSelectIdList('SELECT id,gname FROM spr_cipher_group WHERE ppccz = :ppccz','id','gname', $trainingWork->cipher_group, 'edit_data_table_form_cipher_group', false, array(':ppccz' => $trainingWork->ppccz)); ?>
	</p>
	<p><span><b><u>Первый семестр</u></b></span><br>
		<span>План</span><br>
		<input type='text' id='edit_data_table_form_plan_1' name='edit_data_table_form_plan_1' value='<?php echo $trainingWork->plan_1 ?>'><br>
		<span>Факт</span><br>
		<input type='text' id='edit_data_table_form_fact_1' name='edit_data_table_form_fact_1' maxlength='4' value='<?php echo $trainingWork->fact_1 ?>'>
	</p>
	<p><span><b><u>Второй семестр</u></b></span><br>
		<span>План</span><br>
		<input type='text' id='edit_data_table_form_plan_2' name='edit_data_table_form_plan_2' value='<?php echo $trainingWork->plan_2 ?>'><br>
		<span>Факт</span><br>
		<input type='text' id='edit_data_table_form_fact_2' name='edit_data_table_form_fact_2' maxlength='4' value='<?php echo $trainingWork->fact_2 ?>'>
	</p>
	<p><span><b><u>Учебный год</u></b></span><br>
		<span>План</span><br>
		<input type='text' id='edit_data_table_form_plan_3' name='edit_data_table_form_plan_3' value='<?php echo $trainingWork->plan_3 ?>' disabled><br>
		<span>Факт</span><br>
		<input type='text' id='edit_data_table_form_fact_3' name='edit_data_table_form_fact_3' value='<?php echo $trainingWork->fact_3 ?>' disabled>
	</p>
	<p><span>Причина невыполнения</span><br>
		<input type='text' id='edit_data_table_form_reason_failure' name='edit_data_table_form_reason_failure' value='<?php echo $trainingWork->reason_failure ?>' maxlength='100'>
	</p>
	<p><span>Абсолютная успеваемость</span><br>
		<input type='text' id='edit_data_table_form_absolute_progress' name='edit_data_table_form_absolute_progress' value='<?php echo $trainingWork->absolute_progress ?>' maxlength='100'>
	</p>
	<p><span>Качественная успеваемость</span><br>
		<input type='text' id='edit_data_table_form_quality_progress' name='edit_data_table_form_quality_progress' value='<?php echo $trainingWork->quality_progress ?>' maxlength='100'>
	</p><br>
	<button type='button' id='edit_data_table_form_cancel' name='edit_data_table_form_cancel'>Отменить</button>
	<button type='submit' id='edit_data_table_form_update' name='edit_data_table_form_update' value='<?php if (isset($_POST["button_edit"])) echo $_POST["button_edit"] ?>'>Редактировать</button>
</form>
</div>