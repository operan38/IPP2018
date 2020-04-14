<div id="edit_data_table_form">
	<form id="edit_form" method='POST' action='/admin-employee/update'>
		<input type='text' id='edit_data_table_form_id_pk_pkc' name='edit_data_table_form_id_pk_pkc' value='<?php echo $employee->id_pk_pkc ?>' hidden>
		<input type='text' id='edit_data_table_form_id_department' name='edit_data_table_form_id_department' value='<?php echo $employee->id_department ?>' hidden>
		<p><span>Отделение</span><br>
			<input type='text' id='edit_data_table_form_dep_name' name='edit_data_table_form_dep_name' value='<?php echo $employee->dep_name ?>' disabled>
		</p>
		<p><span>ПК/ПЦК</span><br>
			<input type='text' id='edit_data_table_form_pk_pkc_name' name='edit_data_table_form_pk_pkc_name' value='<?php echo $employee->pk_pkc_name ?>' disabled>
		</p>
		<p><span>Новый логин</span><br>
    		<input type='text' id='edit_data_table_form_login' name='edit_data_table_form_login' value=''>
		</p>
		<p><span>Новый пароль</span><br>
    		<input type='password' id='edit_data_table_form_password' name='edit_data_table_form_password' value=''>
		</p>
		<p><span>Доступ<span class="require_field">*</span></span><br>
    		<?php Widgets::dropDownSimple(array('Стандартный','Председатель ПК/ПЦК','Заведующий отделения'),'edit_data_table_form_rules',$employee->rules) ?>
		</p><br>
		<button type='button' id='edit_data_table_form_cancel' name='edit_data_table_form_cancel'>Отменить</button>
		<button type='submit' id='edit_data_table_form_update' name='edit_data_table_form_update' value='<?php if (isset($_POST["button_edit"])) echo $_POST["button_edit"] ?>'>Редактировать</button>		
	</form>
</div>