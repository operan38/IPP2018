<div id="admin_add_data_table_form">
	<form id="add_form" method='POST' action='/admin-disciplines/insert'>
    	<input type='text' id='admin_add_data_table_form_ppccz' name='admin_add_data_table_form_ppccz' value='<?php echo $_POST["admin_key_ppccz"] ?>' hidden>
		<p><span>Индекс</span><br>
			<?php Widgets::dropDownList('SELECT * FROM spr_index_disciplines ORDER BY dindex','id_ind','dindex','admin_add_data_table_form_dindex'); ?>
		</p>
		<p><span>Наименование</span><br>
    		<input type='text' id='admin_add_data_table_form_dname' name='admin_add_data_table_form_dname'>
		</p><br>
		<button type='button' id='admin_add_data_table_form_cancel' name='admin_add_data_table_form_cancel'>Отменить</button>
		<button type='submit' id='admin_add_data_table_form_insert' name='admin_add_data_table_form_insert'>Ок</button>
	</form>
</div>