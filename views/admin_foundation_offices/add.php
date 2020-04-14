<div id="admin_add_data_table_form">
	<form id="add_form" method='POST' action='/admin-foundation-offices/insert'>
    	<input type='text' id='admin_add_data_table_form_ppccz' name='admin_add_data_table_form_ppccz' value='<?php echo $_POST["admin_key_ppccz"] ?>' hidden>
		<p><span>Тип кабинета</span><br>
        	<?php Widgets::dropDownList('SELECT * FROM spr_t_cabinet ORDER BY cname','id','cname','admin_add_data_table_form_id_t_cabinet'); ?>
		</p>
		<p><span>Наименование</span><br>
    		<input type='text' id='admin_add_data_table_form_oname' name='admin_add_data_table_form_oname'>
		</p><br>
		<button type='button' id='admin_add_data_table_form_cancel' name='admin_add_data_table_form_cancel'>Отменить</button>
		<button type='submit' id='admin_add_data_table_form_insert' name='admin_add_data_table_form_insert'>Ок</button>
	</form>
</div>