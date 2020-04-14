<div id="edit_data_table_form">
	<form id="edit_form" method='POST' action='/admin-type-workew/update'>
		<p><span>Наименование</span><br>
    		<input type='text' id='edit_data_table_form_ew_name' name='edit_data_table_form_ew_name' value='<?php echo $typeWorkew->ew_name ?>'>
		</p><br>
		<button type='button' id='edit_data_table_form_cancel' name='edit_data_table_form_cancel'>Отменить</button>
		<button type='submit' id='edit_data_table_form_update' name='edit_data_table_form_update' value='<?php if (isset($_POST["button_edit"])) echo $_POST["button_edit"] ?>'>Редактировать</button>		
	</form>
</div>