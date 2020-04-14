<div class="join_block_left_form">
<?php Widgets::academicYearForm('analysis') ?>
<div id="filtration_form" class="SMW_filtration_form">
	<p>Фильтры</p><br>
	<p><span>Пед. работник</span><br>
		<?php $scientificMethodicalWork->dropDownEmployee(); ?>
	</p>
	<p><span>Вид деятельности</span><br>
		<?php
		if (Auth::getInstance()->isExsistsTableAnalysisFilter('type_work'))
			Widgets::dropDownSelectIdList('SELECT * FROM spr_t_worksmw ORDER BY smw_name','id_smwork','smw_name',Auth::getInstance()->getTableAnalysisFilter('type_work'),'filtration_form_type_work',true);
		else
			Widgets::dropDownList('SELECT * FROM spr_t_worksmw ORDER BY smw_name','id_smwork','smw_name','filtration_form_type_work'); 
		?>
	</p>
	<p><span>Вид мероприятия</span><br>
		<?php
		if (Auth::getInstance()->isExsistsTableAnalysisFilter('type_event'))
			Widgets::dropDownSelectIdList('SELECT * FROM spr_event_nmp ORDER BY name_event_nmp','id_event_nmp','name_event_nmp',Auth::getInstance()->getTableAnalysisFilter('type_event'),'filtration_form_type_event',true);
		else
			Widgets::dropDownList('SELECT * FROM spr_event_nmp ORDER BY name_event_nmp','id_event_nmp','name_event_nmp','filtration_form_type_event');
		?>
	</p>
	<p><span>Уровень мероприятия</span><br>
		<?php
		if (Auth::getInstance()->isExsistsTableAnalysisFilter('level_event'))
			Widgets::dropDownSelectIdList('SELECT * FROM spr_level_events ORDER BY name_level','id_level','name_level',Auth::getInstance()->getTableAnalysisFilter('level_event'),'filtration_form_level_event',true); 
		else
			Widgets::dropDownList('SELECT * FROM spr_level_events ORDER BY name_level','id_level','name_level','filtration_form_level_event'); 
		?>
	</p>
	<p><span>Вид участия</span><br>
		<?php
		if (Auth::getInstance()->isExsistsTableAnalysisFilter('type_of_participation'))
			Widgets::dropDownSelectIdList('SELECT * FROM spr_t_participation ORDER BY partname','id','partname',Auth::getInstance()->getTableAnalysisFilter('type_of_participation'),'filtration_form_type_of_participation',true);
		else
			Widgets::dropDownList('SELECT * FROM spr_t_participation ORDER BY partname','id','partname','filtration_form_type_of_participation');
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
	<form method='POST' action='/smw/analysis-export-excel'>
		<button type='submit' id='export_excel_submit' name='export_excel_submit'><img src="/web/images/export_excel.png"><span>Экспорт в Excel</span></button>
	</form>
<?php else: ?>
	<button type='button' disabled><img src="/web/images/print_submit.png"><span>Версия для печати</span></button>
	<button type='submit' id='export_excel_submit' disabled><img src="/web/images/export_excel.png"><span>Экспорт в Excel</span></button>
<?php endif ?>
<?php if (Auth::getInstance()->compareRules(Auth::CHAIR_PK_PKS) ||
	 	  Auth::getInstance()->compareRules(Auth::HEAD_DEPARTMENT)): ?>
	<button type='button' onClick='window.location = "/smw/works"'><img src="/web/images/works.png"><span>План</span></button>
<?php endif ?>
</div>
</div>
<div id="table_wrapper">
<p id="main_table_message"></p>
<table id="main_table" class="SMW_main_table">
<thead>
<tr>
	<?php if (Auth::getInstance()->compareRules(Auth::HEAD_UMC)): ?>
	<td id="main_table_title" colspan="11"><img src="/web/images/table.png">Научно-методическая работа</td>
	<?php else: ?>
	<td id="main_table_title" colspan="10"><img src="/web/images/table.png">Научно-методическая работа</td>
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
	<td rowspan="2">Дата записи</td>
</tr>
</thead>
<tbody id="print_table_body"></tbody>
</table>
</div>
<?php Widgets::dialogBoxConfirmDelete() ?>