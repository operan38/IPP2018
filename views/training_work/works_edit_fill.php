<div id="edit_data_table_form">
<form id="edit_form_fill" method='POST' action='/tw/works-update-fill'>
    <p><span>Специальность</span><br>
        <?php Widgets::dropDownSelectIdList('SELECT * FROM ppccz ORDER BY cipher_specialty','id_ppccz','cipher_specialty',$trainingWork->ppccz,'edit_fill_data_table_form_ppccz'); ?>
    </p>
    <p><span>Учебная дисциплина</span><br>
        <?php Widgets::dropDownSelectIdList('SELECT disciplines.id,spr_index_disciplines.dindex,disciplines.dname FROM disciplines INNER JOIN spr_index_disciplines ON disciplines.dindex = spr_index_disciplines.id_ind WHERE ppccz = :ppccz ORDER BY spr_index_disciplines.dindex', 'id', array('dindex','dname'), $trainingWork->discipline, 'edit_fill_data_table_form_discipline', false, array(':ppccz' => $trainingWork->ppccz)); ?>
    </p>
    <p><span>Учебная группа</span><br>
        <?php Widgets::dropDownSelectIdList('SELECT id,gname FROM spr_cipher_group WHERE ppccz = :ppccz','id','gname', $trainingWork->cipher_group, 'edit_fill_data_table_form_cipher_group', false, array(':ppccz' => $trainingWork->ppccz)); ?>
    </p>
    <b><span>Кол-во часов по плану</span></b><br>
    <p><span>I семестр</span><br>
        <input type='text' id='edit_fill_data_table_form_plan_1' name='edit_fill_data_table_form_plan_1' value='<?php echo $trainingWork->plan_1 ?>' maxlength='4'><br>
        <span>II семестр</span><br>
        <input type='text' id='edit_fill_data_table_form_plan_2' name='edit_fill_data_table_form_plan_2' value='<?php echo $trainingWork->plan_2 ?>' maxlength='4'>
    </p><br>
	<button type='button' id='edit_fill_data_table_form_cancel' name='edit_fill_data_table_form_cancel'>Отменить</button>
	<button type='submit' id='edit_fill_data_table_form_update' name='edit_fill_data_table_form_update' value='<?php if (isset($_POST["button_fill_edit"])) echo $_POST["button_fill_edit"] ?>'>Редактировать</button>
</form>
</div>