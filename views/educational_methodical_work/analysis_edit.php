<div id="edit_data_table_form">
<form id="edit_form" method='POST' action='/emw/analysis-update'>
	<p><span>Специальность</span><br>
		<?php Widgets::dropDownSelectIdList('SELECT * FROM ppccz ORDER BY cipher_specialty','id_ppccz','cipher_specialty',$educationalMethodicalWork->ppccz,'edit_data_table_form_ppccz'); ?>
	</p>
	<p><span>Учебная дисциплина</span><br>
		<?php Widgets::dropDownSelectIdList('SELECT disciplines.id,spr_index_disciplines.dindex,disciplines.dname FROM disciplines INNER JOIN spr_index_disciplines ON disciplines.dindex = spr_index_disciplines.id_ind WHERE ppccz = :ppccz ORDER BY spr_index_disciplines.dindex', 'id', array('dindex','dname'), $educationalMethodicalWork->discipline, 'edit_data_table_form_discipline', false, array(':ppccz' => $educationalMethodicalWork->ppccz)); ?>
	</p>
	<p><span>Вид деятельности</span><br>
		<?php Widgets::dropDownSelectIdList('SELECT * FROM spr_t_activities ORDER BY name','id','name',$educationalMethodicalWork->type_activities,'edit_data_table_form_type_activities'); ?>
	</p>
	<p><span>Вид УМД</span><br>
		<?php Widgets::dropDownSelectIdList('SELECT * FROM spr_t_umd ORDER BY uname','id','uname',$educationalMethodicalWork->type_umd,'edit_data_table_form_type_umd'); ?>
	</p>
	<p><span>Тип УМД</span><br>
		<?php Widgets::dropDownSelectIdList('SELECT * FROM spr_t_umd2 ORDER BY name_umd2','id_umd2','name_umd2',$educationalMethodicalWork->type_umd2,'edit_data_table_form_type_umd2'); ?>
	</p>
	<p><span>Срок исполнения</span><br>
		<input type='text' id='edit_data_table_form_date_performance' name='edit_data_table_form_date_performance' value='<?php echo $educationalMethodicalWork->date_performance ?>' maxlength='20'>
	</p>
	<p><span>Краткий отчет о выполнении</span><br>
		<input type='text' id='edit_data_table_form_report' name='edit_data_table_form_report' value='<?php echo $educationalMethodicalWork->report ?>'>
	</p><br>
	<button type='button' id='edit_data_table_form_cancel' name='edit_data_table_form_cancel'>Отменить</button>
	<button type='submit' id='edit_data_table_form_update' name='edit_data_table_form_update' value='<?php if (isset($_POST["button_edit"])) echo $_POST["button_edit"] ?>'>Редактировать</button>
</form>
</div>