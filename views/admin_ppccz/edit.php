<div id="edit_data_table_form">
	<form id="edit_form" method='POST' action='/admin-ppccz/update'>
		<p><span>Отделение</span><br>
			<?php Widgets::dropDownSelectIdList('SELECT * FROM spr_department ORDER BY dep_name','id','dep_name',$ppccz->id_department,'edit_data_table_form_id_department'); ?>
		</p>
		<p><span>Шифр</span><br>
    		<input type='text' id='edit_data_table_form_cipher_specialty' name='edit_data_table_form_cipher_specialty' value='<?php echo $ppccz->cipher_specialty ?>'>
		</p>
		<p><span>Наименование</span><br>
    		<input type='text' id='edit_data_table_form_name_ppccz' name='edit_data_table_form_name_ppccz' value='<?php echo $ppccz->name_ppccz ?>'>
		</p><br>
		<button type='button' id='edit_data_table_form_cancel' name='edit_data_table_form_cancel'>Отменить</button>
		<button type='submit' id='edit_data_table_form_update' name='edit_data_table_form_update' value='<?php if (isset($_POST["button_edit"])) echo $_POST["button_edit"] ?>'>Редактировать</button>		
	</form>
</div>