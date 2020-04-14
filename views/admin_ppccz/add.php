<div id="admin_add_data_table_form">
	<form id="add_form" method='POST' action='/admin-ppccz/insert'>
		<input type='text' id='admin_add_data_table_form_id_department' name='admin_add_data_table_form_id_department' value='<?php echo $_POST["admin_key_id_department"] ?>' hidden>
		<p><span>Шифр</span><br>
    		<input type='text' id='admin_add_data_table_form_cipher_specialty' name='admin_add_data_table_form_cipher_specialty'>
		</p>
		<p><span>Наименование</span><br>
    		<input type='text' id='admin_add_data_table_form_name_ppccz' name='admin_add_data_table_form_name_ppccz'>
		</p><br>
		<button type='button' id='admin_add_data_table_form_cancel' name='admin_add_data_table_form_cancel'>Отменить</button>
		<button type='submit' id='admin_add_data_table_form_insert' name='admin_add_data_table_form_insert'>Ок</button>
	</form>
</div>