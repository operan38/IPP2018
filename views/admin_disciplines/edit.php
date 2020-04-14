<div id="edit_data_table_form">
	<form id="edit_form" method='POST' action='/admin-disciplines/update'>
		<p><span>Специальность</span><br>
			<?php Widgets::dropDownSelectIdList('SELECT * FROM ppccz ORDER BY cipher_specialty','id_ppccz','cipher_specialty',$disciplines->ppccz,'edit_data_table_form_ppccz'); ?>
		</p>
		<p><span>Индекс</span><br>
			<?php Widgets::dropDownSelectIdList('SELECT * FROM spr_index_disciplines ORDER BY dindex','id_ind','dindex',$disciplines->dindex,'edit_data_table_form_dindex'); ?>
		</p>
		<p><span>Наименование</span><br>
    		<input type='text' id='edit_data_table_form_dname' name='edit_data_table_form_dname' value='<?php echo $disciplines->dname ?>'>
		</p><br>
		<button type='button' id='edit_data_table_form_cancel' name='edit_data_table_form_cancel'>Отменить</button>
		<button type='submit' id='edit_data_table_form_update' name='edit_data_table_form_update' value='<?php if (isset($_POST["button_edit"])) echo $_POST["button_edit"] ?>'>Редактировать</button>		
	</form>
</div>