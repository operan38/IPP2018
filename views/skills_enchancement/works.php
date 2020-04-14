<div class="join_block_left_form">
<?php Widgets::academicYearForm('works') ?>
<?php if (Auth::getInstance()->getTableWorksMode() == Auth::TABLE_WORKS_MODE_FILL || Auth::getInstance()->getTableWorksMode() == Auth::TABLE_WORKS_MODE_ADJUSTMENT): ?>
<div id="add_data_table_form" class="SE_add_data_table_form">
	<p>Новая запись</p><br>
	<p><span>Форма повышения</span><br>
		<?php Widgets::dropDownList('SELECT * FROM spr_t_worksew ORDER BY sew_name','id_sework','sew_name','add_data_table_form_type_work'); ?>
	</p>
	<p><span>Дата</span><br>
    	<input type='text' id='add_data_table_form_sdate' name='add_data_table_form_sdate' value='' maxlength='100'>
	</p>
	<p><span>Место</span><br>
    	<input type='text' id='add_data_table_form_place' name='add_data_table_form_place' value='' maxlength='150'>
	</p>
	<p><span>Тема</span><br>
    	<input type='text' id='add_data_table_form_theme' name='add_data_table_form_theme' value='' maxlength='150'>
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
	<form method='POST' action='/se/works-export-excel'>
		<button type='submit' id='export_excel_submit' name='export_excel_submit'><img src="/web/images/export_excel.png"><span>Экспорт в Excel</span></button>
	</form>
<?php else: ?>
	<button type='button' disabled><img src="/web/images/print_submit.png"><span>Версия для печати</span></button>
	<button type='submit' id='export_excel_submit' disabled><img src="/web/images/export_excel.png"><span>Экспорт в Excel</span></button>
<?php endif ?>
<?php if (Auth::getInstance()->compareRules(Auth::CHAIR_PK_PKS) ||
	 	  Auth::getInstance()->compareRules(Auth::HEAD_DEPARTMENT)): ?>
	<button type='button' onClick='window.location = "/se/analysis"'><img src="/web/images/analysis.png"><span>Анализ</span></button>
<?php endif ?>
</div>
</div>
<div id="table_wrapper">
<p id="main_table_message"></p>
<table id="main_table" class="SE_main_table">
<thead>
<tr>
	<?php if (Auth::getInstance()->getTableWorksMode() != Auth::TABLE_WORKS_MODE_READ): ?>
	<td id="main_table_title" colspan="7"><img src="/web/images/table.png">Повышение уровня квалификации <?php $skillsEnchancement->getWorkTableMode(); ?></td>
	<?php else: ?>
	<td id="main_table_title" colspan="6"><img src="/web/images/table.png">Повышение уровня квалификации <?php $skillsEnchancement->getWorkTableMode(); ?></td>
	<?php endif ?>
</tr>
<tr>
	<td rowspan="2" style="min-width: 20px">№</td>
	<td rowspan="2">Форма повышения</td>
	<td rowspan="2">Дата</td>
	<td rowspan="2">Место</td>
	<td rowspan="2">Тема</td>
	<td rowspan="2">Документ/Результат</td>
	<?php if (Auth::getInstance()->getTableWorksMode() != Auth::TABLE_WORKS_MODE_READ): ?>
	<td rowspan="2"></td>
	<?php endif ?>
</tr>
</thead>
<tbody id="main_table_body"></tbody>
</table>
</div>
<div id="print_table_wrapper">
<h3 align="center"> 5. Повышение уровня квалификации</h3>
<table id="print_table" class="SE_main_table">
<thead>
<tr>
	<td rowspan="2" style="min-width: 25px">№</td>
	<td rowspan="2">Форма повышения</td>
	<td rowspan="2">Дата</td>
	<td rowspan="2">Место</td>
	<td rowspan="2">Тема</td>
	<td rowspan="2">Документ/Результат</td>
</tr>
</thead>
<tbody id="print_table_body"></tbody>
</table>
</div>
<?php Widgets::dialogBoxConfirmDelete() ?>