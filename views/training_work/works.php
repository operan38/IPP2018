<div class="join_block_left_form">
<?php Widgets::academicYearForm('works') ?>
<?php if (Auth::getInstance()->getTableWorksMode() == Auth::TABLE_WORKS_MODE_FILL || Auth::getInstance()->getTableWorksMode() == Auth::TABLE_WORKS_MODE_ADJUSTMENT): ?>
<div id="add_data_table_form" class="TW_add_data_table_form">
	<p>Новая запись</p><br>
	<p><span>Специальность</span><br>
		<?php Widgets::dropDownList('SELECT * FROM ppccz ORDER BY cipher_specialty','id_ppccz','cipher_specialty','add_data_table_form_ppccz'); ?>
	</p>
	<p>
    <span>Учебная дисциплина</span><br>
    	<select id='add_data_table_form_discipline' name='add_data_table_form_discipline' disabled></select>
	</p>
	<p><span>Учебная группа</span><br>
    	<select id='add_data_table_form_cipher_group' name='add_data_table_form_cipher_group' disabled></select>
	</p>
	<p><span>Кол-во часов по плану</span></p><br>
	<p><span>I семестр</span>
    	<input type='text' id='add_data_table_form_plan_1' name='add_data_table_form_plan_1' value='0' maxlength='4'>
		<span>II семестр</span>
    	<input type='text' id='add_data_table_form_plan_2' name='add_data_table_form_plan_2' value='0' maxlength='4'>
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
	<form method='POST' action='/tw/works-export-excel'>
		<button type='submit' id='export_excel_submit' name='export_excel_submit'><img src="/web/images/export_excel.png"><span>Экспорт в Excel</span></button>
	</form>
<?php else: ?>
	<button type='button' disabled><img src="/web/images/print_submit.png"><span>Версия для печати</span></button>
	<button type='submit' id='export_excel_submit' disabled><img src="/web/images/export_excel.png"><span>Экспорт в Excel</span></button>
<?php endif ?>
<?php if (Auth::getInstance()->compareRules(Auth::CHAIR_PK_PKS) ||
	 	  Auth::getInstance()->compareRules(Auth::HEAD_DEPARTMENT)): ?>
	<button type='button' onClick='window.location = "/tw/analysis"'><img src="/web/images/analysis.png"><span>Анализ</span></button>
<?php endif ?>
</div>
</div>
<div id="table_wrapper">
<p id="main_table_message"></p>
<table id="main_table" class="TW_main_table">
<thead>
<tr>
	<?php if (Auth::getInstance()->getTableWorksMode() != Auth::TABLE_WORKS_MODE_READ): ?>
	<td id="main_table_title" colspan="15"><img src="/web/images/table.png">Учебная работа <?php $trainingWork->getWorkTableMode(); ?></td>
	<?php else: ?>
	<td id="main_table_title" colspan="14"><img src="/web/images/table.png">Учебная работа <?php $trainingWork->getWorkTableMode(); ?></td>
	<?php endif ?>
</tr>
<tr>
	<td rowspan="2" style="min-width: 25px">№</td>
	<td rowspan="2">Cпециальность</td>
	<td rowspan="2">Уч. дисциплина (ПМ,МДК)</td>
	<td rowspan="2">Наименование уч. дисциплины (ПМ,МДК)</td>
	<td rowspan="2">Группа</td>
	<td colspan="2">Первый семестр</td>
	<td colspan="2">Второй семестр</td>
	<td colspan="2">Учебный год</td>
	<td rowspan="2" style="max-width: 150px">Причина невыполнения</td>
	<td rowspan="2" style="max-width: 150px">Абсолютная успеваемость</td>
	<td rowspan="2" style="max-width: 150px">Качественная успеваемость</td>
	<?php if (Auth::getInstance()->getTableWorksMode() != Auth::TABLE_WORKS_MODE_READ): ?>
	<td rowspan="2"></td>
	<?php endif ?>
</tr>
<tr>
	<td>План</td>
	<td>Факт</td>
	<td>План</td>
	<td>Факт</td>
	<td>План</td>
	<td>Факт</td>
</tr>
</thead>
<tbody id="main_table_body"></tbody>
</table>
</div>
<div id="print_table_wrapper">
<h3 align="center"> 1. Учебная (преподавательская) работа</h3>
<p align="center">(Указывается в часах в соотвествии с установленной учебной нагрузкой)</p>
<table id="print_table" class="TW_main_table">
<thead>
<tr>
	<td rowspan="2" style="min-width: 25px">№</td>
	<td rowspan="2" style="min-width: 120px">Cпециальность</td>
	<td rowspan="2" style="min-width: 80px">Уч. дисциплина (ПМ,МДК)</td>
	<td rowspan="2">Наименование уч. дисциплины (ПМ,МДК)</td>
	<td rowspan="2">Группа</td>
	<td colspan="2">Первый семестр</td>
	<td colspan="2">Второй семестр</td>
	<td colspan="2">Учебный год</td>
	<td rowspan="2" style="max-width: 150px">Причина невыполнения</td>
	<td rowspan="2" style="max-width: 150px">Абсолютная успеваемость</td>
	<td rowspan="2" style="max-width: 150px">Качественная успеваемость</td>
</tr>
<tr>
	<td>План</td>
	<td>Факт</td>
	<td>План</td>
	<td>Факт</td>
	<td>План</td>
	<td>Факт</td>
</tr>
</thead>
<tbody id="print_table_body"></tbody>
</table>
</div>
<?php Widgets::dialogBoxConfirmDelete() ?>