<div id="edit_data_table_form">
	<form id="edit_form" method='POST' action='/admin-posts/update'>
		<p><span>Наименование</span><br>
    		<input type='text' id='edit_data_table_form_post_name' name='edit_data_table_form_post_name' value='<?php echo $posts->post_name ?>'>
		</p><br>
		<button type='button' id='edit_data_table_form_cancel' name='edit_data_table_form_cancel'>Отменить</button>
		<button type='submit' id='edit_data_table_form_update' name='edit_data_table_form_update' value='<?php if (isset($_POST["button_edit"])) echo $_POST["button_edit"] ?>'>Редактировать</button>		
	</form>
</div>