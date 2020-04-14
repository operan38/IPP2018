<div class="join_block_left_form">
<?php Widgets::academicYearForm('works') ?>
<?php if (Auth::getInstance()->getTableWorksMode() == Auth::TABLE_WORKS_MODE_FILL || Auth::getInstance()->getTableWorksMode() == Auth::TABLE_WORKS_MODE_ADJUSTMENT): ?>
<div id="add_data_table_form" class="SMW_add_data_table_form">
	<p>Новая запись</p><br>
	<p><span>Вид деятельности</span><br>
		<?php Widgets::dropDownList('SELECT * FROM spr_t_worksmw ORDER BY smw_name','id_smwork','smw_name','add_data_table_form_type_work'); ?>
	</p>
	<p><span>Вид мероприятия</span><br>
		<?php Widgets::dropDownList('SELECT * FROM spr_event_nmp ORDER BY name_event_nmp','id_event_nmp','name_event_nmp','add_data_table_form_type_event'); ?>
	</p>
	<p><span>Название мероприятия</span><br>
        <input type='text' id='add_data_table_form_name_event' name='add_data_table_form_name_event' value='' maxlength='150'>
	</p>
	<p><span>Уровень мероприятия</span><br>
		<?php Widgets::dropDownList('SELECT * FROM spr_level_events ORDER BY name_level','id_level','name_level','add_data_table_form_level_event'); ?>
	</p>
	<p><span>Дата</span><br>
    	<input type='text' id='add_data_table_form_sdate' name='add_data_table_form_sdate' value='' maxlength='100'>
	</p>
	<p><span>Место</span><br>
    	<input type='text' id='add_data_table_form_place' name='add_data_table_form_place' value='' maxlength='150'>
	</p>
	<p><span>Вид участия</span><br>
		<?php Widgets::dropDownList('SELECT * FROM spr_t_participation ORDER BY partname','id','partname','add_data_table_form_type_of_participation'); ?>
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
	<form method='POST' action='/smw/works-export-excel'>
		<button type='submit' id='export_excel_submit' name='export_excel_submit'><img src="/web/images/export_excel.png"><span>Экспорт в Excel</span></button>
	</form>
<?php else: ?>
	<button type='button' disabled><img src="/web/images/print_submit.png"><span>Версия для печати</span></button>
	<button type='submit' id='export_excel_submit' disabled><img src="/web/images/export_excel.png"><span>Экспорт в Excel</span></button>
<?php endif ?>
<?php if (Auth::getInstance()->compareRules(Auth::CHAIR_PK_PKS) ||
	 	  Auth::getInstance()->compareRules(Auth::HEAD_DEPARTMENT)): ?>
	<button type='button' onClick='window.location = "/smw/analysis"'><img src="/web/images/analysis.png"><span>Анализ</span></button>
<?php endif ?>
</div>
</div>
<div id="table_wrapper">
<p id="main_table_message"></p>
<table id="main_table" class="SMW_main_table">
<thead>
<tr>
	<?php if (Auth::getInstance()->getTableWorksMode() != Auth::TABLE_WORKS_MODE_READ): ?>
	<td id="main_table_title" colspan="10"><img src="/web/images/table.png">Научно-методическая работа <?php $scientificMethodicalWork->getWorkTableMode(); ?></td>
	<?php else: ?>
	<td id="main_table_title" colspan="9"><img src="/web/images/table.png">Научно-методическая работа <?php $scientificMethodicalWork->getWorkTableMode(); ?></td>
	<?php endif ?>
</tr>
<tr>
	<td rowspan="2" style="min-width: 20px">№</td>
	<td rowspan="2">Вид деятельности</td>
	<td rowspan="2">Вид мероприятия</td>
	<td rowspan="2">Название мероприятия</td>
	<td rowspan="2">Уровень мероприятия</td>
	<td rowspan="2">Дата</td>
	<td rowspan="2">Место</td>
	<td rowspan="2">Вид участия</td>
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
<h3 align="center"> 4. Научно-методическая работа</h3>
<table id="print_table" class="SMW_main_table">
<thead>
<tr>
	<td rowspan="2" style="min-width: 25px">№</td>
	<td rowspan="2">Вид деятельности</td>
	<td rowspan="2">Вид мероприятия</td>
	<td rowspan="2">Название мероприятия</td>
	<td rowspan="2">Уровень мероприятия</td>
	<td rowspan="2">Дата</td>
	<td rowspan="2">Место</td>
	<td rowspan="2">Вид участия</td>
	<td rowspan="2">Результат</td>
</tr>
</thead>
<tbody id="print_table_body"></tbody>
</table>
</div>
<?php Widgets::dialogBoxConfirmDelete() ?>