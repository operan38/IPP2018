<div class="join_block_left_form">
<?php Widgets::academicYearForm('works') ?>
<?php if (Auth::getInstance()->getTableWorksMode() == Auth::TABLE_WORKS_MODE_FILL || Auth::getInstance()->getTableWorksMode() == Auth::TABLE_WORKS_MODE_ADJUSTMENT): ?>
<div id="add_data_table_form" class="EMW_add_data_table_form">
	<p>Новая запись</p><br>
	<p><span>Специальность</span><br>
		<?php Widgets::dropDownList('SELECT * FROM ppccz ORDER BY cipher_specialty','id_ppccz','cipher_specialty','add_data_table_form_ppccz'); ?>
	</p>
	<p><span>Учебная дисциплина</span><br>
    	<select id='add_data_table_form_discipline' name='add_data_table_form_discipline' disabled></select>
	</p>
	<p><span>Вид деятельности</span><br>
		<?php Widgets::dropDownList('SELECT * FROM spr_t_activities ORDER BY name','id','name','add_data_table_form_type_activities'); ?>
	</p>
	<p><span>Вид УМД</span><br>
		<?php Widgets::dropDownList('SELECT * FROM spr_t_umd ORDER BY uname','id','uname','add_data_table_form_type_umd'); ?>
	</p>
	<p><span>Тип УМД</span><br>
		<?php Widgets::dropDownList('SELECT * FROM spr_t_umd2 ORDER BY name_umd2','id_umd2','name_umd2','add_data_table_form_type_umd2'); ?>
	</p>
	<p><span>Срок исполнения</span><br>
	<input type='text' id='add_data_table_form_date_performance' name='add_data_table_form_date_performance' value='' maxlength='20'>
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
	<form method='POST' action='/emw/works-export-excel'>
		<button type='submit' id='export_excel_submit' name='export_excel_submit'><img src="/web/images/export_excel.png"><span>Экспорт в Excel</span></button>
	</form>
<?php else: ?>
	<button type='button' disabled><img src="/web/images/print_submit.png"><span>Версия для печати</span></button>
	<button type='submit' id='export_excel_submit' disabled><img src="/web/images/export_excel.png"><span>Экспорт в Excel</span></button>
<?php endif ?>
<?php if (Auth::getInstance()->compareRules(Auth::CHAIR_PK_PKS) ||
	 	  Auth::getInstance()->compareRules(Auth::HEAD_DEPARTMENT)): ?>
	<button type='button' onClick='window.location = "/emw/analysis"'><img src="/web/images/analysis.png"><span>Анализ</span></button>
<?php endif ?>
</div>
</div>
<div id="table_wrapper">
<p id="main_table_message"></p>
<table id="main_table" class="EMW_main_table">
<thead>
<tr>
	<?php if (Auth::getInstance()->getTableWorksMode() != Auth::TABLE_WORKS_MODE_READ): ?>
	<td id="main_table_title" colspan="11"><img src="/web/images/table.png">Учебно-методическая работа <?php $educationalMethodicalWork->getWorkTableMode(); ?></td>
	<?php else: ?>
	<td id="main_table_title" colspan="10"><img src="/web/images/table.png">Учебно-методическая работа <?php $educationalMethodicalWork->getWorkTableMode(); ?></td>
	<?php endif ?>
</tr>
<tr>
	<td rowspan="2" style="min-width: 20px">№</td>
	<td rowspan="2">Cпециальность</td>
	<td rowspan="2">Наименование специальности</td>
	<td rowspan="2">Уч. дисциплина (ПМ,МДК)</td>
	<td rowspan="2">Наименование уч. дисциплины (ПМ,МДК)</td>
	<td rowspan="2">Вид деятельности</td>
	<td rowspan="2">Вид УМД</td>
	<td rowspan="2">Тип УМД</td>
	<td rowspan="2">Срок исполнения</td>
	<td rowspan="2">Краткий отчет о выполнении</td>
	<?php if (Auth::getInstance()->getTableWorksMode() != Auth::TABLE_WORKS_MODE_READ): ?>
	<td rowspan="2"></td>
	<?php endif ?>
</tr>
</thead>
<tbody id="main_table_body"></tbody>
</table>
</div>
<div id="print_table_wrapper">
<h3 align="center"> 2. Учебно-методическая работа</h3>
<table id="print_table" class="EMW_main_table">
<thead>
<tr>
	<td rowspan="2" style="min-width: 25px">№</td>
	<td rowspan="2">Cпециальность</td>
	<td rowspan="2">Наименование специальности</td>
	<td rowspan="2">Уч. дисциплина (ПМ,МДК)</td>
	<td rowspan="2">Наименование уч. дисциплины (ПМ,МДК)</td>
	<td rowspan="2">Вид деятельности</td>
	<td rowspan="2">Вид УМД</td>
	<td rowspan="2">Тип УМД</td>
	<td rowspan="2">Срок исполнения</td>
	<td rowspan="2">Краткий отчет о выполнении</td>
</tr>
</thead>
<tbody id="print_table_body"></tbody>
</table>
</div>
<?php Widgets::dialogBoxConfirmDelete() ?>