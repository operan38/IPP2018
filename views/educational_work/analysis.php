<div class="join_block_left_form">
<?php Widgets::academicYearForm('analysis') ?>
<div id="filtration_form" class="EW_filtration_form">
	<p>Фильтры</p><br>
	<p><span>Пед. работник</span><br>
		<?php $educationalWork->dropDownEmployee(); ?>
	</p>
	<p><span>Специальность</span><br>
		<?php
		if (Auth::getInstance()->isExsistsTableAnalysisFilter('ppccz'))
			Widgets::dropDownSelectIdList('SELECT * FROM ppccz ORDER BY cipher_specialty','id_ppccz','cipher_specialty',Auth::getInstance()->getTableAnalysisFilter('ppccz'),'filtration_form_ppccz',true);
		else
			Widgets::dropDownList('SELECT * FROM ppccz ORDER BY cipher_specialty','id_ppccz','cipher_specialty','filtration_form_ppccz');
		?>
	</p>
	<p><span>Учебная группа</span><br>
		<?php 
        if (Auth::getInstance()->isExsistsTableAnalysisFilter('cipher_group'))
        	Widgets::dropDownSelectIdList('SELECT id,gname FROM spr_cipher_group WHERE ppccz = :ppccz','id','gname', Auth::getInstance()->getTableAnalysisFilter('cipher_group'), 'filtration_form_cipher_group', false, array(':ppccz' => Auth::getInstance()->getTableAnalysisFilter('ppccz')));
        else
        	Widgets::dropDownList('SELECT id,gname FROM spr_cipher_group WHERE ppccz = :ppccz','id','gname','filtration_form_cipher_group', array(':ppccz' => Auth::getInstance()->getTableAnalysisFilter('ppccz')));			
		?>
	</p>
	<p><span>Вид деятельности</span><br>
		<?php
		if (Auth::getInstance()->isExsistsTableAnalysisFilter('type_work'))
			Widgets::dropDownSelectIdList('SELECT * FROM spr_t_workew ORDER BY ew_name','id','ew_name',Auth::getInstance()->getTableAnalysisFilter('type_work'),'filtration_form_type_work',true);
		else
			Widgets::dropDownList('SELECT * FROM spr_t_workew ORDER BY ew_name','id','ew_name','filtration_form_type_work');
		?>
	</p>
	<p><span>Вид УВД</span><br>
		<?php
		if (Auth::getInstance()->isExsistsTableAnalysisFilter('type_activity'))
			Widgets::dropDownSelectIdList('SELECT * FROM spr_t_teach_educational_activity ORDER BY ename','id','ename',Auth::getInstance()->getTableAnalysisFilter('type_activity'),'filtration_form_type_activity',true);
		else
			Widgets::dropDownList('SELECT * FROM spr_t_teach_educational_activity ORDER BY ename','id','ename','filtration_form_type_activity'); 
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
	<form method='POST' action='/ew/analysis-export-excel'>
		<button type='submit' id='export_excel_submit' name='export_excel_submit'><img src="/web/images/export_excel.png"><span>Экспорт в Excel</span></button>
	</form>
<?php else: ?>
	<button type='button' disabled><img src="/web/images/print_submit.png"><span>Версия для печати</span></button>
	<button type='submit' id='export_excel_submit' disabled><img src="/web/images/export_excel.png"><span>Экспорт в Excel</span></button>
<?php endif ?>
<?php if (Auth::getInstance()->compareRules(Auth::CHAIR_PK_PKS) ||
	 	  Auth::getInstance()->compareRules(Auth::HEAD_DEPARTMENT)): ?>
	<button type='button' onClick='window.location = "/ew/works"'><img src="/web/images/works.png"><span>План</span></button>
<?php endif ?>
</div>
</div>
<div id="table_wrapper">
<p id="main_table_message"></p>
<table id="main_table" class="EW_main_table">
<thead>
<tr>
	<?php if (Auth::getInstance()->compareRules(Auth::HEAD_UMC)): ?>
	<td id="main_table_title" colspan="9"><img src="/web/images/table.png">Воспитательная работа</td>
	<?php else: ?>
	<td id="main_table_title" colspan="8"><img src="/web/images/table.png">Воспитательная работа</td>
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
	<td rowspan="2">Дата записи</td>
</tr>
</thead>
<tbody id="print_table_body"></tbody>
</table>
</div>
<?php Widgets::dialogBoxConfirmDelete() ?>