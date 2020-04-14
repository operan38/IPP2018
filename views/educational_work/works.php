<div class="join_block_left_form">
<?php Widgets::academicYearForm('works') ?>
<?php if (Auth::getInstance()->getTableWorksMode() == Auth::TABLE_WORKS_MODE_FILL || Auth::getInstance()->getTableWorksMode() == Auth::TABLE_WORKS_MODE_ADJUSTMENT): ?>
<div id="add_data_table_form" class="EW_add_data_table_form">
	<p>Новая запись</p><br>
	<p><span>Специальность</span><br>
		<?php Widgets::dropDownList('SELECT * FROM ppccz ORDER BY cipher_specialty','id_ppccz','cipher_specialty','add_data_table_form_ppccz'); ?>
	</p>
	<p><span>Учебная группа</span><br>
    	<select id='add_data_table_form_cipher_group' name='add_data_table_form_cipher_group' disabled></select>
	</p>
	<p><span>Вид деятельности</span><br>
		<?php Widgets::dropDownList('SELECT * FROM spr_t_workew ORDER BY ew_name','id','ew_name','add_data_table_form_type_work'); ?>
	</p>
	<p><span>Вид УВД</span><br>
		<?php Widgets::dropDownList('SELECT * FROM spr_t_teach_educational_activity ORDER BY ename','id','ename','add_data_table_form_type_activity'); ?>
	</p>
	<p><span>Дата</span><br>
	<input type='text' id='add_data_table_form_sdate' name='add_data_table_form_sdate' value='' maxlength='20'>
	</p>
	<?php if (Auth::getInstance()->getAcademicYear() != ''): ?>
		<button type='button' id='add_data_table_form_insert' name='add_data_table_form_insert'>Добавить</button>
	<?php else: ?>
		<button type='button' disabled>Добавить</button>
	<?php endif ?>
</div>
<?php endif ?>
<div class="small_form">
<?php if (Auth::getInstance()->getAcademicYear() != ''): ?>
	<button type='button' id='print_submit'><img src="/web/images/print_submit.png"><span>Версия для печати</span></button>
	<form method='POST' action='/ew/works-export-excel'>
		<button type='submit' id='export_excel_submit' name='export_excel_submit'><img src="/web/images/export_excel.png"><span>Экспорт в Excel</span></button>
	</form>
<?php else: ?>
	<button type='button' disabled><img src="/web/images/print_submit.png"><span>Версия для печати</span></button>
	<button type='submit' id='export_excel_submit' disabled><img src="/web/images/export_excel.png"><span>Экспорт в Excel</span></button>
<?php endif ?>
<?php if (Auth::getInstance()->compareRules(Auth::CHAIR_PK_PKS) ||
	 	  Auth::getInstance()->compareRules(Auth::HEAD_DEPARTMENT)): ?>
	<button type='button' onClick='window.location = "/ew/analysis"'><img src="/web/images/analysis.png"><span>Анализ</span></button>
<?php endif ?>
</div>
</div>
<div id="table_wrapper">
<p id="main_table_message"></p>
<table id="main_table" class="EW_main_table">
<thead>
<tr>
	<?php if (Auth::getInstance()->getTableWorksMode() != Auth::TABLE_WORKS_MODE_READ): ?>
	<td id="main_table_title" colspan="8"><img src="/web/images/table.png">Воспитательная работа <?php $educationalWork->getWorkTableMode(); ?></td>
	<?php else: ?>
	<td id="main_table_title" colspan="7"><img src="/web/images/table.png">Воспитательная работа <?php $educationalWork->getWorkTableMode(); ?></td>
	<?php endif ?>
</tr>
<tr>
	<td rowspan="2" style="min-width: 20px">№</td>
	<td rowspan="2">Cпециальность</td>
	<td rowspan="2">Группа</td>
	<td rowspan="2">Вид деятельности</td>
	<td rowspan="2">Вид учебно-воспитательной деятельности</td>
	<td rowspan="2">Дата</td>
	<td rowspan="2">Результат</td>
	<?php if (Auth::getInstance()->getTableWorksMode() != Auth::TABLE_WORKS_MODE_READ): ?>
	<td rowspan="2"></td>
	<?php endif ?>
</tr>
</thead>
<tbody id="main_table_body"></tbody>
</table>
</div>
<div id="print_table_wrapper">
<h3 align="center"> 7. Воспитательная работа</h3>
<table id="print_table" class="EW_main_table">
<thead>
<tr>
	<td rowspan="2" style="min-width: 20px">№</td>
	<td rowspan="2">Cпециальность</td>
	<td rowspan="2">Группа</td>
	<td rowspan="2">Вид деятельности</td>
	<td rowspan="2">Вид учебно-воспитательной деятельности</td>
	<td rowspan="2">Дата</td>
	<td rowspan="2">Результат</td>
</tr>
</thead>
<tbody id="print_table_body"></tbody>
</table>
</div>
<?php Widgets::dialogBoxConfirmDelete() ?>