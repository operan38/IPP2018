<div id="edit_data_table_form">
	<form id="edit_form" method='POST' action='/admin-cipher-group/update'>
		<p><span>Специальность</span><br>
			<?php Widgets::dropDownSelectIdList('SELECT * FROM ppccz ORDER BY cipher_specialty','id_ppccz','cipher_specialty',$cipherGroup->ppccz,'edit_data_table_form_ppccz'); ?>
		</p>
		<p><span>Наименование</span><br>
    		<input type='text' id='edit_data_table_form_gname' name='edit_data_table_form_gname' value='<?php echo $cipherGroup->gname ?>'>
		</p><br>
		<button type='button' id='edit_data_table_form_cancel' name='edit_data_table_form_cancel'>Отменить</button>
		<button type='submit' id='edit_data_table_form_update' name='edit_data_table_form_update' value='<?php if (isset($_POST["button_edit"])) echo $_POST["button_edit"] ?>'>Редактировать</button>		
	</form>
</div>