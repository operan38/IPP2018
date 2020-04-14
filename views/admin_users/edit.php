<div id="edit_data_table_form">
	<form id="edit_form" method='POST' action='/admin-users/update'>
		<p><span>Фамилия<span class="require_field">*</span></span><br>
    		<input type='text' id='edit_data_table_form_surname' name='edit_data_table_form_surname' value='<?php echo $users->surname ?>'>
		</p>
		<p><span>Имя<span class="require_field">*</span></span><br>
    		<input type='text' id='edit_data_table_form_name' name='edit_data_table_form_name' value='<?php echo $users->name ?>'>
		</p>
		<p><span>Отчество<span class="require_field">*</span></span><br>
    		<input type='text' id='edit_data_table_form_patronymic' name='edit_data_table_form_patronymic' value='<?php echo $users->patronymic ?>'>
		</p>
		<p><span>Новый логин</span><br>
    		<input type='text' id='edit_data_table_form_login' name='edit_data_table_form_login' value=''>
		</p>
		<p><span>Новый пароль</span><br>
    		<input type='password' id='edit_data_table_form_password' name='edit_data_table_form_password' value=''>
		</p><br>
		<button type='button' id='edit_data_table_form_cancel' name='edit_data_table_form_cancel'>Отменить</button>
		<button type='submit' id='edit_data_table_form_update' name='edit_data_table_form_update' value='<?php if (isset($_POST["button_edit"])) echo $_POST["button_edit"] ?>'>Редактировать</button>		
	</form>
</div>