<div class="join_block_left_form">
<?php Widgets::academicYearForm('analysis') ?>
<div id="filtration_form" class="SE_filtration_form">
	<p>Фильтры</p><br>
	<p><span>Пед. работник</span><br>
		<?php $skillsEnchancement->dropDownEmployee(); ?>
	</p>
	<p><span>Форма повышения</span><br>
		<?php
		if (Auth::getInstance()->isExsistsTableAnalysisFilter('type_work'))
			Widgets::dropDownSelectIdList('SELECT * FROM spr_t_worksew ORDER BY sew_name','id_sework','sew_name',Auth::getInstance()->getTableAnalysisFilter('type_work'),'filtration_form_type_work',true);
		else
			Widgets::dropDownList('SELECT * FROM spr_t_worksew ORDER BY sew_name','id_sework','sew_name','filtration_form_type_work');
		?>
	</p>
	<div>
		<button id='filtration_form_submit' name='filtration_form_submit'>Применить</button>
    	<button id='filtration_form_reset' name='filtration_form_reset'><img src="/web/images/filter_reset.png" title="Сбросить"></button>
    </div>
</div>
<div class="small_form">
<?php if (Auth::getInstance()->getAcademicYear() != ''): ?>
	<button type='button' id='print_submit'><img src="/web/images/print_submit.png"><span>Версия для печати</span></button>
	<form method='POST' action='/se/analysis-export-excel'>
		<button type='submit' id='export_excel_submit' name='export_excel_submit'><img src="/web/images/export_excel.png"><span>Экспорт в Excel</span></button>
	</form>
<?php else: ?>
	<button type='button' disabled><img src="/web/images/print_submit.png"><span>Версия для печати</span></button>
	<button type='submit' id='export_excel_submit' disabled><img src="/web/images/export_excel.png"><span>Экспорт в Excel</span></button>
<?php endif ?>
<?php if (Auth::getInstance()->compareRules(Auth::CHAIR_PK_PKS) ||
	 	  Auth::getInstance()->compareRules(Auth::HEAD_DEPARTMENT)): ?>
	<button type='button' onClick='window.location = "/se/works"'><img src="/web/images/works.png"><span>План</span></button>
<?php endif ?>
</div>
</div>
<div id="table_wrapper">
<p id="main_table_message"></p>
<table id="main_table" class="SE_main_table">
<thead>
<tr>
	<?php if (Auth::getInstance()->compareRules(Auth::HEAD_UMC)): ?>
	<td id="main_table_title" colspan="8"><img src="/web/images/table.png">Повышение уровня квалификации</td>
	<?php else: ?>
	<td id="main_table_title" colspan="7"><img src="/web/images/table.png">Повышение уровня квалификации</td>
	<?php endif ?>
</tr>
<tr>
	<td rowspan="2" style="min-width: 20px">№</td>
	<td rowspan="2">Форма повышения</td>
	<td rowspan="2">Дата</td>
	<td rowspan="2">Место</td>
	<td rowspan="2">Тема</td>
	<td rowspan="2">Документ/Результат</td>
	<td rowspan="2">Дата записи</td>
	<?php if (Auth::getInstance()->compareRules(Auth::HEAD_UMC)): ?>
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
	<td rowspan="2">Дата записи</td>
</tr>
</thead>
<tbody id="print_table_body"></tbody>
</table>
</div>
<?php Widgets::dialogBoxConfirmDelete() ?>