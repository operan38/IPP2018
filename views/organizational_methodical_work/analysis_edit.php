<div id="edit_data_table_form">
<form id="edit_form" method='POST' action='/omw/analysis-update'>
	<p><span>Вид деятельности</span><br>
		<?php Widgets::dropDownSelectIdList('SELECT * FROM spr_t_work ORDER BY name','id','name',$organizationalMethodicalWork->type_work,'edit_data_table_form_type_work'); ?>
	</p>
	<p><span>Вид мероприятия</span><br>
		<?php Widgets::dropDownSelectIdList('SELECT * FROM spr_t_event ORDER BY evname','id','evname',$organizationalMethodicalWork->type_event,'edit_data_table_form_type_event'); ?>
	</p>
	<p><span>Название мероприятия</span><br>
		<input type='text' id='edit_data_table_form_name_event' name='edit_data_table_form_name_event' value='<?php echo $organizationalMethodicalWork->name_event ?>'>
	</p>
	<p><span>Уровень мероприятия</span><br>
		<?php Widgets::dropDownSelectIdList('SELECT * FROM spr_level_events ORDER BY name_level','id_level','name_level',$organizationalMethodicalWork->lev_event,'edit_data_table_form_lev_event'); ?>
	</p>
	<p><span>Дата</span><br>
		<input type='text' id='edit_data_table_form_sdate' name='edit_data_table_form_sdate' value='<?php echo $organizationalMethodicalWork->sdate ?>'>
	</p>
	<p><span>Инфо о студентах</span><br>
		<input type='text' id='edit_data_table_form_information_students' name='edit_data_table_form_information_students' value='<?php echo $organizationalMethodicalWork->information_students ?>'>
	</p>
	<p><span>Результат</span><br>
		<input type='text' id='edit_data_table_form_result_executing' name='edit_data_table_form_result_executing' value='<?php echo $organizationalMethodicalWork->result_executing ?>'>
	</p><br>
	<button type='button' id='edit_data_table_form_cancel' name='edit_data_table_form_cancel'>Отменить</button>
	<button type='submit' id='edit_data_table_form_update' name='edit_data_table_form_update' value='<?php if (isset($_POST["button_edit"])) echo $_POST["button_edit"] ?>'>Редактировать</button>
</form>
</div>