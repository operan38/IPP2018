<div id="edit_data_table_form">
<form id="edit_form" method='POST' action='/smw/analysis-update'>
	<p><span>Вид деятельности</span><br>
		<?php Widgets::dropDownSelectIdList('SELECT * FROM spr_t_worksmw ORDER BY smw_name','id_smwork','smw_name',$scientificMethodicalWork->type_work,'edit_data_table_form_type_work'); ?>
	</p>
	<p><span>Вид мероприятия</span><br>
		<?php Widgets::dropDownSelectIdList('SELECT * FROM spr_event_nmp ORDER BY name_event_nmp','id_event_nmp','name_event_nmp',$scientificMethodicalWork->type_event,'edit_data_table_form_type_event'); ?>
	</p>
	<p><span>Название мероприятия</span><br>
        <input type='text' id='edit_data_table_form_name_event' name='edit_data_table_form_name_event' value='<?php echo $scientificMethodicalWork->name_event ?>' maxlength='150'>
	</p>
	<p><span>Уровень мероприятия</span><br>
		<?php Widgets::dropDownSelectIdList('SELECT * FROM spr_level_events ORDER BY name_level','id_level','name_level',$scientificMethodicalWork->level_event,'edit_data_table_form_level_event'); ?>
	</p>
	<p><span>Дата</span><br>
    	<input type='text' id='edit_data_table_form_sdate' name='edit_data_table_form_sdate' value='<?php echo $scientificMethodicalWork->sdate ?>' maxlength='100'>
	</p>
	<p><span>Место</span><br>
    	<input type='text' id='edit_data_table_form_place' name='edit_data_table_form_place' value='<?php echo $scientificMethodicalWork->place ?>' maxlength='150'>
	</p>
	<p><span>Вид участия</span><br>
		<?php Widgets::dropDownSelectIdList('SELECT * FROM spr_level_events ORDER BY name_level','id_level','name_level',$scientificMethodicalWork->level_event,'edit_data_table_form_type_of_participation'); ?>
	</p>
	<p><span>Результат</span><br>
		<input type='text' id='edit_data_table_form_result_executing' name='edit_data_table_form_result_executing' value='<?php echo $scientificMethodicalWork->result_executing ?>'>
	</p><br>
	<button type='button' id='edit_data_table_form_cancel' name='edit_data_table_form_cancel'>Отменить</button>
	<button type='submit' id='edit_data_table_form_update' name='edit_data_table_form_update' value='<?php if (isset($_POST["button_edit"])) echo $_POST["button_edit"] ?>'>Редактировать</button>
</form>
</div>