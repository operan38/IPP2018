<div id="edit_data_table_form">
<form id="edit_form_fill" method='POST' action='/ew/works-update-fill'>
	<p><span>Специальность</span><br>
		<?php Widgets::dropDownSelectIdList('SELECT * FROM ppccz ORDER BY cipher_specialty','id_ppccz','cipher_specialty',$educationalWork->ppccz,'edit_fill_data_table_form_ppccz'); ?>
	</p>
    <p><span>Учебная группа</span><br>
        <?php Widgets::dropDownSelectIdList('SELECT id,gname FROM spr_cipher_group WHERE ppccz = :ppccz','id','gname', $educationalWork->cipher_group, 'edit_fill_data_table_form_cipher_group', false, array(':ppccz' => $educationalWork->ppccz)); ?>
    </p>
	<p><span>Вид деятельности</span><br>
		<?php Widgets::dropDownSelectIdList('SELECT * FROM spr_t_workew ORDER BY ew_name','id','ew_name',$educationalWork->type_work,'edit_fill_data_table_form_type_work'); ?>
	</p>
	<p><span>Вид УВД</span><br>
		<?php Widgets::dropDownSelectIdList('SELECT * FROM spr_t_teach_educational_activity ORDER BY ename','id','ename',$educationalWork->type_activity,'edit_fill_data_table_form_type_activity'); ?>
	</p>
	<p><span>Дата</span><br>
		<input type='text' id='edit_fill_data_table_form_sdate' name='edit_fill_data_table_form_sdate' value='<?php echo $educationalWork->sdate ?>' maxlength='20'>
	</p><br>
	<button type='button' id='edit_fill_data_table_form_cancel' name='edit_fill_data_table_form_cancel'>Отменить</button>
	<button type='submit' id='edit_fill_data_table_form_update' name='edit_fill_data_table_form_update' value='<?php if (isset($_POST["button_fill_edit"])) echo $_POST["button_fill_edit"] ?>'>Редактировать</button>
</form>
</div>