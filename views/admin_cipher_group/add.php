<div id="admin_add_data_table_form">
	<form id="add_form" method='POST' action='/admin-cipher-group/insert'>
    	<input type='text' id='admin_add_data_table_form_ppccz' name='admin_add_data_table_form_ppccz' value='<?php echo $_POST["admin_key_ppccz"] ?>' hidden>
		<p><span>Наименование</span><br>
    		<input type='text' id='admin_add_data_table_form_gname' name='admin_add_data_table_form_gname'>
		</p><br>
		<button type='button' id='admin_add_data_table_form_cancel' name='admin_add_data_table_form_cancel'>Отменить</button>
		<button type='submit' id='admin_add_data_table_form_insert' name='admin_add_data_table_form_insert'>Ок</button>
	</form>
</div>