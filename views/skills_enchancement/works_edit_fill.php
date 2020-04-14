<div id="edit_data_table_form">
<form id="edit_form_fill" method='POST' action='/se/works-update-fill'>
	<p><span>Форма повышения</span><br>
		<?php Widgets::dropDownSelectIdList('SELECT * FROM spr_t_worksew ORDER BY sew_name','id_sework','sew_name',$skillsEnchancement->type_work,'edit_fill_data_table_form_type_work'); ?>
	</p>
	<p><span>Дата</span><br>
    	<input type='text' id='edit_fill_data_table_form_sdate' name='edit_fill_data_table_form_sdate' value='<?php echo $skillsEnchancement->sdate ?>' maxlength='100'>
	</p>
	<p><span>Место</span><br>
    	<input type='text' id='edit_fill_data_table_form_place' name='edit_fill_data_table_form_place' value='<?php echo $skillsEnchancement->place ?>' maxlength='150'>
	</p>
	<p><span>Тема</span><br>
    	<input type='text' id='edit_fill_data_table_form_theme' name='edit_fill_data_table_form_theme' value='<?php echo $skillsEnchancement->theme ?>' maxlength='150'>
	</p><br>
	<button type='button' id='edit_fill_data_table_form_cancel' name='edit_fill_data_table_form_cancel'>Отменить</button>
	<button type='submit' id='edit_fill_data_table_form_update' name='edit_fill_data_table_form_update' value='<?php if (isset($_POST["button_fill_edit"])) echo $_POST["button_fill_edit"] ?>'>Редактировать</button>
</form>
</div>