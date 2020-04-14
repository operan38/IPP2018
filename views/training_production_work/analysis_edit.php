<div id="edit_data_table_form">
<form id="edit_form" method='POST' action='/tpw/analysis-update'>
	<p><span>Специальность</span><br>
		<?php Widgets::dropDownSelectIdList('SELECT * FROM ppccz ORDER BY cipher_specialty','id_ppccz','cipher_specialty',$trainingProductionWork->ppccz,'edit_data_table_form_ppccz'); ?>
	</p>
	<!--<p><span>Наименование кабинета</span><br>
        <?php Widgets::dropDownSelectIdList('SELECT foundation_offices.id,spr_t_cabinet.cname,foundation_offices.oname FROM foundation_offices INNER JOIN spr_t_cabinet ON foundation_offices.id_t_cabinet = spr_t_cabinet.id WHERE ppccz = :ppccz', 'id', array('cname', 'oname'), $trainingProductionWork->id_foundation, 'edit_data_table_form_id_foundation', false, array(':ppccz' => $trainingProductionWork->ppccz)); ?>
	</p>-->
	<p><span>Место размещения</span><br>
		<?php Widgets::dropDownSelectIdList('SELECT * FROM spr_placement ORDER BY pname','id','pname',$trainingProductionWork->placement,'edit_data_table_form_placement'); ?>
	</p>
	<p><span>Вид деятельности</span><br>
		<?php Widgets::dropDownSelectIdList('SELECT * FROM spr_t_activities ORDER BY name','id','name',$trainingProductionWork->type_activities,'edit_data_table_form_type_activities'); ?>
	</p>
	<p><span>Вид УПД</span><br>
		<?php Widgets::dropDownSelectIdList('SELECT * FROM spr_t_upd ORDER BY uname','id','uname',$trainingProductionWork->type_upd,'edit_data_table_form_type_upd'); ?>
	</p>
	<p><span>Дата</span><br>
        <input type='text' id='edit_data_table_form_sdate' name='edit_data_table_form_sdate' value='<?php echo $trainingProductionWork->sdate ?>' maxlength='150'>
	</p>
	<p><span>Результат</span><br>
		<input type='text' id='edit_data_table_form_report' name='edit_data_table_form_report' value='<?php echo $trainingProductionWork->report ?>'>
	</p><br>
	<button type='button' id='edit_data_table_form_cancel' name='edit_data_table_form_cancel'>Отменить</button>
	<button type='submit' id='edit_data_table_form_update' name='edit_data_table_form_update' value='<?php if (isset($_POST["button_edit"])) echo $_POST["button_edit"] ?>'>Редактировать</button>
</form>
</div>