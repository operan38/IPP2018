<div id="edit_data_table_form">
	<form id="edit_form" method='POST' action='/admin-foundation-offices/update'>
		<p><span>Специальность</span><br>
			<?php Widgets::dropDownSelectIdList('SELECT * FROM ppccz ORDER BY cipher_specialty','id_ppccz','cipher_specialty',$foundationOffices->ppccz,'edit_data_table_form_ppccz'); ?>
		</p>
		<p><span>Тип кабинета</span><br>
			<?php Widgets::dropDownSelectIdList('SELECT * FROM spr_t_cabinet ORDER BY cname','id','cname',$foundationOffices->id_t_cabinet,'edit_data_table_form_id_t_cabinet'); ?>
		</p>
		<p><span>Наименование</span><br>
    		<input type='text' id='edit_data_table_form_oname' name='edit_data_table_form_oname' value='<?php echo $foundationOffices->oname ?>'>
		</p><br>
		<button type='button' id='edit_data_table_form_cancel' name='edit_data_table_form_cancel'>Отменить</button>
		<button type='submit' id='edit_data_table_form_update' name='edit_data_table_form_update' value='<?php if (isset($_POST["button_edit"])) echo $_POST["button_edit"] ?>'>Редактировать</button>		
	</form>
</div>